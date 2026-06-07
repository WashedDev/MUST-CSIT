<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'file_path',
        'category',
        'user_id',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
