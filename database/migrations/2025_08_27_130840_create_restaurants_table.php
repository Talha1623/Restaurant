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
        Schema::create('restaurants', function (Blueprint $table) {
            $table->id();
             $table->string('name');                      // Restaurant name
        $table->string('owner_name')->nullable();    // Owner name
        $table->string('email')->nullable();
        $table->string('phone')->nullable();
        $table->text('address')->nullable();
        $table->string('city')->nullable();
        $table->string('country')->nullable();
        $table->string('cuisine_type')->nullable();
        $table->time('opening_hours')->nullable();
        $table->time('closing_hours')->nullable();
        $table->enum('delivery_available', ['yes','no'])->default('no');
        $table->decimal('minimum_order', 10, 2)->nullable();
        $table->string('logo')->nullable();          // file path
        $table->enum('status', ['active','inactive'])->default('active');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('restaurants');
    }
};
