@extends('layouts.app')

@section('content')
@php
    $poster = $event->poster_path ?? $event->poster ?? null;
@endphp

<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}"
           class="text-indigo-600 font-bold flex items-center gap-2 mb-6">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 19l-7-7 7-7">
                </path>
            </svg>
            Kembali ke Event
        </a>

        <h1 class="text-4xl font-extrabold">Checkout</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan tiket.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl font-bold">
            {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
            <ul class="list-disc list-inside text-sm font-semibold">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 gap-8">
        <!-- Summary Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 border-b pb-4">Pesanan Anda</h3>

            <div class="flex gap-6 items-start">
                @if($poster)
                    <img src="{{ asset('storage/' . $poster) }}"
                         alt="{{ $event->title }}"
                         class="w-24 h-24 rounded-2xl object-cover">
                @else
                    <img src="https://placehold.co/200x200?text=No+Image"
                         alt="{{ $event->title }}"
                         class="w-24 h-24 rounded-2xl object-cover">
                @endif

                <div>
                    <h4 class="font-extrabold text-lg">{{ $event->title }}</h4>
                    <p class="text-slate-500">
                        {{ \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') }}
                        •
                        {{ $event->location }}
                    </p>
                    <p class="text-indigo-600 font-bold mt-2">
                        1 x Rp {{ number_format($event->price, 0, ',', '.') }}
                    </p>
                </div>
            </div>

            <div class="mt-8 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-500">
                    <span>Harga Tiket</span>
                    <span>Rp {{ number_format($event->price, 0, ',', '.') }}</span>
                </div>

                <div class="flex justify-between text-slate-500">
                    <span>Biaya Layanan</span>
                    <span>Rp 5.000</span>
                </div>

                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t">
                    <span>Total Bayar</span>
                    <span class="text-indigo-600">
                        Rp {{ number_format($event->price + 5000, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 italic text-indigo-600 underline underline-offset-8">
                Data Pemesan Tanpa Login
            </h3>

            <form action="{{ route('checkout.store', $event->id) }}"
                  method="POST"
                  class="space-y-6">
                @csrf

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                        Nama Lengkap
                    </label>

                    <input type="text"
                           name="customer_name"
                           placeholder="Masukkan nama sesuai identitas"
                           class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                           required
                           value="{{ old('customer_name') }}">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                            Email Aktif
                        </label>

                        <input type="email"
                               name="customer_email"
                               placeholder="contoh@gmail.com"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required
                               value="{{ old('customer_email') }}">

                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">
                            E-Ticket akan dikirim ke email ini
                        </p>
                    </div>

                    <div>
                        <label class="block text-sm font-bold text-slate-700 mb-2 uppercase tracking-wide">
                            No. WhatsApp
                        </label>

                        <input type="tel"
                               name="customer_phone"
                               placeholder="08xxxxxxx"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium"
                               required
                               value="{{ old('customer_phone') }}">
                    </div>
                </div>

                <button type="submit"
                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    Bayar Sekarang
                </button>

                <p class="text-center text-xs text-slate-400">
                    Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.
                </p>
            </form>
        </div>
    </div>
</main>

<!-- Overlay Midtrans Simulation -->
<div id="midtrans-overlay"
     class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden items-center justify-center p-6">
    <div class="bg-white w-full max-w-sm rounded-[2rem] overflow-hidden shadow-2xl animate-bounce-in">
        <div class="bg-slate-50 p-6 flex justify-between items-center border-b">
            <div class="font-black text-indigo-700 text-xl">
                Midtrans
            </div>

            <button onclick="hideMidtrans()" class="p-2 hover:bg-slate-200 rounded-full">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round"
                          stroke-linejoin="round"
                          stroke-width="2"
                          d="M6 18L18 6M6 6l18 18">
                    </path>
                </svg>
            </button>
        </div>

        <div class="p-8 text-center">
            <p class="text-slate-500 font-medium">Total Tagihan</p>

            <h2 class="text-3xl font-black text-indigo-700 my-2">
                Rp {{ number_format(session('total_price', $event->price + 5000), 0, ',', '.') }}
            </h2>

            <p class="text-xs text-slate-400">
                Order ID #{{ session('order_id', 'TRX-DEMO') }}
            </p>

            <div class="mt-8 space-y-4">
            @if(session('order_id'))
                <button onclick="window.location.href='{{ route('payment.success', session('order_id')) }}'"
                        class="w-full py-4 border-2 border-indigo-100 rounded-2xl flex justify-between items-center px-6 hover:border-indigo-600 transition group">
                    <span class="font-bold group-hover:text-indigo-600">GoPay / QRIS</span>
                    <span class="text-indigo-400">→</span>
                </button>

                <button onclick="window.location.href='{{ route('payment.success', session('order_id')) }}'"
                        class="w-full py-4 border-2 border-indigo-100 rounded-2xl flex justify-between items-center px-6 hover:border-indigo-600 transition group">
                    <span class="font-bold group-hover:text-indigo-600">Virtual Account</span>
                    <span class="text-indigo-400">→</span>
                </button>

                <button onclick="window.location.href='{{ route('payment.success', session('order_id')) }}'"
                        class="w-full py-4 border-2 border-indigo-100 rounded-2xl flex justify-between items-center px-6 hover:border-indigo-600 transition group">
                    <span class="font-bold group-hover:text-indigo-600">Kartu Debit/Kredit</span>
                    <span class="text-indigo-400">→</span>
                </button>
            @else
                <button type="button"
                        class="w-full py-4 border-2 border-slate-100 rounded-2xl flex justify-between items-center px-6 opacity-50 cursor-not-allowed">
                    <span class="font-bold">Metode Pembayaran</span>
                    <span class="text-slate-400">Submit checkout dulu</span>
                </button>
            @endif
        </div>

            <div class="mt-12 flex items-center justify-center gap-2 text-xs text-slate-400 font-bold uppercase tracking-widest">
                Secure Checkout by Midtrans
            </div>
        </div>
    </div>
</div>

<script>
    function showMidtrans() {
        const overlay = document.getElementById('midtrans-overlay');
        overlay.classList.remove('hidden');
        overlay.classList.add('flex');
    }

    function hideMidtrans() {
        const overlay = document.getElementById('midtrans-overlay');
        overlay.classList.add('hidden');
        overlay.classList.remove('flex');
    }

    @if(session('show_payment'))
        showMidtrans();
    @endif
</script>

<style>
    @keyframes bounce-in {
        0% {
            transform: scale(0.9);
            opacity: 0;
        }

        70% {
            transform: scale(1.05);
            opacity: 1;
        }

        100% {
            transform: scale(1);
        }
    }

    .animate-bounce-in {
        animation: bounce-in 0.4s ease-out forwards;
    }
</style>
@endsection