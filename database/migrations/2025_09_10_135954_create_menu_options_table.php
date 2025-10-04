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
        Schema::create('menu_options', function (Blueprint $table) {
            $table->id('option_id');
            $table->unsignedBigInteger('menu_id');
            $table->string('details', 255);
            $table->decimal('price', 10, 2)->default(0.00);
            $table->boolean('is_checked')->default(false);
            $table->boolean('is_active')->default(true);
            $table->timestamps();

            $table->foreign('menu_id')->references('id')->on('menus')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('menu_options');
    }
};