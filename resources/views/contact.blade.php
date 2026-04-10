<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Kontak</title>

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

<body class="bg-gradient-to-br from-slate-100 via-indigo-50 to-blue-100 min-h-screen">

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
    <div id="menu" class="hidden absolute right-6 top-16 bg-white shadow-xl rounded-2xl p-4 w-44 space-y-2">
        <a href="/profil" class="block bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">Profil</a>
        <a href="/katalog" class="block bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">Katalog</a>
        <a href="/bantuan" class="block bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">Bantuan</a>
        <a href="/contact" class="block bg-pink-500 text-white px-3 py-2 rounded hover:bg-pink-600">Kontak</a>
    </div>

</nav>

<!-- Content -->
<div class="flex items-center justify-center mt-20 px-4">

    <div class="bg-white/70 backdrop-blur-lg border border-white/40
                p-8 rounded-3xl shadow-xl hover:shadow-2xl transition
                text-center max-w-sm w-full">

        <h1 class="text-2xl font-semibold text-indigo-600 mb-2">
            Hubungi Kami
        </h1>

        <p class="text-gray-600 mb-6">
            Email: admin@amikomeventhub.com
        </p>

        <a href="/" class="inline-block bg-indigo-600 text-white font-semibold py-2 px-6 rounded-xl hover:bg-indigo-700 hover:shadow-md transition">
            Kembali ke Home
        </a>

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
