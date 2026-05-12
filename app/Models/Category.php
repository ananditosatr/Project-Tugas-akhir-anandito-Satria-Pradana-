<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Category extends Model
{
    protected $fillable = [
        'name',
        'display_order',
        'status',
    ];

    protected $casts = [
        'display_order' => 'integer',
    ];

    public function menus(): HasMany
    {
        return $this->hasMany(Menu::class);
    }

    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }
}
