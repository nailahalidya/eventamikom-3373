<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'order_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'total_price',
        'status',
        'snap_token',
        'qr_token',
        'checked_in_at',
        'wa_reminder_sent_at',
        'expires_at',
        'is_stock_released',
        'ticket_sent',
    ];

    protected $casts = [
        'checked_in_at' => 'datetime',
        'wa_reminder_sent_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_stock_released' => 'boolean',
        'ticket_sent' => 'boolean',
    ];

    public function releaseStock()
    {
        if (!$this->is_stock_released && $this->event) {
            $this->event->increment('stock');
            $this->is_stock_released = true;
            $this->save();
        }
    }

    /**
     * Kirim e-ticket via Email & WhatsApp — hanya sekali (gunakan flag ticket_sent)
     */
    public function sendTicket(): bool
    {
        if ($this->ticket_sent) {
            return false; // Sudah pernah dikirim
        }

        try {
            \Illuminate\Support\Facades\Mail::to($this->customer_email)
                ->send(new \App\Mail\TicketMail($this));

            app(\App\Services\WhatsAppService::class)->sendTicketNotification($this);

            $this->update(['ticket_sent' => true]);

            return true;
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Failed sending ticket email/WA: ' . $e->getMessage());
            return false;
        }
    }

    /**
     * Relasi ke Event
     */
    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    /**
     * Relasi ke User
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relasi ke Review
     */
    public function review()
    {
        return $this->hasOne(Review::class);
    }

    public function organizer()
    {
        return $this->belongsTo(Organizer::class);
    }
}
