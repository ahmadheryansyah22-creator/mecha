<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AiDiagnostic extends Model
{
    protected $table = 'ai_diagnostics';

    protected $fillable = [
        'vehicle_id',
        'diagnostic_id',
        'symptom_description',
        'ai_analysis',
        'ai_findings',
        'recommended_services',
        'recommended_spare_parts',
        'estimated_cost',
        'confidence_score',
        'severity_prediction',
        'ai_model',
        'raw_response',
        'is_accurate',
        'accuracy_feedback',
    ];

    protected $casts = [
        'ai_findings' => 'array',
        'recommended_services' => 'array',
        'recommended_spare_parts' => 'array',
        'estimated_cost' => 'decimal:2',
        'confidence_score' => 'integer',
        'is_accurate' => 'boolean',
        'accuracy_feedback' => 'integer',
    ];

    public function vehicle(): BelongsTo
    {
        return $this->belongsTo(Vehicle::class);
    }

    public function diagnostic(): BelongsTo
    {
        return $this->belongsTo(Diagnostic::class);
    }
}