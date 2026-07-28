@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="mb-10">
        <h1 class="text-3xl font-black">Edit Event</h1>
        <p class="text-slate-500 font-medium">Perbarui detail event yang sudah dibuat.</p>
    </header>

    @if ($errors->any())
        <div class="mb-6 px-6 py-4 bg-red-100 text-red-700 rounded-2xl font-semibold">
            <ul class="list-disc ml-5">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 p-10 shadow-sm max-w-4xl">
        <form action="{{ route('admin.events.update', $event->id) }}"
              method="POST"
              enctype="multipart/form-data"
              class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-2 gap-6">
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Judul Event
                    </label>
                    <input type="text"
                           name="title"
                           value="{{ old('title', $event->title) }}"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                           required>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Deskripsi
                    </label>
                    <textarea name="description"
                              rows="4"
                              class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">{{ old('description', $event->description) }}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Kategori
                    </label>
                    <select name="category_id"
                            class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                            required>
                        <option value="">Pilih Kategori</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category->id }}"
                                {{ old('category_id', $event->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Tanggal Event
                    </label>
                    <input type="date"
                           name="date"
                           value="{{ old('date', optional($event->date)->format('Y-m-d')) }}"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                           required>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Lokasi
                    </label>
                    <input type="text"
                           name="location"
                           value="{{ old('location', $event->location) }}"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Harga
                    </label>
                    <input type="number"
                           name="price"
                           value="{{ old('price', $event->price) }}"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                           required>
                </div>

                <div>
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Stok
                    </label>
                    <input type="number"
                           name="stock"
                           value="{{ old('stock', $event->stock) }}"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none"
                           required>
                </div>

                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-2">
                        Poster Event
                    </label>

                    @if ($event->poster_path)
                        <div class="mb-4">
                            <img src="{{ $event->poster_url }}"
                                 alt="{{ $event->title }}"
                                 class="w-32 h-40 rounded-xl object-cover shadow-sm">
                        </div>
                    @endif


                    <input type="file"
                           name="poster"
                           accept="image/*"
                           class="w-full px-5 py-3 rounded-xl border border-slate-200 focus:ring-2 focus:ring-indigo-500 outline-none">

                    <p class="text-xs text-slate-400 mt-2">
                        Kosongkan jika tidak ingin mengganti poster.
                    </p>
                </div>

                <!-- Ticket tiers management -->
                <div class="col-span-2">
                    <label class="block text-sm font-bold text-slate-700 mb-4">Ticket Tiers (Early bird / Presale / Regular)</label>
                    <input type="hidden" name="tiers_present" value="1">

                    @php
                        $oldTiers = old('tiers');
                        $tierRows = $oldTiers !== null ? $oldTiers : $event->tiers;
                    @endphp
 
                    <div id="no-tiers-message" class="rounded-xl border border-dashed border-slate-200 bg-slate-50 p-4 text-slate-500 text-sm text-center {{ count($tierRows) ? 'hidden' : '' }}">
                        Belum ada ticket tier. Klik tombol Tambah Tier untuk membuat level penjualan baru.
                    </div>
 
                    <div id="tiers-container" class="space-y-4">
                        @foreach($tierRows as $index => $tier)
                            @php
                                $tierId = data_get($tier, 'id');
                                $tierName = data_get($tier, 'name');
                                $tierPrice = data_get($tier, 'price');
                                $tierStock = data_get($tier, 'stock');
                                $tierStartAt = data_get($tier, 'start_at');
                                $tierEndAt = data_get($tier, 'end_at');
                                $tierPriority = data_get($tier, 'priority', 10);
                                $tierStartAtValue = $tierStartAt ? \Illuminate\Support\Carbon::parse($tierStartAt)->format('Y-m-d') : '';
                                $tierEndAtValue = $tierEndAt ? \Illuminate\Support\Carbon::parse($tierEndAt)->format('Y-m-d') : '';
                            @endphp
                            <div class="tier-row p-4 rounded-xl border border-slate-100 bg-white flex gap-3 items-start">
                                <input type="hidden" data-name="id" name="tiers[{{ $index }}][id]" value="{{ $tierId }}">
                                <input type="hidden" data-name="priority" name="tiers[{{ $index }}][priority]" value="{{ $tierPriority }}">
                                <div class="flex-1 grid grid-cols-6 gap-3">
                                    <div class="col-span-2">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Tier</label>
                                        <input type="text" data-name="name" name="tiers[{{ $index }}][name]" value="{{ $tierName }}" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Harga</label>
                                        <input type="number" data-name="price" name="tiers[{{ $index }}][price]" value="{{ $tierPrice }}" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Stok</label>
                                        <input type="number" data-name="stock" name="tiers[{{ $index }}][stock]" value="{{ $tierStock }}" class="w-full px-3 py-2 rounded border border-slate-200">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Start</label>
                                        <input type="date" data-name="start_at" name="tiers[{{ $index }}][start_at]" value="{{ $tierStartAtValue }}" class="w-full px-3 py-2 rounded border border-slate-200">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">End</label>
                                        <input type="date" data-name="end_at" name="tiers[{{ $index }}][end_at]" value="{{ $tierEndAtValue }}" class="w-full px-3 py-2 rounded border border-slate-200">
                                    </div>
                                </div>
                                <div>
                                    <button type="button" class="btn-remove-tier px-3 py-2 bg-rose-50 text-rose-600 rounded-lg">Hapus</button>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="mt-3">
                        <button type="button" id="btn-add-tier" class="px-4 py-2 bg-indigo-600 text-white rounded-xl">+ Tambah Tier</button>
                        <p class="text-xs text-slate-400 mt-2">Urutkan priority dengan start/end dan priority (nilai kecil = prioritas tinggi). Kosongkan start/end untuk selalu aktif.</p>
                    </div>

                    <template id="tier-template">
                        <div class="tier-row p-4 rounded-xl border border-slate-100 bg-white flex gap-3 items-start">
                            <input type="hidden" data-name="id" name="tiers[__INDEX__][id]" value="">
                            <input type="hidden" data-name="priority" name="tiers[__INDEX__][priority]" value="10">
                            <div class="flex-1 grid grid-cols-6 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Tier</label>
                                    <input type="text" data-name="name" name="tiers[__INDEX__][name]" value="" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Harga</label>
                                    <input type="number" data-name="price" name="tiers[__INDEX__][price]" value="0" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Stok</label>
                                    <input type="number" data-name="stock" name="tiers[__INDEX__][stock]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Start</label>
                                    <input type="date" data-name="start_at" name="tiers[__INDEX__][start_at]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">End</label>
                                    <input type="date" data-name="end_at" name="tiers[__INDEX__][end_at]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn-remove-tier px-3 py-2 bg-rose-50 text-rose-600 rounded-lg">Hapus</button>
                            </div>
                        </div>
                    </template>
 
                    <script>
                        document.addEventListener('DOMContentLoaded', function () {
                            const container = document.getElementById('tiers-container');
                            const btn = document.getElementById('btn-add-tier');
                            const template = document.getElementById('tier-template');
                            const emptyNotice = document.getElementById('no-tiers-message');
 
                            if (!container || !btn || !template) {
                                return;
                            }
 
                            function syncTierIndexes() {
                                const rows = Array.from(container.querySelectorAll('.tier-row'));
                                rows.forEach((row, index) => {
                                    row.querySelectorAll('[data-name]').forEach(input => {
                                        const key = input.dataset.name;
                                        input.name = `tiers[${index}][${key}]`;
                                    });
                                });
                                if (emptyNotice) {
                                    emptyNotice.classList.toggle('hidden', rows.length > 0);
                                }
                            }
 
                            function addTier() {
                                const clone = template.content.cloneNode(true);
                                container.appendChild(clone);
                                syncTierIndexes();
                            }
 
                            document.addEventListener('click', function (e) {
                                const removeButton = e.target.closest('.btn-remove-tier');
                                if (!removeButton) {
                                    return;
                                }
                                e.preventDefault();
                                const row = removeButton.closest('.tier-row');
                                if (row) {
                                    row.remove();
                                    syncTierIndexes();
                                }
                            });
 
                            btn.addEventListener('click', function (e) {
                                e.preventDefault();
                                addTier();
                            });
 
                            syncTierIndexes();
                        });
                    </script>
                </div>
            </div>

            <div class="flex justify-end gap-3 pt-6">
                <a href="{{ route('admin.events.index') }}"
                   class="px-6 py-3 rounded-2xl border border-slate-200 font-bold text-slate-600 hover:bg-slate-50 transition">
                    Batal
                </a>

                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold hover:bg-indigo-700 transition">
                    Update Event
                </button>
            </div>
        </form>
    </div>
</main>
@endsection