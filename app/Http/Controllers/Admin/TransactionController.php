<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Transaction;
use Illuminate\Http\Request;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function index(Request $request)
    {
        // 1. Auto-expire pending transactions if event date has passed or expires_at passed
        $pendingList = Transaction::where('status', 'pending')->with('event')->get();

        foreach ($pendingList as $trx) {
            $isExpiredByTime = $trx->expires_at && Carbon::parse($trx->expires_at)->isPast();
            $isExpiredByEvent = $trx->event && $trx->event->date && Carbon::parse($trx->event->date)->isPast();

            if ($isExpiredByTime || $isExpiredByEvent) {
                $trx->status = 'expired';
                $trx->save();
                $trx->releaseStock();
            }
        }

        // 2. Auto-sync remaining pending transactions with Midtrans API
        $pendingTransactions = Transaction::where('status', 'pending')->take(20)->get();

        if ($pendingTransactions->isNotEmpty()) {
            \Midtrans\Config::$serverKey = env('MIDTRANS_SERVER_KEY');
            \Midtrans\Config::$isProduction = env('MIDTRANS_IS_PRODUCTION', false);

            foreach ($pendingTransactions as $trx) {
                try {
                    $midtransStatus = \Midtrans\Transaction::status($trx->order_id);
                    $status = $midtransStatus->transaction_status ?? null;

                    if (in_array($status, ['capture', 'settlement'])) {
                        $trx->update(['status' => 'settlement']);
                    } elseif (in_array($status, ['cancel', 'deny', 'expire'])) {
                        $trx->update(['status' => 'expired']);
                        $trx->releaseStock();
                    }
                } catch (\Exception $e) {
                    // Ignore sandbox missing transaction
                }
            }
        }

        // 3. Query with Search & Filter
        $query = Transaction::with('event')->latest();

        // Search Filter
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('order_id', 'like', "%{$search}%")
                  ->orWhere('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_email', 'like', "%{$search}%");
            });
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $status = strtolower($request->status);
            if ($status === 'success' || $status === 'settlement') {
                $query->whereIn('status', ['success', 'settlement']);
            } elseif ($status === 'pending') {
                $query->where('status', 'pending');
            } elseif ($status === 'expired' || $status === 'failed') {
                $query->whereIn('status', ['expired', 'failed', 'cancel', 'deny']);
            } else {
                $query->where('status', $status);
            }
        }

        // Period Filter
        if ($request->filled('period') && $request->period !== 'all') {
            if ($request->period === 'this_month') {
                $query->whereMonth('created_at', Carbon::now()->month)
                      ->whereYear('created_at', Carbon::now()->year);
            } elseif ($request->period === 'last_month') {
                $query->whereMonth('created_at', Carbon::now()->subMonth()->month)
                      ->whereYear('created_at', Carbon::now()->subMonth()->year);
            } elseif ($request->period === 'this_year') {
                $query->whereYear('created_at', Carbon::now()->year);
            }
        }

        $transactions = $query->paginate(20)->withQueryString();

        return view('admin.transaction.index', compact('transactions'));
    }

    /**
     * Hapus transaksi
     */
    public function destroy(Transaction $transaction)
    {
        // Release stock jika transaksi masih pending/reserved
        if ($transaction->status === 'pending') {
            $transaction->releaseStock();
        }

        $transaction->delete();

        return back()->with('success', 'Data transaksi berhasil dihapus.');
    }
}