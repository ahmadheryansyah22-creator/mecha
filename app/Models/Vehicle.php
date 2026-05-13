<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Vehicle extends Model
{
    protected $table = 'vehicles';

    protected $fillable = [
        'bengkel_id',
        'license_plate',
        'owner_name',
        'owner_phone',
        'owner_email',
        'vehicle_type',
        'brand',
        'model',
        'year',
        'color',
        'vin',
        'mileage',
        'status',
        'notes',
        'last_service',
    ];

    protected $casts = [
        'last_service' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function bengkel(): BelongsTo
    {
        return $this->belongsTo(Bengkel::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function diagnostics(): HasMany
    {
        return $this->hasMany(Diagnostic::class);
    }
}