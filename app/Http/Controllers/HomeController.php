<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\Event;
use App\Models\Partner;

class HomeController extends Controller
{
    public function index()
    {
        $partners = Partner::latest()->get();
        $categories = Category::latest()->get();
        $events = Event::with(['category', 'reviews'])->latest()->get();

        $topRatedEvents = Event::with(['category', 'organizer', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(3)
            ->get();

        return view('welcome', compact(
            'partners',
            'categories',
            'events',
            'topRatedEvents'
        ));
    }

    public function category(int $id)
    {
        $categories = Category::all();
        $partners = Partner::all();

        $events = Event::with(['category', 'reviews'])
            ->where('category_id', $id)
            ->latest()
            ->get();

        $topRatedEvents = Event::with(['category', 'organizer', 'reviews'])
            ->withAvg('reviews', 'rating')
            ->withCount('reviews')
            ->orderByDesc('reviews_avg_rating')
            ->orderByDesc('reviews_count')
            ->take(3)
            ->get();

        return view('welcome', compact(
            'categories',
            'partners',
            'events',
            'topRatedEvents'
        ));
    }

}
