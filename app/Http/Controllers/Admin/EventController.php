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
            'tiers' => 'nullable|array',
            'tiers.*.name' => 'required_with:tiers|string',
            'tiers.*.price' => 'required_with:tiers|numeric|min:0',
            'tiers.*.start_at' => 'nullable|date',
            'tiers.*.end_at' => 'nullable|date',
            'tiers.*.priority' => 'nullable|integer',
            'tiers.*.stock' => 'nullable|integer',
        ]);

        // Admin selalu menjadi owner
        $data['owner_type'] = 'admin';

        if ($request->hasFile('poster')) {
            $data['poster_path'] = CloudinaryService::upload($request->file('poster'), 'posters');
        }

        $event = Event::create($data);

        // Handle tiers if provided
        if ($request->filled('tiers')) {
            foreach ($request->input('tiers') as $t) {
                \App\Models\TicketTier::create([
                    'event_id' => $event->id,
                    'name' => $t['name'] ?? 'Tier',
                    'price' => $t['price'] ?? 0,
                    'start_at' => !empty($t['start_at']) ? \Carbon\Carbon::parse($t['start_at']) : null,
                    'end_at' => !empty($t['end_at']) ? \Carbon\Carbon::parse($t['end_at']) : null,
                    'priority' => isset($t['priority']) ? (int) $t['priority'] : 10,
                    'stock' => isset($t['stock']) ? (int) $t['stock'] : null,
                ]);
            }
        }

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
            'tiers' => 'nullable|array',
            'tiers.*.id' => 'nullable|integer|exists:ticket_tiers,id',
            'tiers.*.name' => 'required_with:tiers|string',
            'tiers.*.price' => 'required_with:tiers|numeric|min:0',
            'tiers.*.start_at' => 'nullable|date',
            'tiers.*.end_at' => 'nullable|date',
            'tiers.*.priority' => 'nullable|integer',
            'tiers.*.stock' => 'nullable|integer',
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

        // Handle tiers: create/update/delete based on input
        if ($request->has('tiers')) {
            $incoming = $request->input('tiers', []);
            $existingIds = $event->tiers()->pluck('id')->toArray();
            $keep = [];

            foreach ($incoming as $t) {
                if (!empty($t['id'])) {
                    // Update existing
                    $tier = \App\Models\TicketTier::find($t['id']);
                    if ($tier && $tier->event_id == $event->id) {
                        $tier->update([
                            'name' => $t['name'] ?? $tier->name,
                            'price' => $t['price'] ?? $tier->price,
                            'start_at' => !empty($t['start_at']) ? \Carbon\Carbon::parse($t['start_at']) : null,
                            'end_at' => !empty($t['end_at']) ? \Carbon\Carbon::parse($t['end_at']) : null,
                            'priority' => isset($t['priority']) ? (int) $t['priority'] : $tier->priority,
                            'stock' => isset($t['stock']) ? (int) $t['stock'] : $tier->stock,
                        ]);
                        $keep[] = $tier->id;
                    }
                } else {
                    // Create new
                    $new = \App\Models\TicketTier::create([
                        'event_id' => $event->id,
                        'name' => $t['name'] ?? 'Tier',
                        'price' => $t['price'] ?? 0,
                        'start_at' => !empty($t['start_at']) ? \Carbon\Carbon::parse($t['start_at']) : null,
                        'end_at' => !empty($t['end_at']) ? \Carbon\Carbon::parse($t['end_at']) : null,
                        'priority' => isset($t['priority']) ? (int) $t['priority'] : 10,
                        'stock' => isset($t['stock']) ? (int) $t['stock'] : null,
                    ]);
                    $keep[] = $new->id;
                }
            }

            // Delete tiers not sent in incoming
            $toDelete = array_diff($existingIds, $keep);
            if (!empty($toDelete)) {
                \App\Models\TicketTier::whereIn('id', $toDelete)->delete();
            }
        }

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