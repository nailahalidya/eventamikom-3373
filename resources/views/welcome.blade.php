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

        <div class="flex gap-4">
            <a href="#events"
                class="px-8 py-4 bg-indigo-600 text-white rounded-2xl font-bold text-lg shadow-xl shadow-indigo-200 hover:scale-105 transition-transform">
                Mulai Jelajah
            </a>

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

        <img src="{{ asset('assets/concert.png') }}"
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

<!-- Top Rated Events Section -->
@if(isset($topRatedEvents) && $topRatedEvents->count() > 0)
<section class="bg-gradient-to-b from-indigo-900 via-slate-900 to-slate-900 text-white py-16 px-8 my-12 rounded-[2.5rem] max-w-7xl mx-auto relative overflow-hidden shadow-2xl">
    <!-- Background Glows -->
    <div class="absolute -top-24 -right-24 w-96 h-96 bg-amber-500/20 rounded-full blur-3xl pointer-events-none"></div>
    <div class="absolute -bottom-24 -left-24 w-96 h-96 bg-indigo-500/20 rounded-full blur-3xl pointer-events-none"></div>

    <div class="relative z-10 max-w-7xl mx-auto">
        <div class="flex flex-col md:flex-row justify-between items-start md:items-end mb-10 gap-4">
            <div>
                <span class="inline-flex items-center gap-2 px-4 py-1.5 bg-amber-400/20 border border-amber-400/30 text-amber-300 rounded-full text-xs font-extrabold uppercase tracking-wider mb-3">
                    <span>⭐</span> Pilihan Utama Pengunjung
                </span>
                <h2 class="text-3xl md:text-4xl font-black text-white">
                    Event Rating Tertinggi
                </h2>
                <p class="text-indigo-200 text-sm mt-1">
                    Acara favorit dengan ulasan bintang tertinggi dari komunitas & peserta
                </p>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            @foreach($topRatedEvents as $topEvent)
            @php
                $avgRating = number_format($topEvent->reviews_avg_rating ?? $topEvent->reviews->avg('rating') ?? 0, 1);
                $reviewCount = $topEvent->reviews_count ?? $topEvent->reviews->count();
            @endphp
            <div class="bg-white/10 backdrop-blur-xl border border-white/15 rounded-3xl overflow-hidden hover:border-amber-400/50 hover:shadow-2xl hover:shadow-amber-500/10 transition-all duration-300 flex flex-col justify-between group">
                <div>
                    <!-- Poster Image -->
                    <div class="relative aspect-[16/9] overflow-hidden">
                        <img src="{{ $topEvent->poster_url }}" alt="{{ $topEvent->title }}" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-500">
                        
                        <!-- Top Rating Badge -->
                        <div class="absolute top-3 left-3 bg-slate-900/90 backdrop-blur-md text-amber-300 border border-amber-400/40 px-3 py-1.5 rounded-xl text-xs font-black flex items-center gap-1.5 shadow-lg">
                            <span>⭐</span> {{ $avgRating }} / 5.0
                        </div>

                        <div class="absolute top-3 right-3 bg-indigo-600/90 text-white px-3 py-1 rounded-xl text-xs font-bold uppercase tracking-wider">
                            {{ $topEvent->category->name ?? 'Event' }}
                        </div>
                    </div>

                    <!-- Details -->
                    <div class="p-6">
                        <h3 class="text-xl font-black text-white mb-2 line-clamp-1 group-hover:text-amber-300 transition">
                            {{ $topEvent->title }}
                        </h3>

                        <div class="flex items-center gap-2 mb-4">
                            <div class="flex text-amber-400 text-sm">
                                @for($i = 1; $i <= 5; $i++)
                                    @if($i <= round($avgRating))
                                        ★
                                    @else
                                        <span class="text-slate-600">★</span>
                                    @endif
                                @endfor
                            </div>
                            <span class="text-xs text-indigo-200 font-medium">({{ $reviewCount }} Ulasan)</span>
                        </div>

                        <div class="space-y-2 text-xs text-slate-300 mb-4">
                            <div class="flex items-center gap-2">
                                <span>📅</span> <span>{{ \Carbon\Carbon::parse($topEvent->date)->format('d F Y, H:i') }}</span>
                            </div>
                            <div class="flex items-center gap-2">
                                <span>📍</span> <span class="line-clamp-1">{{ $topEvent->location }}</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Footer Price & Action -->
                <div class="p-6 pt-4 flex items-center justify-between border-t border-white/10 mt-auto">
                    <div>
                        <span class="text-[10px] uppercase font-bold text-slate-400 block">Harga Tiket</span>
                        <span class="text-xl font-black text-amber-300">
                            @if ($topEvent->price == 0)
                                Gratis
                            @else
                                Rp {{ number_format($topEvent->current_price ?? $topEvent->price, 0, ',', '.') }}
                            @endif
                        </span>
                    </div>

                    <a href="{{ route('events.show', $topEvent->id) }}" class="px-4 py-2.5 bg-amber-400 hover:bg-amber-300 text-slate-950 font-extrabold text-xs rounded-xl transition shadow-lg shadow-amber-400/20">
                        Beli Tiket
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</section>
@endif

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
                    class="absolute top-4 left-4 px-3 py-1 bg-white/90 backdrop-blur rounded-lg text-xs font-bold uppercase text-indigo-600">
                    {{ $event->category->name ?? 'Tanpa Kategori' }}
                </div>
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

                <div class="flex items-center gap-2 text-slate-500 text-sm mb-4">
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

                <div class="flex justify-between items-center pt-4 border-t">
                    <span class="text-2xl font-black text-indigo-600">
                        @if ($event->price == 0)
                        Gratis
                        @else
                        Rp {{ number_format($event->price, 0, ',', '.') }}
                        @endif
                    </span>

                    <a href="{{ route('events.show', $event->id) }}"
                        class="px-5 py-2 bg-indigo-50 text-indigo-600 rounded-xl font-bold hover:bg-indigo-600 hover:text-white transition">
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