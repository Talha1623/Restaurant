<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class MenuImage extends Model
{
    protected $primaryKey = 'image_id';
    
    protected $fillable = [
        'menu_id',
        'image_url',
        'is_primary',
        'sort_order'
    ];

    protected $casts = [
        'is_primary' => 'boolean',
        'sort_order' => 'integer',
    ];

    /**
     * Get the menu that owns the image.
     */
    public function menu(): BelongsTo
    {
        return $this->belongsTo(Menu::class);
    }
}