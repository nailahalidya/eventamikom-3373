@extends('layouts.app')

@section('content')

<div class="max-w-xl mx-auto py-20">

    <h2 class="text-3xl font-bold mb-8">

        Edit Review

    </h2>

    <form
        method="POST"
        action="{{ route('reviews.update',$review->id) }}">

        @csrf
        @method('PUT')

        <label>Rating</label>

        <select
            name="rating"
            class="w-full border rounded-xl p-3 mb-5">

            @for($i=5;$i>=1;$i--)

            <option
                value="{{ $i }}"
                {{ $review->rating==$i?'selected':'' }}>

                {{ str_repeat('⭐',$i) }}

            </option>

            @endfor

        </select>

        <label>Review</label>

        <textarea
            name="review"
            rows="5"
            class="w-full border rounded-xl p-4 mb-5">{{ $review->review }}</textarea>

        <div class="flex items-center gap-2 mb-6">

            <input
                type="checkbox"
                name="is_anonymous"
                value="1"
                {{ $review->is_anonymous ? 'checked':'' }}>

            <span>Kirim sebagai anonim</span>

        </div>

        <button
            class="bg-indigo-600 text-white px-6 py-3 rounded-xl">

            Simpan Perubahan

        </button>

    </form>

</div>

@endsection