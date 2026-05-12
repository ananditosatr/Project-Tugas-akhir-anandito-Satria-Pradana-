<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Menu extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'description',
        'price',
        'image_base64',
        'stock',
        'is_available',
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'stock' => 'integer',
        'is_available' => 'boolean',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function scopeAvailable($query)
    {
        return $query->where('is_available', true)->where('stock', '>', 0);
    }
}
