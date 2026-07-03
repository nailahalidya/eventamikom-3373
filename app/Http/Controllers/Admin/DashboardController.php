<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;

class DashboardController extends Controller
{
    public function index()
    {
        // Status transaksi yang dianggap sudah berhasil/lunas
        $successStatuses = ['success', 'settlement'];

        // Ambil 5 transaksi terbaru untuk dashboard
        $latestTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        // Alias tambahan kalau ada Blade yang pakai nama recentTransactions
        $recentTransactions = $latestTransactions;

        // Total pendapatan dari transaksi sukses/lunas
        $totalRevenue = Transaction::whereIn('status', $successStatuses)
            ->sum('total_price');

        // Tiket terjual dari transaksi sukses/lunas
        $ticketsSold = Transaction::whereIn('status', $successStatuses)
            ->count();

        // Event aktif: event yang tanggalnya hari ini atau ke depan
        $activeEvents = Event::where('date', '>=', now())
            ->count();

        // Transaksi yang masih pending
        $pendingOrders = Transaction::where('status', 'pending')
            ->count();

        return view('admin.index', compact(
            'latestTransactions',
            'recentTransactions',
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'pendingOrders'
        ));
    }
}