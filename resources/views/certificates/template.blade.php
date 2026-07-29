@php
/**
 * Certificate Blade Template for DomPDF
 */
@endphp
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8" />
    <title>Sertifikat Kehadiran - {{ $event->title }}</title>
    <style>
        @page {
            margin: 0;
            size: A4 landscape;
        }
        body {
            font-family: 'Helvetica', 'Arial', sans-serif;
            background-color: #ffffff;
            margin: 0;
            padding: 0;
            color: #1e293b;
        }
        .cert-container {
            width: 100%;
            height: 100%;
            padding: 30px;
            box-sizing: border-box;
            background: #ffffff;
        }
        .border-outer {
            border: 8px solid #312e81;
            padding: 8px;
            height: 94%;
            box-sizing: border-box;
            position: relative;
        }
        .border-inner {
            border: 2px solid #d97706;
            padding: 40px 50px;
            height: 96%;
            box-sizing: border-box;
            text-align: center;
            background: #fafaf9;
        }
        .logo-header {
            font-size: 18px;
            font-weight: bold;
            color: #4338ca;
            letter-spacing: 2px;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .cert-title {
            font-size: 36px;
            font-weight: bold;
            color: #1e1b4b;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 5px;
        }
        .cert-subtitle {
            font-size: 14px;
            color: #b45309;
            font-weight: bold;
            letter-spacing: 1.5px;
            text-transform: uppercase;
            margin-bottom: 30px;
        }
        .presented-to {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 10px;
        }
        .participant-name {
            font-size: 32px;
            font-weight: bold;
            color: #312e81;
            border-bottom: 2px solid #cbd5e1;
            display: inline-block;
            padding-bottom: 8px;
            margin-bottom: 25px;
            min-width: 400px;
        }
        .appreciation-text {
            font-size: 15px;
            color: #334155;
            line-height: 1.6;
            max-width: 700px;
            margin: 0 auto 25px auto;
        }
        .event-name {
            font-size: 22px;
            font-weight: bold;
            color: #0f172a;
            margin: 5px 0;
        }
        .meta-info {
            font-size: 12px;
            color: #64748b;
            margin-top: 30px;
        }
        .footer-table {
            width: 100%;
            margin-top: 40px;
        }
        .footer-cell {
            width: 50%;
            text-align: center;
            vertical-align: bottom;
        }
        .sig-line {
            width: 180px;
            border-top: 1.5px solid #475569;
            margin: 10px auto 5px auto;
        }
        .sig-title {
            font-size: 12px;
            font-weight: bold;
            color: #1e293b;
        }
        .sig-sub {
            font-size: 11px;
            color: #64748b;
        }
        .badge-seal {
            width: 70px;
            height: 70px;
            background: #d97706;
            color: #ffffff;
            border-radius: 50%;
            margin: 0 auto;
            line-height: 70px;
            font-size: 11px;
            font-weight: bold;
            letter-spacing: 1px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body>
<div class="cert-container">
    <div class="border-outer">
        <div class="border-inner">
            <div class="logo-header">🎓 AMIKOM EVENT HUB</div>
            <div class="cert-title">SERTIFIKAT KEHADIRAN</div>
            <div class="cert-subtitle">Certificate of Attendance</div>

            <div class="presented-to">Diberikan secara resmi kepada:</div>
            <div class="participant-name">{{ $user->name }}</div>

            <div class="appreciation-text">
                Atas partisipasi dan keikutsertaannya secara aktif dalam kegiatan seminar/workshop:
                <div class="event-name">"{{ $event->title }}"</div>
                yang diselenggarakan oleh <strong>{{ $event->organizer->organization_name ?? 'AmikomEventHub' }}</strong>
                pada tanggal {{ \Carbon\Carbon::parse($event->date)->translatedFormat('d F Y') }} di {{ $event->location }}.
            </div>

            <table class="footer-table">
                <tr>
                    <td class="footer-cell">
                        <div class="badge-seal">OFFICIAL</div>
                        <div class="meta-info" style="margin-top: 10px;">
                            ID Sertifikat: #CERT-{{ $event->id }}-{{ $user->id }}<br>
                            Diterbitkan: {{ now()->translatedFormat('d F Y') }}
                        </div>
                    </td>
                    <td class="footer-cell">
                        <div class="sig-line"></div>
                        <div class="sig-title">Panitia Penyelenggara</div>
                        <div class="sig-sub">{{ $event->organizer->organization_name ?? 'Amikom EventHub Committee' }}</div>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
</body>
</html>
