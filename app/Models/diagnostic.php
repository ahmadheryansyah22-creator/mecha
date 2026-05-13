<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Diagnostic extends Model
{
    protected $fillable = [
        'vehicle_id',
        'mechanic_id',
        'customer_complaint',
        'visual_inspection',
        'findings',
        'affected_systems',
        'estimated_cost',
        'severity',
        'status',
        'completed_at',
    ];

    protected $casts = [
        'affected_systems' => 'array',
        'estimated_cost' => 'decimal:2',
        'completed_at' => 'datetime',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class);
    }

    public function aiDiagnostic(): HasOne
    {
        return $this->hasOne(AiDiagnostic::class);
    }
}