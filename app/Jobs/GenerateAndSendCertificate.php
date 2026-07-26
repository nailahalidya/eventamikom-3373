<?php

namespace App\Jobs;

use App\Mail\CertificateMail;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\User;
use App\Services\CertificateService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;

class GenerateAndSendCertificate implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $userId;
    protected $eventId;

    /**
     * Create a new job instance.
     */
    public function __construct(int $userId, int $eventId)
    {
        $this->userId = $userId;
        $this->eventId = $eventId;
    }

    /**
     * Execute the job.
     */
    public function handle(CertificateService $service)
    {
        $user = User::findOrFail($this->userId);
        $event = Event::findOrFail($this->eventId);

        // Generate PDF and get storage path
        $pdfPath = $service->generate($user, $event);

        // Store record
        $certificate = Certificate::create([
            'user_id'    => $user->id,
            'event_id'   => $event->id,
            'file_path'  => $pdfPath,
            'issued_at'  => now(),
        ]);

        // Send email with attachment
        Mail::to($user->email)->queue(new CertificateMail($certificate));
    }
}
