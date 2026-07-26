<?php

namespace App\Console\Commands;

use App\Models\Transaction;
use App\Services\WhatsAppService;
use Illuminate\Console\Command;

class SendAbandonedCartReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cart:recover-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Kirim pengingat WhatsApp Abandoned Cart berisi link pembayaran Midtrans ke pembeli yang belum menyelesaikan transaksi';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $waService)
    {
        $pendingTransactions = Transaction::with('event')
            ->where('status', 'pending')
            ->whereNull('wa_reminder_sent_at')
            ->where('created_at', '<=', now()->subMinutes(3))
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>', now());
            })
            ->get();

        if ($pendingTransactions->isEmpty()) {
            $this->info('Tidak ada transaksi pending yang perlu diingatkan saat ini.');
            return 0;
        }

        $count = 0;
        foreach ($pendingTransactions as $transaction) {
            $this->info("Mengirim pengingat WA Abandoned Cart ke {$transaction->customer_name} (#{$transaction->order_id})...");
            $waService->sendAbandonedCartRecovery($transaction);
            $count++;
        }

        $this->info("Berhasil mengirim {$count} pengingat WhatsApp Abandoned Cart!");
        return 0;
    }
}
