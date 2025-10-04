<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuProperty extends Model
{
    protected $fillable = [
        'menu_id',
        'size',
        'discount_price',
        'is_active'
    ];

    protected $casts = [
        'discount_price' => 'decimal:2',
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu that owns the property.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}