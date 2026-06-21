<?php

namespace App\Models;

use App\Models\Traits\Auditable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable, Auditable;

    protected $fillable = [
        'firstname',
        'lastname',
        'email',
        'password',
        'reg_number',
        'programme',
        'year',
        'notification_preferences',
        'onboarding_completed',
    ];

    protected $hidden = ['password', 'remember_token'];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'membership_paid'          => 'boolean',
            'paid_at'                  => 'datetime',
            'notification_preferences' => 'array',
            'permissions'              => 'array',
        ];
    }

    public function getNameAttribute(): string
    {
        return trim($this->firstname . ' ' . $this->lastname);
    }

    public function isAdmin(): bool
    {
        return in_array($this->role, ['admin', 'executive']);
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->role === 'admin') {
            return true;
        }
        $perms = $this->permissions ?? [];
        return in_array($permission, $perms);
    }

    public static function availablePermissions(): array
    {
        return [
            'manage_members',
            'manage_events',
            'manage_elections',
            'manage_articles',
            'manage_documents',
            'manage_merch',
            'manage_payments',
            'view_audit_logs',
        ];
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function votes()
    {
        return $this->hasMany(Vote::class);
    }

    public function articles()
    {
        return $this->hasMany(Article::class);
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function merchPurchases()
    {
        return $this->hasMany(MerchPurchase::class);
    }

    public function cartItems()
    {
        return $this->hasMany(CartItem::class);
    }

    public function cartCount(): int
    {
        return $this->cartItems()->sum('quantity');
    }

    public function wantsNotification(string $type): bool
    {
        $prefs = $this->notification_preferences ?? [];
        if (empty($prefs)) {
            return true;
        }
        return in_array($type, $prefs);
    }
}
