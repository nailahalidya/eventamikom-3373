<?php

namespace App\Services;

use App\Models\Transaction;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class WhatsAppService
{
    /**
     * Format phone number to international 62 format (Indonesia)
     */
    public static function formatPhone($phone)
    {
        $cleaned = preg_replace('/[^0-9]/', '', $phone);
        if (str_starts_with($cleaned, '0')) {
            return '62' . substr($cleaned, 1);
        }
        return $cleaned;
    }

    /**
     * Send WhatsApp E-Ticket Confirmation after successful payment
     */
    public function sendTicketNotification(Transaction $transaction)
    {
        $phone = self::formatPhone($transaction->customer_phone);
        $eventName = $transaction->event->title ?? 'Event AmikomEventHub';
        $ticketUrl = route('checkout.success', $transaction->order_id);

        $message = "Halo *{$transaction->customer_name}*! 🎉\n\n" .
                   "Pembayaran tiket Anda untuk *{$eventName}* telah *BERHASIL*!\n\n" .
                   "📌 *Order ID:* #{$transaction->order_id}\n" .
                   "🎟️ *Kode Tiket:* {$transaction->qr_token}\n" .
                   "💰 *Total Bayar:* Rp " . number_format($transaction->total_price, 0, ',', '.') . "\n\n" .
                   "Lihat dan tunjukkan E-Ticket QR Code Anda melalui link berikut saat masuk lokasi acara:\n" .
                   "👉 {$ticketUrl}\n\n" .
                   "Terima kasih telah menggunakan *AmikomEventHub*! 🚀";

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send WhatsApp Abandoned Cart Recovery (Payment Link Reminder)
     */
    public function sendAbandonedCartRecovery(Transaction $transaction)
    {
        $phone = self::formatPhone($transaction->customer_phone);
        $eventName = $transaction->event->title ?? 'Event AmikomEventHub';
        $paymentUrl = route('checkout.payment', $transaction->order_id);

        $message = "Halo *{$transaction->customer_name}*! 👋\n\n" .
                   "Pesanan tiket *{$eventName}* Anda masih menunggu pembayaran.\n" .
                   "Jangan sampai kehabisan tiket! Selesaikan pembayaran Anda sebelum transaksi kedaluwarsa.\n\n" .
                   "📌 *Order ID:* #{$transaction->order_id}\n" .
                   "💰 *Total Tagihan:* Rp " . number_format($transaction->total_price, 0, ',', '.') . "\n\n" .
                   "Klik link di bawah ini untuk melanjutkan pembayaran Midtrans Anda:\n" .
                   "👉 {$paymentUrl}\n\n" .
                   "Pertanyaan? Hubungi tim support AmikomEventHub.";

        // Update timestamp so we don't send duplicate reminders
        $transaction->update([
            'wa_reminder_sent_at' => now(),
        ]);

        return $this->sendMessage($phone, $message);
    }

    /**
     * Send HTTP API message to Fonnte / WhatsApp Gateway API
     */
    protected function sendMessage($phone, $message)
    {
        $token = env('FONNTE_WA_TOKEN', env('WA_API_TOKEN'));
        $endpoint = env('FONNTE_WA_URL', 'https://api.fonnte.com/send');

        if (empty($token)) {
            Log::info("WhatsApp Notification Simulated (No WA Token in .env)", [
                'phone' => $phone,
                'message' => $message,
            ]);
            return [
                'success' => true,
                'simulated' => true,
                'message' => 'Notifikasi WhatsApp disimulasikan di log.',
            ];
        }

        try {
            $response = Http::withHeaders([
                'Authorization' => $token,
            ])->post($endpoint, [
                'target' => $phone,
                'message' => $message,
            ]);

            Log::info("WhatsApp API Response", ['body' => $response->body()]);

            return [
                'success' => $response->successful(),
                'response' => $response->json(),
            ];
        } catch (\Exception $e) {
            Log::error("Failed to send WhatsApp message: " . $e->getMessage());
            return [
                'success' => false,
                'error' => $e->getMessage(),
            ];
        }
    }
}
