<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'AmikomEventHub - Temukan Event Seru!')</title>

    <!-- PWA Settings -->
    <link rel="manifest" href="/manifest.json">
    <meta name="theme-color" content="#4f46e5">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="black-translucent">
    <meta name="apple-mobile-web-app-title" content="AmikomEventHub">
    <link rel="apple-touch-icon" href="/assets/icon-192.png">

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800;900&display=swap"
        rel="stylesheet">

    <script>
        let deferredPrompt;
        if ('serviceWorker' in navigator) {
            window.addEventListener('load', () => {
                navigator.serviceWorker.register('/sw.js')
                    .then(reg => console.log('AmikomEventHub PWA Active!', reg))
                    .catch(err => console.log('SW Registration error:', err));
            });
        }

        window.addEventListener('beforeinstallprompt', (e) => {
            e.preventDefault();
            deferredPrompt = e;
            const pwaBtns = document.querySelectorAll('.pwa-install-btn');
            pwaBtns.forEach(btn => btn.classList.remove('hidden'));
        });

        function triggerPwaInstall() {
            if (deferredPrompt) {
                deferredPrompt.prompt();
                deferredPrompt.userChoice.then((choiceResult) => {
                    if (choiceResult.outcome === 'accepted') {
                        console.log('User installed PWA application');
                    }
                    deferredPrompt = null;
                });
            } else {
                const modal = document.getElementById('pwa-instruction-modal');
                if (modal) {
                    modal.classList.remove('hidden');
                } else {
                    alert("📱 Buka di Aplikasi / Install HP AmikomEventHub:\n\n1. Di HP Android: Klik Titik Tiga (⋮) di kanan atas -> pilih 'Tambah ke Layar Utama' / 'Install Aplikasi'.\n2. Di iPhone (Safari): Klik Tombol Bagikan 📤 -> pilih 'Tambah ke Layar Utama ➕'.");
                }
            }
        }

        function closePwaModal() {
            const modal = document.getElementById('pwa-instruction-modal');
            if (modal) modal.classList.add('hidden');
        }
    </script>

    <style>
        body {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        .glass {
            background: rgba(255, 255, 255, 0.7);
            backdrop-filter: blur(10px);
        }

        ::-webkit-scrollbar {
            height: 8px;
        }

        ::-webkit-scrollbar-thumb {
            background: #6366f1;
            border-radius: 20px;
        }

        ::-webkit-scrollbar-track {
            background: #f1f5f9;
        }
    </style>
</head>

<body class="bg-slate-50 text-slate-900">

    <nav
        class="glass sticky top-8 z-40 mx-4 mt-4 px-6 py-4 rounded-2xl border border-white/20 shadow-lg flex justify-between items-center">

        <!-- Logo -->
        <a href="{{ url('/') }}" class="flex items-center gap-3">
            <div
                class="w-10 h-10 bg-indigo-600 rounded-xl flex items-center justify-center text-white font-bold text-xl">
                AH
            </div>

            <span class="text-xl font-bold tracking-tight">
                AmikomEventHub
            </span>
        </a>

        <!-- Menu -->
        <div class="hidden md:flex items-center gap-8">

            <a href="{{ url('/') }}"
                class="text-slate-600 hover:text-indigo-600 transition">
                Jelajahi
            </a>

            <a href="{{ url('/#events') }}"
                class="text-slate-600 hover:text-indigo-600 transition">
                Kategori
            </a>


            <!-- Help Center Dropdown -->
            <div class="relative group">

                <a href="{{ route('help.center') }}"
                    class="flex items-center gap-2 text-slate-600 hover:text-indigo-600 transition">

                    Pusat Bantuan

                    <svg class="w-4 h-4 transition group-hover:rotate-180"
                        fill="none"
                        stroke="currentColor"
                        viewBox="0 0 24 24">

                        <path stroke-linecap="round"
                            stroke-linejoin="round"
                            stroke-width="2"
                            d="M19 9l-7 7-7-7" />

                    </svg>

                </a>


                    <div
                        class="absolute right-0 top-full pt-3 hidden group-hover:block z-50">

                        <div class="bg-white rounded-2xl shadow-xl border border-slate-100 w-72 p-5">

                            <a href="{{ url('/help-center') }}"
                                class="block p-3 rounded-xl hover:bg-indigo-50 transition">

                                <p class="font-bold text-slate-800">
                                    Pusat Bantuan
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Cari informasi dan bantuan
                                </p>

                            </a>


                            <a href="{{ url('/tickets') }}"
                                class="block p-3 rounded-xl hover:bg-indigo-50 transition">

                                <p class="font-bold text-slate-800">
                                    Temukan Tiket Saya
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Lihat tiket dan transaksi
                                </p>

                            </a>


                            <a href="{{ url('/cara-pesan') }}"
                                class="block p-3 rounded-xl hover:bg-indigo-50 transition">

                                <p class="font-bold text-slate-800">
                                    Cara Pesan
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    Panduan membeli tiket
                                </p>

                            </a>

                        </div>

                    </div>


            </div>


            <a href="#footer"
                class="text-slate-600 hover:text-indigo-600 transition">
                Hubungi Kami
            </a>

            <button type="button"
                onclick="triggerPwaInstall()"
                class="pwa-install-btn px-3 py-1.5 bg-emerald-50 hover:bg-emerald-600 text-emerald-700 hover:text-white rounded-xl font-bold text-xs transition-all flex items-center gap-1.5 shadow-sm border border-emerald-200 cursor-pointer">
                <span>📱</span> Buka di Aplikasi
            </button>

        </div>

        <!-- Login Google / User -->
        <div class="flex items-center">

            @guest

            <a href="{{ route('google.login') }}"
                class="flex items-center gap-2 px-5 py-2 bg-white border border-slate-200 rounded-xl hover:bg-slate-50 transition shadow-sm">

                <img
                    src="https://www.svgrepo.com/show/475656/google-color.svg"
                    alt="Google"
                    class="w-5 h-5">

                <span class="font-semibold">
                    Continue with Google
                </span>

            </a>

            @else

            <div class="flex items-center gap-4">

                @if(Auth::user()->avatar)

                <img
                    src="{{ Auth::user()->avatar }}"
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full object-cover border">

                @else

                <img
                    src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}"
                    alt="{{ Auth::user()->name }}"
                    class="w-10 h-10 rounded-full border">

                @endif

                <div class="hidden lg:block">

                    <p class="font-semibold text-sm">
                        {{ Auth::user()->name }}
                    </p>

                    <p class="text-xs text-slate-400">
                        {{ Auth::user()->email }}
                    </p>

                </div>

                <form action="{{ route('logout') }}" method="POST">
                    @csrf

                    <button
                        class="px-4 py-2 rounded-xl bg-red-500 text-white hover:bg-red-600 transition">

                        Logout

                    </button>

                </form>

            </div>

            @endguest

        </div>

    </nav>
    @if(session('success'))

    <div class="max-w-7xl mx-auto mt-6">

        <div class="bg-green-100 border border-green-300 text-green-700 px-6 py-4 rounded-2xl">

            {{ session('success') }}

        </div>

    </div>

    @endif

    <main>
        @yield('content')
    </main>

    <footer id="footer" class="bg-indigo-900 text-indigo-100 py-20 px-6 mt-20">

        <div class="max-w-7xl mx-auto grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-12">

            <!-- Logo -->
            <div class="space-y-4">

                <div class="flex items-center gap-2">

                    <div
                        class="w-10 h-10 bg-white rounded-xl flex items-center justify-center text-indigo-900 font-bold text-xl">
                        AH
                    </div>

                    <span class="text-xl font-bold text-white">
                        AmikomEventHub
                    </span>

                </div>

                <p class="max-w-xs text-indigo-300 text-sm leading-7">
                    Platform digital untuk memudahkan publikasi event, reservasi tiket,
                    serta pengelolaan transaksi bagi mahasiswa, organisasi, dan
                    penyelenggara event di Universitas Amikom Yogyakarta.
                </p>

            </div>

            <!-- Kategori -->
            <div>

                <h4 class="text-white font-bold mb-6">
                    Kategori
                </h4>

                <ul class="space-y-4 text-sm">

                    @foreach ($globalCategories ?? $categories ?? [] as $cat)
                    <li>
                        <a href="{{ url('/category/' . $cat->id) }}"
                            class="hover:text-white transition text-indigo-300">
                            {{ $cat->name }}
                        </a>
                    </li>
                    @endforeach

                </ul>

            </div>

            <!-- Navigasi -->
            <div>

                <h4 class="text-white font-bold mb-6">
                    Navigasi
                </h4>

                <ul class="space-y-4 text-sm text-indigo-300">

                    <li>
                        <a href="{{ url('/') }}" class="hover:text-white transition">
                            Home
                        </a>
                    </li>

                    <li>
                        <a href="{{ url('/#events') }}" class="hover:text-white transition">
                            Semua Event
                        </a>
                    </li>

                    <li>
                        <a href="/cara-pesan" class="hover:text-white transition">
                            Cara Pembayaran
                        </a>
                    </li>

                </ul>

            </div>

            <!-- Partnership Organizer -->
            <div>

                <h4 class="text-white font-bold mb-6">
                    Partnership Organizer
                </h4>

                <p class="text-sm text-indigo-300 leading-7 mb-5">

                    Apakah organisasi, UKM, Himpunan, atau komunitas Anda
                    sering mengadakan event?

                    <br><br>

                    Bergabunglah sebagai
                    <span class="font-semibold text-white">
                        Partner Organizer
                    </span>
                    dan nikmati kemudahan mengelola publikasi event,
                    penjualan tiket, transaksi pembayaran, serta laporan
                    event secara real-time dalam satu dashboard.

                </p>

                <a href="{{ route('register') }}"
                    class="inline-flex items-center justify-center px-3 py-2 rounded-lg bg-white text-indigo-900 text-sm font-semibold hover:bg-indigo-100 transition">

                    Daftar Sebagai Organizer

                </a>

            </div>

            <!-- Kontak -->
            <div>

                <h4 class="text-white font-bold mb-6">
                    Hubungi Kami
                </h4>

                <ul class="space-y-4 text-sm text-indigo-300">

                    <li>
                        📧 support@amikom.ac.id
                    </li>

                    <li>
                        📞 +62 812 3456 7890
                    </li>

                    <li>
                        📍 Universitas Amikom Yogyakarta
                    </li>

                </ul>

            </div>

        </div>

        <div class="max-w-7xl mx-auto pt-12 mt-12 border-t border-indigo-800 text-center">

            <p class="text-indigo-400 text-sm">
                © {{ date('Y') }} <span class="font-semibold text-white">AmikomEventHub</span>.
                Platform Manajemen Event dan Reservasi Tiket Terintegrasi untuk
                Mahasiswa, Organisasi, dan Komunitas Universitas Amikom Yogyakarta.
            </p>

        </div>

    </footer>

    <!-- PWA Install Banner -->
    <div id="pwa-install-banner" class="hidden fixed bottom-6 left-6 right-6 md:left-auto md:right-6 md:max-w-md bg-slate-900 text-white p-4 rounded-2xl shadow-2xl border border-indigo-500/30 z-50 flex items-center justify-between gap-4">
        <div class="flex items-center gap-3">
            <img src="/assets/icon-192.png" alt="App Icon" class="w-12 h-12 rounded-xl object-cover border border-white/20">
            <div>
                <p class="font-bold text-sm text-white">Install AmikomEventHub</p>
                <p class="text-xs text-slate-300">Pasang aplikasi di layar utama HP kamu untuk akses super cepat!</p>
            </div>
        </div>
        <div class="flex items-center gap-2 shrink-0">
            <button id="pwa-install-btn" class="px-3.5 py-2 bg-indigo-600 hover:bg-indigo-500 text-white font-bold text-xs rounded-xl transition shadow-lg shadow-indigo-600/30">
                Install HP
            </button>
            <button onclick="document.getElementById('pwa-install-banner').classList.add('hidden')" class="p-1.5 text-slate-400 hover:text-white transition">
                ✕
            </button>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const btn = document.getElementById('pwa-install-btn');
            if (btn) {
                btn.addEventListener('click', async () => {
                    if (deferredPrompt) {
                        deferredPrompt.prompt();
                        const { outcome } = await deferredPrompt.userChoice;
                        if (outcome === 'accepted') {
                            console.log('PWA installation accepted');
                        }
                        deferredPrompt = null;
                        document.getElementById('pwa-install-banner').classList.add('hidden');
                    }
                });
            }
        });
    </script>

    <!-- PWA Instruction Modal -->
    <div id="pwa-instruction-modal" class="fixed inset-0 bg-slate-900/60 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4">
        <div class="bg-white rounded-3xl p-6 md:p-8 max-w-md w-full shadow-2xl relative border border-slate-100">
            <button type="button" onclick="closePwaModal()" class="absolute top-4 right-4 w-9 h-9 bg-slate-100 text-slate-500 hover:bg-slate-200 rounded-full flex items-center justify-center font-bold text-lg">
                ✕
            </button>
            <div class="text-center mb-6">
                <div class="w-16 h-16 bg-indigo-600 rounded-2xl mx-auto flex items-center justify-center text-white text-2xl font-black shadow-lg shadow-indigo-200 mb-3">
                    AH
                </div>
                <h3 class="text-xl font-black text-slate-900">Buka / Install AmikomEventHub</h3>
                <p class="text-xs text-slate-500 mt-1">Nikmati akses super cepat layaknya aplikasi HP tanpa perlu unduh PlayStore/AppStore!</p>
            </div>
            
            <div class="space-y-4 text-sm">
                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="font-bold text-slate-800 flex items-center gap-2 mb-1">
                        <span>🤖</span> Pengguna Android (Chrome / Edge / Brave)
                    </div>
                    <ol class="list-decimal list-inside text-xs text-slate-600 space-y-1 pl-1">
                        <li>Ketuk ikon <strong>Titik Tiga (⋮)</strong> di pojok kanan atas browser.</li>
                        <li>Pilih menu <strong>"Tambahkan ke Layar Utama"</strong> atau <strong>"Install Aplikasi"</strong>.</li>
                    </ol>
                </div>

                <div class="p-4 bg-slate-50 rounded-2xl border border-slate-100">
                    <div class="font-bold text-slate-800 flex items-center gap-2 mb-1">
                        <span>🍏</span> Pengguna iPhone (Safari)
                    </div>
                    <ol class="list-decimal list-inside text-xs text-slate-600 space-y-1 pl-1">
                        <li>Ketuk tombol <strong>Bagikan (Share 📤)</strong> di bagian bawah Safari.</li>
                        <li>Geser ke bawah dan pilih <strong>"Tambah ke Layar Utama (Add to Home Screen ➕)"</strong>.</li>
                    </ol>
                </div>
            </div>

            <button type="button" onclick="closePwaModal()" class="w-full mt-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition cursor-pointer">
                Mengerti
            </button>
        </div>
    </div>

    @stack('scripts')

</body>

</html>