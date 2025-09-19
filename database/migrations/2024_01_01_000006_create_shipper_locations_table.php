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
        Schema::create('shipper_locations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained(); // Shipper ID
            $table->decimal('latitude', 10, 8);
            $table->decimal('longitude', 11, 8);
            $table->decimal('speed', 5, 2)->nullable(); // Current speed in km/h
            $table->decimal('heading', 5, 2)->nullable(); // Direction in degrees
            $table->string('address')->nullable(); // Reverse geocoded address
            $table->boolean('is_online')->default(true);
            $table->boolean('is_busy')->default(false);
            $table->timestamps();
            
            // Index for faster queries
            $table->index(['latitude', 'longitude']);
            $table->index(['is_online', 'is_busy']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shipper_locations');
    }
};
