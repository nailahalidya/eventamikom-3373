@extends('layouts.app')

@section('content')
@php
    $poster = $event->poster_path ?? $event->poster ?? null;
@endphp

<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">
    <div class="lg:col-span-1">
        <div class="sticky top-32">
            @if($poster)
                <img src="{{ asset('storage/' . $poster) }}"
                     alt="{{ $event->title }}"
                     class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-3/4">
            @else
                <img src="{{ asset('storage/asset/concert.png') }}"
                     alt="{{ $event->title }}"
                     class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-3/4">
            @endif

            <div class="mt-8 p-6 bg-white rounded-3xl border border-slate-100 shadow-sm">
                <h4 class="font-bold mb-4">Penyelenggara</h4>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-indigo-600 font-bold">
                        AH
                    </div>

                    <div>
                        <p class="font-bold text-slate-800">Admin Super</p>
                        <p class="text-xs text-slate-400">Penyelenggara Utama</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="lg:col-span-2 space-y-8">
        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 md:p-10 shadow-sm space-y-6">
            <span class="inline-block px-4 py-1.5 bg-indigo-50 text-indigo-600 rounded-full text-xs font-bold uppercase tracking-wider">
                {{ $event->category->name ?? 'Tanpa Kategori' }}
            </span>

            <h1 class="text-3xl md:text-5xl font-black text-slate-900 leading-tight">
                {{ $event->title }}
            </h1>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 pt-6 border-t border-slate-100">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Tanggal & Waktu</p>
                        <p class="font-bold text-slate-800">{{ \Carbon\Carbon::parse($event->date)->format('d F Y, H:i') }} WIB</p>
                    </div>
                </div>

                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center shrink-0">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-xs text-slate-400 font-bold uppercase tracking-wider">Lokasi Tempat</p>
                        <p class="font-bold text-slate-800">{{ $event->location }}</p>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 md:p-10 shadow-sm">
            <h3 class="text-xl font-bold mb-4 text-slate-800">Deskripsi Acara</h3>
            <p class="text-slate-600 leading-relaxed whitespace-pre-line">
                {{ $event->description ?? 'Tidak ada deskripsi untuk acara ini.' }}
            </p>
        </div>

        <div class="bg-indigo-900 text-white rounded-[2.5rem] p-8 md:p-10 shadow-xl shadow-indigo-100 flex flex-col md:flex-row justify-between items-center gap-6">
            <div>
                <p class="text-indigo-200 text-sm font-semibold mb-1">Harga Tiket Resmi</p>
                <h2 class="text-3xl md:text-4xl font-black">
                    @if ($event->price == 0)
                        Gratis
                    @else
                        Rp {{ number_format($event->price, 0, ',', '.') }}
                    @endif
                </h2>
            </div>

            @if($event->stock > 0)
                <a href="{{ route('admin.transactions.create', $event->id) }}"
                   class="w-full md:w-auto px-8 py-4 bg-white text-indigo-900 rounded-2xl font-bold text-lg text-center shadow-lg hover:scale-105 transition-transform">
                    Pesan Tiket Sekarang
                </a>
            @else
                <button class="w-full md:w-auto px-8 py-4 bg-indigo-800 text-indigo-400 rounded-2xl font-bold text-lg cursor-not-allowed" disabled>
                    Tiket Habis
                </button>
            @endif
        </div>

        <div class="bg-white rounded-[2.5rem] border border-slate-100 p-8 md:p-10 shadow-sm space-y-4">
            <h3 class="text-xl font-bold text-slate-800">Informasi Penting</h3>
            <ul class="space-y-3 text-sm text-slate-500 font-medium">
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    E-Ticket akan dikirimkan otomatis setelah pembayaran berhasil.
                </li>
                <li class="flex items-start gap-2">
                    <svg class="w-5 h-5 text-green-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Tiket dapat discan di pintu masuk.
                </li>
                <li class="flex items-start gap-2 text-rose-500">
                    <svg class="w-5 h-5 text-rose-500 mt-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Tiket yang sudah dibeli tidak dapat direfund.
                </li>
            </ul>
        </div>
    </div>
</main>
@endsection