@extends('layouts.admin')
@section('content')
    <main class="flex-1 p-10 overflow-y-auto">
        <header class="flex justify-between items-center mb-10">
            <div>
                <h1 class="text-3xl font-black">Laporan & Kelola Transaksi</h1>
                <p class="text-slate-500 font-medium">Pantau arus kas, filter status, dan kelola transaksi tiket Anda.</p>
            </div>
            <div class="flex gap-4">
                <button onclick="window.print()"
                    class="px-6 py-3 border-2 border-slate-200 rounded-2xl font-bold hover:bg-white hover:border-indigo-600 hover:text-indigo-600 transition">
                    Cetak Laporan
                </button>
            </div>
        </header>

        @if(session('success'))
            <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center justify-between">
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <!-- Filter & Search Form -->
            <form method="GET" action="{{ route('admin.transactions.index') }}" class="px-8 py-6 bg-slate-50/50 border-b flex flex-wrap gap-4 items-center">
                <div class="flex-1 min-w-[280px] flex gap-2">
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari Order ID, Nama, atau Email..."
                        class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition text-sm font-medium tracking-wide">
                </div>
                <div class="flex flex-wrap gap-3 items-center">
                    <select name="status" onchange="this.form.submit()"
                        class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold text-slate-700">
                        <option value="all" {{ request('status') == 'all' ? 'selected' : '' }}>Semua Status</option>
                        <option value="success" {{ request('status') == 'success' ? 'selected' : '' }} class="text-emerald-600">Success / Settlement</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }} class="text-amber-600">Pending</option>
                        <option value="expired" {{ request('status') == 'expired' ? 'selected' : '' }} class="text-rose-600">Expired / Failed</option>
                    </select>

                    <select name="period" onchange="this.form.submit()"
                        class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none text-sm font-bold text-slate-700">
                        <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua Periode</option>
                        <option value="this_month" {{ request('period') == 'this_month' ? 'selected' : '' }}>Bulan Ini</option>
                        <option value="last_month" {{ request('period') == 'last_month' ? 'selected' : '' }}>Bulan Lalu</option>
                        <option value="this_year" {{ request('period') == 'this_year' ? 'selected' : '' }}>Tahun Ini</option>
                    </select>

                    <button type="submit" class="px-5 py-3 bg-indigo-600 text-white font-bold rounded-xl text-sm shadow hover:bg-indigo-700 transition">
                        Cari
                    </button>

                    @if(request()->anyFilled(['search', 'status', 'period']))
                        <a href="{{ route('admin.transactions.index') }}" class="px-4 py-3 bg-slate-200 text-slate-700 font-bold rounded-xl text-sm hover:bg-slate-300 transition">
                            Reset
                        </a>
                    @endif
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-8 py-4">Order ID</th>
                            <th class="px-8 py-4">Detail Pembeli</th>
                            <th class="px-8 py-4">Event</th>
                            <th class="px-8 py-4">Tgl Transaksi</th>
                            <th class="px-8 py-4">Status</th>
                            <th class="px-8 py-4 text-right">Total Tagihan</th>
                            <th class="px-8 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-t">
                        @forelse($transactions as $trx)
                            <tr class="hover:bg-slate-50/50 transition">
                                <td class="px-8 py-6">
                                    <span class="font-mono font-bold text-indigo-600 bg-indigo-50 px-3 py-1 rounded-lg text-sm">#{{ $trx->order_id }}</span>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-bold text-slate-800">{{ $trx->customer_name }}</p>
                                    <p class="text-xs text-slate-500">{{ $trx->customer_email }}</p>
                                </td>
                                <td class="px-8 py-6">
                                    <p class="font-medium text-slate-700">{{ $trx->event->title ?? '-' }}</p>
                                </td>
                                <td class="px-8 py-6 text-sm text-slate-500">
                                    {{ $trx->created_at ? $trx->created_at->format('d M Y, H:i') : '-' }}
                                </td>
                                <td class="px-8 py-6">
                                    @if(in_array(strtolower($trx->status), ['success', 'settlement']))
                                        <span class="px-3 py-1 bg-emerald-100 text-emerald-700 rounded-lg text-xs font-bold uppercase ring-1 ring-emerald-200">Success</span>
                                    @elseif(strtolower($trx->status) === 'pending')
                                        <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-lg text-xs font-bold uppercase ring-1 ring-amber-200">Pending</span>
                                    @elseif(in_array(strtolower($trx->status), ['expired', 'failed', 'cancel', 'deny']))
                                        <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase ring-1 ring-rose-200">Expired</span>
                                    @else
                                        <span class="px-3 py-1 bg-slate-100 text-slate-700 rounded-lg text-xs font-bold uppercase ring-1 ring-slate-200">{{ $trx->status }}</span>
                                    @endif
                                </td>
                                <td class="px-8 py-6 text-right font-black text-slate-900">
                                    Rp {{ number_format($trx->total_price ?? 0, 0, ',', '.') }}
                                </td>
                                <td class="px-8 py-6 text-center">
                                    <form action="{{ route('admin.transactions.destroy', $trx->id) }}" method="POST" onsubmit="return confirm('Apakah Anda yakin ingin menghapus transaksi ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="px-3 py-1.5 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-lg text-xs font-bold transition border border-rose-200">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="7" class="px-8 py-10 text-center text-slate-400 font-medium">
                                    Belum ada data transaksi yang sesuai dengan filter.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-8 py-6 bg-slate-50/50 border-t">
                {{ $transactions->links() }}
            </div>
        </div>
    </main>
@endsection
