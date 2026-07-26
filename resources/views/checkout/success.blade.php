@extends('layouts.app')
@section('title', 'E-Ticket & Pembayaran - AmikomEventHub')

@section('content')
<main class="max-w-3xl mx-auto px-6 py-16 text-center">

    @if(session('success'))
        <div class="mb-8 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-200 p-8 md:p-12 shadow-xl inline-block w-full max-w-lg">
        
        <div class="w-20 h-20 bg-emerald-100 text-emerald-600 rounded-3xl flex items-center justify-center mx-auto mb-6 shadow-inner">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>

        <h2 class="text-3xl font-black text-slate-900 mb-2">E-Ticket Terbit!</h2>
        <p class="text-slate-500 font-medium text-sm mb-6">
            Pesanan <span class="font-mono font-bold text-indigo-600">#{{ $transaction->order_id }}</span>
        </p>

        @if(in_array(strtolower($transaction->status), ['settlement', 'success', 'used']))
            <!-- QR CODE E-TICKET CARD -->
            <div class="bg-slate-50 border border-slate-200 rounded-3xl p-6 mb-8 text-center">
                <div class="inline-block p-4 bg-white rounded-2xl shadow-md border mb-4">
                    <div id="qrcode" class="flex justify-center"></div>
                </div>

                <p class="font-mono text-xs text-slate-400 font-bold tracking-widest uppercase mb-1">Kode Tiket Unik</p>
                <p class="font-mono text-base font-black text-slate-800 bg-slate-200/60 py-1.5 px-4 rounded-xl inline-block">
                    {{ $transaction->qr_token ?? $transaction->order_id }}
                </p>

                <p class="text-xs text-slate-500 mt-4 leading-relaxed font-medium">
                    📸 Tunjukkan kode QR di atas kepada panitia registrasi di lokasi acara untuk proses check-in.
                </p>

                @if($transaction->checked_in_at)
                    <div class="mt-4 p-2 bg-indigo-50 border border-indigo-200 rounded-xl text-indigo-700 text-xs font-bold">
                        ✅ Sudah Check-in pada {{ $transaction->checked_in_at->format('H:i, d M Y') }}
                    </div>
                @endif
            </div>
        @else
            <div class="p-6 bg-amber-50 border border-amber-200 rounded-3xl mb-8 text-amber-800 text-sm font-medium">
                ⏳ Pembayaran Anda sedang menunggu konfirmasi lunas dari Midtrans. QR Code akan aktif setelah pembayaran diverifikasi.
            </div>
        @endif

        <div class="text-left bg-slate-50 p-6 rounded-2xl border border-slate-100 text-xs space-y-2 text-slate-600 mb-8">
            <div class="flex justify-between"><span class="text-slate-400 font-semibold">Nama Pemegang:</span> <span class="font-bold text-slate-800">{{ $transaction->customer_name }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400 font-semibold">Email:</span> <span class="font-bold text-slate-800">{{ $transaction->customer_email }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400 font-semibold">Event:</span> <span class="font-bold text-slate-800">{{ $transaction->event->title ?? '-' }}</span></div>
            <div class="flex justify-between"><span class="text-slate-400 font-semibold">Total Tagihan:</span> <span class="font-bold text-emerald-600">Rp {{ number_format($transaction->total_price, 0, ',', '.') }}</span></div>
        </div>

        <div class="flex flex-col sm:flex-row gap-3 justify-center">
            <a href="{{ route('home') }}"
               class="px-6 py-3.5 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition text-sm shadow-lg shadow-indigo-200">
                Kembali ke Beranda
            </a>
            <button onclick="window.print()"
               class="px-6 py-3.5 border-2 border-slate-200 text-slate-700 rounded-2xl font-bold hover:bg-slate-100 transition text-sm">
                🖨 Cetak Tiket
            </button>
        </div>
    </div>
</main>

<script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
<script>
    document.addEventListener("DOMContentLoaded", function () {
        const qrContent = "{{ $transaction->qr_token ?? $transaction->order_id }}";
        const qrContainer = document.getElementById("qrcode");
        if (qrContainer && qrContent) {
            new QRCode(qrContainer, {
                text: qrContent,
                width: 180,
                height: 180,
                colorDark : "#0f172a",
                colorLight : "#ffffff",
                correctLevel : QRCode.CorrectLevel.H
            });
        }
    });
</script>
@endsection