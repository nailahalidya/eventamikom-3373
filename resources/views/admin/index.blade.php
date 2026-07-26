@extends('layouts.admin')

@section('content')
<!-- Header -->
<header class="flex justify-between items-center mb-10">
    <div>
        <h1 class="text-3xl font-black">Dashboard Ringkasan</h1>
        <p class="text-slate-500 font-medium">Selamat datang kembali, Admin!</p>
    </div>

    <div class="flex items-center gap-4">
        <div class="text-right hidden md:block">
            <p class="font-bold">Admin Super</p>
            <p class="text-xs text-slate-400">Penyelenggara Utama</p>
        </div>

        <div class="w-12 h-12 bg-white rounded-2xl shadow-sm border flex items-center justify-center p-1">
            <img src="https://ui-avatars.com/api/?name=Admin+Super&background=6366f1&color=fff"
                 class="rounded-xl"
                 alt="Admin Super">
        </div>
    </div>
</header>

<!-- Stats Grid -->
<div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-6 mb-10">
    <!-- Total Pendapatan -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-indigo-50 text-indigo-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>

        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pendapatan</p>
        <h3 class="text-2xl font-black">
            Rp {{ number_format($totalRevenue ?? 0, 0, ',', '.') }}
        </h3>
    </div>

    <!-- Tiket Terjual -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-green-50 text-green-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M15 5v2m0 4v2m0 4v2M5 5a2 2 0 00-2 2v3a2 2 0 110 4v3a2 2 0 002 2h14a2 2 0 002-2v-3a2 2 0 110-4V7a2 2 0 00-2-2H5z">
                </path>
            </svg>
        </div>

        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Tiket Terjual</p>
        <h3 class="text-2xl font-black">
            {{ number_format($ticketsSold ?? 0, 0, ',', '.') }}
        </h3>
    </div>

    <!-- Total Pengguna -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-sky-50 text-sky-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                </path>
            </svg>
        </div>

        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Total Pengguna</p>
        <h3 class="text-2xl font-black">
            {{ number_format($totalUsers ?? 0, 0, ',', '.') }}
        </h3>
    </div>

    <!-- Event Aktif -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-orange-50 text-orange-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>

        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Event Aktif</p>
        <h3 class="text-2xl font-black">
            {{ $activeEvents ?? 0 }} Event
        </h3>
    </div>

    <!-- Pesanan Pending -->
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <div class="w-12 h-12 bg-rose-50 text-rose-600 rounded-2xl flex items-center justify-center mb-4">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round"
                      stroke-linejoin="round"
                      stroke-width="2"
                      d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z">
                </path>
            </svg>
        </div>

        <p class="text-slate-400 text-sm font-bold uppercase mb-1">Pesanan Pending</p>
        <h3 class="text-2xl font-black">
            {{ $pendingOrders ?? 0 }} Pesanan
        </h3>
    </div>
</div>

<!-- Charts Section: Pertumbuhan Pengguna & Penyelenggaraan Event -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

    <!-- Grafik Pertumbuhan Pengguna -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-black text-xl text-slate-800">Pertumbuhan Pengguna</h3>
                <p class="text-sm text-slate-400 mt-1">Tren pendaftaran akun pengguna (6 Bulan Terakhir)</p>
            </div>
            <div class="flex items-center gap-2 bg-indigo-50 px-3.5 py-1.5 rounded-xl text-indigo-600 font-bold text-sm">
                <span>+{{ $currentMonthUsers }} Bulan Ini</span>
                @if(($userGrowthPercentage ?? 0) >= 0)
                    <span class="text-xs bg-indigo-100 text-indigo-700 px-2 py-0.5 rounded-md">↑ {{ $userGrowthPercentage }}%</span>
                @else
                    <span class="text-xs bg-rose-100 text-rose-700 px-2 py-0.5 rounded-md">↓ {{ abs($userGrowthPercentage) }}%</span>
                @endif
            </div>
        </div>
        <div class="relative w-full h-72">
            <canvas id="userGrowthChart"></canvas>
        </div>
    </div>

    <!-- Grafik Pertumbuhan Penyelenggaraan Event -->
    <div class="bg-white p-8 rounded-3xl border border-slate-100 shadow-sm flex flex-col justify-between">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="font-black text-xl text-slate-800">Pertumbuhan Event</h3>
                <p class="text-sm text-slate-400 mt-1">Tren pembuatan & penyelenggaraan event (6 Bulan Terakhir)</p>
            </div>
            <div class="flex items-center gap-2 bg-emerald-50 px-3.5 py-1.5 rounded-xl text-emerald-600 font-bold text-sm">
                <span>Total {{ $totalEvents ?? 0 }} Event</span>
            </div>
        </div>
        <div class="relative w-full h-72">
            <canvas id="eventGrowthChart"></canvas>
        </div>
    </div>

</div>

<!-- Laporan Organizer -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-10">

    <!-- Ringkasan Organizer -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm">

        <div class="p-8 border-b">
            <h3 class="font-black text-xl">Laporan Organizer</h3>
            <p class="text-sm text-slate-400 mt-1">
                Ringkasan data seluruh organizer.
            </p>
        </div>

        <div class="grid grid-cols-2 gap-5 p-8">

            <div class="bg-indigo-50 rounded-2xl p-5">
                <p class="text-slate-500 text-sm font-semibold">
                    Total Organizer
                </p>

                <h2 class="text-3xl font-black mt-2 text-indigo-600">
                    {{ $totalOrganizers ?? 0 }}
                </h2>
            </div>

            <div class="bg-green-50 rounded-2xl p-5">
                <p class="text-slate-500 text-sm font-semibold">
                    Approved
                </p>

                <h2 class="text-3xl font-black mt-2 text-green-600">
                    {{ $approvedOrganizers ?? 0 }}
                </h2>
            </div>

            <div class="bg-yellow-50 rounded-2xl p-5">
                <p class="text-slate-500 text-sm font-semibold">
                    Pending
                </p>

                <h2 class="text-3xl font-black mt-2 text-yellow-600">
                    {{ $pendingOrganizers ?? 0 }}
                </h2>
            </div>

            <div class="bg-rose-50 rounded-2xl p-5">
                <p class="text-slate-500 text-sm font-semibold">
                    Rejected
                </p>

                <h2 class="text-3xl font-black mt-2 text-rose-600">
                    {{ $rejectedOrganizers ?? 0 }}
                </h2>
            </div>

        </div>

    </div>

    <!-- Organizer Terbaru -->
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

        <div class="p-8 border-b flex justify-between items-center">

            <div>
                <h3 class="font-black text-xl">Organizer Terbaru</h3>

                <p class="text-sm text-slate-400 mt-1">
                    Pendaftaran organizer terbaru.
                </p>
            </div>

            <a href="{{ route('admin.organizers.index') }}"
               class="text-indigo-600 font-bold hover:underline">
                Lihat Semua
            </a>

        </div>

        <div class="divide-y">

            @forelse($recentOrganizers as $organizer)

                <div class="flex justify-between items-center p-6">

                    <div>

                        <h4 class="font-bold">
                            {{ $organizer->name }}
                        </h4>

                        <p class="text-sm text-slate-400">
                            {{ $organizer->email }}
                        </p>

                    </div>

                    @if($organizer->status == 'approved')

                        <span class="px-3 py-1 rounded-lg bg-green-100 text-green-700 text-xs font-bold">
                            Approved
                        </span>

                    @elseif($organizer->status == 'pending')

                        <span class="px-3 py-1 rounded-lg bg-yellow-100 text-yellow-700 text-xs font-bold">
                            Pending
                        </span>

                    @else

                        <span class="px-3 py-1 rounded-lg bg-rose-100 text-rose-700 text-xs font-bold">
                            Rejected
                        </span>

                    @endif

                </div>

            @empty

                <div class="p-8 text-center text-slate-500">

                    Belum ada organizer.

                </div>

            @endforelse

        </div>

    </div>

</div>

<!-- Latest Transactions Table -->
<div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
    <div class="p-8 border-b flex justify-between items-center">
        <div>
            <h3 class="font-black text-xl">Transaksi Terakhir</h3>
            <p class="text-sm text-slate-400 mt-1">
                Data transaksi terbaru yang masuk ke sistem.
            </p>
        </div>

        <a href="{{ route('admin.transactions.index') }}"
           class="text-indigo-600 font-bold hover:underline">
            Lihat Semua
        </a>
    </div>

    <div class="overflow-x-auto">
        <table class="w-full text-left border-collapse">
            <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                <tr>
                    <th class="px-8 py-4">Tgl Transaksi</th>
                    <th class="px-8 py-4">Pembeli</th>
                    <th class="px-8 py-4">Event</th>
                    <th class="px-8 py-4">Status</th>
                    <th class="px-8 py-4 text-right">Total</th>
                </tr>
            </thead>

            <tbody class="divide-y border-t">
                @forelse($latestTransactions as $trx)
                    <tr class="hover:bg-slate-50 transition">
                        <td class="px-8 py-6 text-sm text-slate-600 max-w-xs break-all">
                            {{ $trx->created_at ? $trx->created_at->format('d M y - H:i') : '-' }}
                            <br>
                            <span class="text-xs text-slate-400">
                                {{ $trx->order_id }}
                            </span>
                        </td>

                        <td class="px-8 py-6">
                            <p class="font-bold uppercase tracking-wide text-sm truncate max-w-[150px]">
                                {{ $trx->customer_name }}
                            </p>
                            <p class="text-xs text-slate-400 truncate max-w-[150px]">
                                {{ $trx->customer_email }}
                            </p>
                        </td>

                        <td class="px-8 py-6 font-medium text-slate-600 max-w-xs truncate">
                            {{ $trx->event->title ?? '-' }}
                        </td>

                        <td class="px-8 py-6 whitespace-nowrap">
                            @if($trx->status === 'success' || $trx->status === 'settlement')
                                <span class="px-3 py-1 bg-green-100 text-green-700 rounded-lg text-xs font-bold uppercase">
                                    Success
                                </span>
                            @elseif($trx->status === 'pending')
                                <span class="px-3 py-1 bg-orange-100 text-orange-700 rounded-lg text-xs font-bold uppercase">
                                    Pending
                                </span>
                            @elseif($trx->status === 'challenge')
                                <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-lg text-xs font-bold uppercase">
                                    Challenge
                                </span>
                            @else
                                <span class="px-3 py-1 bg-rose-100 text-rose-700 rounded-lg text-xs font-bold uppercase">
                                    {{ $trx->status ?? 'Unknown' }}
                                </span>
                            @endif
                        </td>

                        <td class="px-8 py-6 font-black text-indigo-600 whitespace-nowrap text-right">
                            Rp {{ number_format($trx->total_price ?? 0, 0, ',', '.') }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5" class="px-8 py-10 text-center text-slate-500">
                            Belum ada transaksi.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const months = @json($months ?? []);
    const userGrowthData = @json($userGrowthData ?? []);
    const eventGrowthData = @json($eventGrowthData ?? []);

    // Grafik Pertumbuhan Pengguna (Line Chart)
    const ctxUser = document.getElementById('userGrowthChart');
    if (ctxUser) {
        const userCtx = ctxUser.getContext('2d');
        const userGradient = userCtx.createLinearGradient(0, 0, 0, 300);
        userGradient.addColorStop(0, 'rgba(99, 102, 241, 0.35)');
        userGradient.addColorStop(1, 'rgba(99, 102, 241, 0.0)');

        new Chart(userCtx, {
            type: 'line',
            data: {
                labels: months,
                datasets: [{
                    label: 'Pengguna Baru',
                    data: userGrowthData,
                    borderColor: '#6366f1',
                    borderWidth: 3,
                    backgroundColor: userGradient,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#4f46e5',
                    pointBorderColor: '#ffffff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                    pointHoverRadius: 7
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold', family: 'Plus Jakarta Sans' },
                        bodyFont: { size: 13, family: 'Plus Jakarta Sans' },
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Pengguna Baru';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { family: 'Plus Jakarta Sans', weight: '600' },
                            color: '#64748b'
                        },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }

    // Grafik Pertumbuhan Event (Bar Chart)
    const ctxEvent = document.getElementById('eventGrowthChart');
    if (ctxEvent) {
        const eventCtx = ctxEvent.getContext('2d');
        const eventGradient = eventCtx.createLinearGradient(0, 0, 0, 300);
        eventGradient.addColorStop(0, '#10b981');
        eventGradient.addColorStop(1, '#059669');

        new Chart(eventCtx, {
            type: 'bar',
            data: {
                labels: months,
                datasets: [{
                    label: 'Event Diselenggarakan',
                    data: eventGrowthData,
                    backgroundColor: eventGradient,
                    borderRadius: 8,
                    barThickness: 24,
                    maxBarThickness: 32
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        backgroundColor: '#0f172a',
                        padding: 12,
                        titleFont: { size: 14, weight: 'bold', family: 'Plus Jakarta Sans' },
                        bodyFont: { size: 13, family: 'Plus Jakarta Sans' },
                        cornerRadius: 10,
                        displayColors: false,
                        callbacks: {
                            label: function(context) {
                                return context.parsed.y + ' Event Diselenggarakan';
                            }
                        }
                    }
                },
                scales: {
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Plus Jakarta Sans', weight: '600' }, color: '#64748b' }
                    },
                    y: {
                        beginAtZero: true,
                        ticks: {
                            precision: 0,
                            font: { family: 'Plus Jakarta Sans', weight: '600' },
                            color: '#64748b'
                        },
                        grid: { color: '#f1f5f9' }
                    }
                }
            }
        });
    }
});
</script>
@endsection