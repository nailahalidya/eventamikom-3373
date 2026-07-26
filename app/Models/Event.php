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
        if ($this->early_bird_until && $now->lte($this->early_bird_until) && !is_null($this->early_bird_price)) {
            return 'Early Bird';
        }
        if ($this->presale_until && $now->lte($this->presale_until) && !is_null($this->presale_price)) {
            return 'Presale';
        }
        return 'Regular';
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
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

}