@extends('layouts.master')

@section('content')
    <div class="container">

        <h2>Tambah Partner</h2>

        <form action="{{ route('partners.store') }}" method="POST">

            @csrf

            <label>Nama Partner</label>

            <input type="text" name="name" value="{{ old('name') }}">

            <br><br>

            <label>Logo URL</label>

            <input type="text" name="logo_url" value="{{ old('logo_url') }}">

            <br><br>

            <button type="submit">
                Simpan
            </button>

        </form>

    </div>
@endsection
