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
        // Kiểm tra và tạo lại bảng nếu cần
        if (Schema::hasTable('order_status_history')) {
            // Kiểm tra cấu trúc hiện tại
            if (!Schema::hasColumn('order_status_history', 'user_id')) {
                Schema::table('order_status_history', function (Blueprint $table) {
                    $table->foreignId('user_id')->nullable()->constrained()->after('order_id');
                });
            }
            
            if (Schema::hasColumn('order_status_history', 'note') && !Schema::hasColumn('order_status_history', 'notes')) {
                Schema::table('order_status_history', function (Blueprint $table) {
                    $table->renameColumn('note', 'notes');
                });
            }
            
            if (!Schema::hasColumn('order_status_history', 'notes')) {
                Schema::table('order_status_history', function (Blueprint $table) {
                    $table->string('notes')->nullable()->after('status');
                });
            }
        } else {
            // Tạo bảng mới nếu chưa tồn tại
            Schema::create('order_status_history', function (Blueprint $table) {
                $table->id();
                $table->foreignId('order_id')->constrained()->onDelete('cascade');
                $table->foreignId('user_id')->constrained();
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
                $table->decimal('latitude', 10, 8)->nullable();
                $table->decimal('longitude', 11, 8)->nullable();
                $table->timestamps();
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Không xóa bảng để tránh mất dữ liệu
    }
};
