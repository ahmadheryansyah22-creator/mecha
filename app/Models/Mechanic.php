<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Mechanic extends Model
{
    protected $fillable = [
        'bengkel_id',
        'name',
        'phone',
        'email',
        'expertise',
        'salary',
        'experience_years',
        'certification',
        'status',
        'notes',
        'join_date',
    ];

    protected $casts = [
        'salary' => 'decimal:2',
        'join_date' => 'date',
    ];

    public function bengkel(): BelongsTo
    {
        return $this->belongsTo(Bengkel::class);
    }

    public function diagnostics(): HasMany
    {
        return $this->hasMany(Diagnostic::class);
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    public function ratings(): HasMany
    {
        return $this->hasMany(Rating::class);
    }
}