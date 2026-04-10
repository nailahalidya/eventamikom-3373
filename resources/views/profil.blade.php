<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Profil</title>

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

<body class="bg-gradient-to-br from-slate-100 via-blue-50 to-indigo-100 min-h-screen">

<nav class="bg-white/80 backdrop-blur-md shadow-md px-6 py-4 flex justify-between items-center relative">

    <!-- Home kiri -->
    <a href="/" class="bg-red-500 text-white px-5 py-2 rounded-xl hover:bg-red-600 transition shadow-md">
        Home
    </a>

    <!-- Hamburger Button -->
    <button onclick="toggleMenu()" class="text-2xl font-bold">
        ☰
    </button>

    <!-- Dropdown Menu -->
    <div id="menu" class="hidden absolute right-6 top-16 bg-white shadow-lg rounded-xl p-4 w-40 space-y-2">
        <a href="/profil" class="block bg-blue-500 text-white px-3 py-2 rounded hover:bg-blue-600">Profil</a>
        <a href="/katalog" class="block bg-green-500 text-white px-3 py-2 rounded hover:bg-green-600">Katalog</a>
        <a href="/bantuan" class="block bg-yellow-500 text-white px-3 py-2 rounded hover:bg-yellow-600">Bantuan</a>
        <a href="/contact" class="block bg-pink-500 text-white px-3 py-2 rounded hover:bg-pink-600">Kontak</a>
    </div>

</nav>

<!-- Content -->
<div class="flex justify-center items-center mt-16 px-4">

    <div class="w-full max-w-md bg-white/70 backdrop-blur-lg border border-white/40
                p-8 rounded-3xl shadow-xl hover:shadow-2xl transition duration-300">

        <h1 class="text-center text-3xl font-semibold text-blue-600 mb-4">
             PROFIL
        </h1>

        <div class="space-y-2 text-gray-700">
            <p><span class="font-medium">Nama :</span> Nailah Putri Alidya</p>
            <p><span class="font-medium">Email :</span> Nailah@example.com</p>
        </div>

        <div class="mt-4 text-gray-600 text-sm">
            Ini adalah halaman profil pengguna dengan tampilan modern.
        </div>

    </div>

</div>

<script>
    function toggleMenu() {
        const menu = document.getElementById("menu");
        menu.classList.toggle("hidden");
    }
</script>

</body>
</html>
