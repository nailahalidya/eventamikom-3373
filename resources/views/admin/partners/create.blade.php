@extends('layouts.admin')

@section('title', 'Tambah Partner')
@section('page_title', 'Tambah Partner')
@section('page_subtitle', 'Tambahkan data partner organizer baru')

@section('content')

<div class="max-w-2xl bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">
            Form Tambah Partner
        </h2>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.partners.store') }}"
              method="POST"
              class="space-y-6">

            @csrf

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600">
                    Nama Partner
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name') }}"
                       required
                       placeholder="Masukkan nama partner"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600">

                @error('name')
                    <p class="text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600">
                    Logo URL
                </label>

                <input type="text"
                       name="logo_url"
                       value="{{ old('logo_url') }}"
                       placeholder="https://..."
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600">

                @error('logo_url')
                    <p class="text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700">
                    Simpan
                </button>

                <a href="{{ route('admin.partners.index') }}"
                   class="px-6 py-3 bg-slate-200 text-slate-700 rounded-xl font-bold hover:bg-slate-300">
                    Kembali
                </a>
            </div>

        </form>
    </div>

</div>

@endsection
