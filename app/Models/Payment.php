<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    protected $fillable = [
        'user_id',
        'booking_id',
        'type',
        'amount',
        'currency',
        'gateway',
        'gateway_reference',
        'status',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'paid_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function isSuccessful(): bool
    {
        return $this->status === 'completed';
    }

    public function isMembership(): bool
    {
        return $this->type === 'membership';
    }

    public function isEventPayment(): bool
    {
        return $this->type === 'event';
    }
}
