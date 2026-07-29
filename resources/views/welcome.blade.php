@extends('layouts.app')

@section('content')

<!-- Hero Section -->
<section class="max-w-7xl mx-auto px-6 py-20 flex flex-col md:flex-row items-center gap-12">
    <div class="flex-1 space-y-8">
        <span
            class="inline-block px-4 py-1.5 bg-indigo-100 text-indigo-700 rounded-full text-sm font-bold uppercase tracking-wider">
            #1 Event Platform
        </span>

        <h1 class="text-5xl md:text-7xl font-extrabold leading-tight">
            Temukan & Pesan <span class="text-indigo-600">Tiket Event</span> Impianmu.
        </h1>

        <p class="text-lg text-slate-500 max-w-lg leading-relaxed">
            Dari konser musik hingga workshop teknologi, semua ada di genggamanmu. Pesan aman & cepat dengan Midtrans.
        </p>

        <div class="flex flex-wrap gap-4 items-center">
            <a href="#events"
                class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                Mulai Jelajah
            </a>

            <button type="button"
                onclick="triggerPwaInstall()"
                class="pwa-install-btn px-8 py-4 bg-gradient-to-r from-emerald-500 to-teal-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-emerald-200 hover:scale-105 transition-all flex items-center gap-2 cursor-pointer">
                <span class="animate-bounce">📱</span> Buka di Aplikasi
            </button>

            <a href="{{ route('cara-pesan') }}"
                class="px-8 py-4 border-2 border-slate-200 rounded-2xl font-bold text-lg hover:border-indigo-600 hover:text-indigo-600 transition">
                Cara Pesan
            </a>
        </div>
    </div>

    <div class="flex-1 relative">
        <div
            class="absolute -top-10 -left-10 w-64 h-64 bg-indigo-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob">
        </div>

        <div
            class="absolute -bottom-10 -right-10 w-64 h-64 bg-purple-400 rounded-full mix-blend-multiply filter blur-3xl opacity-20 animate-blob animation-delay-2000">
        </div>

        <!-- URL Cloudinary Langsung Dipasang -->
        <img src="https://res.cloudinary.com/jy0bx3eb/image/upload/v1785082271/JTHyX1fhJzSbufNQbNux43v0KbgLk9xJPEvpKDse_fm2tzy.jpg"
            alt="Concert"
            class="rounded-[2rem] shadow-2xl relative z-10 w-full object-cover aspect-[4/5] object-center">

        <div class="absolute -bottom-6 -left-6 glass p-6 rounded-2xl shadow-xl z-20 border border-white">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-green-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M5 13l4 4L19 7">
                        </path>
                    </svg>
                </div>

                <div>
                    <p class="text-xs text-slate-500 font-bold uppercase">
                        Terverifikasi
                    </p>

                    <p class="font-bold">
                        Pembayaran Aman via Midtrans
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- Events Grid -->
<section id="events" class="max-w-7xl mx-auto px-6 py-20">

    <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-12 gap-6">
        <div>
            <h2 class="text-3xl font-extrabold mb-2">
                Event Terdekat
            </h2>

            <p class="text-slate-500 font-medium">
                Jangan sampai ketinggalan acara seru minggu ini!
            </p>
        </div>

        <div class="flex gap-2 flex-wrap">
            <a href="/"
                class="p-3 border rounded-xl hover:bg-white hover:shadow-md transition">
                Semua Kategori
            </a>

            @foreach ($categories as $category)
            <a href="{{ route('category.filter', $category->id) }}"
                class="p-3 border rounded-xl hover:bg-white hover:shadow-md transition">
                {{ $category->name }}
            </a>
            @endforeach
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">

        @forelse($events as $event)

        <div
            class="group bg-white rounded-3xl border border-slate-100 shadow-sm hover:shadow-2xl transition-all duration-300 overflow-hidden">

            <div class="relative overflow-hidden aspect-[3/4]">

                <img src="{{ $event->poster_url }}"
                    alt="{{ $event->title }}"
                    class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">

                <div
                    class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600 shadow-sm">
                    {{ $event->category->name ?? 'Tanpa Kategori' }}
                </div>

                @if($event->active_tier_name && $event->active_tier_name !== 'Regular')
                    <div
                        class="absolute top-4 right-4 px-3 py-1 bg-gradient-to-r from-amber-500 to-orange-600 text-white shadow-lg rounded-lg text-xs font-black uppercase tracking-wider">
                        🔥 {{ $event->active_tier_name }}
                    </div>
                @endif
            </div>

            <div class="p-6">

                <h3 class="text-xl font-bold mb-2 group-hover:text-indigo-600 transition">
                    {{ $event->title }}
                </h3>

                <div class="flex items-center gap-2 mt-2">

                    <span class="text-yellow-400">
                        ⭐
                    </span>

                    <span class="font-semibold">
                        {{ number_format($event->reviews->avg('rating') ?? 0,1) }}
                    </span>

                    <span class="text-gray-500 text-sm">
                        ({{ $event->reviews->count() }} Review)
                    </span>

                </div>

                <div class="flex items-center gap-2 text-slate-500 text-sm mb-4 mt-2">
                    <svg class="w-4 h-4"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                        </path>
                    </svg>

                    <span>
                        {{ \Carbon\Carbon::parse($event->date)->format('d F Y, H:i') }}
                    </span>
                </div>

                <div class="space-y-2 mb-4">

                    <div class="flex items-center gap-2 text-slate-500 text-sm">

                        <svg class="w-4 h-4"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M17.657 16.657L13.414 20.9a2 2 0 01-2.827 0L6.343 16.657A8 8 0 1117.657 16.657z">
                            </path>
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M15 11a3 3 0 11-6 0 3 3 0 016 0z">
                            </path>
                        </svg>

                        <span>{{ $event->location }}</span>

                    </div>

                    <div class="flex items-center gap-2 text-sm">

                        <svg class="w-4 h-4 text-indigo-500"
                            fill="none"
                            stroke="currentColor"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round"
                                stroke-linejoin="round"
                                stroke-width="2"
                                d="M5.121 17.804A11.955 11.955 0 0112 15c2.5 0 4.847.765 6.879 2.074M15 11a3 3 0 11-6 0 3 3 0 016 0zM19 21H5a2 2 0 01-2-2v-1a9 9 0 0118 0v1a2 2 0 01-2 2z">
                            </path>
                        </svg>

                        @if($event->owner_type === 'admin')

                        <span class="text-slate-500">
                            Diselenggarakan oleh
                            <span class="font-semibold text-indigo-600">
                                AmikomEventHub
                            </span>
                        </span>

                        @else

                        <span class="text-slate-500">
                            Diselenggarakan oleh
                            <span class="font-semibold text-indigo-600">
                                {{ $event->organizer->organization_name ?? '-' }}
                            </span>
                        </span>

                        @endif

                    </div>

                </div>

                <div class="flex items-center justify-between gap-3 pt-4 border-t border-slate-100 mt-2">
                    <div>
                        <span class="block text-[11px] font-extrabold text-indigo-600 uppercase tracking-wider mb-0.5">
                            {{ $event->active_tier_name ? $event->active_tier_name : 'Harga Reguler' }}
                        </span>
                        <div class="flex items-baseline gap-2 flex-wrap">
                            <span class="text-xl md:text-2xl font-black text-slate-900 tracking-tight">
                                @if ($event->current_price == 0)
                                    <span class="text-emerald-600 font-black">Gratis 🎟️</span>
                                @else
                                    <span class="text-indigo-600 font-black">Rp {{ number_format($event->current_price, 0, ',', '.') }}</span>
                                @endif
                            </span>
                            @if($event->price > $event->current_price)
                                <span class="text-xs text-slate-400 line-through font-bold">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </span>
                            @endif
                        </div>
                    </div>

                    <a href="{{ route('events.show', $event->id) }}"
                        class="shrink-0 px-4 py-2.5 bg-indigo-50 text-indigo-600 rounded-2xl font-bold text-sm hover:bg-indigo-600 hover:text-white shadow-sm hover:shadow-indigo-200 transition-all duration-200 active:scale-95">
                        Lihat Detail
                    </a>
                </div>

            </div>

        </div>

        @empty

        <div class="col-span-3 text-center text-slate-400 italic py-10">
            Belum ada event tersedia.
        </div>

        @endforelse

    </div>

</section>

<!-- Partner Section -->
<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="text-center mb-12">
        <h2 class="text-3xl font-extrabold mb-2">
            Partner Kami
        </h2>

        <p class="text-slate-500 font-medium">
            Platform AmikomEventHub didukung oleh berbagai partner.
        </p>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-4 gap-6">

        @forelse($partners as $partner)

        <div
            class="bg-white rounded-3xl border border-slate-100 shadow-sm p-6 text-center hover:shadow-lg transition">

            @if ($partner->logo_url)
            <img src="{{ $partner->logo_url }}"
                alt="{{ $partner->name }}"
                class="w-20 h-20 mx-auto rounded-2xl object-cover border mb-4">
            @else
            <div
                class="w-20 h-20 mx-auto rounded-2xl bg-slate-100 flex items-center justify-center text-slate-400 mb-4">
                Logo
            </div>
            @endif

            <h3 class="font-bold text-slate-800">
                {{ $partner->name }}
            </h3>

        </div>

        @empty

        <div class="col-span-4 text-center text-slate-400 italic">
            Belum ada data partner.
        </div>

        @endforelse

    </div>

</section>

@endsection