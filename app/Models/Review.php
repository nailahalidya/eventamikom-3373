<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = [
        'user_id',
        'guest_name',
        'is_anonymous',
        'event_id',
        'rating',
        'review',
    ];

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Nama yang akan ditampilkan
     */
    public function getDisplayNameAttribute()
    {
        // Jika anonim
        if ($this->is_anonymous) {
            return '👤 Anonim';
        }

        // Jika login Google
        if ($this->user) {
            return $this->user->name;
        }

        // Jika guest
        return $this->guest_name ?: 'Guest';
    }

    /**
     * Avatar yang akan ditampilkan
     */
    public function getAvatarAttribute()
    {
        // User login Google
        if ($this->user && !empty($this->user->avatar)) {
            return $this->user->avatar;
        }

        // Avatar default berdasarkan nama
        return 'https://ui-avatars.com/api/?background=4f46e5&color=fff&name='
            . urlencode($this->display_name);
    }
}