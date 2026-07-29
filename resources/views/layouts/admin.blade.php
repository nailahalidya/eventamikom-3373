<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - AmikomEventHub</title>

    <!-- PWA Settings -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AmikomEventHub">
    <link rel="apple-touch-icon" href="/assets/icon-192.png">

    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <script>
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('SW Registered!', reg))
                    .catch(err => console.log('SW Registration failed', err));
            });
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900 flex min-h-screen">

    <aside class="w-64 bg-indigo-900 text-indigo-100 flex flex-col p-6 space-y-8 sticky top-0 h-screen">

        <div class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                AH
            </div>

            <span class="text-xl font-bold text-white tracking-tight">
                AmikomEventHub
            </span>
        </div>

        <nav class="flex-1 space-y-2">

            <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">
                Main Menu
            </p>

            @if(auth()->user()->role == 'admin')

            <a href="{{ route('admin.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Dashboard
            </a>

            <a href="{{ route('admin.organizers.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('organizers.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Kelola Organizer
            </a>

            <a href="{{ route('admin.events.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.events.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Kelola Event
            </a>

            <a href="{{ route('admin.categories.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.categories.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Kelola Kategori
            </a>

            <a href="{{ route('admin.partners.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.partners.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Kelola Partner
            </a>

            <a href="{{ route('admin.transactions.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.transactions.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                 Kelola Transaksi
            </a>

            <a href="{{ route('admin.coupons.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('admin.coupons.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Kupon Diskon
            </a>

            <a href="{{ route('checkin.show') }}" target="_blank"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition bg-emerald-600 hover:bg-emerald-500 text-white mt-2">
                Check-in Scanner
            </a>

            @endif

            {{-- ========================= --}}
            {{-- ORGANIZER --}}
            {{-- ========================= --}}
            @if(auth()->user()->role == 'organizer')

            <a href="{{ route('organizer.dashboard') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('organizer.dashboard') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Dashboard
            </a>

            <a href="{{ route('organizer.events.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('organizer.events.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Event Saya
            </a>

            <a href="{{ route('organizer.transaction.index') }}"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
                    {{ request()->routeIs('organizer.transactions.*') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
                Transaksi
            </a>

            @endif

            <hr class="border-indigo-800 my-4">

            <a href="#"
                onclick="event.preventDefault(); if(confirm('Keluar dari panel {{ auth()->user()->role == 'admin' ? 'Admin' : 'Organizer' }}?')) { document.getElementById('logout-form').submit(); }"
                class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-red-200 hover:bg-red-600 hover:text-white transition cursor-pointer">

                <svg xmlns="http://www.w3.org/2000/svg"
                    class="w-5 h-5"
                    fill="none"
                    viewBox="0 0 24 24"
                    stroke="currentColor">

                    <path stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a2 2 0 01-2 2H6a2 2 0 01-2-2V7a2 2 0 012-2h5a2 2 0 012 2v1" />

                </svg>

                Keluar

            </a>

            <form id="logout-form"
                action="{{ auth()->user() && auth()->user()->role === 'organizer' ? route('organizer.logout') : route('admin.logout') }}"
                method="POST"
                class="hidden">

                @csrf

            </form>

        </nav>

        <div class="border-t border-indigo-800 pt-4 text-xs text-indigo-300">

            <p class="font-bold text-white">
                {{ auth()->user()->role == 'admin' ? 'Admin' : 'Organizer' }}
            </p>

            <p>AmikomEventHub</p>

        </div>

    </aside>

    <main class="flex-1 p-10 overflow-y-auto">

        @yield('content')

    </main>

</body>

</html>