<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;

class CouponController extends Controller
{
    public function index()
    {
        $coupons = Coupon::with(['event', 'ticketTier'])->latest()->paginate(15);
        $events = Event::select('id', 'title')->orderBy('title')->get();
        $tiers = \App\Models\TicketTier::with('event')->orderBy('event_id')->get();

        return view('admin.coupons.index', compact('coupons', 'events', 'tiers'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|string|max:50|unique:coupons,code',
            'type' => 'required|in:fixed,percent',
            'discount_amount' => 'required|numeric|min:1',
            'max_uses' => 'nullable|integer|min:1',
            'expires_at' => 'nullable|date',
            'event_id' => 'nullable|exists:events,id',
            'ticket_tier_ids' => 'nullable|array',
            'ticket_tier_ids.*' => 'nullable|exists:ticket_tiers,id',
        ]);

        $coupon = Coupon::create([
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'discount_amount' => $request->discount_amount,
            'max_uses' => $request->max_uses,
            'expires_at' => $request->expires_at ? \Carbon\Carbon::parse($request->expires_at)->endOfDay() : null,
            'event_id' => $request->event_id,
            // keep ticket_tier_id for backward compatibility but prefer ticket_tier_ids pivot
            'ticket_tier_id' => $request->ticket_tier_id ?? null,
            'is_active' => true,
        ]);

        // Sync many-to-many ticket tiers if provided
        if ($request->filled('ticket_tier_ids')) {
            $coupon->ticketTiers()->sync($request->ticket_tier_ids);
        } else {
            // if form submitted with single ticket_tier_id (backwards compatibility), sync that single id
            if ($request->filled('ticket_tier_id')) {
                $coupon->ticketTiers()->sync([$request->ticket_tier_id]);
            }
        }

        return back()->with('success', 'Kupon diskon berhasil ditambahkan.');
    }

    public function destroy(Coupon $coupon)
    {
        $coupon->delete();
        return back()->with('success', 'Kupon berhasil dihapus.');
    }
}
