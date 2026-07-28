<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'discount_amount',
        'max_uses',
        'used_count',
        'expires_at',
        'event_id',
        'ticket_tier_id',
        'is_active',
    ];

    protected $casts = [
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

    public function isValidForEvent($eventId)
    {
        if (!$this->is_active) return false;
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
