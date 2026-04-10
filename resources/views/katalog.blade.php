<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Katalog</title>

    <!-- Font Poppins -->
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

    <!-- Tailwind -->
    <script src="https://cdn.tailwindcss.com"></script>

    <style>
        body {
            font-family: 'Poppins', sans-serif;
        }
    </style>
</head>

<body class="bg-gradient-to-br from-slate-100 via-green-50 to-emerald-100 min-h-screen">

<!-- Navbar -->
<nav class="bg-white/80 backdrop-blur-md shadow-md px-6 py-4 flex justify-between items-center relative">

    <!-- Home kiri -->
    <a href="/" class="bg-red-500 text-white px-5 py-2 rounded-xl hover:bg-red-600 transition shadow-md">
        Home
    </a>

    <!-- Hamburger -->
    <button onclick="toggleMenu()" class="text-2xl font-bold">
        ☰
    </button>

    <!-- Dropdown -->
    <div id="menu" class="hidden absolute right-6 top-16 bg-white shadow-lg rounded-xl p-4 w-40 space-y-2">
        <a href="/profil" class="block bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">Profil</a>
        <a href="/katalog" class="block bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">Katalog</a>
        <a href="/bantuan" class="block bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">Bantuan</a>
        <a href="/contact" class="block bg-pink-500 text-white px-3 py-2 rounded hover:bg-pink-600">Kontak</a>
    </div>

</nav>

<!-- Content -->
<div class="px-6 mt-12">

    <h1 class="text-3xl font-semibold text-center text-blue-400 mb-10">
        Katalog Produk
    </h1>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-8">

        <!-- Card 1 -->
        <div class="bg-white/70 backdrop-blur-lg border border-white/40 p-6 rounded-3xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition duration-300">

            <img src="https://images.unsplash.com/photo-1523275335684-37898b6baf30"
                 class="rounded-2xl mb-4 h-40 w-full object-cover">

            <h2 class="font-semibold text-lg text-blue-400 mb-2">Produk 1</h2>
            <p class="text-blue-300 text-sm">Laptop modern untuk kebutuhan kerja dan belajar.</p>

        </div>

        <!-- Card 2 -->
        <div class="bg-white/70 backdrop-blur-lg border border-white/40 p-6 rounded-3xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition duration-300">

            <img src="https://images.unsplash.com/photo-1505740420928-5e560c06d30e"
                 class="rounded-2xl mb-4 h-40 w-full object-cover">

            <h2 class="font-semibold text-lg text-blue-400 mb-2">Produk 2</h2>
            <p class="text-blue-300 text-sm">Headphone dengan kualitas suara premium.</p>

        </div>

        <!-- Card 3 -->
        <div class="bg-white/70 backdrop-blur-lg border border-white/40 p-6 rounded-3xl shadow-xl hover:shadow-2xl hover:-translate-y-1 transition duration-300">

            <img src="https://images.unsplash.com/photo-1510557880182-3d4d3cba35a5"
                 class="rounded-2xl mb-4 h-40 w-full object-cover">

            <h2 class="font-semibold text-lg text-blue-400 mb-2">Produk 3</h2>
            <p class="text-blue-300 text-sm">Smartwatch untuk gaya hidup sehat.</p>

        </div>

    </div>

</div>

<!-- Script -->
<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.classList.toggle("hidden");
    }
</script>

</body>
</html>
