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
        $events = Event::with('category')->latest()->get();

        return view('welcome', compact(
            'partners',
            'categories',
            'events'
        ));
    }
public function category(int $id){
    $categories=Category::all();

    $partners=Partner::all();

    $events=Event::with('category')
            ->where(
                'category_id',
                $id
            )
            ->latest()
            ->get();

    return view(
        'welcome',
        compact(
            'categories',
            'partners',
            'events'
        )
    );
}
}
