<?php

// Test script to create sample data for agent reports
// Run this file to populate database with sample orders and users

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create Laravel application instance
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "=== TẠO DỮ LIỆU MẪU CHO BÁO CÁO AGENT ===\n\n";
    
    // Create sample users (shippers)
    $shippers = [];
    for ($i = 1; $i <= 5; $i++) {
        $shipper = \App\Models\User::firstOrCreate([
            'email' => "shipper{$i}@test.com"
        ], [
            'name' => "Shipper {$i}",
            'password' => bcrypt('password'),
            'role' => 'shipper',
            'phone' => '091234567' . $i,
            'city' => 'Hà Nội',
            'status' => 'active'
        ]);
        $shippers[] = $shipper;
        echo "✓ Tạo shipper: {$shipper->name}\n";
    }
    
    // Create sample customers
    $customers = [];
    for ($i = 1; $i <= 3; $i++) {
        $customer = \App\Models\User::firstOrCreate([
            'email' => "customer{$i}@test.com"
        ], [
            'name' => "Khách hàng {$i}",
            'password' => bcrypt('password'),
            'role' => 'user',
            'phone' => '098765432' . $i,
            'city' => 'Hà Nội',
            'status' => 'active'
        ]);
        $customers[] = $customer;
        echo "✓ Tạo customer: {$customer->name}\n";
    }
    
    // Create sample orders with different statuses and dates
    $statuses = ['delivered', 'delivered', 'delivered', 'in_transit', 'pickup', 'failed'];
    $packageTypes = ['document', 'parcel', 'food', 'fragile'];
    
    for ($day = 0; $day < 30; $day++) {
        $orderCount = rand(2, 8); // Random 2-8 orders per day
        
        for ($j = 0; $j < $orderCount; $j++) {
            $shipper = $shippers[array_rand($shippers)];
            $customer = $customers[array_rand($customers)];
            $status = $statuses[array_rand($statuses)];
            $packageType = $packageTypes[array_rand($packageTypes)];
            
            $createdAt = \Carbon\Carbon::now()->subDays($day)->addHours(rand(8, 18));
            $completedAt = null;
            
            if (in_array($status, ['delivered', 'failed'])) {
                $completedAt = $createdAt->copy()->addHours(rand(2, 24));
            }
            
            $shippingFee = $packageType === 'express' ? rand(30000, 80000) : rand(15000, 50000);
            $codAmount = rand(100000, 2000000);
            $totalFee = $shippingFee + $codAmount;
            
            $order = \App\Models\Order::create([
                'tracking_number' => 'CX' . date('ymd', $createdAt->timestamp) . str_pad(rand(1, 9999), 4, '0', STR_PAD_LEFT),
                'user_id' => $customer->id,
                'shipper_id' => $shipper->id,
                'status' => $status,
                'package_type' => $packageType,
                'weight' => rand(1, 20) / 10, // 0.1kg to 2kg
                'value' => rand(50000, 2000000), // Giá trị hàng hóa từ 50k-2M VND
                
                // Pickup info
                'pickup_name' => 'Cửa hàng ABC',
                'pickup_phone' => '0912345678',
                'pickup_address' => 'Số 123 Đường ABC',
                'pickup_ward' => 'Phường ' . rand(1, 20),
                'pickup_district' => 'Quận ' . rand(1, 12),
                'pickup_city' => 'Hà Nội',
                'pickup_latitude' => 21.0285 + (rand(-100, 100) / 10000),
                'pickup_longitude' => 105.8542 + (rand(-100, 100) / 10000),
                
                // Delivery info
                'delivery_name' => $customer->name,
                'delivery_phone' => $customer->phone,
                'delivery_address' => 'Số ' . rand(1, 500) . ' Đường XYZ',
                'delivery_ward' => 'Phường ' . rand(1, 30),
                'delivery_district' => 'Quận ' . rand(1, 12),
                'delivery_city' => 'Hà Nội',
                'delivery_latitude' => 21.0285 + (rand(-100, 100) / 10000),
                'delivery_longitude' => 105.8542 + (rand(-100, 100) / 10000),
                
                // Financial info
                'cod_amount' => $codAmount,
                'shipping_fee' => $shippingFee,
                'total_fee' => $totalFee,
                'payment_status' => $status === 'delivered' ? 'paid' : 'pending',
                
                // Timestamps
                'created_at' => $createdAt,
                'updated_at' => $createdAt,
                'assigned_at' => $createdAt,
                'completed_at' => $completedAt,
                
                // Other fields
                'notes' => 'Ghi chú mẫu cho đơn hàng',
                'length' => rand(10, 50),
                'width' => rand(10, 50),
                'height' => rand(5, 30)
            ]);
            
            if ($j === 0) { // Only show first order of each day
                echo "✓ Ngày " . $createdAt->format('d/m/Y') . ": Tạo {$orderCount} đơn hàng\n";
            }
        }
    }
    
    echo "\n=== HOÀN THÀNH TẠO DỮ LIỆU MẪU ===\n";
    echo "✅ Đã tạo " . count($shippers) . " shippers\n";
    echo "✅ Đã tạo " . count($customers) . " customers\n";
    
    $totalOrders = \App\Models\Order::count();
    $deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
    $totalRevenue = \App\Models\Order::where('status', 'delivered')->sum('total_fee');
    
    echo "✅ Tổng đơn hàng: {$totalOrders}\n";
    echo "✅ Đơn đã giao: {$deliveredOrders}\n";
    echo "✅ Tổng doanh thu: " . number_format($totalRevenue, 0, ',', '.') . " VND\n\n";
    
    echo "🚀 Bây giờ bạn có thể truy cập: /test/agent/reports để xem báo cáo!\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>