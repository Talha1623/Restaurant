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
        Schema::table('menus', function (Blueprint $table) {
            // Add new fields
            $table->string('currency', 10)->default('GBP')->after('price');
            $table->boolean('is_available')->default(true)->after('status');
            $table->tinyInteger('spice_level')->default(0)->after('is_available');
            $table->integer('preparation_time')->nullable()->after('spice_level');
            $table->integer('calories')->nullable()->after('preparation_time');
            $table->json('tags')->nullable()->after('calories');
            
            // Add category_id foreign key
            $table->unsignedBigInteger('category_id')->nullable()->after('category');
            $table->foreign('category_id')->references('category_id')->on('categories')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('menus', function (Blueprint $table) {
            $table->dropForeign(['category_id']);
            $table->dropColumn([
                'currency',
                'is_available',
                'spice_level',
                'preparation_time',
                'calories',
                'tags',
                'category_id'
            ]);
        });
    }
};