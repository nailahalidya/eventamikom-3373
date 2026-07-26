@extends('layouts.admin')

@section('content')

<div class="space-y-8">

    {{-- Header --}}
    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 p-8">

        <div class="flex items-start gap-6">

            {{-- Logo --}}
            @if($organizer->logo)

                <img
                    src="{{ asset('storage/'.$organizer->logo) }}"
                    class="w-28 h-28 rounded-xl object-cover border">

            @else

                <div class="w-28 h-28 rounded-xl bg-slate-200 flex items-center justify-center text-slate-500">
                    Logo
                </div>

            @endif

            {{-- Informasi --}}
            <div class="flex-1">

                <h1 class="text-3xl font-bold text-slate-800">
                    {{ $organizer->name }}
                </h1>

                <p class="text-slate-500 mt-2">
                    {{ $organizer->email }}
                </p>

                <p class="text-slate-500">
                    {{ $organizer->phone }}
                </p>

                <p class="mt-4 text-slate-600">
                    {{ $organizer->description ?? '-' }}
                </p>

                <div class="mt-5">

                    @if($organizer->status == 'approved')

                        <span class="px-4 py-2 rounded-full bg-green-100 text-green-700 font-semibold">
                            Approved
                        </span>

                    @elseif($organizer->status == 'pending')

                        <span class="px-4 py-2 rounded-full bg-yellow-100 text-yellow-700 font-semibold">
                            Pending
                        </span>

                    @else

                        <span class="px-4 py-2 rounded-full bg-red-100 text-red-700 font-semibold">
                            Rejected
                        </span>

                    @endif

                </div>

            </div>

        </div>

    </div>

    {{-- Statistik --}}
    <div class="grid grid-cols-1 md:grid-cols-4 gap-5">

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <p class="text-slate-500">Total Event</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $organizer->events->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <p class="text-slate-500">Event Aktif</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $organizer->events->where('status','published')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <p class="text-slate-500">Event Pending</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $organizer->events->where('status','pending')->count() }}
            </h2>
        </div>

        <div class="bg-white rounded-xl shadow-sm border p-6">
            <p class="text-slate-500">Event Selesai</p>
            <h2 class="text-3xl font-bold mt-2">
                {{ $organizer->events->where('status','finished')->count() }}
            </h2>
        </div>

    </div>

    {{-- Daftar Event --}}
    <div class="bg-white rounded-2xl shadow-sm border">

        <div class="px-6 py-4 border-b">
            <h2 class="text-xl font-bold">
                Daftar Event Organizer
            </h2>
        </div>

        <div class="overflow-x-auto">

            <table class="w-full">

                <thead class="bg-slate-100">

                    <tr>

                        <th class="text-left px-6 py-3">Event</th>
                        <th class="text-left px-6 py-3">Status</th>
                        <th class="text-left px-6 py-3">Tanggal</th>

                    </tr>

                </thead>

                <tbody>

                @forelse($organizer->events as $event)

                    <tr class="border-t hover:bg-slate-50">

                        <td class="px-6 py-4 font-medium">
                            {{ $event->title }}
                        </td>

                        <td class="px-6 py-4">

                            <span class="px-3 py-1 rounded-full bg-slate-100 text-slate-700 text-sm">

                                {{ ucfirst($event->status) }}

                            </span>

                        </td>

                        <td class="px-6 py-4">

                            {{ $event->created_at->format('d M Y') }}

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="3" class="text-center py-8 text-slate-500">

                            Organizer belum memiliki event.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <a href="{{ route('admin.organizers.index') }}"
        class="inline-flex items-center px-5 py-3 bg-indigo-700 text-white rounded-xl hover:bg-indigo-800">

        ← Kembali

    </a>

</div>

@endsection