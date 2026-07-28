<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketTier extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'name',
        'price',
        'start_at',
        'end_at',
        'priority',
        'stock',
    ];

    protected $casts = [
        'start_at' => 'datetime',
        'end_at' => 'datetime',
        'price' => 'decimal:2',
        'stock' => 'integer',
    ];

    public function event()
    {
        return $this->belongsTo(Event::class);
    }
}
