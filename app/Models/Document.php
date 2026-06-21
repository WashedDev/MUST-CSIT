<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    use HasFactory, Auditable;
    protected $fillable = [
        'title',
        'file_path',
        'category',
        'version',
        'access_level',
        'user_id',
    ];

    public function uploader()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
