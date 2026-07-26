<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organizer extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'logo',
        'email',
        'phone',
        'description',
        'status',
    ];

    public function getLogoUrlAttribute()
    {
        if (!$this->logo) {
            return 'https://ui-avatars.com/api/?background=4f46e5&color=fff&name=' . urlencode($this->name);
        }

        if (\Illuminate\Support\Str::startsWith($this->logo, ['http://', 'https://'])) {
            return $this->logo;
        }

        return asset('storage/' . $this->logo);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }


    public function events()
    {
        return $this->hasMany(Event::class);
    }
}
