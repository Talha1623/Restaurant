<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Restaurant extends Model
{
    use HasApiTokens;
     protected $fillable = [
        'legal_name',
        'business_name',
        'email',
        'password',
        'phone',
        'contact_person',
        'address',
        'address_line1',
        'city',
        'postcode',
        'opening_time',
        'closing_time',
        'cuisine_tags',
        'delivery_zone',
        'delivery_postcode',
        'min_order',
        'logo',
        'banner',
        'status',
        'blocked',
    ];

    protected $hidden = [
        'password',
    ];

    /**
     * Get the categories for the restaurant.
     */
    public function categories()
    {
        return $this->hasMany(RestaurantCategory::class);
    }

    /**
     * Get the addons for the restaurant.
     */
    public function addons()
    {
        return $this->hasMany(RestaurantAddon::class);
    }

    /**
     * Get the menus for the restaurant.
     */
    public function menus()
    {
        return $this->hasMany(Menu::class);
    }

    /**
     * Get the certificates for the restaurant.
     */
    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }
}
