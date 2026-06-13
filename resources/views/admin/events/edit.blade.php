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
                            <img src="{{ asset('storage/' . $event->poster_path) }}"
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