<?php

namespace App\Http\Controllers;

use App\Jobs\GenerateAndSendCertificate;
use App\Models\Certificate;
use App\Models\Event;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CertificateController extends Controller
{
    /**
     * Trigger manual issuance of certificate (misal dari scan barcode kehadiran atau tombol manual).
     */
    public function issue(Request $request, Event $event)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);

        $user = User::findOrFail($request->user_id);

        // Cek apakah sertifikat sudah pernah dibuat
        $existing = Certificate::where('user_id', $user->id)
            ->where('event_id', $event->id)
            ->first();

        if ($existing) {
            return back()->with('info', 'Sertifikat untuk peserta ini sudah pernah diterbitkan.');
        }

        // Dispatch job pembuatan & pengiriman sertifikat
        GenerateAndSendCertificate::dispatch($user->id, $event->id);

        return back()->with('success', 'Proses penerbitan sertifikat telah dikirim ke antrean & email peserta.');
    }

    /**
     * Download sertifikat PDF peserta.
     */
    public function download(Certificate $certificate)
    {
        // Pastikan pengguna yang login adalah pemilik sertifikat atau admin
        if (auth()->id() !== $certificate->user_id && auth()->user()?->role !== 'admin') {
            abort(403, 'Anda tidak memiliki akses ke sertifikat ini.');
        }

        if (!file_exists($certificate->file_path)) {
            return back()->with('error', 'File sertifikat tidak ditemukan.');
        }

        return response()->download($certificate->file_path, "Sertifikat-{$certificate->event->title}-{$certificate->user->name}.pdf");
    }
}
