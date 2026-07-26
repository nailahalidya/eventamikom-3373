<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Review;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Simpan review
     */
    public function store(Request $request, Event $event)
    {
        // Validasi
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|string|max:1000',
            'guest_name' => 'nullable|string|max:100',
            'is_anonymous' => 'nullable|boolean',
        ]);

        Review::create([

            // Jika login maka isi user_id, jika tidak login maka null
            'user_id' => Auth::check() ? Auth::id() : null,

            // Jika login nama guest tidak perlu
            'guest_name' => Auth::check()
                ? null
                : ($request->guest_name ?: 'Guest'),

            'is_anonymous' => $request->boolean('is_anonymous'),

            'event_id' => $event->id,

            'rating' => $request->rating,

            'review' => $request->review,

        ]);

        return redirect()
            ->route('events.show', $event->id)
            ->with('success', 'Terima kasih! Review berhasil dikirim.')
            ->withFragment('reviews');
    }

    public function edit(Review $review)
    {
        if (!auth()->check() || auth()->id() != $review->user_id) {
            abort(403);
        }

        return view('reviews.edit', compact('review'));
    }

    public function update(Request $request, Review $review)
    {
        if (!auth()->check() || auth()->id() != $review->user_id) {
            abort(403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'review' => 'required|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'review' => $request->review,
            'is_anonymous' => $request->boolean('is_anonymous')
        ]);

        return back()->with('success', 'Review berhasil diperbarui.');
    }

    public function destroy(Review $review)
    {
        if (!auth()->check() || auth()->id() != $review->user_id) {
            abort(403);
        }

        $review->delete();

        return back()->with('success', 'Review berhasil dihapus.');
    }

    public function report(Request $request, Review $review)
    {
        // Validasi input
        $request->validate([
            'reason' => 'required|string|max:255',
        ]);

        // Simpan laporan ke database atau kirim email ke admin
        // Contoh: Report::create([...]);

        return back()->with('success', 'Terima kasih! Laporan Anda telah diterima.');
    }
}
