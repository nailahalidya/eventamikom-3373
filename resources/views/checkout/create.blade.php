@extends('layouts.app')

@section('content')
@php
    $poster = $event->poster_path ?? $event->poster ?? null;
    $currentPrice = $event->current_price;
    $tierName = $event->active_tier_name;
@endphp

<main class="max-w-3xl mx-auto px-6 py-20">
    <div class="mb-12">
        <a href="{{ route('events.show', $event->id) }}"
           class="text-indigo-600 font-bold flex items-center gap-2 mb-6 hover:underline">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
            Kembali ke Event
        </a>

        <h1 class="text-4xl font-extrabold text-slate-900">Checkout Tiket</h1>
        <p class="text-slate-500 mt-2">Lengkapi data Anda untuk mendapatkan e-ticket.</p>
    </div>

    @if(session('error'))
        <div class="mb-6 p-4 bg-rose-100 text-rose-700 rounded-2xl font-bold border border-rose-200">
            ⚠️ {{ session('error') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 bg-rose-100 text-rose-700 rounded-2xl border border-rose-200">
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
            <div class="flex justify-between items-center mb-6 border-b pb-4">
                <h3 class="text-xl font-bold text-slate-800">Ringkasan Pesanan</h3>
                @if($tierName !== 'Regular')
                    <span class="px-3 py-1 bg-amber-100 text-amber-800 rounded-full text-xs font-black uppercase tracking-wide">
                        🔥 Harga {{ $tierName }}
                    </span>
                @endif
            </div>

            <div class="flex gap-6 items-start">
                <img src="{{ $event->poster_url }}" alt="{{ $event->title }}" class="w-24 h-24 rounded-2xl object-cover shadow-sm">


                <div class="flex-1">
                    <h4 class="font-extrabold text-lg text-slate-900 leading-tight">{{ $event->title }}</h4>
                    <p class="text-slate-500 text-sm mt-1">
                        {{ $event->date ? \Carbon\Carbon::parse($event->date)->format('d M Y, H:i') : '' }} • {{ $event->location }}
                    </p>
                    <p class="text-indigo-600 font-black mt-2 text-lg">
                        @if($currentPrice == 0)
                            <span class="text-emerald-600 uppercase font-black">Gratis</span>
                        @else
                            1 x Rp {{ number_format($currentPrice, 0, ',', '.') }}
                        @endif
                    </p>
                </div>
            </div>

            <!-- Coupon Input Box -->
            <div class="mt-6 pt-6 border-t">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-2">Kode Kupon / Voucher Promo</label>
                <div class="flex gap-2">
                    <input type="text" id="input-coupon" placeholder="Masukkan kode (mis: MAHASISWA50)"
                        class="flex-1 px-4 py-3 bg-slate-50 border border-slate-200 rounded-xl text-sm font-mono font-bold uppercase focus:ring-2 focus:ring-indigo-500 outline-none">
                    <button type="button" onclick="applyCoupon()"
                        class="px-5 py-3 bg-slate-900 hover:bg-slate-800 text-white font-bold text-sm rounded-xl transition">
                        Terapkan
                    </button>
                </div>
                <div id="coupon-feedback" class="mt-2 text-xs font-bold hidden"></div>
            </div>

            <div class="mt-6 pt-6 border-t space-y-3">
                <div class="flex justify-between text-slate-600 text-sm">
                    <span>Harga Tiket</span>
                    <span id="label-ticket-price" class="font-semibold">
                        {{ $currentPrice == 0 ? 'Rp 0 (Gratis)' : 'Rp ' . number_format($currentPrice, 0, ',', '.') }}
                    </span>
                </div>

                <div id="row-discount" class="flex justify-between text-emerald-600 text-sm font-bold hidden">
                    <span>Diskon Kupon</span>
                    <span id="label-discount">- Rp 0</span>
                </div>

                <div id="row-admin-fee" class="flex justify-between text-slate-600 text-sm {{ $currentPrice == 0 ? 'hidden' : '' }}">
                    <span>Biaya Layanan</span>
                    <span>Rp 5.000</span>
                </div>

                <div class="flex justify-between text-2xl font-black mt-4 pt-4 border-t text-slate-900">
                    <span>Total Tagihan</span>
                    <span id="label-total-price" class="text-indigo-600">
                        Rp {{ number_format($currentPrice == 0 ? 0 : $currentPrice + 5000, 0, ',', '.') }}
                    </span>
                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="bg-white rounded-3xl border border-slate-200 p-8 shadow-sm">
            <h3 class="text-xl font-bold mb-6 text-slate-800">Data Pemesan</h3>

            <form action="{{ route('checkout.store', $event->id) }}" method="POST" class="space-y-6">
                @csrf
                <input type="hidden" name="coupon_code" id="form-coupon-code">

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Nama Lengkap</label>
                    <input type="text" name="customer_name" placeholder="Masukkan nama sesuai identitas" required
                           value="{{ old('customer_name') }}"
                           class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">Email Aktif</label>
                        <input type="email" name="customer_email" placeholder="contoh@gmail.com" required
                               value="{{ old('customer_email') }}"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                        <p class="text-[10px] text-slate-400 mt-2 font-bold uppercase tracking-tighter">E-Ticket akan dikirim ke email ini</p>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase tracking-wide mb-2">No. WhatsApp</label>
                        <input type="tel" name="customer_phone" placeholder="08xxxxxxx" required
                               value="{{ old('customer_phone') }}"
                               class="w-full px-5 py-4 bg-white border-2 border-slate-100 rounded-2xl focus:ring-4 focus:ring-indigo-500/10 focus:border-indigo-600 outline-none transition font-medium">
                    </div>
                </div>

                <button type="submit" id="btn-submit-checkout"
                        class="w-full py-5 bg-indigo-600 text-white rounded-2xl font-black text-xl shadow-xl shadow-indigo-200 hover:bg-indigo-700 active:scale-95 transition-all">
                    {{ $currentPrice == 0 ? 'Daftar Gratis Sekarang 🎟️' : 'Bayar Sekarang' }}
                </button>

                <p class="text-center text-xs text-slate-400 font-medium">
                    Dengan menekan tombol di atas, Anda menyetujui Syarat & Ketentuan kami.
                </p>
            </form>
        </div>
    </div>
</main>

<script>
    function applyCoupon() {
        const code = document.getElementById('input-coupon').value.trim();
        const feedback = document.getElementById('coupon-feedback');

        if (!code) {
            feedback.className = "mt-2 text-xs font-bold text-rose-600 block";
            feedback.innerText = "Masukkan kode kupon terlebih dahulu.";
            return;
        }

        feedback.className = "mt-2 text-xs font-bold text-slate-500 block animate-pulse";
        feedback.innerText = "Memeriksa kupon...";

        fetch("{{ route('api.coupon.apply') }}", {
            method: "POST",
            headers: {
                "Content-Type": "application/json",
                "X-CSRF-TOKEN": "{{ csrf_token() }}"
            },
            body: JSON.stringify({
                code: code,
                event_id: {{ $event->id }}
            })
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                feedback.className = "mt-2 text-xs font-bold text-emerald-600 block";
                feedback.innerText = "✅ " + data.message;
                
                document.getElementById('form-coupon-code').value = data.coupon_code;
                document.getElementById('row-discount').classList.remove('hidden');
                document.getElementById('label-discount').innerText = "- " + data.discount_formatted;
                document.getElementById('label-total-price').innerText = data.total_price_formatted;

                if (data.admin_fee === 0) {
                    document.getElementById('row-admin-fee').classList.add('hidden');
                } else {
                    document.getElementById('row-admin-fee').classList.remove('hidden');
                }

                if (data.total_price === 0) {
                    document.getElementById('btn-submit-checkout').innerText = "Daftar Gratis Sekarang 🎟️";
                }
            } else {
                feedback.className = "mt-2 text-xs font-bold text-rose-600 block";
                feedback.innerText = "❌ " + data.message;
            }
        })
        .catch(err => {
            feedback.className = "mt-2 text-xs font-bold text-rose-600 block";
            feedback.innerText = "Gagal memproses kupon.";
        });
    }
</script>
@endsection