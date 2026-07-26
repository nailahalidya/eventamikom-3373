<?php

namespace App\Console\Commands;

use App\Jobs\GenerateAndSendCertificate;
use App\Models\Event;
use App\Models\Transaction;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Console\Command;

class GenerateCertificatesForEndedEvents extends Command
{
    protected $signature = 'certificates:generate';
    protected $description = 'Generate and email e‑certificates for events that have ended and for participants with successful transactions.';

    public function handle()
    {
        // Find events that ended before now
        $endedEvents = Event::where('date', '<', now())->get();

        foreach ($endedEvents as $event) {
            // Find successful transactions for this event
            $transactions = Transaction::where('event_id', $event->id)
                ->whereIn('status', ['success', 'settlement', 'Success', 'Settlement', 'PAID', 'paid'])
                ->get();

            foreach ($transactions as $transaction) {
                $user = User::where('email', $transaction->customer_email)->first();
                if (!$user) continue;

                // Avoid duplicate certificates
                $already = $event->certificates()->where('user_id', $user->id)->exists();
                if ($already) continue;

                // Dispatch job to generate and send
                GenerateAndSendCertificate::dispatch($user->id, $event->id);
            }
        }

        $this->info('Certificate generation jobs dispatched.');
        return 0;
    }
}
