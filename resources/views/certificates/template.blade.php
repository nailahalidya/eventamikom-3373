@php
/**
 * Certificate Blade Template
 *
 * Variables expected:
 *  - $user  (App\Models\User)
 *  - $event (App\Models\Event)
 */
@endphp
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <title>Certificate of Attendance</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
        body {
            font-family: 'Inter', sans-serif;
            background: #f8fafc;
            padding: 40px;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
            margin: 0;
        }
        .certificate {
            width: 1123px; /* A4 landscape at 96dpi */
            height: 794px;
            border: 12px solid #4f46e5;
            padding: 40px;
            box-sizing: border-box;
            background: white;
            position: relative;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .title {
            font-size: 48px;
            font-weight: 600;
            color: #4f46e5;
        }
        .content {
            text-align: center;
            margin-top: 30px;
        }
        .name {
            font-size: 36px;
            font-weight: 600;
            margin: 20px 0;
        }
        .event {
            font-size: 24px;
            margin-bottom: 10px;
        }
        .date {
            font-size: 20px;
            color: #6b7280;
        }
        .signature {
            position: absolute;
            bottom: 40px;
            right: 80px;
            text-align: center;
        }
        .signature img {
            width: 200px;
        }
        .signature span {
            display: block;
            margin-top: 8px;
            font-weight: 600;
        }
    </style>
</head>
<body>
<div class="certificate">
    <div class="header">
        <div class="title">Certificate of Attendance</div>
    </div>
    <div class="content">
        <div class="event">{{ $event->title ?? $event->name }}</div>
        <div class="name">{{ $user->name }}</div>
        <div class="date">Awarded on {{ now()->format('F j, Y') }}</div>
    </div>
    <div class="signature">
        <!-- Placeholder for signature image -->
        <img src="{{ public_path('images/signature.png') }}" alt="Signature" />
        <span>Organizer</span>
    </div>
</div>
</body>
</html>
