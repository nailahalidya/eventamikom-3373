@extends('layouts.admin')

@section('title', 'Kelola Kupon Diskon - Admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black text-slate-900">Kelola Kupon Diskon</h1>
            <p class="text-slate-500 font-medium mt-1">Buat & atur kode voucher promo untuk pembeli tiket.</p>
        </div>
    </header>

    @if(session('success'))
        <div class="mb-6 p-4 bg-emerald-50 border border-emerald-200 text-emerald-700 rounded-2xl font-bold flex items-center justify-between">
            <span>✅ {{ session('success') }}</span>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Form Tambah Kupon -->
        <div class="bg-white p-8 rounded-[2.5rem] border border-slate-100 shadow-sm h-fit">
            <h2 class="text-xl font-black mb-6 text-slate-900">Tambah Kupon Baru</h2>

            <form action="{{ route('admin.coupons.store') }}" method="POST" class="space-y-4">
                @csrf

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kode Kupon</label>
                    <input type="text" name="code" placeholder="Contoh: MAHASISWA50" required
                        class="w-full px-4 py-3 rounded-xl border border-slate-200 uppercase font-mono font-bold focus:ring-2 focus:ring-indigo-500 outline-none text-sm">
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Tipe Diskon</label>
                        <select name="type" required class="w-full px-3 py-3 rounded-2xl border border-slate-200 text-sm font-bold text-slate-700 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
                            <option value="fixed">Nominal (Rp)</option>
                            <option value="percent">Persentase (%)</option>
                        </select>
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Nilai Diskon</label>
                        <input type="number" name="discount_amount" placeholder="Contoh: 50000 atau 50" required min="1"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 font-bold focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none text-sm">
                    </div>
                </div>

                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Batas Pakai</label>
                        <input type="number" name="max_uses" placeholder="Opsional, misal 100" min="1"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none text-sm font-medium">
                    </div>

                    <div>
                        <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Mulai Berlaku</label>
                        <input type="date" name="starts_at"
                            class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none text-sm font-medium">
                    </div>
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Kadaluwarsa</label>
                    <input type="date" name="expires_at"
                        class="w-full px-4 py-3 rounded-2xl border border-slate-200 focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200 outline-none text-sm font-medium">
                </div>

                <div>
                    <label class="block text-xs font-bold text-slate-700 uppercase mb-2">Khusus Event (Opsional)</label>
                    <select name="event_id" id="coupon-event-select" class="w-full px-3 py-3 rounded-2xl border border-slate-200 text-sm font-medium text-slate-700 outline-none focus:border-emerald-400 focus:ring-2 focus:ring-emerald-200">
                        <option value="">Berlaku Semua Event</option>
                        @foreach($events as $ev)
                            <option value="{{ $ev->id }}">{{ $ev->title }}</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-slate-400 mt-2">Pilih event untuk memfilter ticket tier. Kosong = berlaku semua event dan semua tier.</p>
                </div>

                <div>
                    <div class="flex items-center justify-between gap-3 mb-3">
                        <label class="text-xs font-bold text-slate-700 uppercase">Target Ticket Tier (Opsional)</label>
                        <span id="tier-status" class="text-xs font-medium text-slate-500">Pilih event untuk melihat tier terkait</span>
                    </div>

                    <select name="ticket_tier_ids[]" id="coupon-tier-select" multiple class="w-full px-3 py-3 rounded-2xl border border-slate-200 text-sm font-medium text-slate-700 outline-none min-h-[120px] hidden">
                        @foreach($tiers as $t)
                            <option value="{{ $t->id }}" data-event-id="{{ $t->event_id }}" title="{{ $t->event->title ?? 'Event' }} — {{ $t->name }}">
                                {{ $t->event->title ?? 'Event' }} — {{ $t->name }} — Rp {{ number_format($t->price,0,',','.') }}
                            </option>
                        @endforeach
                    </select>

                    <p class="text-xs text-slate-400 mt-2" id="tier-help-text">Target tier hanya diperlukan jika kupon ini ingin dibatasi untuk tier tertentu.</p>
                </div>

                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-3 mt-4">
                    <button type="submit" class="w-full sm:w-auto py-3 px-5 bg-emerald-600 hover:bg-emerald-700 text-white rounded-2xl font-black text-sm shadow-lg shadow-emerald-200 transition">
                        Simpan Kupon
                    </button>
                    <button type="reset" class="w-full sm:w-auto py-3 px-5 bg-slate-100 hover:bg-slate-200 text-slate-700 rounded-2xl font-bold text-sm transition">
                        Reset Form
                    </button>
                </div>

                <script>
                    (function(){
                        const eventSelect = document.getElementById('coupon-event-select');
                        const tierSelect = document.getElementById('coupon-tier-select');
                        const tierStatus = document.getElementById('tier-status');
                        const tierHelpText = document.getElementById('tier-help-text');

                        function filterTiers(){
                            const selectedEvent = eventSelect.value;
                            let visible = false;

                            for (const opt of tierSelect.options) {
                                const ev = opt.dataset.eventId;
                                const show = selectedEvent && ev === selectedEvent;
                                opt.style.display = show ? '' : 'none';

                                if (show) {
                                    visible = true;
                                } else {
                                    opt.selected = false;
                                }
                            }

                            if (!selectedEvent) {
                                tierSelect.classList.add('hidden');
                                tierSelect.disabled = true;
                                tierStatus.textContent = 'Pilih event terlebih dahulu untuk menampilkan target tier.';
                                tierHelpText.textContent = 'Jika event tidak dipilih, kupon akan berlaku untuk semua tier.';
                            } else if (visible) {
                                tierSelect.classList.remove('hidden');
                                tierSelect.disabled = false;
                                tierStatus.textContent = 'Pilih satu atau lebih tier jika perlu.';
                                tierHelpText.textContent = 'Biarkan kosong untuk membuat kupon berlaku untuk semua tier pada event ini.';
                            } else {
                                tierSelect.classList.remove('hidden');
                                tierSelect.disabled = true;
                                tierStatus.textContent = 'Tidak ada tier tersedia untuk event ini.';
                                tierHelpText.textContent = 'Buat ticket tier terlebih dahulu di halaman event jika ingin membatasi kupon ke tier tertentu.';
                            }
                        }

                        if(eventSelect && tierSelect){
                            eventSelect.addEventListener('change', filterTiers);
                            const parentForm = eventSelect.closest('form');

                            if (parentForm) {
                                parentForm.addEventListener('reset', function () {
                                    setTimeout(filterTiers, 0);
                                });
                            }

                            filterTiers();
                        }
                    })();
                </script>
            </form>
        </div>

        <!-- Daftar Kupon -->
        <div class="lg:col-span-2 bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
            <div class="px-8 py-6 border-b bg-slate-50/50">
                <h3 class="font-black text-lg text-slate-800">Daftar Kupon Aktif</h3>
                <p class="text-sm text-slate-500 mt-2">Lihat ringkasan penggunaan kupon, event terkait, dan periode berlaku di sini.</p>
            </div>

            <div class="overflow-x-auto">
                <table class="w-full min-w-[960px] text-left border-collapse">
                    <thead class="bg-slate-50 text-slate-500 uppercase text-[10px] font-black tracking-widest">
                        <tr>
                            <th class="px-6 py-4">Kode</th>
                            <th class="px-6 py-4">Diskon</th>
                            <th class="px-6 py-4">Digunakan</th>
                            <th class="px-6 py-4">Event / Tier</th>
                            <th class="px-6 py-4">Kadaluarsa</th>
                            <th class="px-6 py-4 text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y border-t border-slate-100">
                        @forelse($coupons as $c)
                            <tr class="hover:bg-slate-50 transition-colors">
                                <td class="px-6 py-4 align-top">
                                    <span class="inline-flex items-center gap-2 rounded-full bg-violet-50 px-3 py-1 text-xs font-black uppercase tracking-[0.08em] text-violet-700">
                                        {{ $c->code }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 align-top text-slate-800 font-semibold text-sm">
                                    @if($c->type === 'percent')
                                        {{ $c->discount_amount }}%
                                    @else
                                        Rp {{ number_format($c->discount_amount, 0, ',', '.') }}
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top">
                                    <div class="inline-flex items-center gap-2 rounded-full bg-emerald-100 px-3 py-1 text-xs font-semibold text-emerald-700">
                                        {{ $c->used_count }}{{ $c->max_uses ? ' / ' . $c->max_uses : '' }}
                                    </div>
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-slate-700">
                                    <div class="font-semibold text-slate-900 line-clamp-2" title="{{ $c->event->title ?? 'Semua Event' }}">
                                        {{ $c->event->title ?? 'Semua Event' }}
                                    </div>
                                    @if(\Illuminate\Support\Facades\Schema::hasTable('coupon_ticket_tier') && $c->ticketTiers && $c->ticketTiers->isNotEmpty())
                                        <div class="mt-2 text-xs text-slate-500">
                                            Tier: {{ $c->ticketTiers->pluck('name')->join(', ') }}
                                        </div>
                                    @else
                                        <div class="mt-2 text-xs text-slate-400">Semua tier</div>
                                    @endif
                                </td>
                                <td class="px-6 py-4 align-top text-sm text-slate-500">
                                    {{ $c->expires_at ? $c->expires_at->format('d M Y') : 'Tanpa batas' }}
                                </td>
                                <td class="px-6 py-4 align-top text-center">
                                    <form action="{{ route('admin.coupons.destroy', $c->id) }}" method="POST" onsubmit="return confirm('Hapus kupon {{ $c->code }}?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="inline-flex items-center justify-center px-4 py-2 bg-rose-50 text-rose-600 hover:bg-rose-600 hover:text-white rounded-full text-xs font-bold transition border border-rose-200">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="px-8 py-10 text-center text-slate-400 font-medium">
                                    Belum ada kupon diskon. Buat kupon pertama Anda di sebelah kiri.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="px-6 py-4 bg-slate-50/50 border-t border-slate-100">
                {{ $coupons->links() }}
            </div>
        </div>

    </div>
</main>
@endsection
