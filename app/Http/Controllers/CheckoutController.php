<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Coupon;
use App\Models\Event;
use App\Models\Transaction;
use App\Mail\TicketMail;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();
        $ticketPrice = $event->current_price;
        $activeTier = $event->active_tier_name;

        return view('checkout.create', compact('event', 'categories', 'ticketPrice', 'activeTier'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
            'coupon_code' => 'nullable|string',
        ]);

        if ($event->stock <= 0) {
            return back()
                ->withInput()
                ->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        $basePrice = $event->current_price;
        $discount = 0;
        $coupon = null;

        if ($request->filled('coupon_code')) {
            $coupon = Coupon::where('code', strtoupper($request->coupon_code))->first();
            if ($coupon && $coupon->isValidForEvent($event->id)) {
                $discount = $coupon->calculateDiscount($basePrice);
            }
        }

        $ticketFinalPrice = max(0, $basePrice - $discount);
        
        // Fee admin 5000 jika berbayar, 0 jika tiket gratis
        $adminFee = ($basePrice == 0 || $ticketFinalPrice == 0) ? 0 : 5000;
        $totalPrice = $ticketFinalPrice + $adminFee;

        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));
        $qrToken = 'QR-' . strtoupper(Str::random(12));

        // --- BYPASS MIDTRANS UNTUK ACARA GRATIS / HARGA RP 0 ---
        if ($totalPrice == 0) {
            $transaction = Transaction::create([
                'event_id' => $event->id,
                'order_id' => $orderId,
                'customer_name' => $request->customer_name,
                'customer_email' => $request->customer_email,
                'customer_phone' => $request->customer_phone,
                'total_price' => 0,
                'status' => 'settlement',
                'qr_token' => $qrToken,
            ]);

            // Potong stok langsung
            $event->decrement('stock');

            if ($coupon) {
                $coupon->increment('used_count');
            }

            // Kirim E-Ticket ke email & WhatsApp
            $transaction->sendTicket();

            return redirect()->route('checkout.success', $orderId)
                ->with('success', 'Pendaftaran berhasil! E-Ticket Anda telah dikirimkan ke email dan WhatsApp.');
        }

        // --- TRANSAKSI BERBAYAR (MIDTRANS) ---
        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'pending',
            'qr_token' => $qrToken,
            'expires_at' => now()->addMinutes(15),
        ]);

        // Tahan stok sementara
        $event->decrement('stock');

        if ($coupon) {
            $coupon->increment('used_count');
        }

        // Send Abandoned Cart Recovery (Payment Link via WA) immediately on creation
        try {
            app(WhatsAppService::class)->sendAbandonedCartRecovery($transaction);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending WA payment link recovery: ' . $e->getMessage());
        }

        \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        \Midtrans\Config::$isProduction = false;
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

        if (env('MIDTRANS_SERVER_KEY')) {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = false;
            \Midtrans\Config::$isSanitized = true;
            \Midtrans\Config::$is3ds = true;

            try {
                $midtransStatus = \Midtrans\Transaction::status($transaction->order_id);
                $status = $midtransStatus->transaction_status ?? null;

                if (in_array($status, ['capture', 'settlement'])) {
                    $transaction->update(['status' => 'settlement']);
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
        ]);

        $event = Event::findOrFail($request->event_id);
        $coupon = Coupon::where('code', strtoupper(trim($request->code)))->first();

        if (!$coupon) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak ditemukan.'], 404);
        }

        if (!$coupon->isValidForEvent($event->id)) {
            return response()->json(['success' => false, 'message' => 'Kode kupon tidak berlaku atau sudah kadaluarsa.'], 422);
        }

        $basePrice = $event->current_price;
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