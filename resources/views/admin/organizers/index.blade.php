@extends('layouts.admin')

@section('content')

<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-slate-800">
                Kelola Organizer
            </h1>
            <p class="text-slate-500 mt-1">
                Daftar seluruh organizer yang mendaftar di AmikomEventHub.
            </p>
        </div>
    </div>

    @if(session('success'))
        <div class="bg-green-100 border border-green-300 text-green-700 px-4 py-3 rounded-xl">
            {{ session('success') }}
        </div>
    @endif

    <div class="bg-white rounded-2xl shadow-sm border border-slate-200 overflow-hidden">

        <div class="overflow-x-auto">

            <table class="min-w-full">

                <thead class="bg-slate-100">

                    <tr class="text-sm text-slate-700">

                        <th class="px-6 py-4 text-left">No</th>
                        <th class="px-6 py-4 text-left">Logo</th>
                        <th class="px-6 py-4 text-left">Nama Organizer</th>
                        <th class="px-6 py-4 text-left">Email</th>
                        <th class="px-6 py-4 text-left">Telepon</th>
                        <th class="px-6 py-4 text-left">Status</th>
                        <th class="px-6 py-4 text-center">Aksi</th>

                    </tr>

                </thead>

                <tbody class="divide-y divide-slate-100">

                @forelse($organizers as $organizer)

                    <tr class="hover:bg-slate-50 transition">

                        <td class="px-6 py-4">
                            {{ $loop->iteration + ($organizers->currentPage()-1) * $organizers->perPage() }}
                        </td>

                        <td class="px-6 py-4">
                            <img
                                src="{{ $organizer->logo_url }}"
                                alt="{{ $organizer->name }}"
                                class="w-14 h-14 rounded-xl object-cover border">
                        </td>

                        <td class="px-6 py-4 font-semibold text-slate-800">
                            {{ $organizer->name }}
                        </td>

                        <td class="px-6 py-4 text-slate-600">
                            {{ $organizer->user->email ?? '-' }}
                        </td>

                        <td class="px-6 py-4">
                            {{ $organizer->phone }}
                        </td>

                        <td class="px-6 py-4">

                            @if($organizer->status == 'approved')

                                <span class="px-3 py-1 rounded-full bg-green-100 text-green-700 text-xs font-semibold">
                                    Approved
                                </span>

                            @elseif($organizer->status == 'pending')

                                <span class="px-3 py-1 rounded-full bg-yellow-100 text-yellow-700 text-xs font-semibold">
                                    Pending
                                </span>

                            @else

                                <span class="px-3 py-1 rounded-full bg-red-100 text-red-700 text-xs font-semibold">
                                    Rejected
                                </span>

                            @endif

                        </td>

                        <td class="px-6 py-4">

                            <div class="flex justify-center gap-2">

                                <a href="{{ route('admin.organizers.show',$organizer) }}"
                                    class="px-3 py-2 rounded-lg bg-blue-600 hover:bg-blue-700 text-white text-sm">

                                    Detail

                                </a>

                                @if($organizer->status == 'pending')

                                <form action="{{ route('admin.organizers.approve',$organizer) }}"
                                    method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        onclick="return confirm('Approve organizer ini?')"
                                        class="px-3 py-2 rounded-lg bg-green-600 hover:bg-green-700 text-white text-sm">

                                        Approve

                                    </button>

                                </form>

                                <form action="{{ route('admin.organizers.reject',$organizer) }}"
                                    method="POST">

                                    @csrf
                                    @method('PATCH')

                                    <button
                                        onclick="return confirm('Reject organizer ini?')"
                                        class="px-3 py-2 rounded-lg bg-red-600 hover:bg-red-700 text-white text-sm">

                                        Reject

                                    </button>

                                </form>

                                @endif

                                <form action="{{ route('admin.organizers.destroy', $organizer) }}"
                                    method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button
                                        onclick="return confirm('Apakah Anda yakin ingin menghapus organizer ini?')"
                                        class="px-3 py-2 rounded-lg bg-red-500 hover:bg-red-600 text-white text-sm font-medium transition">
                                        Hapus
                                    </button>
                                </form>

                            </div>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="7"
                            class="text-center py-10 text-slate-500">

                            Belum ada organizer yang mendaftar.

                        </td>

                    </tr>

                @endforelse

                </tbody>

            </table>

        </div>

    </div>

    <div>
        {{ $organizers->links() }}
    </div>

</div>

@endsection