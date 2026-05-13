<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SparePart extends Model
{
    protected $table = 'spare_parts';

    protected $fillable = [
        'name',
        'code',
        'description',
        'price',
        'stock',
        'min_stock',
        'category',
        'manufacturer',
        'supplier',
        'status',
        'last_restock',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'min_stock' => 'integer',
        'last_restock' => 'datetime',
    ];

    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }
}