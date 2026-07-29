<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Event;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
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
 
        DB::transaction(function () use ($request, $data) {
            $event = Event::create($data);

            // Handle tiers if provided
            if ($request->boolean('tiers_present') || $request->filled('tiers')) {
                foreach ($request->input('tiers', []) as $idx => $t) {
                    \App\Models\TicketTier::create([
                        'event_id' => $event->id,
                        'name' => $t['name'] ?? 'Tier',
                        'price' => $t['price'] ?? 0,
                        'start_at' => !empty($t['start_at']) ? \Carbon\Carbon::parse($t['start_at'])->startOfDay() : null,
                        'end_at' => !empty($t['end_at']) ? \Carbon\Carbon::parse($t['end_at'])->endOfDay() : null,
                        'priority' => isset($t['priority']) && $t['priority'] !== '' ? (int) $t['priority'] : ($idx + 1) * 10,
                        'stock' => isset($t['stock']) && $t['stock'] !== '' ? (int) $t['stock'] : null,
                    ]);
                }

                $activeTier = $event->fresh()->getActiveTier();
                if ($activeTier) {
                    $event->update(['price' => $activeTier->price]);
                }
            }
        });
 
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

        DB::transaction(function () use ($request, $event, $data) {
            $event->update($data);

            if ($request->boolean('tiers_present') || $request->has('tiers')) {
                $incoming = $request->input('tiers', []);
                $existingIds = $event->tiers()->pluck('id')->toArray();
                $keep = [];

                foreach ($incoming as $idx => $t) {
                    $startAt = !empty($t['start_at']) ? \Carbon\Carbon::parse($t['start_at'])->startOfDay() : null;
                    $endAt = !empty($t['end_at']) ? \Carbon\Carbon::parse($t['end_at'])->endOfDay() : null;
                    $priority = isset($t['priority']) && $t['priority'] !== '' ? (int) $t['priority'] : ($idx + 1) * 10;

                    if (!empty($t['id'])) {
                        // Update existing
                        $tier = \App\Models\TicketTier::find($t['id']);
                        if ($tier && $tier->event_id == $event->id) {
                            $tier->update([
                                'name' => $t['name'] ?? $tier->name,
                                'price' => $t['price'] ?? $tier->price,
                                'start_at' => $startAt,
                                'end_at' => $endAt,
                                'priority' => $priority,
                                'stock' => isset($t['stock']) && $t['stock'] !== '' ? (int) $t['stock'] : $tier->stock,
                            ]);
                            $keep[] = $tier->id;
                        }
                    } else {
                        // Create new
                        $newTier = \App\Models\TicketTier::create([
                            'event_id' => $event->id,
                            'name' => $t['name'] ?? 'Tier',
                            'price' => $t['price'] ?? 0,
                            'start_at' => $startAt,
                            'end_at' => $endAt,
                            'priority' => $priority,
                            'stock' => isset($t['stock']) && $t['stock'] !== '' ? (int) $t['stock'] : null,
                        ]);
                        $keep[] = $newTier->id;
                    }
                }

                // Delete removed tiers
                $toDelete = array_diff($existingIds, $keep);
                if (!empty($toDelete)) {
                    \App\Models\TicketTier::whereIn('id', $toDelete)->delete();
                }
            }

            // Sync base price attribute with current active tier price if tiers exist
            $activeTier = $event->fresh()->getActiveTier();
            if ($activeTier) {
                $event->update(['price' => $activeTier->price]);
            }
        });

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