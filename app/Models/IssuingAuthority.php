<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IssuingAuthority extends Model
{
    protected $fillable = [
        'name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    public function certificates()
    {
        return $this->hasMany(Certificate::class, 'issuing_authority', 'name');
    }
}
