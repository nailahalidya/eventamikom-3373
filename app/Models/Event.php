<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    protected $fillable = [
    'title',
    'description',
    'poster_path',
    'location',
    'date',
    'price',
    'stock',
    'category_id',
];

    public function category()
    {
        return $this->belongsTo(Category::class);
    }
}
