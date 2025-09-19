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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('tracking_number')->unique();
            
            // Customer information
            $table->foreignId('user_id')->constrained(); // Customer who created the order
            $table->foreignId('agent_id')->nullable()->constrained('users'); // Agent who processed the order
            $table->foreignId('shipper_id')->nullable()->constrained('users'); // Shipper assigned to deliver
            
            // Pickup information
            $table->string('pickup_name');
            $table->string('pickup_phone');
            $table->string('pickup_address');
            $table->string('pickup_ward');
            $table->string('pickup_district');
            $table->string('pickup_city');
            $table->decimal('pickup_latitude', 10, 8)->nullable();
            $table->decimal('pickup_longitude', 11, 8)->nullable();
            
            // Delivery information
            $table->string('delivery_name');
            $table->string('delivery_phone');
            $table->string('delivery_address');
            $table->string('delivery_ward');
            $table->string('delivery_district');
            $table->string('delivery_city');
            $table->decimal('delivery_latitude', 10, 8)->nullable();
            $table->decimal('delivery_longitude', 11, 8)->nullable();
            
            // Package information
            $table->enum('package_type', ['document', 'parcel', 'food', 'fragile', 'other']);
            $table->decimal('weight', 8, 2); // in kg
            $table->decimal('length', 8, 2)->nullable(); // in cm
            $table->decimal('width', 8, 2)->nullable(); // in cm
            $table->decimal('height', 8, 2)->nullable(); // in cm
            $table->decimal('value', 12, 2); // declared value
            $table->decimal('cod_amount', 12, 2)->default(0); // cash on delivery amount
            $table->text('notes')->nullable();
            
            // Service & payment
            $table->foreignId('shipping_service_id')->nullable()->constrained();
            $table->decimal('shipping_fee', 12, 2);
            $table->decimal('insurance_fee', 12, 2)->default(0);
            $table->decimal('total_fee', 12, 2);
            $table->enum('payment_method', ['cash', 'bank_transfer', 'e_wallet']);
            $table->enum('payment_status', ['pending', 'paid', 'refunded'])->default('pending');
            
            // Status tracking
            $table->enum('status', [
                'pending', // Order created, waiting for agent to process
                'confirmed', // Order confirmed by agent
                'assigned', // Shipper assigned
                'pickup', // Shipper is picking up
                'picked_up', // Package picked up
                'in_transit', // Package in transit
                'delivering', // Shipper is delivering
                'delivered', // Successfully delivered
                'failed', // Delivery failed
                'returned', // Package returned
                'cancelled' // Order cancelled
            ])->default('pending');
            
            // Timestamps
            $table->timestamp('confirmed_at')->nullable();
            $table->timestamp('assigned_at')->nullable();
            $table->timestamp('pickup_at')->nullable();
            $table->timestamp('picked_up_at')->nullable();
            $table->timestamp('in_transit_at')->nullable();
            $table->timestamp('delivering_at')->nullable();
            $table->timestamp('completed_at')->nullable();
            $table->timestamp('cancelled_at')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
