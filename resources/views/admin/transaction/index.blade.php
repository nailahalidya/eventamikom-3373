@extends('layouts.admin')

@section('title', 'Laporan & Kelola Transaksi - Admin')
@section('page_title', 'Laporan & Kelola Transaksi')
@section('page_subtitle', 'Pantau arus kas, filter status, dan kelola data transaksi tiket.')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <header class="flex justify-between items-center">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Transaksi</h1>
            <p class="text-slate-500 font-medium mt-1">Seluruh riwayat transaksi tiket dari database.</p>
        </div>
        <button onclick="window.print()"
            class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold hover:bg-white hover:border-indigo-600 hover:text-indigo-600 transition text-sm">
            🖨 Cetak Laporan
        </button>
    </header>

    {{-- Flash Message --}}
    @if(session('success'))
        <div class="p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-semibold flex items-center gap-3">
            <span>✅ {{ session('success') }}</span>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">

        {{-- Filter & Search Form --}}
        <form method="GET" action="{{ route('admin.transactions.index') }}"
            class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">

            {{-- Search --}}
            <div class="flex-1 min-w-[280px]">
                <input type="text" name="search" value="{{ request('search') }}"
                    placeholder="Cari Order ID, Nama, atau Email..."
                    class="w-full px-5 py-3 rounded-xl border border-slate-200 bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium">
            </div>

            <div class="flex flex-wrap gap-3 items-center">
                {{-- Status Filter --}}
                <select name="status" onchange="this.form.submit()"
                    class="px-5 py-3 rounded-xl border border-slate-200 bg-white outline-none text-sm font-bold text-slate-700">
                    <option value="all"       {{ request('status', 'all') === 'all'     ? 'selected' : '' }}>Semua Status</option>
                    <option value="success"   {{ request('status') === 'success'        ? 'selected' : '' }}>✅ Success</option>
                    <option value="pending"   {{ request('status') === 'pending'        ? 'selected' : '' }}>⏳ Pending</option>
                    <option value="expired"   {{ request('status') === 'expired'        ? 'selected' : '' }}>❌ Expired / Failed</option>
                </select>

                {{-- Period Filter --}}
                <select name="period" onchange="this.form.submit()"
                    class="px-5 py-3 rounded-xl border border-slate-200 bg-white outline-none text-sm font-bold text-slate-700">
                    <option value="all"        {{ request('period', 'all') === 'all'         ? 'selected' : '' }}>Semua Periode</option>
                    <option value="this_month" {{ request('period') === 'this_month'         ? 'selected' : '' }}>Bulan Ini</option>
                    <option value="last_month" {{ request('period') === 'last_month'         ? 'selected' : '' }}>Bulan Lalu</option>
                    <option value="this_year"  {{ request('period') === 'this_year'          ? 'selected' : '' }}>Tahun Ini</option>
                </select>

                <button type="submit"
                    class="px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl text-sm shadow hover:bg-indigo-700 transition">
                    Cari
                </button>

                @if(request()->anyFilled(['search', 'status', 'period']))
                    <a href="{{ route('admin.transactions.index') }}"
                        class="px-4 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-300 transition">
                        Reset
                    </a>
                @endif
            </div>
        </form>

        {{-- Table --}}
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4">Order ID</th>
                        <th class="px-8 py-4">Pembeli</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4">Tgl Transaksi</th>
                        <th class="px-8 py-4 text-center">Status</th>
                        <th class="px-8 py-4 text-right">Total</th>
                        <th class="px-8 py-4 text-center">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y border-t">
                    @forelse($transactions as $trx)
                        <tr class="hover:bg-slate-50/50 transition group">
                            {{-- Order ID --}}
                            <td class="px-8 py-5">
                                <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg text-xs">
                                    {{ $trx->order_id }}
                                </span>
                            </td>

                            {{-- Pembeli --}}
                            <td class="px-8 py-5">
                                <p class="font-bold text-slate-800 text-sm">{{ $trx->customer_name }}</p>
                                <p class="text-xs text-slate-500 leading-relaxed">
                                    {{ $trx->customer_email }}<br>
                                    {{ $trx->customer_phone }}
                                </p>
                            </td>

                            {{-- Event --}}
                            <td class="px-8 py-5">
                                <p class="font-semibold text-slate-700 text-sm">{{ $trx->event->title ?? '-' }}</p>
                                @if($trx->event)
                                    <p class="text-xs text-slate-400">{{ $trx->event->location ?? '' }}</p>
                                @endif
                            </td>

                            {{-- Tanggal --}}
                            <td class="px-8 py-5 text-sm text-slate-500 whitespace-nowrap">
                                {{ $trx->created_at ? $trx->created_at->format('d M Y') : '-' }}<br>
                                <span class="text-xs text-slate-400">{{ $trx->created_at ? $trx->created_at->format('H:i') : '' }}</span>
                            </td>

                            {{-- Status Badge --}}
                            <td class="px-8 py-5 text-center">
                                @php $s = strtolower($trx->status); @endphp
                                @if(in_array($s, ['success', 'settlement']))
                                    <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase ring-1 ring-emerald-200">Success</span>
                                @elseif($s === 'pending')
                                    <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase ring-1 ring-amber-200">Pending</span>
                                @elseif(in_array($s, ['expired', 'failed', 'cancel', 'deny', 'expire']))
                                    <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">Expired</span>
                                @else
                                    <span class="px-3 py-1 bg-slate-100 text-slate-600 rounded-lg text-xs font-bold uppercase ring-1 ring-slate-200">{{ $trx->status }}</span>
                                @endif
                            </td>

                            {{-- Total --}}
                            <td class="px-8 py-5 text-right font-black text-slate-900 whitespace-nowrap">
                                Rp {{ number_format($trx->total_price ?? 0, 0, ',', '.') }}
                            </td>

                            {{-- Hapus --}}
                            <td class="px-8 py-5 text-center">
                                <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST"
                                    onsubmit="return confirm('Hapus transaksi {{ $trx->order_id }}? Stok tiket akan dikembalikan jika masih pending.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="px-3 py-1.5 bg-rose-50 text-rose-500 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-bold transition border border-rose-200">
                                        Hapus
                                    </button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-8 py-12 text-center text-slate-400 font-medium">
                                <div class="flex flex-col items-center gap-2">
                                    <span class="text-4xl">📭</span>
                                    <span>Belum ada transaksi yang sesuai dengan filter.</span>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        {{-- Pagination --}}
        <div class="px-8 py-6 bg-slate-50/50 border-t flex items-center justify-between">
            <p class="text-sm text-slate-500 font-medium">
                Menampilkan {{ $transactions->firstItem() ?? 0 }}–{{ $transactions->lastItem() ?? 0 }}
                dari <strong>{{ $transactions->total() }}</strong> transaksi
            </p>
            {{ $transactions->links() }}
        </div>

    </div>
</div>
@endsection