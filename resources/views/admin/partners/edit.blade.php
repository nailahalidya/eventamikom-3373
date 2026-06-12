@extends('layouts.admin')

@section('title', 'Edit Partner')
@section('page_title', 'Edit Partner')
@section('page_subtitle', 'Ubah informasi partner')

@section('content')

<div class="max-w-2xl bg-white rounded-3xl border border-slate-100 shadow-sm overflow-hidden">

    <div class="p-6 border-b border-slate-100 bg-slate-50">
        <h2 class="text-xl font-bold text-slate-800">
            Form Edit Partner
        </h2>
    </div>

    <div class="p-6">
        <form action="{{ route('admin.partners.update', $partner->id) }}"
              method="POST"
              class="space-y-6">

            @csrf
            @method('PUT')

            <div class="space-y-2">
                <label class="text-sm font-semibold text-slate-600">
                    Nama Partner
                </label>

                <input type="text"
                       name="name"
                       value="{{ old('name', $partner->name) }}"
                       required
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
                       value="{{ old('logo_url', $partner->logo_url) }}"
                       class="w-full px-4 py-3 border border-slate-200 rounded-xl focus:outline-none focus:border-indigo-600">

                @error('logo_url')
                    <p class="text-red-500 text-sm">
                        {{ $message }}
                    </p>
                @enderror
            </div>

            @if($partner->logo_url)
                <div>
                    <p class="text-sm font-semibold text-slate-600 mb-2">
                        Preview Logo
                    </p>

                    <img src="{{ $partner->logo_url }}"
                         class="w-24 h-24 rounded-xl object-cover border">
                </div>
            @endif

            <div class="flex gap-3">
                <button type="submit"
                        class="px-6 py-3 bg-indigo-600 text-white rounded-xl font-bold hover:bg-indigo-700">
                    Simpan
                </button>

                <a href="{{ route('admin.partners.index') }}"
                   class="px-6 py-3 bg-slate-200 rounded-xl font-bold">
                    Kembali
                </a>
            </div>

        </form>
    </div>

</div>

@endsection
