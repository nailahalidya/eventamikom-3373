<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TicketController extends Controller
{
    /**
     * Halaman 'Temukan Tiket Saya' (Public Ticket Search & User Tickets)
     */
    public function index(Request $request)
    {
        $search = trim($request->input('search'));
        $transactions = collect();
        $hasSearched = false;

        if (!empty($search)) {
            $hasSearched = true;
            $transactions = Transaction::with(['event'])
                ->where(function ($q) use ($search) {
                    $q->where('order_id', 'like', "%{$search}%")
                      ->orWhere('customer_email', 'like', "%{$search}%")
                      ->orWhere('customer_phone', 'like', "%{$search}%")
                      ->orWhere('customer_name', 'like', "%{$search}%");
                })
                ->latest()
                ->get();
        } elseif (Auth::check()) {
            // Jika user login dan belum melakukan pencarian spesifik,
            // tampilkan transaksi miliknya otomatis
            $user = Auth::user();
            $transactions = Transaction::with(['event'])
                ->where(function ($q) use ($user) {
                    $q->where('user_id', $user->id)
                      ->orWhere('customer_email', $user->email);
                })
                ->latest()
                ->get();
        }

        return view('tickets.index', compact('transactions', 'search', 'hasSearched'));
    }
}