<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;


class EventController extends Controller
{
    public function show($id)
    {
        $event = Event::with([
            'category',
            'organizer',
            'reviews.user'
        ])->findOrFail($id);

        $reviews = $event->reviews()
            ->with('user')
            ->latest()
            ->get();

        $averageRating = $event->reviews()->avg('rating') ?? 0;

        return view('event-detail', compact(
            'event',
            'reviews',
            'averageRating'
        ));
    }
}
