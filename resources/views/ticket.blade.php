@extends('layouts.app')

@section('content')
<div class="max-w-md w-full animate-in fade-in zoom-in duration-500">
    <div class="text-center mb-8">
        <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4 border-4 border-white shadow-sm">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7"></path>
            </svg>
        </div>
        <h1 class="text-3xl font-black text-slate-800">Pembayaran Berhasil!</h1>
        <p class="text-slate-500 mt-2">Tiket Anda telah terbit dan siap digunakan.</p>
    </div>

    <div class="bg-white text-slate-900 rounded-[2.5rem] overflow-hidden shadow-2xl relative border border-slate-100">
        <div class="p-8 bg-indigo-50 border-b-4 border-dashed border-white text-center relative">
            <p class="text-indigo-600 font-bold uppercase tracking-widest text-xs mb-2">E-Ticket Resmi</p>
            <h2 class="text-2xl font-black leading-tight">Jazz Night 2024: A Celebration</h2>

            <div class="absolute -left-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full shadow-inner"></div>
            <div class="absolute -right-4 -bottom-4 w-8 h-8 bg-slate-50 rounded-full shadow-inner"></div>
        </div>

        <div class="p-8 space-y-8">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Nama Pembeli</p>
                    <p class="font-bold text-slate-800">Donni Prabowo</p>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Tanggal & Waktu</p>
                    <p class="font-bold text-slate-800">16 Nov, 19:30</p>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Order ID</p>
                    <p class="font-bold text-slate-800 font-mono text-sm">TRX-99210</p>
                </div>
                <div>
                    <p class="text-slate-400 text-[10px] font-bold uppercase mb-1">Lokasi</p>
                    <p class="font-bold text-slate-800">Blue Note Lounge</p>
                </div>
            </div>

            <div class="bg-slate-50 p-6 rounded-3xl flex flex-col items-center border border-slate-100">
                <p class="text-slate-400 text-[10px] font-bold uppercase mb-4 tracking-widest">Scan QR untuk Check-in</p>

                <div class="w-40 h-40 bg-white p-2 rounded-xl shadow-sm border border-slate-200 grid grid-cols-4 grid-rows-4 gap-1">
                    @for ($i = 0; $i < 16; $i++)
                        <div class="{{ rand(0,1) ? 'bg-slate-800' : 'bg-white' }} rounded-sm"></div>
                    @endfor
                </div>

                <p class="mt-4 font-mono font-bold text-slate-600 text-xs">TKT-001293848</p>
            </div>
        </div>

        <div class="px-8 pb-8 space-y-3">
            <button onclick="window.print()" class="w-full py-4 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-200 hover:bg-indigo-700 hover:-translate-y-0.5 transition-all">
                Cetak / Simpan PDF
            </button>
            <a href="/" class="block text-center py-2 text-slate-400 font-semibold text-sm hover:text-indigo-600 transition">
                Kembali ke Beranda
            </a>
        </div>
    </div>
</div>
@endsection
