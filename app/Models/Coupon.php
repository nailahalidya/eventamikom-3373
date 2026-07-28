<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'max_uses',
        'used_count',
        'starts_at',
        'expires_at',
        'event_id',
        'ticket_tier_id',
        'is_active',
    ];

    protected $casts = [
        'starts_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_active' => 'boolean',
    ];

    public function ticketTier()
    {
        return $this->belongsTo(\App\Models\TicketTier::class, 'ticket_tier_id');
    }

    /**
     * New: many-to-many relation so a coupon can target multiple ticket tiers.
     */
    public function ticketTiers()
    {
        return $this->belongsToMany(\App\Models\TicketTier::class, 'coupon_ticket_tier');
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    protected static function hasTicketTierPivotTable(): bool
    {
        return Schema::hasTable('coupon_ticket_tier');
    }

    public function isTierRestricted(): bool
    {
        if ($this->ticket_tier_id) {
            return true;
        }

        if (!static::hasTicketTierPivotTable()) {
            return false;
        }

        return $this->ticketTiers()->exists();
    }

    public function allowedTierIds(): array
    {
        $ids = [];

        if (static::hasTicketTierPivotTable()) {
            $ids = $this->ticketTiers->pluck('id')->toArray();
        }

        if ($this->ticket_tier_id) {
            $ids[] = $this->ticket_tier_id;
        }

        return collect($ids)
            ->filter()
            ->unique()
            ->values()
            ->all();
    }

    public function appliesToTicketTier(?\App\Models\TicketTier $tier): bool
    {
        if (!$this->isTierRestricted()) {
            return true;
        }

        if (!$tier) {
            return false;
        }

        return in_array($tier->id, $this->allowedTierIds(), true);
    }

    public function isValidForEvent($eventId)
    {
        if (!$this->is_active) return false;
        // check starts_at (if set) and expires_at
        if ($this->starts_at && now()->lt($this->starts_at)) return false;
        if ($this->expires_at && now()->gt($this->expires_at)) return false;
        if ($this->max_uses && $this->used_count >= $this->max_uses) return false;
        if ($this->event_id && $this->event_id != $eventId) return false;

        return true;
    }

    public function calculateDiscount($originalPrice)
    {
        if ($this->type === 'percent') {
            return min($originalPrice, (int) round(($originalPrice * $this->discount_amount) / 100));
        }

        return min($originalPrice, $this->discount_amount);
    }
}
