<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Transaction;
use App\Mail\TicketMail;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();
        $ticketPrice = $event->current_price;
        $activeTier = $event->active_tier_name;

        // Load available tiers (those active by date) to show to user
        try {
            $now = now();
            $tiers = $event->tiers()
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->get();
        } catch (\Throwable $e) {
            $tiers = collect();
        }

        return view('checkout.create', compact('event', 'categories', 'ticketPrice', 'activeTier', 'tiers'));
    }

    protected function buildTransactionPayload(array $data, ?Coupon $coupon, ?\App\Models\TicketTier $selectedTier): array
    {
        if (Schema::hasColumn('transactions', 'coupon_id')) {
            $data['coupon_id'] = $coupon?->id;
        }

        if (Schema::hasColumn('transactions', 'coupon_code')) {
            $data['coupon_code'] = $coupon?->code;
        }

        if (Schema::hasColumn('transactions', 'coupon_used_counted')) {
            $data['coupon_used_counted'] = (bool) $coupon;
        }

        if (Schema::hasColumn('transactions', 'ticket_tier_id')) {
            $data['ticket_tier_id'] = $selectedTier?->id;
        }

        return $data;
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'coupon_code' => 'nullable|string',
            'tier_id' => 'nullable|integer|exists:ticket_tiers,id',
        ]);
 
        if ($event->stock <= 0) {
            return back()
                ->withInput()
                ->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }
 
        $selectedTier = null;
        $basePrice = $event->current_price;
 
        if ($request->filled('tier_id')) {
            $selectedTier = \App\Models\TicketTier::where('id', $request->tier_id)
                ->where('event_id', $event->id)
                ->where(function ($q) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', now());
                })
                ->first();
 
            if (!$selectedTier || (!is_null($selectedTier->stock) && $selectedTier->stock <= 0)) {
                return back()
                    ->withInput()
                    ->with('error', 'Pilihan tiket tidak valid atau tiket kategori ini sudah habis.');
            }
 
            $basePrice = $selectedTier->price;
        }
 
        $discount = 0;
        $coupon = null;
 
        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper(trim($request->coupon_code)))->first();
            if (!$coupon) {
                return back()
                    ->withInput()
                    ->with('error', 'Kode kupon tidak ditemukan.');
            }
 
            if (!$coupon->isValidForEvent($event->id)) {
                return back()
                    ->withInput()
                    ->with('error', 'Kode kupon tidak berlaku untuk event ini atau sudah kadaluarsa.');
            }
 
            if (!$coupon->appliesToTicketTier($selectedTier)) {
                return back()
                    ->withInput()
                    ->with('error', 'Kupon ini tidak berlaku untuk jenis tiket yang dipilih.');
            }
 
            $discount = $coupon->calculateDiscount($basePrice);
        }

        $ticketFinalPrice = max(0, $basePrice - $discount);
        
        // Fee admin 5000 jika berbayar, 0 jika tiket gratis
        $adminFee = ($basePrice == 0 || $ticketFinalPrice == 0) ? 0 : 5000;
        $totalPrice = $ticketFinalPrice + $adminFee;

        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));
        $qrToken = 'QR-' . strtoupper(Str::random(12));

        // --- BYPASS MIDTRANS UNTUK ACARA GRATIS / HARGA RP 0 ---
        if ($totalPrice == 0) {
            try {
                $transaction = DB::transaction(function () use ($event, $orderId, $request, $qrToken, $coupon, $selectedTier) {
                    if ($selectedTier && !is_null($selectedTier->stock)) {
                        $tierReserved = \App\Models\TicketTier::where('id', $selectedTier->id)
                            ->where('stock', '>', 0)
                            ->decrement('stock');
                        if (!$tierReserved) {
                            throw new \Exception('Mohon maaf, tiket kategori ' . $selectedTier->name . ' sudah habis.');
                        }
                    }

                    $reserved = Event::where('id', $event->id)
                        ->where('stock', '>', 0)
                        ->decrement('stock');

                    if (!$reserved) {
                        throw new \Exception('Mohon maaf, tiket untuk acara ini sudah habis.');
                    }

                    return Transaction::create($this->buildTransactionPayload([
                        'event_id' => $event->id,
                        'order_id' => $orderId,
                        'customer_name' => $request->customer_name,
                        'customer_email' => $request->customer_email,
                        'customer_phone' => $request->customer_phone,
                        'total_price' => 0,
                        'status' => 'settlement',
                        'qr_token' => $qrToken,
                    ], $coupon, $selectedTier));
                });
            } catch (\Exception $e) {
                return back()->withInput()->with('error', 'Gagal membuat transaksi: ' . $e->getMessage());
            }

            if ($coupon) {
                $coupon->increment('used_count');
            }

            $transaction->sendTicket();

            return redirect()->route('checkout.success', $orderId)
                ->with('success', 'Pendaftaran berhasil! E-Ticket Anda telah dikirimkan ke email dan WhatsApp.');
        }

        // --- TRANSAKSI BERBAYAR (MIDTRANS) ---
        try {
            $transaction = DB::transaction(function () use ($event, $orderId, $request, $totalPrice, $qrToken, $coupon, $selectedTier) {
                if ($selectedTier && !is_null($selectedTier->stock)) {
                    $tierReserved = \App\Models\TicketTier::where('id', $selectedTier->id)
                        ->where('stock', '>', 0)
                        ->decrement('stock');
                    if (!$tierReserved) {
                        throw new \Exception('Mohon maaf, tiket kategori ' . $selectedTier->name . ' sudah habis.');
                    }
                }

                $reserved = Event::where('id', $event->id)
                    ->where('stock', '>', 0)
                    ->decrement('stock');

                if (!$reserved) {
                    throw new \Exception('Mohon maaf, tiket untuk acara ini sudah habis.');
                }

                return Transaction::create($this->buildTransactionPayload([
                    'event_id' => $event->id,
                    'order_id' => $orderId,
                    'customer_name' => $request->customer_name,
                    'customer_email' => $request->customer_email,
                    'customer_phone' => $request->customer_phone,
                    'total_price' => $totalPrice,
                    'status' => 'pending',
                    'snap_token' => null,
                    'qr_token' => $qrToken,
                    'expires_at' => now()->addMinutes(15),
                ], $coupon, $selectedTier));
            });
        } catch (\Exception $e) {
            return back()
                ->withInput()
                ->with('error', 'Gagal memproses checkout: ' . $e->getMessage());
        }

        // Send Abandoned Cart Recovery (Payment Link via WA) immediately on creation
        try {
            app(WhatsAppService::class)->sendAbandonedCartRecovery($transaction);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending WA payment link recovery: ' . $e->getMessage());
        }

        \Midtrans\Config::$serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = config('services.midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false);
        \Midtrans\Config::$isSanitized = true;
        \Midtrans\Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => $orderId,
                'gross_amount' => $totalPrice,
            ],
            'customer_details' => [
                'first_name' => $request->customer_name,
                'email' => $request->customer_email,
                'phone' => $request->customer_phone,
            ],
            'item_details' => [
                [
                    'id' => $event->id,
                    'price' => $ticketFinalPrice,
                    'quantity' => 1,
                    'name' => Str::limit($event->title, 45),
                ],
            ],
            'callbacks' => [
                'finish' => route('checkout.success', $orderId),
            ],
        ];

        if ($adminFee > 0) {
            $params['item_details'][] = [
                'id' => 'ADMIN-FEE',
                'price' => $adminFee,
                'quantity' => 1,
                'name' => 'Biaya Layanan',
            ];
        }

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $transaction->update([
                'snap_token' => $snapToken,
            ]);

            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
            // Rollback stok if error
            $transaction->releaseStock();
            $transaction->update(['status' => 'failed']);

            return back()
                ->withInput()
                ->with('error', 'Gagal memproses pembayaran: ' . $e->getMessage());
        }
    }

    public function payment($orderId)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->firstOrFail();

        $categories = Category::all();

        return view('checkout.payment', compact('transaction', 'categories'));
    }

    public function success($order_id)
    {
        $transaction = Transaction::with('event')
            ->where('order_id', $order_id)
            ->firstOrFail();

        $serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        if (!empty($serverKey)) {
            \Midtrans\Config::$serverKey = $serverKey;
            \Midtrans\Config::$isProduction = config('services.midtrans.is_production') ?? env('MIDTRANS_IS_PRODUCTION', false);
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            try {
                $midtransStatus = \Midtrans\Transaction::status($transaction->order_id);
                $status = $midtransStatus->transaction_status ?? null;

                if (in_array($status, ['capture', 'settlement'])) {
                    $transaction->update(['status' => 'settlement']);
                    $transaction->refresh();
                } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                    $transaction->update(['status' => 'failed']);
                    $transaction->releaseStock();
                }
            } catch (\Exception $e) {
                // Ignore midtrans status check error if simulation/dummy
            }
        }

        // Pastikan QR token terisi jika settlement
        if (in_array(strtolower($transaction->status), ['settlement', 'success'])) {
            // Pastikan qr_token ada
            if (!$transaction->qr_token) {
                $transaction->update([
                    'qr_token' => 'QR-' . strtoupper(Str::random(12)),
                ]);
                $transaction->refresh();
            }

            // Kirim E-Ticket via email & WhatsApp HANYA SEKALI
            $transaction->sendTicket();
        }

        return view('checkout.success', compact('transaction'));
    }

    public function applyCoupon(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
            'event_id' => 'required|integer',
            'tier_id' => 'nullable|integer|exists:ticket_tiers,id',
        ]);

        $event = Event::findOrFail($request->event_id);
        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();
 
        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak ditemukan.'], 200);
        }
 
        if (!$coupon->isValidForEvent($event->id)) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak berlaku atau sudah kadaluarsa.'], 200);
        }
 
        $selectedTier = null;
        $basePrice = $event->current_price;
        if ($request->filled('tier_id')) {
            $selectedTier = \App\Models\TicketTier::where('id', $request->tier_id)
                ->where('event_id', $event->id)
                ->where(function ($q) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', now());
                })
                ->where(function ($q) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', now());
                })
                ->first();
 
            if (!$selectedTier) {
                return response()->json(['success' => false, 'message' => 'Pilihan tier tiket tidak valid atau tidak tersedia.'], 200);
            }
 
            $basePrice = $selectedTier->price;
        }
 
        if (!$coupon->appliesToTicketTier($selectedTier)) {
            return response()->json(['success' => false, 'message' => 'Kupon ini tidak berlaku untuk tier tiket yang dipilih.'], 200);
        }

        $discount = $coupon->calculateDiscount($basePrice);
        $finalTicketPrice = max(0, $basePrice - $discount);
        $adminFee = ($basePrice == 0 || $finalTicketPrice == 0) ? 0 : 5000;
        $totalPrice = $finalTicketPrice + $adminFee;

        return response()->json([
            'success' => true,
            'message' => 'Kupon berhasil diterapkan!',
            'coupon_code' => $coupon->code,
            'discount' => $discount,
            'discount_formatted' => 'Rp ' . number_format($discount, 0, ',', '.'),
            'final_ticket_price' => $finalTicketPrice,
            'admin_fee' => $adminFee,
            'total_price' => $totalPrice,
            'total_price_formatted' => 'Rp ' . number_format($totalPrice, 0, ',', '.'),
        ]);
    }
}