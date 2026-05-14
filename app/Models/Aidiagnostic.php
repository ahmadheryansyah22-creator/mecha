<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiDiagnostic extends Model
{
    protected $fillable = [
        'diagnostic_id',
        'symptoms',
        'ai_response',
    ];

    public function diagnostic()
    {
        return $this->belongsTo(Diagnostic::class);
    }
}