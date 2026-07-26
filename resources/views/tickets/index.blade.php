@extends('layouts.app')

@section('title', 'Temukan Tiket Saya — AmikomEventHub')

@section('content')
<main class="min-h-screen py-16 px-6 max-w-7xl mx-auto">

    <!-- Header Section -->
    <div class="text-center max-w-3xl mx-auto mb-12 space-y-4">
        <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-xs font-black uppercase tracking-wider">
            🎟️ E-Ticket Lookup
        </span>

        <h1 class="text-4xl md:text-5xl font-black text-slate-900 tracking-tight">
            Temukan & Unduh <span class="text-indigo-600">Tiket Saya</span>
        </h1>

        <p class="text-slate-500 text-base leading-relaxed font-medium">
            Masukkan Order ID (contoh: TRX-...), Email, atau Nomor WhatsApp yang Anda gunakan saat pemesanan tiket.
        </p>

        <!-- Search Form -->
        <form action="{{ route('tickets.index') }}" method="GET" class="pt-4 max-w-xl mx-auto">
            <div class="flex gap-2">
                <div class="relative flex-1">
                    <input type="text" 
                           name="search" 
                           value="{{ $search ?? '' }}"
                           placeholder="Ketik Order ID / Email / No. WhatsApp..." 
                           required
                           class="w-full pl-12 pr-4 py-4 rounded-2xl border border-slate-200 shadow-lg shadow-indigo-100 focus:outline-none focus:ring-2 focus:ring-indigo-600 focus:border-transparent text-sm font-semibold transition">
                    <div class="absolute left-4 top-1/2 -translate-y-1/2 text-slate-400 text-lg">
                        🔎
                    </div>
                </div>

                <button type="submit" class="px-7 py-4 bg-indigo-600 hover:bg-indigo-700 text-white rounded-2xl font-black text-sm transition shadow-lg shadow-indigo-600/30 flex items-center gap-2 shrink-0">
                    Cari Tiket
                </button>
            </div>
        </form>
    </div>

    <!-- Results Section -->
    <div class="max-w-5xl mx-auto">
        @if($hasSearched && $transactions->isEmpty())
            <div class="bg-white rounded-3xl p-12 text-center border border-slate-100 shadow-sm max-w-md mx-auto space-y-4">
                <div class="w-16 h-16 bg-slate-100 rounded-2xl flex items-center justify-center text-3xl mx-auto">
                    🔍
                </div>
                <h3 class="text-xl font-black text-slate-800">Tiket Tidak Ditemukan</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Tidak ditemukan riwayat pemesanan dengan kata kunci "<span class="font-bold text-slate-800">{{ $search }}</span>". Pastikan Order ID atau Email yang dimasukkan sudah benar.
                </p>
                <a href="{{ route('help.center') }}" class="inline-block px-5 py-2.5 bg-indigo-50 text-indigo-600 font-bold text-xs rounded-xl hover:bg-indigo-100 transition">
                    Hubungi CS via WhatsApp
                </a>
            </div>
        @elseif($transactions->isNotEmpty())
            <div class="space-y-6">
                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-black text-slate-800">
                        @if($hasSearched)
                            Hasil Pencarian Tiket ({{ $transactions->count() }})
                        @else
                            Tiket Terpesan Saya ({{ $transactions->count() }})
                        @endif
                    </h2>
                    @if(Auth::check())
                        <span class="text-xs text-slate-400 font-semibold">Login sebagai: {{ Auth::user()->email }}</span>
                    @endif
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    @foreach($transactions as $trx)
                    <div class="bg-white rounded-3xl p-6 border border-slate-100 shadow-sm hover:shadow-xl transition-all duration-300 flex flex-col justify-between space-y-4">
                        <div>
                            <!-- Header Status & Date -->
                            <div class="flex justify-between items-start gap-2 mb-3">
                                <div>
                                    <span class="font-mono text-xs font-bold text-indigo-600 bg-indigo-50 px-2.5 py-1 rounded-lg">
                                        {{ $trx->order_id }}
                                    </span>
                                </div>

                                <div>
                                    @if(in_array(strtolower($trx->status), ['settlement', 'success']))
                                        <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full font-extrabold text-[11px] uppercase tracking-wider">
                                            ✅ Tiket Lunas
                                        </span>
                                    @elseif(strtolower($trx->status) === 'pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full font-extrabold text-[11px] uppercase tracking-wider">
                                            ⏳ Menunggu Bayar
                                        </span>
                                    @else
                                        <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full font-extrabold text-[11px] uppercase tracking-wider">
                                            ❌ {{ strtoupper($trx->status) }}
                                        </span>
                                    @endif
                                </div>
                            </div>

                            <!-- Event Info -->
                            <h3 class="text-lg font-black text-slate-900 leading-snug mb-2">
                                {{ $trx->event->title ?? 'Event Amikom' }}
                            </h3>

                            <div class="space-y-1.5 text-xs text-slate-500 font-medium">
                                <p class="flex items-center gap-2">
                                    <span>👤</span> <span>Pembeli: <strong>{{ $trx->customer_name }}</strong> ({{ $trx->customer_email }})</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span>📅</span> <span>{{ \Carbon\Carbon::parse($trx->event->date ?? now())->format('d F Y, H:i') }}</span>
                                </p>
                                <p class="flex items-center gap-2">
                                    <span>📍</span> <span>{{ $trx->event->location ?? '-' }}</span>
                                </p>
                            </div>
                        </div>

                        <!-- Footer Price & Action Button -->
                        <div class="pt-4 border-t border-slate-100 flex items-center justify-between">
                            <div>
                                <span class="text-[10px] text-slate-400 font-bold uppercase block">Total Biaya</span>
                                <span class="text-lg font-black text-indigo-600">
                                    Rp {{ number_format($trx->total_price, 0, ',', '.') }}
                                </span>
                            </div>

                            @if(in_array(strtolower($trx->status), ['settlement', 'success']))
                                <a href="{{ route('ticket', $trx->order_id) }}" target="_blank" class="px-4 py-2.5 bg-indigo-600 hover:bg-indigo-700 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-indigo-600/20 flex items-center gap-1.5">
                                    <span>Lihat E-Ticket & QR</span>
                                    <span>→</span>
                                </a>
                            @elseif(strtolower($trx->status) === 'pending')
                                <a href="{{ route('checkout.payment', $trx->order_id) }}" class="px-4 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-amber-500/20 flex items-center gap-1.5">
                                    <span>Bayar Sekarang</span>
                                    <span>💳</span>
                                </a>
                            @else
                                <span class="text-xs text-slate-400 font-semibold italic">Transaksi Kedaluwarsa</span>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        @else
            <!-- Empty state when guest hasn't searched yet -->
            <div class="bg-indigo-50/60 rounded-3xl p-10 text-center border border-indigo-100 max-w-xl mx-auto space-y-4">
                <div class="w-16 h-16 bg-white rounded-2xl flex items-center justify-center text-3xl mx-auto shadow-sm">
                    🎫
                </div>
                <h3 class="text-lg font-black text-slate-800">Cari Tiket Anda Dalam Hitungan Detik</h3>
                <p class="text-slate-500 text-sm leading-relaxed">
                    Cukup tuliskan email atau Order ID di form atas untuk melihat riwayat tiket, status pembayaran, serta mendownload QR Code tiket masuk event.
                </p>
            </div>
        @endif
    </div>

</main>
@endsection
