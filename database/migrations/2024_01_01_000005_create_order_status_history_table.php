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
        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained(); // User who updated the status
            $table->enum('status', [
                'pending',
                'confirmed',
                'assigned',
                'pickup',
                'picked_up', 
                'in_transit',
                'delivering',
                'delivered',
                'failed',
                'returned',
                'cancelled'
            ]);
            $table->string('notes')->nullable();
            $table->decimal('latitude', 10, 8)->nullable(); // Location where status was updated
            $table->decimal('longitude', 11, 8)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_status_history');
    }
};
