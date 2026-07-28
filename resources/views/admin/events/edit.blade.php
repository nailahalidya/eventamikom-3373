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
                           value="{{ old('date', $event->date) }}"
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

                    <div id="tiers-container" class="space-y-4">
                        @foreach($event->tiers as $tier)
                            <div class="tier-row p-4 rounded-xl border border-slate-100 bg-white flex gap-3 items-start">
                                <input type="hidden" name="tiers[][id]" value="{{ $tier->id }}">
                                <div class="flex-1 grid grid-cols-6 gap-3">
                                    <div class="col-span-2">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Tier</label>
                                        <input type="text" name="tiers[][name]" value="{{ $tier->name }}" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Harga</label>
                                        <input type="number" name="tiers[][price]" value="{{ $tier->price }}" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Stok</label>
                                        <input type="number" name="tiers[][stock]" value="{{ $tier->stock ?? '' }}" class="w-full px-3 py-2 rounded border border-slate-200">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">Start</label>
                                        <input type="date" name="tiers[][start_at]" value="{{ optional($tier->start_at)->format('Y-m-d') }}" class="w-full px-3 py-2 rounded border border-slate-200">
                                    </div>
                                    <div class="col-span-1">
                                        <label class="block text-[11px] font-bold text-slate-600 mb-1">End</label>
                                        <input type="date" name="tiers[][end_at]" value="{{ optional($tier->end_at)->format('Y-m-d') }}" class="w-full px-3 py-2 rounded border border-slate-200">
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
                            <input type="hidden" name="tiers[][id]" value="">
                            <div class="flex-1 grid grid-cols-6 gap-3">
                                <div class="col-span-2">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Nama Tier</label>
                                    <input type="text" name="tiers[][name]" value="" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Harga</label>
                                    <input type="number" name="tiers[][price]" value="0" class="w-full px-3 py-2 rounded border border-slate-200" required>
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Stok</label>
                                    <input type="number" name="tiers[][stock]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">Start</label>
                                    <input type="date" name="tiers[][start_at]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                                <div class="col-span-1">
                                    <label class="block text-[11px] font-bold text-slate-600 mb-1">End</label>
                                    <input type="date" name="tiers[][end_at]" value="" class="w-full px-3 py-2 rounded border border-slate-200">
                                </div>
                            </div>
                            <div>
                                <button type="button" class="btn-remove-tier px-3 py-2 bg-rose-50 text-rose-600 rounded-lg">Hapus</button>
                            </div>
                        </div>
                    </template>

                    <script>
                        (function(){
                            const container = document.getElementById('tiers-container');
                            const btn = document.getElementById('btn-add-tier');
                            const template = document.getElementById('tier-template').content;

                            function removeHandler(e){
                                const row = e.target.closest('.tier-row');
                                if(row) row.remove();
                            }

                            document.addEventListener('click', function(e){
                                if(e.target && e.target.classList.contains('btn-remove-tier')){
                                    removeHandler(e);
                                }
                            });

                            btn.addEventListener('click', function(){
                                const clone = document.importNode(template, true);
                                container.appendChild(clone);
                            });
                        })();
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