@extends('layouts.admin')

@section('title', 'Kelola Kategori')
@section('page_title', 'Kelola Kategori')
@section('page_subtitle', 'Manajemen data kategori event')

@section('content')

<div class="space-y-8">

    @if(session('success'))
        <div class="bg-green-100 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    {{-- FORM TAMBAH KATEGORI --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <h2 class="text-xl font-bold mb-4 text-slate-800">
            Tambah Kategori Baru
        </h2>

        <form action="{{ route('admin.categories.store') }}" method="POST"
              class="flex flex-col md:flex-row gap-4 items-end">
            @csrf

            <div class="flex-1 space-y-2 w-full">
                <label class="text-sm font-semibold text-slate-600">
                    Nama Kategori
                </label>

                <input type="text" name="name"
                       value="{{ old('name') }}"
                       placeholder="Masukkan nama kategori"
                       required
                       class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 transition">

                @error('name')
                    <p class="text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                Simpan
            </button>
        </form>
    </div>


    {{-- SEARCH --}}
    <div class="bg-white p-6 rounded-3xl border border-slate-100 shadow-sm">
        <form action="{{ route('admin.categories.index') }}" method="GET"
              class="flex flex-col md:flex-row gap-3">

            <input type="text" name="search"
                   value="{{ request('search') }}"
                   placeholder="Cari nama kategori..."
                   class="w-full px-4 py-2.5 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600 bg-white transition">

            <button type="submit"
                    class="px-6 py-2.5 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700 transition">
                Cari
            </button>

            @if(request('search'))
                <a href="{{ route('admin.categories.index') }}"
                   class="px-6 py-2.5 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300 transition text-center">
                    Reset
                </a>
            @endif
        </form>
    </div>


    {{-- TABEL KATEGORI --}}
    <div class="bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left border-collapse">
                <thead>
                    <tr class="border-b border-slate-100 text-slate-400 text-xs font-bold uppercase tracking-wider bg-slate-50">
                        <th class="py-4 px-6 w-20">No</th>
                        <th class="py-4 px-6">Nama Kategori</th>
                        <th class="py-4 px-6">Dibuat</th>
                        <th class="py-4 px-6">Diupdate</th>
                        <th class="py-4 px-6 text-center w-40">Aksi</th>
                    </tr>
                </thead>

                <tbody class="divide-y divide-slate-50 text-sm font-medium text-slate-700">
                    @forelse($categories as $index => $category)
                        <tr class="hover:bg-slate-50 transition">
                            <td class="py-4 px-6 text-slate-400">
                                {{ $index + 1 }}
                            </td>

                            <td class="py-4 px-6 font-semibold text-slate-800">
                                {{ $category->name }}
                            </td>

                            <td class="py-4 px-6">
                                {{ $category->created_at }}
                            </td>

                            <td class="py-4 px-6">
                                {{ $category->updated_at }}
                            </td>

                            <td class="py-4 px-6">
                                <div class="flex justify-center gap-2">

                                    <a href="{{ route('admin.categories.edit', $category->id) }}"
                                       class="px-4 py-2 bg-indigo-50 text-indigo-600 rounded-xl hover:bg-indigo-600 hover:text-white transition">
                                        Edit
                                    </a>

                                    <form action="{{ route('admin.categories.destroy', $category->id) }}"
                                          method="POST"
                                          onsubmit="return confirm('Yakin ingin menghapus kategori ini?')">
                                        @csrf
                                        @method('DELETE')

                                        <button type="submit"
                                                class="px-4 py-2 bg-red-50 text-red-600 rounded-xl hover:bg-red-600 hover:text-white transition">
                                            Hapus
                                        </button>
                                    </form>

                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="5"
                                class="py-12 text-center text-slate-400 italic">
                                Data kategori belum tersedia.
                            </td>
                        </tr>
                    @endforelse
                </tbody>

            </table>
        </div>
    </div>

</div>

@endsection
