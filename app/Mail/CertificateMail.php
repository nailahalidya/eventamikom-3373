<?php

namespace App\Mail;

use App\Models\Certificate;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class CertificateMail extends Mailable
{
    use Queueable, SerializesModels;

    public $certificate;

    /**
     * Create a new message instance.
     */
    public function __construct(Certificate $certificate)
    {
        $this->certificate = $certificate;
    }

    /**
     * Build the message.
     */
    public function build()
    {
        return $this->subject('Your Attendance Certificate')
            ->view('emails.certificate')
            ->attach($this->certificate->file_path, [
                'as' => 'certificate.pdf',
                'mime' => 'application/pdf',
            ]);
    }
}
