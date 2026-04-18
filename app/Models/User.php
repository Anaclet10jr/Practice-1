<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    const ROLE_ADMIN  = 'admin';
    const ROLE_AGENT  = 'agent';
    const ROLE_CLIENT = 'client';

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'phone',
        'avatar',
        'agency_name',
        'license_number',
        'is_approved',
        'bio',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password'          => 'hashed',
        'is_approved'       => 'boolean',
    ];

    // ── Role helpers ──────────────────────────────────────────────
    public function isAdmin(): bool  { return $this->role === self::ROLE_ADMIN; }
    public function isAgent(): bool  { return $this->role === self::ROLE_AGENT; }
    public function isClient(): bool { return $this->role === self::ROLE_CLIENT; }

    // ── Relationships ─────────────────────────────────────────────
    public function properties()
    {
        return $this->hasMany(Property::class, 'agent_id');
    }

    public function favorites()
    {
        return $this->belongsToMany(Property::class, 'favorites')
                    ->withTimestamps();
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }
}
