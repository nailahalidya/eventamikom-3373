@extends('layouts.admin')

@section('content')
<main class="flex-1 p-10 overflow-y-auto">
    <header class="flex justify-between items-center mb-10">
        <div>
            <h1 class="text-3xl font-black">Kelola Event</h1>
            <p class="text-slate-500 font-medium">Buat dan atur acara seru Anda di sini.</p>
        </div>

        <a href="{{ route('admin.events.create') }}"
           class="px-6 py-3 bg-indigo-600 text-white rounded-2xl font-bold shadow-lg shadow-indigo-100 hover:bg-indigo-700 active:scale-95 transition">
            + Tambah Event Baru
        </a>
    </header>

    @if (session('success'))
        <div class="mb-6 px-6 py-4 bg-green-100 text-green-700 rounded-2xl font-semibold">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-[2.5rem] border border-slate-100 shadow-sm overflow-hidden">
        <div class="px-8 py-6 bg-slate-50/50 border-b flex gap-4">
            <input type="text"
                   placeholder="Cari nama event..."
                   class="flex-1 px-5 py-3 rounded-xl border-slate-200 border bg-white focus:ring-2 focus:ring-indigo-500 outline-none transition">

            <select class="px-5 py-3 rounded-xl border-slate-200 border bg-white outline-none">
                <option>Semua Kategori</option>

                @foreach ($categories as $category)
                    <option>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead class="bg-slate-50 text-slate-400 uppercase text-[10px] font-black tracking-widest">
                    <tr>
                        <th class="px-8 py-4 w-16">No</th>
                        <th class="px-8 py-4">Poster</th>
                        <th class="px-8 py-4">Event</th>
                        <th class="px-8 py-4">Harga / Stok</th>
                        <th class="px-8 py-4">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y border-t">
                    @forelse ($events as $event)
                        <tr class="hover:bg-slate-50/50 transition">
                            <td class="px-8 py-6 font-bold text-slate-400">
                                {{ $loop->iteration }}
                            </td>

                            <td class="px-8 py-6">
                                @if ($event->poster)
                                    <img src="{{ asset('storage/' . $event->poster) }}"
                                         alt="{{ $event->title }}"
                                         class="w-16 h-20 rounded-xl object-cover shadow-sm">
                                @else
                                    <div class="w-16 h-20 rounded-xl bg-slate-100 flex items-center justify-center text-xs text-slate-400">
                                        No Image
                                    </div>
                                @endif
                            </td>

                            <td class="px-8 py-6">
                                <p class="font-black text-slate-800">
                                    {{ $event->title }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    {{ $event->category->name ?? 'Tanpa Kategori' }}
                                    •
                                    {{ \Carbon\Carbon::parse($event->date)->format('d M Y') }}
                                </p>

                                <p class="text-xs text-slate-400 mt-1">
                                    {{ $event->location }}
                                </p>
                            </td>

                            <td class="px-8 py-6">
                                <p class="font-bold text-indigo-600">
                                    Rp {{ number_format($event->price, 0, ',', '.') }}
                                </p>

                                <p class="text-xs text-slate-400">
                                    Stok: {{ $event->stock }}
                                </p>
                            </td>

                            <td class="px-8 py-6">
                                <div class="flex gap-2">
                                    <a href="{{ route('admin.events.edit', $event->id) }}"
                                       class="p-2.5 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round"
                                                  stroke-linejoin="round"
                                                  stroke-width="2"
                                                  d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                                        </svg>
                                    </a>

                                    <form action="{{ route('admin.events.destroy', $event->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus event ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="p-2.5 bg-rose-50 text-rose-600 rounded-xl hover:bg-rose-600 hover:text-white transition">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round"
                                                      stroke-linejoin="round"
                                                      stroke-width="2"
                                                      d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5" class="px-8 py-10 text-center text-slate-400 font-semibold">
                                Belum ada event yang ditambahkan.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</main>
@endsection