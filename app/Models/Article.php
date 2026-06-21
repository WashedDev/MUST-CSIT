<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Article extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'title',
        'body',
        'type',
        'status',
        'user_id',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'datetime',
        ];
    }

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function getReadTimeAttribute(): string
    {
        $words = str_word_count(strip_tags($this->body));
        $mins = max(1, intdiv($words, 200));
        return $mins . ' min read';
    }

    public function comments()
    {
        return $this->morphMany(Comment::class, 'commentable');
    }
}
