<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Coupon;
use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

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
            'starts_at' => 'nullable|date',
            'expires_at' => 'nullable|date',
            'event_id' => 'nullable|exists:events,id',
            'ticket_tier_ids' => 'nullable|array',
            'ticket_tier_ids.*' => 'nullable|exists:ticket_tiers,id',
        ]);

        $couponData = [
            'code' => strtoupper(trim($request->code)),
            'type' => $request->type,
            'discount_amount' => $request->discount_amount,
            'max_uses' => $request->max_uses,
            'event_id' => $request->event_id,
            'is_active' => true,
        ];

        if (Schema::hasColumn('coupons', 'starts_at')) {
            $couponData['starts_at'] = $request->starts_at ? \Carbon\Carbon::parse($request->starts_at)->startOfDay() : null;
        }

        if (Schema::hasColumn('coupons', 'expires_at')) {
            $couponData['expires_at'] = $request->expires_at ? \Carbon\Carbon::parse($request->expires_at)->endOfDay() : null;
        }

        if (Schema::hasColumn('coupons', 'ticket_tier_id')) {
            $couponData['ticket_tier_id'] = $request->ticket_tier_id ?? null;
        }

        $coupon = Coupon::create($couponData);

        if (Schema::hasTable('coupon_ticket_tier')) {
            if ($request->filled('ticket_tier_ids')) {
                $coupon->ticketTiers()->sync($request->ticket_tier_ids);
            } elseif ($request->filled('ticket_tier_id')) {
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
