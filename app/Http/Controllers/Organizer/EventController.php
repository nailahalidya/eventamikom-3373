<?php

namespace App\Http\Controllers\Organizer;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Event;
use App\Services\CloudinaryService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class EventController extends Controller
{
    public function index()
    {
        $organizer = Auth::user()->organizer;

        $events = Event::with('category')
            ->where('organizer_id', $organizer->id)
            ->latest()
            ->get();

        $categories = Category::all();

        return view('organizer.events.index', compact(
            'events',
            'categories'
        ));
    }

    public function create()
    {
        $categories = Category::all();

        return view('organizer.events.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $organizer = Auth::user()->organizer;

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:40960',
        ]);

        $data['owner_type'] = 'organizer';
        $data['organizer_id'] = $organizer->id;

        if ($request->hasFile('poster')) {
            $uploadedUrl = CloudinaryService::upload($request->file('poster'), 'posters');
            if ($uploadedUrl) {
                $data['poster_path'] = $uploadedUrl;
            }
        }

        Event::create($data);

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil ditambahkan.');
    }

    public function edit(Event $event)
    {
        $organizer = Auth::user()->organizer;

        abort_if($event->organizer_id !== $organizer->id, 403);

        $categories = Category::all();

        return view('organizer.events.edit', compact('event', 'categories'));
    }

    public function update(Request $request, Event $event)
    {
        $organizer = Auth::user()->organizer;

        abort_if($event->organizer_id !== $organizer->id, 403);

        $data = $request->validate([
            'category_id' => 'required|exists:categories,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'date' => 'required|date',
            'location' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'stock' => 'required|numeric|min:0',
            'poster' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:40960',
        ]);

        if ($request->hasFile('poster')) {
            // Hapus file lokal lama jika bukan URL Cloudinary
            if (
                $event->poster_path &&
                !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])
            ) {
                try {
                    Storage::disk('public')->delete($event->poster_path);
                } catch (\Exception $e) {
                    // Abaikan jika storage read-only (Vercel)
                }
            }

            $uploadedUrl = CloudinaryService::upload($request->file('poster'), 'posters');
            if ($uploadedUrl) {
                $data['poster_path'] = $uploadedUrl;
            }
        }

        $event->update($data);

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil diperbarui.');
    }

    public function destroy(Event $event)
    {
        $organizer = Auth::user()->organizer;

        abort_if($event->organizer_id !== $organizer->id, 403);

        if (
            $event->poster_path &&
            !\Illuminate\Support\Str::startsWith($event->poster_path, ['http://', 'https://'])
        ) {
            try {
                Storage::disk('public')->delete($event->poster_path);
            } catch (\Exception $e) {
                // Abaikan jika storage read-only (Vercel)
            }
        }

        $event->delete();

        return redirect()
            ->route('organizer.events.index')
            ->with('success', 'Event berhasil dihapus.');
    }
}