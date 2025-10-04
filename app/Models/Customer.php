<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;

class Customer extends Model
{
    use HasApiTokens;
 protected $fillable = [
        // Basic Info
        'first_name',
        'last_name',
        'email',
        'phone',
        'gender',
        'dob',

        // Address
        'address_line1',
        'city',
        'postcode',
        'country',

        // Account Info
        'username',
        'password',
        'status',
        'registration_date',

        // Payment / Billing
        'preferred_payment_method',
        'card_last_four',
        'billing_address_same_as_home',

        // Identity / Legal
        'ni_number',   // National Insurance Number
        'id_document',

        // Preferences / Notes
        'delivery_instructions',
        'customer_type',
        'marketing_opt_in',

        // Analytics / Activity
        'total_orders',
        'total_spent',
        'last_order_date',

        // Extra fields
        'loyalty_points',
        'notes'
    ];
}
