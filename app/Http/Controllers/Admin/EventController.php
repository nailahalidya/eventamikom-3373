<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Services\CloudinaryService;

class EventController extends Controller
{
    public function index()
    {
        $events = Event::with('category')->latest()->get();
        $categories = Category::all();

        return view('admin.events.index', compact('events', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('admin.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'early_bird_price' => 'nullable|numeric|min:0',
            'early_bird_until' => 'nullable|date',
            'presale_price' => 'nullable|numeric|min:0',
            'presale_until' => 'nullable|date',
            'stock' => 'required|numeric|min:0',
            'poster' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:40960',
        ]);

        // Admin selalu menjadi owner
        $data['owner_type'] = 'admin';

        if ($request->hasFile('poster')) {
            $data['poster_path'] = CloudinaryService::upload($request->file('poster'), 'posters');
        }

        Event::create($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $categories = Category::all();

        return view('admin.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'early_bird_price' => 'nullable|numeric|min:0',
            'early_bird_until' => 'nullable|date',
            'presale_price' => 'nullable|numeric|min:0',
            'presale_until' => 'nullable|date',
            'stock' => 'required|numeric|min:0',
            'poster' => 'nullable|file|mimes:jpg,jpeg,png,webp|max:10240',
        ]);

        $data['owner_type'] = $event->owner_type ?? 'admin';

        if ($request->hasFile('poster')) {
            // Hapus gambar lama dari Cloudinary jika ada
            if ($event->poster_path) {
                CloudinaryService::delete($event->poster_path);
            }

            // Upload gambar baru
            $data['poster_path'] = CloudinaryService::upload($request->file('poster'), 'posters');
        }

        $event->update($data);

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        // Hapus poster dari Cloudinary jika ada
        if ($event->poster_path) {
            CloudinaryService::delete($event->poster_path);
        }

        $event->delete();

        return redirect()
            ->route('admin.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}