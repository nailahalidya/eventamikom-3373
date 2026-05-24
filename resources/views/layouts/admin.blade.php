<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Admin Dashboard' }} - AmikomEventHub</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">
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
                AH</div>
            <span class="text-xl font-bold text-white tracking-tight">AmikomEventHub</span>
        </div>
        <nav class="flex-1 space-y-2">
    <p class="text-[10px] font-bold uppercase tracking-widest text-indigo-400 mb-4 px-2">
        Main Menu
    </p>

    <a href="{{ route('admin.index') }}"
       class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold transition
       {{ request()->routeIs('admin.index') ? 'bg-indigo-800 text-white' : 'hover:bg-indigo-800' }}">
        Dashboard
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

    <a href="/"
   onclick="return confirm('Keluar dari halaman admin?')"
   class="flex items-center gap-3 px-4 py-3 rounded-xl font-bold text-red-200 hover:bg-red-600 hover:text-white transition">

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
</nav>
<div class="border-t border-indigo-800 pt-4 text-xs text-indigo-300">
    <p class="font-bold text-white">Admin Panel</p>
    <p>AmikomEventHub</p>
</div>
    </aside>


    <main class="flex-1 p-10 overflow-y-auto">
        @yield('content')
    </main>
</body>

</html>
