<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rating extends Model
{
    protected $fillable = [
        'order_id',
        'mechanic_id',
        'service_quality',
        'professionalism',
        'timeliness',
        'overall_rating',
        'review',
        'would_recommend',
        'tanggal_rating',
    ];

    protected $casts = [
        'service_quality' => 'integer',
        'professionalism' => 'integer',
        'timeliness' => 'integer',
        'overall_rating' => 'integer',
        'would_recommend' => 'boolean',
        'tanggal_rating' => 'datetime',
    ];

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function mechanic(): BelongsTo
    {
        return $this->belongsTo(Mechanic::class);
    }
}