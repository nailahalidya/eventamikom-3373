<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->latest()->get();

        return view('admin.events.index', compact('events'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
{
    $request->validate([
        'title' => 'required|string|max:255',
        'description' => 'required',
        'location' => 'required|string|max:255',
        'date' => 'required',
        'price' => 'required|numeric',
        'stock' => 'required|numeric',
        'category_id' => 'required|exists:categories,id',
        'poster' => 'required|image|mimes:jpg,jpeg,png,webp|max:2048',
    ]);

    $posterPath = null;

    if ($request->hasFile('poster')) {
        $posterPath = $request->file('poster')->store('posters', 'public');
    }

    Event::create([
        'title' => $request->title,
        'description' => $request->description,
        'poster_path' => $posterPath,
        'location' => $request->location,
        'date' => $request->date,
        'price' => $request->price,
        'stock' => $request->stock,
        'category_id' => $request->category_id,
    ]);

    return redirect()
        ->route('admin.events.index')
        ->with('success', 'Event berhasil ditambahkan');
}

    public function edit(Event $event)
    {
        $categories = Category::all();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required',
            'location' => 'required|string|max:255',
            'date' => 'required',
            'price' => 'required|numeric',
            'stock' => 'required|numeric',
            'category_id' => 'required|exists:categories,id',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
        ]);

        $posterPath = $event->poster;

        if ($request->hasFile('poster')) {
            if ($event->poster) {
                Storage::disk('public')->delete($event->poster);
            }

            $posterPath = $request->file('poster')->store('posters', 'public');
        }

        $event->update([
            'title' => $request->title,
            'description' => $request->description,
            'poster' => $posterPath,
            'location' => $request->location,
            'date' => $request->date,
            'price' => $request->price,
            'stock' => $request->stock,
            'category_id' => $request->category_id,
        ]);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui');
    }

    public function destroy(Event $event)
    {
        if ($event->poster) {
            Storage::disk('public')->delete($event->poster);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus');
    }
}
