<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Certificate extends Model
{
    protected $fillable = [
        'restaurant_id',
        'name',
        'type',
        'issue_date',
        'expiry_date',
        'issuing_authority',
        'certificate_number',
        'description',
        'certificate_file',
        'status',
    ];

    protected $casts = [
        'issue_date' => 'date',
        'expiry_date' => 'date',
    ];

    public function restaurant()
    {
        return $this->belongsTo(Restaurant::class);
    }
}
