<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    protected $fillable = ['user_id', 'merch_item_id', 'quantity'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function merchItem()
    {
        return $this->belongsTo(MerchItem::class);
    }
}
