<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Certificate;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'organizer_id',
        'category_id',
        'poster_path',
        'owner_type',
        'title',
        'description',
        'date',
        'location',
        'price',
        'early_bird_price',
        'early_bird_until',
        'presale_price',
        'presale_until',
        'stock',
    ];

    protected $casts = [
        'date' => 'datetime',
        'early_bird_until' => 'datetime',
        'presale_until' => 'datetime',
    ];

    public function getCurrentPriceAttribute()
    {
        $now = now();

        // Prefer explicit ticket tiers if any are defined for this event.
        // Find first tier whose start_at <= now (or null) and end_at >= now (or null), ordered by priority.
        try {
            $tier = $this->tiers()
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->orderBy('priority', 'asc')
                ->first();

            if ($tier) {
                return $tier->price;
            }
        } catch (\Throwable $e) {
            // In case tiers table/migration not yet applied, fall back to legacy fields.
        }

        // Legacy single-tier fallback (kept for backward compatibility)
        if ($this->early_bird_until && $now->lte($this->early_bird_until) && !is_null($this->early_bird_price)) {
            return $this->early_bird_price;
        }
        if ($this->presale_until && $now->lte($this->presale_until) && !is_null($this->presale_price)) {
            return $this->presale_price;
        }
        return $this->price;
    }

    public function getActiveTierNameAttribute()
    {
        $now = now();

        // Check explicit tiers first
        try {
            $tier = $this->tiers()
                ->where(function ($q) use ($now) {
                    $q->whereNull('start_at')->orWhere('start_at', '<=', $now);
                })
                ->where(function ($q) use ($now) {
                    $q->whereNull('end_at')->orWhere('end_at', '>=', $now);
                })
                ->orderBy('priority', 'asc')
                ->first();

            if ($tier) {
                return $tier->name;
            }
        } catch (\Throwable $e) {
            // ignore and fall back
        }

        // Legacy fallback
        if ($this->early_bird_until && $now->lte($this->early_bird_until) && !is_null($this->early_bird_price)) {
            return 'Early Bird';
        }
        if ($this->presale_until && $now->lte($this->presale_until) && !is_null($this->presale_price)) {
            return 'Presale';
        }
        return 'Regular';
    }

    /**
     * Accessor URL Poster (Cloudinary, External HTTP, atau Local Asset)
     */
    public function getPosterUrlAttribute()
    {
        if (!$this->poster_path) {
            return asset('assets/concert.png');
        }

        if (\Illuminate\Support\Str::startsWith($this->poster_path, ['http://', 'https://'])) {
            return $this->poster_path;
        }

        return asset('storage/' . $this->poster_path);
    }


    /**
     * Relasi ke Organizer
     */
    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }

    /**
     * Relasi ke Category
     */
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Relasi ke Review
     */
    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    /**
     * Relasi ke Transaction
     */
    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * New: Ticket tiers (early bird, presale 1, presale 2, regular, ...)
     */
    public function tiers()
    {
        return $this->hasMany(TicketTier::class)->orderBy('priority', 'asc');
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

}