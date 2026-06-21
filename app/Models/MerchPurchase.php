<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchPurchase extends Model
{
    protected $fillable = [
        'user_id',
        'merch_item_id',
        'quantity',
        'amount',
        'status',
        'gateway_reference',
        'paid_at',
    ];

    protected function casts(): array
    {
        return [
            'amount'  => 'decimal:2',
            'paid_at' => 'datetime',
        ];
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function item()
    {
        return $this->belongsTo(MerchItem::class, 'merch_item_id');
    }
}
