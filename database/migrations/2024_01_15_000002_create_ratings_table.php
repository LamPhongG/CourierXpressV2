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
        Schema::create('ratings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->onDelete('cascade');
            $table->foreignId('customer_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('shipper_id')->constrained('users')->onDelete('cascade');
            $table->tinyInteger('rating')->unsigned(); // 1-5
            $table->text('comment')->nullable();
            $table->tinyInteger('delivery_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('communication_rating')->unsigned()->nullable(); // 1-5
            $table->tinyInteger('timeliness_rating')->unsigned()->nullable(); // 1-5
            $table->timestamps();
            
            $table->unique('order_id'); // One rating per order
            $table->index(['shipper_id', 'created_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ratings');
    }
};