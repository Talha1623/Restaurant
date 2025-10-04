<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuOption extends Model
{
    protected $primaryKey = 'option_id';
    
    protected $fillable = [
        'menu_id',
        'details',
        'price',
        'is_checked',
        'is_active'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'is_checked' => 'boolean',
        'is_active' => 'boolean',
    ];

    /**
     * Get the menu that owns the option.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}