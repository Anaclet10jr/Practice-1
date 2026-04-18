<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Inquiry extends Model
{
    use HasFactory;

    const STATUS_NEW      = 'new';
    const STATUS_READ     = 'read';
    const STATUS_REPLIED  = 'replied';
    const STATUS_CLOSED   = 'closed';

    protected $fillable = [
        'property_id',
        'user_id',        // null if guest
        'name',
        'email',
        'phone',
        'message',
        'status',
    ];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
