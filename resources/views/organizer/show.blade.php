@extends('layouts.admin')

@section('content')

<div class="container">

    <h2>Detail Organizer</h2>

    <div class="card">
        <div class="card-body">

            <p><strong>Nama :</strong> {{ $organizer->name }}</p>
            <p><strong>Email :</strong> {{ $organizer->email }}</p>
            <p><strong>Telepon :</strong> {{ $organizer->phone }}</p>
            <p><strong>Status :</strong> {{ ucfirst($organizer->status) }}</p>
            <p><strong>Deskripsi :</strong> {{ $organizer->description }}</p>

            <p>
                <strong>Total Event :</strong>
                {{ $organizer->events->count() }}
            </p>

            <a href="{{ route('admin.organizers.index') }}"
                class="btn btn-secondary">
                Kembali
            </a>

        </div>
    </div>

</div>

@endsection