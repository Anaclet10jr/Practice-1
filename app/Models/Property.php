<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Property extends Model
{
    use HasFactory, SoftDeletes;

    const STATUS_PENDING  = 'pending';
    const STATUS_APPROVED = 'approved';
    const STATUS_REJECTED = 'rejected';
    const STATUS_SOLD     = 'sold';
    const STATUS_RENTED   = 'rented';

    const TYPE_SALE   = 'sale';
    const TYPE_RENT   = 'rent';
    const TYPE_BOTH   = 'both';

    protected $fillable = [
        'agent_id',
        'title',
        'slug',
        'description',
        'type',           // sale | rent | both
        'status',         // pending | approved | rejected | sold | rented
        'price',
        'price_period',   // monthly | yearly (for rent)
        'bedrooms',
        'bathrooms',
        'area_sqm',
        'address',
        'district',
        'sector',
        'latitude',
        'longitude',
        'features',       // JSON: ["parking","pool","garden",…]
        'images',         // JSON: array of storage paths
        'is_featured',
        'views_count',
        'rejection_reason',
    ];

    protected $casts = [
        'features'    => 'array',
        'images'      => 'array',
        'is_featured' => 'boolean',
        'price'       => 'decimal:2',
        'latitude'    => 'decimal:7',
        'longitude'   => 'decimal:7',
    ];

    // ── Scopes ────────────────────────────────────────────────────
    public function scopeApproved($q)  { return $q->where('status', self::STATUS_APPROVED); }
    public function scopePending($q)   { return $q->where('status', self::STATUS_PENDING); }
    public function scopeFeatured($q)  { return $q->where('is_featured', true); }

    public function scopeForSale($q)   { return $q->whereIn('type', [self::TYPE_SALE, self::TYPE_BOTH]); }
    public function scopeForRent($q)   { return $q->whereIn('type', [self::TYPE_RENT, self::TYPE_BOTH]); }

    // ── Relationships ─────────────────────────────────────────────
    public function agent()
    {
        return $this->belongsTo(User::class, 'agent_id');
    }

    public function inquiries()
    {
        return $this->hasMany(Inquiry::class);
    }

    public function favoritedBy()
    {
        return $this->belongsToMany(User::class, 'favorites')->withTimestamps();
    }

    // ── Helpers ───────────────────────────────────────────────────
    public function getCoverImageAttribute(): ?string
    {
        return !empty($this->images) ? $this->images[0] : null;
    }

    public function getFormattedPriceAttribute(): string
    {
        $symbol = 'RWF';
        $formatted = number_format($this->price);
        if ($this->type === self::TYPE_RENT && $this->price_period) {
            return "{$symbol} {$formatted} / {$this->price_period}";
        }
        return "{$symbol} {$formatted}";
    }
}
