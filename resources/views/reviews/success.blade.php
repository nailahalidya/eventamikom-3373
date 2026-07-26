@extends('layouts.app')

@section('content')

<div class="min-h-[70vh] flex items-center justify-center px-6">

    <div class="bg-white rounded-3xl shadow-xl p-12 text-center max-w-lg w-full">

        <div class="text-6xl mb-6">
            ⭐⭐⭐⭐⭐
        </div>

        <h1 class="text-3xl font-black text-slate-800 mb-4">
            Terima Kasih!
        </h1>

        <p class="text-slate-500 mb-8">
            Review Anda berhasil dikirim dan akan membantu pengguna lain dalam memilih event.
        </p>

        <div class="flex flex-col gap-4">

            <a href="{{ url('/') }}"
                class="bg-indigo-600 text-white py-3 rounded-xl font-bold hover:bg-indigo-700 transition">
                Kembali ke Beranda
            </a>

            <a href="{{ url('/#events') }}"
                class="border border-slate-300 py-3 rounded-xl font-semibold hover:bg-slate-50 transition">
                Lihat Event Lain
            </a>

        </div>

    </div>

</div>

@endsection