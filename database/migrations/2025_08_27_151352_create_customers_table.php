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
        Schema::create('customers', function (Blueprint $table) {
            $table->id();
             // Basic Info
            $table->string('first_name');
            $table->string('last_name');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->date('dob')->nullable();

            // Address
            $table->string('address_line1')->nullable();
            $table->string('address_line2')->nullable();
            $table->string('city')->nullable();
            $table->string('county')->nullable();
            $table->string('postcode', 10)->nullable();
            $table->string('country')->nullable();

            // Account Info
            $table->string('username')->nullable()->unique();
            $table->string('password');
            $table->enum('status', ['active', 'inactive', 'blocked'])->default('active');
            $table->date('registration_date')->nullable();

            // Payment / Billing
            $table->string('preferred_payment_method')->nullable();
            $table->string('card_last_four', 4)->nullable();
            $table->boolean('billing_address_same_as_home')->default(true);

            // Identity / Legal
            $table->string('ni_number', 20)->nullable(); // UK National Insurance Number
            $table->string('id_document')->nullable();

            // Preferences / Notes
            $table->text('delivery_instructions')->nullable();
            $table->string('customer_type')->nullable();
            $table->boolean('marketing_opt_in')->default(false);

            // Analytics / Activity
            $table->integer('total_orders')->default(0);
            $table->decimal('total_spent', 10, 2)->default(0);
            $table->date('last_order_date')->nullable();

            // Extra fields
            $table->integer('loyalty_points')->default(0);
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('customers');
    }
};
