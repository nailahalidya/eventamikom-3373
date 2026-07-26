<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>E-Ticket — {{ $transaction->event->title ?? 'AmikomEventHub' }}</title>
</head>
<body style="font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f8fafc; margin: 0; padding: 40px 20px; color: #1e293b;">
    <div style="max-w: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 24px; overflow: hidden; box-shadow: 0 10px 25px rgba(0,0,0,0.05); border: 1px solid #e2e8f0;">
        
        <!-- Header -->
        <div style="background-color: #4f46e5; padding: 32px; text-align: center; color: #ffffff;">
            <div style="font-size: 32px; margin-bottom: 8px;">🎟️</div>
            <h1 style="margin: 0; font-size: 24px; font-weight: 800; tracking-tight: -0.5px;">E-Ticket Resmi Anda</h1>
            <p style="margin: 6px 0 0 0; color: #c7d2fe; font-size: 14px;">Terima kasih telah memesan tiket di AmikomEventHub</p>
        </div>

        <!-- Ticket Body -->
        <div style="padding: 32px;">
            <div style="background-color: #f1f5f9; border-radius: 16px; padding: 20px; text-align: center; margin-bottom: 24px;">
                <span style="font-size: 11px; font-weight: 700; color: #6366f1; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 4px;">Nama Event</span>
                <h2 style="margin: 0; font-size: 20px; font-weight: 800; color: #0f172a;">{{ $transaction->event->title ?? 'Event Amikom' }}</h2>
            </div>

            <!-- Detail Grid -->
            <table style="width: 100%; border-collapse: collapse; margin-bottom: 24px;">
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; width: 50%;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Nama Pemegang</span>
                        <strong style="font-size: 14px; color: #1e293b;">{{ $transaction->customer_name }}</strong>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #f1f5f9; width: 50%;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Order ID</span>
                        <strong style="font-size: 14px; color: #4f46e5; font-family: monospace;">{{ $transaction->order_id }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Tanggal & Waktu</span>
                        <strong style="font-size: 14px; color: #1e293b;">{{ $transaction->event ? \Carbon\Carbon::parse($transaction->event->date)->format('d M Y, H:i') : '-' }}</strong>
                    </td>
                    <td style="padding: 12px; border-bottom: 1px solid #f1f5f9;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Lokasi</span>
                        <strong style="font-size: 14px; color: #1e293b;">{{ $transaction->event->location ?? '-' }}</strong>
                    </td>
                </tr>
                <tr>
                    <td style="padding: 12px;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Total Bayar</span>
                        <strong style="font-size: 14px; color: #059669;">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</strong>
                    </td>
                    <td style="padding: 12px;">
                        <span style="font-size: 11px; color: #94a3b8; font-weight: 700; text-transform: uppercase; display: block;">Status</span>
                        <strong style="font-size: 14px; color: #059669; text-transform: uppercase;">{{ $transaction->status }}</strong>
                    </td>
                </tr>
            </table>

            <!-- QR Code Section -->
            <div style="background-color: #f8fafc; border: 2px dashed #cbd5e1; border-radius: 20px; padding: 24px; text-align: center;">
                <span style="font-size: 11px; color: #64748b; font-weight: 700; text-transform: uppercase; letter-spacing: 1px; display: block; margin-bottom: 12px;">Kode QR Tiket Unik</span>
                
                <div style="display: inline-block; background-color: #ffffff; padding: 12px; border-radius: 12px; border: 1px solid #e2e8f0;">
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=160x160&data={{ urlencode($transaction->qr_token ?? $transaction->order_id) }}" alt="QR Code" style="width: 160px; height: 160px; display: block;" />
                </div>

                <p style="margin: 12px 0 0 0; font-family: monospace; font-weight: 700; font-size: 14px; color: #334155;">
                    {{ $transaction->qr_token ?? $transaction->order_id }}
                </p>
                <p style="margin: 6px 0 0 0; font-size: 11px; color: #94a3b8;">
                    Tunjukkan QR Code ini kepada petugas pintu pada hari-H untuk check-in.
                </p>
            </div>
        </div>

        <!-- Footer -->
        <div style="background-color: #f1f5f9; padding: 20px; text-align: center; font-size: 12px; color: #94a3b8;">
            AmikomEventHub &copy; {{ date('Y') }} — Platform Ticketing Event Terpercaya
        </div>
    </div>
</body>
</html>
