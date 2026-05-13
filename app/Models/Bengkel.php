<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Bengkel extends Model
{
    protected $fillable = [
        'name',
        'address',
        'phone',
        'email',
        'city',
        'province',
        'latitude',
        'longitude',
        'description',
        'status',
        'owner_name',
        'owner_phone',
    ];

    protected $casts = [
        'latitude' => 'float',
        'longitude' => 'float',
    ];

    public function mechanics(): HasMany
    {
        return $this->hasMany(Mechanic::class);
    }

    public function vehicles(): HasMany
    {
        return $this->hasMany(Vehicle::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}