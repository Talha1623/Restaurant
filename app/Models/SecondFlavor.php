<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SecondFlavor extends Model
{
    protected $fillable = [
        'name',
        'image',
        'is_active'
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];
}

