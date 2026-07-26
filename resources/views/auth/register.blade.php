<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register Organizer - AmikomEventHub</title>

    <script src="https://cdn.tailwindcss.com"></script>

    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700&display=swap"
        rel="stylesheet">

    <style>
        body{
            font-family:'Plus Jakarta Sans',sans-serif;
        }
    </style>

</head>

<body class="bg-slate-100 min-h-screen flex items-center justify-center py-10">

<div class="w-full max-w-3xl bg-white rounded-3xl shadow-xl p-10">

    <div class="text-center mb-10">

        <h1 class="text-3xl font-bold text-indigo-700">
            Daftar Sebagai Organizer
        </h1>

        <p class="text-slate-500 mt-3">
            Bergabunglah sebagai Partner Organizer dan kelola event Anda melalui
            dashboard AmikomEventHub.
        </p>

    </div>

    @if($errors->any())

        <div class="mb-6 rounded-xl bg-red-50 border border-red-200 p-4">

            <ul class="list-disc ml-5 text-red-600">

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

    @endif

    <form action="{{ route('register.post') }}" method="POST">

        @csrf

        <div class="grid md:grid-cols-2 gap-6">

            <div class="md:col-span-2">

                <label class="block font-semibold mb-2">
                    Nama Organisasi
                </label>

                <input
                    type="text"
                    name="name"
                    value="{{ old('name') }}"
                    placeholder="Contoh : HIMASI Amikom"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    required>

            </div>

            <div class="md:col-span-2">

                <label class="block font-semibold mb-2">
                    Logo Organizer (Image URL)
                </label>

                <input
                    type="url"
                    name="logo"
                    value="{{ old('logo') }}"
                    placeholder="https://example.com/logo.png"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500">

                <p class="text-xs text-slate-400 mt-2">
                    Masukkan URL logo organisasi. Kosongkan jika belum memiliki logo.
                </p>

            </div>

            <div>

                <label class="block font-semibold mb-2">
                    Email Organizer
                </label>

                <input
                    type="email"
                    name="email"
                    value="{{ old('email') }}"
                    placeholder="organisasi@email.com"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    required>

            </div>

            <div>

                <label class="block font-semibold mb-2">
                    Nomor WhatsApp
                </label>

                <input
                    type="text"
                    name="phone"
                    value="{{ old('phone') }}"
                    placeholder="08xxxxxxxxxx"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    required>

            </div>

            <div class="md:col-span-2">

                <label class="block font-semibold mb-2">
                    Deskripsi Organizer
                </label>

                <textarea
                    name="description"
                    rows="4"
                    placeholder="Ceritakan singkat mengenai organisasi atau komunitas Anda..."
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500">{{ old('description') }}</textarea>

            </div>

            <div>

                <label class="block font-semibold mb-2">
                    Password
                </label>

                <input
                    type="password"
                    name="password"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    required>

            </div>

            <div>

                <label class="block font-semibold mb-2">
                    Konfirmasi Password
                </label>

                <input
                    type="password"
                    name="password_confirmation"
                    class="w-full rounded-xl border px-4 py-3 focus:ring-2 focus:ring-indigo-500"
                    required>

            </div>

        </div>

        <div class="mt-8 p-5 rounded-2xl bg-indigo-50 border border-indigo-100">

            <h4 class="font-semibold text-indigo-700 mb-2">
                Proses Verifikasi Organizer
            </h4>

            <p class="text-sm text-slate-600 leading-7">
                Setelah pendaftaran berhasil, akun Anda akan berstatus
                <span class="font-semibold">Pending</span>.
                Admin AmikomEventHub akan melakukan verifikasi sebelum akun dapat
                digunakan untuk membuat dan mengelola event.
            </p>

        </div>

        <button
            type="submit"
            class="w-full mt-8 bg-indigo-700 hover:bg-indigo-800 transition text-white font-semibold py-4 rounded-xl">

            Daftar Sebagai Organizer

        </button>

    </form>

    <div class="mt-8 text-center">

        <p class="text-slate-600">

            Sudah memiliki akun?

            <a href="{{ route('login') }}"
               class="text-indigo-700 font-semibold hover:underline">

                Login

            </a>

        </p>

    </div>

</div>

</body>
</html>