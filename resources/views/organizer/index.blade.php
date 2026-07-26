@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    <div class="flex justify-between items-center">

        <div>

            <h1 class="text-3xl font-black">
                Dashboard Organizer
            </h1>

            <p class="text-slate-500 mt-1">
                Selamat datang,
                <span class="font-semibold">
                    {{ $organizer->name }}
                </span>
            </p>

        </div>

    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-2 xl:grid-cols-4 gap-6">

        <div class="bg-white rounded-3xl shadow-sm border p-6">
            <p class="text-slate-400 text-sm">
                Total Event
            </p>

            <h2 class="text-3xl font-black mt-2">
                {{ $totalEvents }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border p-6">
            <p class="text-slate-400 text-sm">
                Total Transaksi
            </p>

            <h2 class="text-3xl font-black mt-2">
                {{ $totalTransactions }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border p-6">
            <p class="text-slate-400 text-sm">
                Tiket Terjual
            </p>

            <h2 class="text-3xl font-black mt-2">
                {{ $totalTickets }}
            </h2>
        </div>

        <div class="bg-white rounded-3xl shadow-sm border p-6">
            <p class="text-slate-400 text-sm">
                Total Pendapatan
            </p>

            <h2 class="text-3xl font-black mt-2 text-indigo-600">
                Rp {{ number_format($totalIncome,0,',','.') }}
            </h2>
        </div>

    </div>

    {{-- Event Terbaru --}}
    <div class="bg-white rounded-3xl shadow-sm border">

        <div class="p-6 border-b">

            <h2 class="font-black text-xl">
                Event Terbaru
            </h2>

        </div>

        <div class="divide-y">

            @forelse($latestEvents as $event)

                <div class="flex justify-between items-center p-6">

                    <div>

                        <h3 class="font-semibold">
                            {{ $event->title }}
                        </h3>

                        <p class="text-sm text-slate-400">
                            {{ $event->created_at->format('d M Y') }}
                        </p>

                    </div>

                    <span class="px-3 py-1 rounded-full bg-indigo-100 text-indigo-700 text-sm">

                        {{ ucfirst($event->status) }}

                    </span>

                </div>

            @empty

                <div class="p-8 text-center text-slate-500">

                    Belum memiliki event.

                </div>

            @endforelse

        </div>

    </div>

    {{-- Transaksi Terbaru --}}
    <div class="bg-white rounded-3xl shadow-sm border">

        <div class="p-6 border-b">

            <h2 class="font-black text-xl">
                Transaksi Terbaru
            </h2>

        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="px-6 py-4 text-left">
                            Order ID
                        </th>

                        <th class="px-6 py-4 text-left">
                            Event
                        </th>

                        <th class="px-6 py-4 text-left">
                            Status
                        </th>

                        <th class="px-6 py-4 text-right">
                            Total
                        </th>

                    </tr>

                </thead>

                <tbody>

                    @forelse($latestTransactions as $trx)

                        <tr class="border-t">

                            <td class="px-6 py-4">

                                {{ $trx->order_id }}

                            </td>

                            <td class="px-6 py-4">

                                {{ $trx->event->title ?? '-' }}

                            </td>

                            <td class="px-6 py-4">

                                {{ ucfirst($trx->status) }}

                            </td>

                            <td class="px-6 py-4 text-right font-semibold">

                                Rp {{ number_format($trx->total_price,0,',','.') }}

                            </td>

                        </tr>

                    @empty

                        <tr>

                            <td colspan="4"
                                class="text-center py-8 text-slate-500">

                                Belum ada transaksi.

                            </td>

                        </tr>

                    @endforelse

                </tbody>

            </table>

        </div>

    </div>

</div>

@endsection