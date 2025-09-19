<?php
// Script tạo dữ liệu toàn diện theo DATA_CREATION_GUIDE.md

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create Laravel application instance
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "=== TẠO DỮ LIỆU TOÀN DIỆN CHO COURIERXPRESS ===\n\n";
    
    // Tạo thêm Admin accounts
    $admins = [
        [
            'name' => 'System Administrator',
            'email' => 'admin@courierxpress.com',
            'phone' => '0901000001',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'status' => 'active',
            'city' => 'Hồ Chí Minh'
        ],
        [
            'name' => 'Super Admin',
            'email' => 'superadmin@courierxpress.com', 
            'phone' => '0901000002',
            'password' => bcrypt('123456'),
            'role' => 'admin',
            'status' => 'active',
            'city' => 'Hà Nội'
        ]
    ];

    foreach ($admins as $adminData) {
        $admin = \App\Models\User::firstOrCreate(
            ['email' => $adminData['email']],
            $adminData
        );
        echo "✓ Tạo admin: {$admin->name}\n";
    }

    // Tạo thêm Agent accounts
    $agents = [
        [
            'name' => 'Nguyễn Văn Agent HCM',
            'email' => 'agent.hcm@courierxpress.com',
            'phone' => '0902000001',
            'password' => bcrypt('123456'),
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => true
        ],
        [
            'name' => 'Trần Thị Agent HN',
            'email' => 'agent.hn@courierxpress.com',
            'phone' => '0902000002',
            'password' => bcrypt('123456'),
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Hà Nội',
            'is_online' => true
        ],
        [
            'name' => 'Lê Văn Agent DN',
            'email' => 'agent.dn@courierxpress.com',
            'phone' => '0902000003',
            'password' => bcrypt('123456'),
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Đà Nẵng',
            'is_online' => false
        ]
    ];

    foreach ($agents as $agentData) {
        $agent = \App\Models\User::firstOrCreate(
            ['email' => $agentData['email']],
            $agentData
        );
        echo "✓ Tạo agent: {$agent->name}\n";
    }

    // Tạo thêm Shipper accounts
    $shippers = [
        [
            'name' => 'Shipper Nguyễn Văn A',
            'email' => 'shipper.a@courierxpress.com',
            'phone' => '0903000001',
            'password' => bcrypt('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => true
        ],
        [
            'name' => 'Shipper Trần Văn B',
            'email' => 'shipper.b@courierxpress.com',
            'phone' => '0903000002',
            'password' => bcrypt('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => true
        ],
        [
            'name' => 'Shipper Lê Thị C',
            'email' => 'shipper.c@courierxpress.com',
            'phone' => '0903000003',
            'password' => bcrypt('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hà Nội',
            'is_online' => true
        ],
        [
            'name' => 'Shipper Phạm Văn D',
            'email' => 'shipper.d@courierxpress.com',
            'phone' => '0903000004',
            'password' => bcrypt('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Đà Nẵng',
            'is_online' => true
        ],
        [
            'name' => 'Shipper Hoàng Thị E',
            'email' => 'shipper.e@courierxpress.com',
            'phone' => '0903000005',
            'password' => bcrypt('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => false
        ]
    ];

    foreach ($shippers as $shipperData) {
        $shipper = \App\Models\User::firstOrCreate(
            ['email' => $shipperData['email']],
            $shipperData
        );
        echo "✓ Tạo shipper: {$shipper->name}\n";
    }

    // Tạo thêm Customer accounts
    $customers = [
        [
            'name' => 'Khách hàng Nguyễn Văn Nam',
            'email' => 'customer1@example.com',
            'phone' => '0904000001',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hồ Chí Minh'
        ],
        [
            'name' => 'Khách hàng Trần Thị Lan',
            'email' => 'customer2@example.com',
            'phone' => '0904000002',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hà Nội'
        ],
        [
            'name' => 'Khách hàng Lê Văn Hùng',
            'email' => 'customer3@example.com',
            'phone' => '0904000003',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Đà Nẵng'
        ],
        [
            'name' => 'Doanh nghiệp ABC Corp',
            'email' => 'business@abccorp.com',
            'phone' => '0904000004',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hồ Chí Minh'
        ],
        [
            'name' => 'Khách hàng VIP Premium',
            'email' => 'vip@premium.com',
            'phone' => '0904000005',
            'password' => bcrypt('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hồ Chí Minh'
        ]
    ];

    foreach ($customers as $customerData) {
        $customer = \App\Models\User::firstOrCreate(
            ['email' => $customerData['email']],
            $customerData
        );
        echo "✓ Tạo customer: {$customer->name}\n";
    }

    // Tạo thêm shipping services
    $additionalServices = [
        [
            'name' => 'Giao hàng đồ ăn',
            'code' => 'FOOD_DELIVERY',
            'description' => 'Dịch vụ chuyên biệt cho đồ ăn, giữ nhiệt độ',
            'base_price' => 15000.00,
            'price_per_km' => 1500.00,
            'price_per_kg' => 3000.00,
            'estimated_delivery_time' => 2,
            'is_active' => true
        ],
        [
            'name' => 'Giao hàng dễ vỡ',
            'code' => 'FRAGILE',
            'description' => 'Dịch vụ cho hàng dễ vỡ, đóng gói đặc biệt',
            'base_price' => 40000.00,
            'price_per_km' => 4000.00,
            'price_per_kg' => 8000.00,
            'estimated_delivery_time' => 24,
            'is_active' => true
        ],
        [
            'name' => 'Giao hàng giá trị cao',
            'code' => 'HIGH_VALUE',
            'description' => 'Dành cho hàng hóa có giá trị cao, bảo hiểm kèm theo',
            'base_price' => 60000.00,
            'price_per_km' => 6000.00,
            'price_per_kg' => 12000.00,
            'estimated_delivery_time' => 12,
            'is_active' => true
        ],
        [
            'name' => 'Giao hàng tài liệu',
            'code' => 'DOCUMENT',
            'description' => 'Chuyên giao tài liệu, hợp đồng quan trọng',
            'base_price' => 25000.00,
            'price_per_km' => 2500.00,
            'price_per_kg' => 1000.00,
            'estimated_delivery_time' => 24,
            'is_active' => true
        ]
    ];

    foreach ($additionalServices as $serviceData) {
        $service = \App\Models\ShippingService::firstOrCreate(
            ['code' => $serviceData['code']],
            $serviceData
        );
        echo "✓ Tạo service: {$service->name}\n";
    }

    echo "\n=== HOÀN THÀNH TẠO DỮ LIỆU TOÀN DIỆN ===\n";
    
    $totalUsers = \App\Models\User::count();
    $totalAdmins = \App\Models\User::where('role', 'admin')->count();
    $totalAgents = \App\Models\User::where('role', 'agent')->count();
    $totalShippers = \App\Models\User::where('role', 'shipper')->count();
    $totalCustomers = \App\Models\User::where('role', 'user')->count();
    $totalServices = \App\Models\ShippingService::count();
    $totalOrders = \App\Models\Order::count();
    
    echo "✅ Tổng users: {$totalUsers}\n";
    echo "   - Admins: {$totalAdmins}\n";
    echo "   - Agents: {$totalAgents}\n";
    echo "   - Shippers: {$totalShippers}\n";
    echo "   - Customers: {$totalCustomers}\n";
    echo "✅ Tổng services: {$totalServices}\n";
    echo "✅ Tổng orders: {$totalOrders}\n\n";

    echo "🎯 TÀI KHOẢN ĐỂ TEST:\n";
    echo "📧 Admin: admin@courierxpress.com (password: 123456)\n";
    echo "📧 Agent HCM: agent.hcm@courierxpress.com (password: 123456)\n";
    echo "📧 Shipper A: shipper.a@courierxpress.com (password: 123456)\n";
    echo "📧 Customer 1: customer1@example.com (password: 123456)\n\n";
    
    echo "🚀 Bây giờ bạn có thể:\n";
    echo "   - Truy cập admin panel\n";
    echo "   - Test các chức năng của từng role\n";
    echo "   - Xem báo cáo và thống kê\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}
?>