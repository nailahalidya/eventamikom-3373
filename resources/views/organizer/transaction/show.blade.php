@extends('layouts.admin')

@section('title','Detail Transaksi')

@section('page_title','Detail Transaksi')

@section('page_subtitle','Informasi lengkap transaksi pelanggan.')

@section('content')

<div class="max-w-5xl mx-auto space-y-6">

    <div class="bg-white rounded-3xl shadow-sm border overflow-hidden">

        <div class="px-8 py-6 border-b">

            <h2 class="text-2xl font-black">

                {{ $transaction->order_id }}

            </h2>

        </div>

        <div class="grid md:grid-cols-2 gap-8 p-8">

            <div>

                <h3 class="font-bold text-lg mb-4">

                    Data Pembeli

                </h3>

                <div class="space-y-3">

                    <div>

                        <p class="text-sm text-slate-500">
                            Nama
                        </p>

                        <p class="font-semibold">
                            {{ $transaction->customer_name }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Email
                        </p>

                        <p class="font-semibold">
                            {{ $transaction->customer_email }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Telepon
                        </p>

                        <p class="font-semibold">
                            {{ $transaction->customer_phone }}
                        </p>

                    </div>

                </div>

            </div>

            <div>

                <h3 class="font-bold text-lg mb-4">

                    Detail Event

                </h3>

                <div class="space-y-3">

                    <div>

                        <p class="text-sm text-slate-500">
                            Event
                        </p>

                        <p class="font-semibold">
                            {{ $transaction->event->title }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Lokasi
                        </p>

                        <p class="font-semibold">
                            {{ $transaction->event->location }}
                        </p>

                    </div>

                    <div>

                        <p class="text-sm text-slate-500">
                            Tanggal Event
                        </p>

                        <p class="font-semibold">
                            {{ \Carbon\Carbon::parse($transaction->event->date)->format('d M Y') }}
                        </p>

                    </div>

                </div>

            </div>

        </div>

        <div class="border-t p-8">

            <h3 class="font-bold text-lg mb-4">

                Detail Pembayaran

            </h3>

            <div class="grid md:grid-cols-4 gap-6">

                <div>

                    <p class="text-sm text-slate-500">
                        Status
                    </p>

                    <p class="font-bold text-indigo-600">

                        {{ strtoupper($transaction->status) }}

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Total
                    </p>

                    <p class="font-bold">

                        Rp {{ number_format($transaction->total_price,0,',','.') }}

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Tanggal Transaksi
                    </p>

                    <p class="font-bold">

                        {{ $transaction->created_at->format('d M Y H:i') }}

                    </p>

                </div>

                <div>

                    <p class="text-sm text-slate-500">
                        Order ID
                    </p>

                    <p class="font-mono font-bold">

                        {{ $transaction->order_id }}

                    </p>

                </div>

            </div>

        </div>

        <div class="border-t px-8 py-6 bg-slate-50">

            <a href="{{ route('organizer.transaction.index') }}"
                class="px-5 py-3 rounded-xl bg-slate-800 hover:bg-slate-900 text-white font-semibold">

                Kembali

            </a>

        </div>

    </div>

</div>

@endsection