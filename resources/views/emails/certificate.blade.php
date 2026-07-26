<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Certificate Kehadiran</title>
</head>
<body style="font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif; background-color: #f4f6f9; margin: 0; padding: 20px;">
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05);">
        <div style="background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 100%); padding: 30px 20px; text-align: center; color: #ffffff;">
            <h1 style="margin: 0; font-size: 24px; font-weight: 700;">Selamat! E-Sertifikat Anda Telah Terbit</h1>
            <p style="margin: 8px 0 0 0; opacity: 0.9; font-size: 14px;">EventAmikom Official E-Certificate</p>
        </div>
        <div style="padding: 30px; color: #334155; line-height: 1.6;">
            <p>Halo <strong>{{ $certificate->user->name ?? 'Peserta' }}</strong>,</p>
            <p>Terima kasih telah berpartisipasi dan menghadiri event <strong>{{ $certificate->event->title ?? 'Seminar/Workshop' }}</strong>.</p>
            <p>E-Sertifikat bukti kehadiran Anda telah berhasil diterbitkan dan dilampirkan pada email ini dalam format dokumen PDF.</p>
            <div style="background-color: #f8fafc; border-left: 4px solid #4f46e5; padding: 15px; border-radius: 4px; margin: 20px 0;">
                <p style="margin: 0; font-size: 14px; color: #475569;">
                    <strong>Tanggal Terbit:</strong> {{ \Carbon\Carbon::parse($certificate->issued_at)->translatedFormat('d F Y H:i') }}<br>
                    <strong>ID Sertifikat:</strong> #CERT-{{ $certificate->id }}
                </p>
            </div>
            <p style="font-size: 14px; color: #64748b;">Silakan unduh lampiran PDF di bawah ini untuk menyimpan sertifikat Anda.</p>
        </div>
        <div style="background-color: #f1f5f9; padding: 15px 30px; text-align: center; font-size: 12px; color: #94a3b8;">
            &copy; {{ date('Y') }} EventAmikom. All rights reserved.
        </div>
    </div>
</body>
</html>
