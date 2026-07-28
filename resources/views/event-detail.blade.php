@extends('layouts.app')

@section('content')
<main class="max-w-7xl mx-auto px-6 py-12 grid grid-cols-1 lg:grid-cols-3 gap-12">

    <!-- ================= LEFT ================= -->
    <div class="lg:col-span-1">

        <div class="sticky top-32">

            <img
                src="{{ $event->poster_url }}"
                class="w-full rounded-[2.5rem] shadow-2xl border-8 border-white object-cover aspect-[3/4]">



            <div class="mt-8 p-6 bg-white rounded-3xl shadow border">
                <h4 class="font-bold mb-4">Penyelenggara</h4>

                @if($event->organizer)
                <div class="flex items-center gap-4">
                    <img src="{{ $event->organizer->logo_url }}" alt="{{ $event->organizer->name }}" class="w-12 h-12 rounded-2xl object-cover border">
                    <div>
                        <p class="font-bold text-slate-800">{{ $event->organizer->name }}</p>
                        <p class="text-xs text-indigo-600 font-semibold">Verified Organizer</p>
                    </div>
                </div>
                @if($event->organizer->description)
                <p class="text-xs text-slate-500 mt-4 leading-relaxed line-clamp-3">
                    {{ $event->organizer->description }}
                </p>
                @endif
                @if($event->organizer->email || $event->organizer->phone)
                <div class="mt-4 pt-4 border-t border-slate-100 space-y-1.5 text-xs text-slate-500">
                    @if($event->organizer->email)
                    <div class="flex items-center gap-2">
                        <span>📧</span> <span>{{ $event->organizer->email }}</span>
                    </div>
                    @endif
                    @if($event->organizer->phone)
                    <div class="flex items-center gap-2">
                        <span>📞</span> <span>{{ $event->organizer->phone }}</span>
                    </div>
                    @endif
                </div>
                @endif
                @else
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 rounded-2xl bg-indigo-600 text-white flex items-center justify-center font-black text-xl">
                        AH
                    </div>
                    <div>
                        <p class="font-bold text-slate-800">AmikomEventHub</p>
                        <p class="text-xs text-indigo-600 font-semibold">Official Admin</p>
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>


    <!-- ================= RIGHT ================= -->

    <div class="lg:col-span-2 space-y-12">

        <!-- Header -->

        <div>

            <span class="px-4 py-1 rounded-full bg-indigo-100 text-indigo-600 font-bold text-sm">
                {{ $event->category->name ?? 'Event' }}
            </span>

            <h1 class="text-5xl font-black mt-4">
                {{ $event->title }}
            </h1>

            <div class="flex gap-8 mt-5 text-slate-500">

                <span>
                    📅 {{ \Carbon\Carbon::parse($event->date)->format('d F Y H:i') }}
                </span>

                <span>
                    📍 {{ $event->location }}
                </span>

            </div>

        </div>


        <!-- Deskripsi -->

        <div>

            <h2 class="text-2xl font-bold mb-4">
                Deskripsi Event
            </h2>

            <p class="leading-8 text-slate-600">
                {{ $event->description }}
            </p>

        </div>


        <!-- CTA -->

        <div class="bg-gradient-to-r from-indigo-600 to-violet-700 rounded-[2rem] p-10 text-white flex flex-col md:flex-row justify-between items-start md:items-center gap-6 shadow-xl">

            <div>

                <div class="flex items-center gap-2">
                    <p class="uppercase text-indigo-200 tracking-wider text-xs font-bold">
                        Harga Tiket
                    </p>
                    @if($event->active_tier_name !== 'Regular')
                        <span class="px-3 py-0.5 rounded-full bg-amber-400 text-slate-900 font-black text-xs uppercase shadow-sm">
                            🔥 {{ $event->active_tier_name }}
                        </span>
                    @endif
                </div>

                <div class="flex items-baseline gap-3 mt-1">
                    <h2 class="text-4xl md:text-5xl font-black">
                        @if($event->current_price == 0)
                            Gratis
                        @else
                            Rp {{ number_format($event->current_price, 0, ',', '.') }}
                        @endif
                    </h2>

                    @if($event->active_tier_name !== 'Regular' && $event->price > $event->current_price)
                        <span class="text-lg line-through text-indigo-300 font-medium">
                            Rp {{ number_format($event->price, 0, ',', '.') }}
                        </span>
                    @endif
                </div>

                <p class="mt-3 text-sm text-indigo-100 font-medium">
                    🎟 Sisa <b>{{ $event->stock }}</b> tiket
                </p>

            </div>

            <a href="{{ route('checkout.create', $event->id) }}"
                class="w-full md:w-auto text-center px-8 py-4 rounded-xl bg-white text-indigo-600 font-black text-lg shadow-lg hover:bg-indigo-50 transition active:scale-95">
                {{ $event->current_price == 0 ? 'Daftar Gratis 🎟️' : 'Pesan Sekarang' }}
            </a>

        </div>


        <!-- Kebijakan -->

        <div>

            <h2 class="text-2xl font-bold mb-4">
                Kebijakan Tiket
            </h2>

            <ul class="space-y-3 text-slate-600">

                <li>✔ E-ticket dikirim otomatis.</li>

                <li>✔ QR Code dapat dipindai saat acara.</li>

                <li class="text-red-500">
                    ✖ Tiket tidak dapat direfund.
                </li>

            </ul>

        </div>


        <!-- ================= REVIEW ================= -->

        <div
            id="reviews"
            class="bg-white rounded-3xl shadow border p-8">

            <div class="flex justify-between items-center mb-8">

                <div>

                    <h2 class="text-3xl font-bold">

                        ⭐ Rating Pengunjung

                    </h2>

                    <p class="text-slate-500">

                        {{ number_format($event->reviews->avg('rating') ?? 0, 1) }}
                        {{ $event->reviews->count() }}

                    </p>

                </div>

            </div>
            <!-- FORM REVIEW -->

            <form
                action="{{ route('reviews.store',$event->id) }}"
                method="POST"
                class="mb-10 space-y-5">

                @csrf

                @guest

                <div>

                    <label class="font-semibold">
                        Nama
                    </label>

                    <input
                        type="text"
                        name="guest_name"
                        class="w-full border rounded-xl p-3 mt-2"
                        placeholder="Masukkan nama">

                </div>

                @endguest

                <div>

                    <label class="font-semibold">
                        Rating
                    </label>

                    <select
                        name="rating"
                        class="w-full border rounded-xl p-3 mt-2">

                        <option value="5">⭐⭐⭐⭐⭐</option>
                        <option value="4">⭐⭐⭐⭐</option>
                        <option value="3">⭐⭐⭐</option>
                        <option value="2">⭐⭐</option>
                        <option value="1">⭐</option>

                    </select>

                </div>

                <div>

                    <label class="font-semibold">
                        Review
                    </label>

                    <textarea
                        name="review"
                        rows="4"
                        class="w-full border rounded-xl p-4 mt-2"
                        placeholder="Bagaimana pengalamanmu?"></textarea>

                </div>

                <div class="flex items-center gap-3">

                    <input
                        type="checkbox"
                        name="is_anonymous"
                        value="1">

                    <label>
                        Kirim sebagai Anonim
                    </label>

                </div>

                <button
                    class="bg-indigo-600 text-white px-6 py-3 rounded-xl">

                    Kirim Review

                </button>

            </form>

            <hr class="my-10">

            <!-- ================= LIST REVIEW ================= -->

            <div class="mt-10">

                <h2 class="text-3xl font-bold mb-6">
                    Semua Review
                </h2>

                <div class="flex gap-6 overflow-x-auto snap-x snap-mandatory pb-5">

                    @foreach($reviews as $review)

                    <div class="min-w-[380px] max-w-[380px] bg-white rounded-3xl shadow border p-6 flex-shrink-0 snap-start">

                        <div class="flex justify-between items-start">

                            <!-- Avatar -->

                            <div class="flex items-center gap-4">

                                <img
                                    src="{{ $review->avatar }}"
                                    class="w-14 h-14 rounded-full object-cover">

                                <div>

                                    <h4 class="font-bold text-lg">

                                        {{ $review->display_name }}

                                    </h4>

                                    <p class="text-sm text-slate-400">

                                        {{ $review->created_at->diffForHumans() }}

                                    </p>

                                </div>

                            </div>

                            <!-- MENU -->

                            <div class="relative">

                                <button
                                    onclick="toggleMenu({{ $review->id }})"
                                    class="text-2xl font-bold hover:text-indigo-600">

                                    ⋮

                                </button>

                                <div
                                    id="menu-{{ $review->id }}"
                                    class="hidden absolute right-0 mt-2 bg-white border rounded-xl shadow-lg w-44 z-50">

                                    @if(Auth::check() && Auth::id()==$review->user_id)

                                    <a
                                        href="{{ route('reviews.edit',$review->id) }}"
                                        class="block px-4 py-3 hover:bg-gray-100">

                                        ✏ Edit Review

                                    </a>

                                    <form
                                        action="{{ route('reviews.destroy',$review->id) }}"
                                        method="POST">

                                        @csrf
                                        @method('DELETE')

                                        <button
                                            class="w-full text-left px-4 py-3 text-red-500 hover:bg-red-50">

                                            🗑 Hapus Review

                                        </button>

                                    </form>

                                    @endif

                                    <form
                                        action="{{ route('reviews.report',$review->id) }}"
                                        method="POST">

                                        @csrf

                                        <button
                                            class="w-full text-left px-4 py-3 hover:bg-gray-100">

                                            🚩 Laporkan

                                        </button>

                                    </form>

                                </div>

                            </div>

                        </div>

                        <!-- BINTANG -->

                        <div class="flex gap-1 mt-6">

                            @for($i=1;$i<=5;$i++)

                                @if($i <=$review->rating)

                                <span class="text-yellow-400 text-2xl">★</span>

                                @else

                                <span class="text-gray-300 text-2xl">☆</span>

                                @endif

                                @endfor

                        </div>

                        <!-- REVIEW -->

                        <p class="mt-5 leading-8 text-slate-600">

                            {{ $review->review }}

                        </p>

                    </div>

                    @endforeach

                </div>

            </div>
</main>
@push('scripts')

<script>
    function toggleMenu(id) {

        document.querySelectorAll("[id^='menu-']").forEach(menu => {
            if (menu.id != "menu-" + id) {
                menu.classList.add("hidden");
            }
        });

        document
            .getElementById("menu-" + id)
            .classList.toggle("hidden");

    }

    window.onclick = function(e) {

        if (!e.target.closest(".relative")) {

            document.querySelectorAll("[id^='menu-']").forEach(menu => {
                menu.classList.add("hidden");
            });

        }

    }
</script>

@endpush
@endsection