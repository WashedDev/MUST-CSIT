<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MerchItem extends Model
{
    protected $fillable = [
        'name',
        'description',
        'price',
        'stock',
        'image',
        'images',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price'     => 'decimal:2',
            'is_active' => 'boolean',
            'images'    => 'array',
        ];
    }

    public function purchases()
    {
        return $this->hasMany(MerchPurchase::class);
    }

    public function inStock(): bool
    {
        return $this->stock > 0;
    }

    public function imageUrl(): ?string
    {
        return $this->image ? asset('storage/' . $this->image) : null;
    }

    public function allImages(): array
    {
        $urls = [];

        if ($this->image) {
            $urls[] = $this->imageUrl();
        }

        if ($this->images) {
            foreach ($this->images as $path) {
                $urls[] = asset('storage/' . $path);
            }
        }

        return $urls;
    }
}
