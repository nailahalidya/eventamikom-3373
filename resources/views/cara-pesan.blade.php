@extends('layouts.app')

@section('title', 'Cara Pesan Tiket')

@section('content')

<section class="max-w-7xl mx-auto px-6 py-20">

    <div class="text-center mb-16">
        <span class="inline-block px-4 py-2 bg-indigo-100 text-indigo-600 rounded-full font-semibold text-sm">
            Panduan Pemesanan
        </span>

        <h1 class="text-5xl font-extrabold mt-6 mb-4">
            Cara Memesan Tiket Event
        </h1>

        <p class="text-slate-500 max-w-2xl mx-auto text-lg">
            Ikuti langkah-langkah berikut untuk memesan tiket event favoritmu
            melalui AmikomEventHub dengan mudah dan aman.
        </p>
    </div>

    <div class="grid lg:grid-cols-2 gap-8">

        <!-- STEP 1 -->
        <div class="bg-white rounded-3xl shadow-sm border p-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold mb-6">
                1
            </div>

            <h3 class="text-2xl font-bold mb-3">
                Pilih Event
            </h3>

            <p class="text-slate-500 leading-relaxed">
                Jelajahi berbagai event yang tersedia pada halaman utama.
                Pilih event yang ingin kamu ikuti, lalu klik
                <strong>"Lihat Detail"</strong>.
            </p>
        </div>

        <!-- STEP 2 -->
        <div class="bg-white rounded-3xl shadow-sm border p-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold mb-6">
                2
            </div>

            <h3 class="text-2xl font-bold mb-3">
                Isi Data Pemesan
            </h3>

            <p class="text-slate-500 leading-relaxed">
                Masukkan nama lengkap, email aktif, dan nomor WhatsApp yang dapat
                dihubungi untuk menerima informasi tiket.
            </p>
        </div>

        <!-- STEP 3 -->
        <div class="bg-white rounded-3xl shadow-sm border p-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold mb-6">
                3
            </div>

            <h3 class="text-2xl font-bold mb-3">
                Lakukan Pembayaran
            </h3>

            <p class="text-slate-500 leading-relaxed">
                Klik tombol <strong>Bayar Sekarang</strong>.
                Kamu akan diarahkan ke halaman pembayaran Midtrans yang
                mendukung QRIS, Virtual Account, E-Wallet, Kartu Kredit,
                dan metode pembayaran lainnya.
            </p>
        </div>

        <!-- STEP 4 -->
        <div class="bg-white rounded-3xl shadow-sm border p-8">
            <div class="w-14 h-14 rounded-2xl bg-indigo-100 text-indigo-600 flex items-center justify-center text-2xl font-bold mb-6">
                4
            </div>

            <h3 class="text-2xl font-bold mb-3">
                Tiket Berhasil Dipesan
            </h3>

            <p class="text-slate-500 leading-relaxed">
                Setelah pembayaran berhasil diverifikasi, status transaksi akan
                berubah menjadi <strong>Success</strong> dan tiketmu berhasil
                dipesan.
            </p>
        </div>

    </div>

    <!-- Bantuan -->
    <div class="mt-20 bg-gradient-to-r from-indigo-600 to-violet-600 rounded-[32px] p-12 text-center text-white shadow-2xl">

        <h2 class="text-4xl font-extrabold mb-4">
            Masih Mengalami Kendala?
        </h2>

        <p class="max-w-2xl mx-auto text-indigo-100 text-lg mb-10">
            Tim AmikomEventHub siap membantu apabila mengalami kesulitan
            saat melakukan pemesanan ataupun pembayaran tiket.
        </p>

        <div class="flex flex-col md:flex-row justify-center gap-5">

            <a href="{{ url('/') }}"
                class="px-8 py-4 bg-white text-indigo-600 rounded-2xl font-bold hover:scale-105 transition">
                Kembali ke Beranda
            </a>

            <a href="https://wa.me/62895621125641?text=Halo%20Admin%20AmikomEventHub,%20saya%20ingin%20bertanya%20mengenai%20pemesanan%20tiket."
                target="_blank"
                class="px-8 py-4 bg-green-500 rounded-2xl font-bold hover:bg-green-600 transition shadow-lg">
                Hubungi Admin via WhatsApp
            </a>

        </div>

    </div>

</section>

@endsection