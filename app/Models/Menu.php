<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Menu extends Model
{
    protected $fillable = [
        'name',
        'description',
        'ingredients',
        'price',
        'vat_price',
        'currency',
        'category',
        'category_id',
        'second_flavor_id',
        'image',
        'status',
        'is_available',
        'spice_level',
        'preparation_time',
        'calories',
        'tags',
        'allergen',
        'dietary_flags',
        'cold_drinks_addons',
        'restaurant_id'
    ];

    protected $casts = [
        'price' => 'decimal:2',
        'vat_price' => 'decimal:2',
        'is_available' => 'boolean',
        'spice_level' => 'integer',
        'preparation_time' => 'integer',
        'calories' => 'integer',
        'tags' => 'array',
        'dietary_flags' => 'array',
        'cold_drinks_addons' => 'array',
    ];

    /**
     * Get the tags attribute, ensuring it's always an array
     */
    public function getTagsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_string($value)) {
            return array_filter(array_map('trim', explode(',', $value)));
        }
        
        return is_array($value) ? $value : [];
    }

    /**
     * Get the dietary_flags attribute, ensuring it's always an array
     */
    public function getDietaryFlagsAttribute($value)
    {
        if (is_null($value)) {
            return [];
        }
        
        if (is_string($value)) {
            return array_filter(array_map('trim', explode(',', $value)));
        }
        
        return is_array($value) ? $value : [];
    }

    public function restaurant(): BelongsTo
    {
        return $this->belongsTo(Restaurant::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(MenuCategory::class, 'category_id');
    }

    public function secondFlavor(): BelongsTo
    {
        return $this->belongsTo(\App\Models\SecondFlavor::class, 'second_flavor_id');
    }

    /**
     * Get the ingredients for the menu item.
     */
    public function ingredients(): BelongsToMany
    {
        return $this->belongsToMany(Ingredient::class, 'menu_ingredients', 'menu_id', 'ingredient_id')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }

    /**
     * Get the properties for the menu item.
     */
    public function properties(): HasMany
    {
        return $this->hasMany(MenuProperty::class);
    }

    /**
     * Get the options for the menu item.
     */
    public function options(): HasMany
    {
        return $this->hasMany(MenuOption::class);
    }

    /**
     * Get the images for the menu item.
     */
    public function images(): HasMany
    {
        return $this->hasMany(MenuImage::class);
    }

    /**
     * Get the primary image for the menu item.
     */
    public function primaryImage(): HasMany
    {
        return $this->hasMany(MenuImage::class)->where('is_primary', true);
    }
}
