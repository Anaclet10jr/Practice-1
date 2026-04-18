<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    //
}
protected $fillable = [
    'user_id',
    'title',
    'description',
    'price',
    'type',
    'address',
    'lat',
    'lng',
    'bedrooms',
    'bathrooms',
    'area'
];

public function user()
{
    return $this->belongsTo(User::class);
}