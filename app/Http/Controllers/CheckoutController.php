<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class CheckoutController extends Controller
{
    public function create(Event $event)
    {
        $categories = Category::all();

        return view('checkout.create', compact('event', 'categories'));
    }

    public function store(Request $request, Event $event)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_email' => 'required|email|max:255',
            'customer_phone' => 'required|string|max:20',
        ]);

        if ($event->stock <= 0) {
            return back()
                ->withInput()
                ->with('error', 'Mohon maaf, tiket untuk acara ini sudah habis.');
        }

        $orderId = 'TRX-' . time() . '-' . strtoupper(Str::random(5));
        $adminFee = 5000;
        $totalPrice = $event->price + $adminFee;

        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        // --- INTEGRASI SNAP MIDTRANS ---

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
                    'price' => $event->price,
                    'quantity' => 1,
                    'name' => $event->title,
                ],
                [
                    'id' => 'ADMIN-FEE',
                    'price' => $adminFee,
                    'quantity' => 1,
                    'name' => 'Biaya Admin',
                ],
            ],
        ];

        try {
            $snapToken = \Midtrans\Snap::getSnapToken($params);

            $transaction->update([
                'snap_token' => $snapToken,
            ]);

            return redirect()->route('checkout.payment', $transaction->order_id);

        } catch (\Exception $e) {
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
    $categories = Category::all();

    $transaction = Transaction::where('order_id', $order_id)->firstOrFail();

    \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
    \Midtrans\Config::$isProduction = false;

    try {
        $midtransStatus = \Midtrans\Transaction::status($order_id);

        if (in_array($midtransStatus->transaction_status, ['capture', 'settlement'])) {
            $transaction->update([
                'status' => 'success',
            ]);

            if ($transaction->event && $transaction->event->stock > 0) {
                $transaction->event->decrement('stock');
            }
        }

        return view('checkout.success', compact('transaction', 'categories'));

    } catch (\Exception $e) {
        return redirect()
            ->route('checkout.payment', $transaction->order_id)
            ->with('error', 'Gagal memvalidasi pembayaran: ' . $e->getMessage());
    }
}
}