<?php

namespace App\Services;

use App\Models\Certificate;
use App\Models\Event;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Barryvdh\DomPDF\Facade\Pdf;

class CertificateService
{
    /**
     * Generate a PDF certificate for a user attending an event.
     * Returns the absolute path to the stored PDF.
     */
    public function generate(User $user, Event $event): string
    {
        // Render Blade view to HTML
        $html = view('certificates.template', compact('user', 'event'))->render();

        // Generate PDF (dompdf) and store it
        $pdf = Pdf::loadHTML($html);
        $fileName = "certificate_{$event->id}_{$user->id}.pdf";
        $filePath = "certificates/{$fileName}";

        // Ensure directory exists
        Storage::disk('local')->put($filePath, $pdf->output());

        // Return absolute path for further use
        return storage_path('app/' . $filePath);
    }
}
