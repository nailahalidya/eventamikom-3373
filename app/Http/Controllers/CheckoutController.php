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
        $totalPrice = $event->price + 5000;

        $transaction = Transaction::create([
            'event_id' => $event->id,
            'order_id' => $orderId,
            'customer_name' => $request->customer_name,
            'customer_email' => $request->customer_email,
            'customer_phone' => $request->customer_phone,
            'total_price' => $totalPrice,
            'status' => 'pending',
        ]);

        return redirect()
            ->route('checkout.create', $event->id)
            ->with('show_payment', true)
            ->with('order_id', $transaction->order_id)
            ->with('total_price', $transaction->total_price);
    }

    public function paymentSuccess($orderId)
    {
    $transaction = Transaction::where('order_id', $orderId)->firstOrFail();

    $transaction->update([
        'status' => 'success',
    ]);

    return redirect()
        ->route('ticket')
        ->with('success', 'Pembayaran berhasil. Tiket berhasil dibuat.');
    }
}