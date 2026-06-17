<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'description',
        'date',
        'location',
        'capacity',
        'price',
        'tag',
        'event_type',
        'registration_deadline',
        'cancel_deadline',
    ];

    protected function casts(): array
    {
        return [
            'date'                 => 'datetime',
            'price'                => 'decimal:2',
            'capacity'             => 'integer',
            'registration_deadline' => 'datetime',
            'cancel_deadline'      => 'datetime',
        ];
    }

    public function isPaid(): bool
    {
        return ! is_null($this->price) && $this->price > 0;
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function hasUnlimitedCapacity(): bool
    {
        return $this->capacity === 0;
    }

    public function availableSeats(): ?int
    {
        if ($this->hasUnlimitedCapacity()) {
            return null;
        }

        return $this->capacity - $this->bookings()->count();
    }
}
