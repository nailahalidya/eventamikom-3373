<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\Organizer;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        // Status transaksi yang dianggap sudah berhasil/lunas
        $successStatuses = ['success', 'settlement'];

        // Auto-sync pending transactions dengan Midtrans API
        $pendingCheck = Transaction::where('status', 'pending')->take(10)->get();
        if ($pendingCheck->isNotEmpty()) {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            foreach ($pendingCheck as $trx) {
                try {
                    $midtransStatus = \Midtrans\Transaction::status($trx->order_id);
                    $status = $midtransStatus->transaction_status ?? null;

                    if (in_array($status, ['capture', 'settlement'])) {
                        $trx->update(['status' => 'settlement']);
                    } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                        $trx->update(['status' => 'failed']);
                    }
                } catch (\Exception $e) {
                    // Abaikan jika id tidak ditemukan di Midtrans
                }
            }
        }

        // Ambil 5 transaksi terbaru untuk dashboard
        $latestTransactions = Transaction::with('event')
            ->latest()
            ->take(5)
            ->get();

        // Alias tambahan kalau ada Blade yang pakai nama recentTransactions
        $recentTransactions = $latestTransactions;

        // Total pendapatan dari transaksi sukses/lunas
        $totalRevenue = Transaction::whereIn('status', ['success', 'settlement'])
            ->sum('total_price');

        // Tiket terjual dari transaksi sukses/lunas
        $ticketsSold = Transaction::whereIn('status', ['success', 'settlement'])
            ->count();

        // Event aktif: event yang tanggalnya hari ini atau ke depan
        $activeEvents = Event::where('date', '>=', now())
            ->count();

        $totalEvents = Event::count();

        // Transaksi yang masih pending
        $pendingOrders = Transaction::where('status', 'pending')
            ->count();

        $totalOrganizers = Organizer::count();

        $approvedOrganizers = Organizer::where('status', 'approved')->count();

        $pendingOrganizers = Organizer::where('status', 'pending')->count();

        $rejectedOrganizers = Organizer::where('status', 'rejected')->count();

        $recentOrganizers = Organizer::latest()
            ->take(5)
            ->get();

        // Data statistik Pengguna & Pertumbuhan
        $totalUsers = User::count();

        // Menghitung tren pertumbuhan bulanan (6 bulan terakhir)
        $months = [];
        $userGrowthData = [];
        $eventGrowthData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthLabel = $date->translatedFormat('M Y');
            $startOfMonth = $date->copy()->startOfMonth();
            $endOfMonth = $date->copy()->endOfMonth();

            $uCount = User::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();
            $eCount = Event::whereBetween('created_at', [$startOfMonth, $endOfMonth])->count();

            $months[] = $monthLabel;
            $userGrowthData[] = $uCount;
            $eventGrowthData[] = $eCount;
        }

        $currentMonthUsers = User::whereBetween('created_at', [
            Carbon::now()->startOfMonth(),
            Carbon::now()->endOfMonth()
        ])->count();

        $lastMonthUsers = User::whereBetween('created_at', [
            Carbon::now()->subMonth()->startOfMonth(),
            Carbon::now()->subMonth()->endOfMonth()
        ])->count();

        if ($lastMonthUsers > 0) {
            $userGrowthPercentage = round((($currentMonthUsers - $lastMonthUsers) / $lastMonthUsers) * 100, 1);
        } else {
            $userGrowthPercentage = $currentMonthUsers > 0 ? 100 : 0;
        }

        return view('admin.index', compact(
            'latestTransactions',
            'totalRevenue',
            'ticketsSold',
            'activeEvents',
            'totalEvents',
            'pendingOrders',

            'totalOrganizers',
            'approvedOrganizers',
            'pendingOrganizers',
            'rejectedOrganizers',
            'recentOrganizers',

            'totalUsers',
            'currentMonthUsers',
            'userGrowthPercentage',
            'months',
            'userGrowthData',
            'eventGrowthData'
        ));
    }
}

