<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Ingredient extends Model
{
    protected $primaryKey = 'ingredient_id';
    
    protected $fillable = [
        'name',
        'allergen_info'
    ];

    /**
     * Get the menu items that use this ingredient.
     */
    public function menuItems(): BelongsToMany
    {
        return $this->belongsToMany(Menu::class, 'menu_ingredients', 'ingredient_id', 'menu_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}