<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Ambil 3 transaksi terbaru untuk tabel dashboard
        $latestTransactions = Transaction::with('event')
            ->latest()
            ->take(3)
            ->get();

        // Total pendapatan hanya dari transaksi yang sudah sukses
        $totalRevenue = Transaction::whereIn('status', ['success', 'settlement'])
            ->sum('total_price');

        // Tiket terjual dihitung dari jumlah transaksi sukses
        $ticketsSold = Transaction::whereIn('status', ['success', 'settlement'])
            ->count();

        // Jumlah event yang ada
        $activeEvents = Event::count();

        // Jumlah transaksi pending
        $pendingOrders = Transaction::where('status', 'pending')
            ->count();

        return view('admin.index', compact(
            'latestTransactions',
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders'
        ));
    }
}