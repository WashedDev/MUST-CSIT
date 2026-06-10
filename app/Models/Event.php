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
    ];

    protected function casts(): array
    {
        return [
            'date'  => 'datetime',
            'price' => 'decimal:2',
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

    public function availableSeats(): int
    {
        return $this->capacity - $this->bookings()->count();
    }
}
