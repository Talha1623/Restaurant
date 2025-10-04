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
        Schema::table('riders', function (Blueprint $table) {
            // Remove license_expiry column
            $table->dropColumn('license_expiry');
            
            // Add license image columns
            $table->string('license_front_image')->nullable()->after('license_number');
            $table->string('license_back_image')->nullable()->after('license_front_image');
            
            // Remove salary columns
            $table->dropColumn(['salary_type', 'salary_amount']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('riders', function (Blueprint $table) {
            // Add back license_expiry column
            $table->date('license_expiry')->nullable()->after('license_number');
            
            // Remove license image columns
            $table->dropColumn(['license_front_image', 'license_back_image']);
            
            // Add back salary columns
            $table->string('salary_type')->nullable()->after('joining_date');
            $table->decimal('salary_amount', 10, 2)->nullable()->after('salary_type');
        });
    }
};
