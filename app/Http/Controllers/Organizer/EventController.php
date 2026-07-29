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
            'tiers' => 'nullable|array',
            'tiers.*.name' => 'required_with:tiers|string',
            'tiers.*.price' => 'required_with:tiers|numeric|min:0',
            'tiers.*.start_at' => 'nullable|date',
            'tiers.*.end_at' => 'nullable|date',
            'tiers.*.priority' => 'nullable|integer',
            'tiers.*.stock' => 'nullable|integer',
        ]);

        $data['owner_type'] = 'organizer';
        $data['organizer_id'] = $organizer->id;

        if ($request->hasFile('poster')) {
            $uploadedUrl = CloudinaryService::upload($request->file('poster'), 'posters');
            if ($uploadedUrl) {
                $data['poster_path'] = $uploadedUrl;
            }
        }

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $data) {
            $event = Event::create($data);

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
            'tiers' => 'nullable|array',
            'tiers.*.id' => 'nullable|integer|exists:ticket_tiers,id',
            'tiers.*.name' => 'required_with:tiers|string',
            'tiers.*.price' => 'required_with:tiers|numeric|min:0',
            'tiers.*.start_at' => 'nullable|date',
            'tiers.*.end_at' => 'nullable|date',
            'tiers.*.priority' => 'nullable|integer',
            'tiers.*.stock' => 'nullable|integer',
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

        \Illuminate\Support\Facades\DB::transaction(function () use ($request, $event, $data) {
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

                $toDelete = array_diff($existingIds, $keep);
                if (!empty($toDelete)) {
                    \App\Models\TicketTier::whereIn('id', $toDelete)->delete();
                }
            }

            $activeTier = $event->fresh()->getActiveTier();
            if ($activeTier) {
                $event->update(['price' => $activeTier->price]);
            }
        });

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