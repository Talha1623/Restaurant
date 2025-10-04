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
        Schema::create('riders', function (Blueprint $table) {
            $table->id();
              // 🟢 Basic Info
            $table->string('name');
            $table->string('email')->nullable()->unique();
            $table->string('phone');
            $table->string('cnic')->nullable(); // Pakistan specific (optional for UK)

            // 🟢 Address Info
            $table->string('city')->nullable();
            $table->string('postcode')->nullable();
            $table->string('address')->nullable();

            // 🟢 Vehicle Info
            $table->string('vehicle_type');
            $table->string('vehicle_number')->nullable();
            $table->string('license_number')->nullable();
            $table->date('license_expiry')->nullable(); // License expiry date

            // 🟢 UK Rider Specific
            $table->date('dob')->nullable(); // Date of Birth
            $table->string('ni_number')->nullable(); // National Insurance Number
            $table->string('bank_account_number')->nullable();
            $table->string('bank_sort_code')->nullable();

            // 🟢 Employment Info
            $table->date('joining_date')->nullable();
            $table->enum('salary_type', ['fixed', 'per_delivery'])->nullable();
            $table->decimal('salary_amount', 10, 2)->nullable();

            // 🟢 Documents (File Paths)
            $table->string('photo')->nullable();
            $table->string('insurance_doc')->nullable();
            $table->string('mot_doc')->nullable();
            $table->string('right_to_work_doc')->nullable();

            // 🟢 Other Info
            $table->text('features')->nullable(); // JSON / extra features
            $table->enum('status', ['active', 'inactive'])->default('inactive');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('riders');
    }
};
