<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;

class CheckinController extends Controller
{
    public function show()
    {
        return view('checkin.scanner');
    }

    public function scan(Request $request)
    {
        $request->validate([
            'code' => 'required|string',
        ]);

        $code = trim($request->code);

        $transaction = Transaction::with('event')
            ->where('qr_token', $code)
            ->orWhere('order_id', $code)
            ->first();

        if (!$transaction) {
            return response()->json([
                'success' => false,
                'status' => 'NOT_FOUND',
                'message' => 'Tiket TIDAK DITEMUKAN! Periksa kembali QR Code atau Order ID.',
            ], 404);
        }

        if (!in_array(strtolower($transaction->status), ['settlement', 'success', 'used'])) {
            return response()->json([
                'success' => false,
                'status' => 'UNPAID',
                'message' => 'Tiket BELUM LUNAS / KADALUARSA (Status: ' . strtoupper($transaction->status) . ').',
                'customer_name' => $transaction->customer_name,
                'order_id' => $transaction->order_id,
            ], 422);
        }

        if ($transaction->checked_in_at) {
            return response()->json([
                'success' => false,
                'status' => 'ALREADY_USED',
                'message' => '⚠️ DOUBLE ENTRY DETECTED! Tiket ini SUDAH DIGUNAKAN pada ' . $transaction->checked_in_at->format('H:i:s, d M Y'),
                'customer_name' => $transaction->customer_name,
                'event_title' => $transaction->event->title ?? '-',
                'order_id' => $transaction->order_id,
                'checked_in_at' => $transaction->checked_in_at->format('d M Y H:i:s'),
            ], 409);
        }

        // Mark as checked in
        $transaction->update([
            'checked_in_at' => now(),
            'status' => 'used',
        ]);

        // Terbitkan & kirim E-Sertifikat otomatis via Email saat check-in terverifikasi
        if ($transaction->event_id) {
            try {
                $user = \App\Models\User::where('email', $transaction->customer_email)->first();
                if (!$user) {
                    $user = \App\Models\User::firstOrCreate(
                        ['email' => $transaction->customer_email],
                        [
                            'name' => $transaction->customer_name ?? 'Peserta Event',
                            'role' => 'user',
                            'password' => \Illuminate\Support\Facades\Hash::make(\Illuminate\Support\Str::random(16)),
                        ]
                    );
                }

                $exists = \App\Models\Certificate::where('user_id', $user->id)
                    ->where('event_id', $transaction->event_id)
                    ->exists();

                if (!$exists) {
                    \App\Jobs\GenerateAndSendCertificate::dispatch($user->id, $transaction->event_id);
                }
            } catch (\Exception $e) {
                \Illuminate\Support\Facades\Log::error('Gagal memicu e-certificate pada checkin: ' . $e->getMessage());
            }
        }

        return response()->json([
            'success' => true,
            'status' => 'SUCCESS',
            'message' => '✅ CHECK-IN BERHASIL! E-Sertifikat otomatis diproses ke email.',
            'customer_name' => $transaction->customer_name,
            'customer_email' => $transaction->customer_email,
            'event_title' => $transaction->event->title ?? '-',
            'order_id' => $transaction->order_id,
            'checkin_time' => $transaction->checked_in_at->format('H:i:s'),
        ]);
    }
}
