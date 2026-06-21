<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Poll extends Model
{
    protected $fillable = ['question', 'description', 'status', 'ends_at', 'created_by'];

    protected function casts(): array
    {
        return ['ends_at' => 'datetime'];
    }

    public function options()
    {
        return $this->hasMany(PollOption::class);
    }

    public function votes()
    {
        return $this->hasMany(PollVote::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function isActive(): bool
    {
        return $this->status === 'active' && (! $this->ends_at || now()->lessThan($this->ends_at));
    }
}
