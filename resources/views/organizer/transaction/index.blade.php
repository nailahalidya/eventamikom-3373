@extends('layouts.admin')

@section('title', 'Transaksi Event Saya | EventAmikom')
@section('page_title', 'Transaksi Event Saya')
@section('page_subtitle', 'Pantau transaksi dari seluruh event yang Anda kelola.')

@section('content')

<div class="bg-white rounded-[2rem] border border-slate-100 shadow-sm overflow-hidden">

    <div class="px-8 py-6 border-b bg-slate-50/60">

        <h2 class="text-xl font-black text-slate-800">
            Transaksi Event Saya
        </h2>

        <p class="text-sm text-slate-500 mt-1">
            Menampilkan seluruh transaksi dari event yang Anda kelola.
        </p>

    </div>

    <div class="overflow-x-auto">

        <table class="w-full text-left border-collapse">

            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">

                <tr>

                    <th class="px-8 py-4">Order ID</th>

                    <th class="px-8 py-4">Pembeli</th>

                    <th class="px-8 py-4">Event</th>

                    <th class="px-8 py-4">Tanggal</th>

                    <th class="px-8 py-4">Status</th>

                    <th class="px-8 py-4 text-right">Total</th>

                    <th class="px-8 py-4 text-center">Aksi</th>

                </tr>

            </thead>

            <tbody class="divide-y border-t">

                @forelse($transactions as $trx)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-8 py-6">

                            <span class="font-mono font-bold px-3 py-1 rounded-lg bg-indigo-50 text-indigo-600 text-sm">

                                {{ $trx->order_id }}

                            </span>

                        </td>

                        <td class="px-8 py-6">

                            <p class="font-semibold text-slate-800">

                                {{ $trx->customer_name }}

                            </p>

                            <p class="text-xs text-slate-500">

                                {{ $trx->customer_email }}

                            </p>

                            <p class="text-xs text-slate-500">

                                {{ $trx->customer_phone }}

                            </p>

                        </td>

                        <td class="px-8 py-6">

                            <p class="font-semibold text-slate-800">

                                {{ $trx->event->title ?? '-' }}

                            </p>

                            <p class="text-xs text-slate-500">

                                {{ $trx->event->location ?? '-' }}

                            </p>

                            @if($trx->event && $trx->event->date)

                                <p class="text-xs text-slate-400">

                                    {{ \Carbon\Carbon::parse($trx->event->date)->format('d M Y') }}

                                </p>

                            @endif

                        </td>

                        <td class="px-8 py-6 text-sm text-slate-500">

                            {{ $trx->created_at->format('d M Y H:i') }}

                        </td>

                        <td class="px-8 py-6">

                            @if(in_array($trx->status,['success','settlement']))

                                <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-bold">

                                    SUCCESS

                                </span>

                            @elseif($trx->status=='pending')

                                <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-bold">

                                    PENDING

                                </span>

                            @elseif($trx->status=='expire')

                                <span class="px-3 py-1 rounded-lg bg-gray-100 text-gray-700 text-xs font-bold">

                                    EXPIRED

                                </span>

                            @elseif($trx->status=='cancel')

                                <span class="px-3 py-1 rounded-lg bg-red-100 text-red-700 text-xs font-bold">

                                    CANCEL

                                </span>

                            @else

                                <span class="px-3 py-1 rounded-lg bg-slate-100 text-slate-700 text-xs font-bold">

                                    {{ strtoupper($trx->status) }}

                                </span>

                            @endif

                        </td>

                        <td class="px-8 py-6 text-right font-black text-slate-900">

                            Rp {{ number_format($trx->total_price,0,',','.') }}

                        </td>

                        <td class="px-8 py-6 text-center">

                            <a href="{{ route('organizer.transaction.show',$trx) }}"
                               class="inline-flex items-center px-4 py-2 rounded-lg bg-indigo-600 hover:bg-indigo-700 text-white text-sm">

                                Detail

                            </a>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7" class="px-8 py-12 text-center text-slate-500">

                            Belum ada transaksi untuk event yang Anda kelola.

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    <div class="px-8 py-6 bg-slate-50 border-t">

        {{ $transactions->links() }}

    </div>

</div>

@endsection