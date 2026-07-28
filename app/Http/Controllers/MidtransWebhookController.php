<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Mail\TicketMail;
use App\Services\WhatsAppService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;

class MidtransWebhookController extends Controller
{
    public function handle(Request $request)
    {
        Log::info('Midtrans Callback Received', $request->all());

        $payload = $request->all();

        $signatureKey = $payload['signature_key'] ?? null;
        $orderId = $payload['order_id'] ?? null;
        $transactionStatus = $payload['transaction_status'] ?? null;
        $fraudStatus = $payload['fraud_status'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;

        if (!$orderId || !$signatureKey || !$statusCode || !$grossAmount) {
            return response()->json([
                'message' => 'Invalid payload, but callback endpoint is reachable',
            ], 200);
        }

        if (!$this->verifySignature($orderId, $statusCode, $grossAmount, $signatureKey)) {
            Log::warning('Midtrans callback signature verification failed', [
                'order_id' => $orderId,
                'received_signature' => $signatureKey,
            ]);

            return response()->json([
                'message' => 'Signature verification failed',
            ], 403);
        }

        $transaction = Transaction::with('event')
            ->where('order_id', $orderId)
            ->first();

        if (!$transaction) {
            Log::warning('Transaction not found for Midtrans callback', [
                'order_id' => $orderId,
                'payload' => $payload,
            ]);

            return response()->json([
                'message' => 'Transaction not found, but callback received',
                'order_id' => $orderId,
            ], 200);
        }

        if (in_array($transaction->status, ['success', 'settlement'])) {
            return response()->json([
                'message' => 'Already processed',
            ], 200);
        }

        if ($transactionStatus === 'capture') {
            if ($fraudStatus === 'challenge') {
                $transaction->status = 'challenge';
            } else {
                $transaction->status = 'success';
                $this->processSuccess($transaction);
            }
        } elseif ($transactionStatus === 'settlement') {
            $transaction->status = 'settlement';
            $this->processSuccess($transaction);
        } elseif ($transactionStatus === 'pending') {
            $transaction->status = 'pending';
        } elseif (in_array($transactionStatus, ['cancel', 'deny', 'expire'])) {
            $transaction->status = 'failed';
        }

        $transaction->save();

        return response()->json([
            'message' => 'OK',
            'order_id' => $orderId,
            'status' => $transaction->status,
        ], 200);
    }

    private function processSuccess(Transaction $transaction)
    {
        // Stok sudah ditahan saat checkout/pembuatan transaksi pending.
        // Hanya kirim tiket, jangan mengurangi stok lagi.
        $transaction->sendTicket();
    }

    private function verifySignature(string $orderId, string $statusCode, string $grossAmount, string $signatureKey): bool
    {
        $serverKey = env('MIDTRANS_SERVER_KEY');
        if (!$serverKey) {
            Log::error('Midtrans signature verification failed because MIDTRANS_SERVER_KEY is missing.');
            return false;
        }

        $expectedSignature = hash('sha512', $orderId . $statusCode . $grossAmount . $serverKey);

        return hash_equals($expectedSignature, $signatureKey);
    }
}
