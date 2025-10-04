<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Add new fields
            $table->string('legal_name')->nullable()->after('name');
            $table->string('business_name')->nullable()->after('legal_name');
            $table->string('address_line1')->nullable()->after('business_name');
            $table->string('contact_person')->nullable()->after('phone');
            $table->string('password')->nullable()->after('email');
            $table->time('opening_time')->nullable()->after('password');
            $table->time('closing_time')->nullable()->after('opening_time');
            $table->text('cuisine_tags')->nullable()->after('closing_time');
            $table->string('delivery_zone')->nullable()->after('cuisine_tags');
            $table->string('delivery_postcode')->nullable()->after('delivery_zone');
            $table->decimal('min_order', 8, 2)->nullable()->after('delivery_postcode');
            $table->string('banner')->nullable()->after('logo');
            
            // Remove old fields that are no longer needed
            $table->dropColumn(['owner_name', 'cuisine_type', 'opening_hours', 'closing_hours', 'delivery_available', 'minimum_order', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('restaurants', function (Blueprint $table) {
            // Remove new fields
            $table->dropColumn([
                'legal_name', 'business_name', 'address_line1', 'contact_person', 
                'password', 'opening_time', 'closing_time', 'cuisine_tags', 
                'delivery_zone', 'delivery_postcode', 'min_order', 'banner'
            ]);
            
            // Add back old fields
            $table->string('owner_name')->nullable();
            $table->string('cuisine_type')->nullable();
            $table->time('opening_hours')->nullable();
            $table->time('closing_hours')->nullable();
            $table->string('delivery_available')->nullable();
            $table->decimal('minimum_order', 8, 2)->nullable();
            $table->string('status')->default('active');
        });
    }
};
