<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\ShippingService;
use App\Models\Order;
use App\Models\Rating;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Tạo admin
        $admin = User::firstOrCreate([
            'email' => 'admin@courierxpress.com'
        ], [
            'name' => 'Admin CourierXpress',
            'phone' => '0901234567',
            'password' => Hash::make('123456'),
            'role' => 'admin',
            'status' => 'active',
        ]);

        // Tạo agent
        $agent = User::firstOrCreate([
            'email' => 'agent@courierxpress.com'
        ], [
            'name' => 'Agent Hồ Chí Minh',
            'phone' => '0901234568', 
            'password' => Hash::make('123456'),
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
        ]);

        // Tạo shipper
        $shipper = User::firstOrCreate([
            'email' => 'shipper@courierxpress.com'
        ], [
            'name' => 'Nguyễn Văn Shipper',
            'phone' => '0901234569',
            'password' => Hash::make('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => true,
        ]);

        // Tạo khách hàng
        $customer = User::firstOrCreate([
            'email' => 'customer@courierxpress.com'
        ], [
            'name' => 'Trần Thị Khách Hàng',
            'phone' => '0901234570',
            'password' => Hash::make('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
        ]);

        // Tạo thêm một số users cho test
        User::firstOrCreate(['email' => 'user1@test.com'], [
            'name' => 'Test User 1',
            'phone' => '0901111111',
            'password' => Hash::make('123456'),
            'role' => 'user',
            'status' => 'active',
        ]);

        User::firstOrCreate(['email' => 'shipper2@test.com'], [
            'name' => 'Test Shipper 2',
            'phone' => '0902222222',
            'password' => Hash::make('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'is_online' => false,
        ]);

        // Tạo dịch vụ vận chuyển
        $services = [
            [
                'name' => 'Giao hàng tiêu chuẩn',
                'code' => 'STANDARD',
                'description' => 'Giao hàng trong 1-2 ngày làm việc',
                'base_price' => 20000.00,
                'price_per_km' => 2000.00,
                'price_per_kg' => 5000.00,
                'estimated_delivery_time' => 48, // 48 hours
                'is_active' => true,
            ],
            [
                'name' => 'Giao hàng nhanh',
                'code' => 'EXPRESS',
                'description' => 'Giao hàng trong ngày',
                'base_price' => 35000.00,
                'price_per_km' => 3000.00,
                'price_per_kg' => 7000.00,
                'estimated_delivery_time' => 24, // 24 hours
                'is_active' => true,
            ],
            [
                'name' => 'Giao hàng siêu tốc',
                'code' => 'SUPER_EXPRESS',
                'description' => 'Giao hàng trong 2-4 giờ',
                'base_price' => 50000.00,
                'price_per_km' => 5000.00,
                'price_per_kg' => 10000.00,
                'estimated_delivery_time' => 4, // 4 hours
                'is_active' => true,
            ],
        ];
        
        foreach ($services as $service) {
            ShippingService::firstOrCreate(
                ['code' => $service['code']], 
                $service
            );
        }

        // Tạo một số đơn hàng test cho shipper
        $testOrders = [
            [
                'tracking_number' => 'CX' . date('Ymd') . '001',
                'user_id' => $customer->id,
                'shipper_id' => $shipper->id,
                'pickup_name' => 'Nguyen Van A',
                'pickup_phone' => '0123456789',
                'pickup_address' => '123 Nguyen Trai, Phuong 1',
                'pickup_ward' => 'Phuong 1',
                'pickup_district' => 'Quan 1',
                'pickup_city' => 'Ho Chi Minh City',
                'delivery_name' => 'Tran Thi B',
                'delivery_phone' => '0987654321',
                'delivery_address' => '456 Le Loi, Phuong 2',
                'delivery_ward' => 'Phuong 2',
                'delivery_district' => 'Quan 3',
                'delivery_city' => 'Ho Chi Minh City',
                'package_type' => 'document',
                'weight' => 1.0,
                'value' => 100000,
                'cod_amount' => 50000,
                'shipping_service_id' => 1,
                'shipping_fee' => 30000,
                'insurance_fee' => 0,
                'total_fee' => 30000,
                'payment_method' => 'cash',
                'status' => 'assigned',
                'assigned_at' => now(),
            ],
            [
                'tracking_number' => 'CX' . date('Ymd') . '002',
                'user_id' => $customer->id,
                'shipper_id' => $shipper->id,
                'pickup_name' => 'Le Van C',
                'pickup_phone' => '0123456780',
                'pickup_address' => '789 Vo Van Tan, Phuong 5',
                'pickup_ward' => 'Phuong 5',
                'pickup_district' => 'Quan 3',
                'pickup_city' => 'Ho Chi Minh City',
                'delivery_name' => 'Pham Thi D',
                'delivery_phone' => '0987654322',
                'delivery_address' => '321 Cach Mang Thang 8, Phuong 6',
                'delivery_ward' => 'Phuong 6',
                'delivery_district' => 'Quan 10',
                'delivery_city' => 'Ho Chi Minh City',
                'package_type' => 'parcel',
                'weight' => 2.5,
                'value' => 200000,
                'cod_amount' => 0,
                'shipping_service_id' => 2,
                'shipping_fee' => 45000,
                'insurance_fee' => 5000,
                'total_fee' => 50000,
                'payment_method' => 'bank_transfer',
                'status' => 'in_transit',
                'assigned_at' => now()->subHours(2),
                'pickup_at' => now()->subMinutes(30),
                'picked_up_at' => now()->subMinutes(20),
                'in_transit_at' => now()->subMinutes(10),
            ],
            [
                'tracking_number' => 'CX' . date('Ymd') . '003',
                'user_id' => $customer->id,
                'shipper_id' => $shipper->id,
                'pickup_name' => 'Hoang Van E',
                'pickup_phone' => '0123456781',
                'pickup_address' => '555 Dong Khoi, Phuong Ben Nghe',
                'pickup_ward' => 'Phuong Ben Nghe',
                'pickup_district' => 'Quan 1',
                'pickup_city' => 'Ho Chi Minh City',
                'delivery_name' => 'Vu Thi F',
                'delivery_phone' => '0987654323',
                'delivery_address' => '777 Hai Ba Trung, Phuong 8',
                'delivery_ward' => 'Phuong 8',
                'delivery_district' => 'Quan 3',
                'delivery_city' => 'Ho Chi Minh City',
                'package_type' => 'food',
                'weight' => 0.8,
                'value' => 50000,
                'cod_amount' => 50000,
                'shipping_service_id' => 3,
                'shipping_fee' => 25000,
                'insurance_fee' => 0,
                'total_fee' => 25000,
                'payment_method' => 'cash',
                'status' => 'delivered',
                'assigned_at' => now()->subDay(),
                'pickup_at' => now()->subDay()->addHours(2),
                'picked_up_at' => now()->subDay()->addHours(3),
                'in_transit_at' => now()->subDay()->addHours(4),
                'delivering_at' => now()->subDay()->addHours(6),
                'completed_at' => now()->subDay()->addHours(7),
            ]
        ];

        foreach ($testOrders as $orderData) {
            Order::firstOrCreate(
                ['tracking_number' => $orderData['tracking_number']],
                $orderData
            );
        }

        // Tạo một rating test cho đơn hàng đã giao
        $deliveredOrder = Order::where('status', 'delivered')->first();
        if ($deliveredOrder) {
            Rating::firstOrCreate(
                ['order_id' => $deliveredOrder->id],
                [
                    'customer_id' => $customer->id,
                    'shipper_id' => $shipper->id,
                    'rating' => 5,
                    'comment' => 'Dịch vụ xuất sắc! Giao hàng nhanh và chu đáo.',
                    'delivery_rating' => 5,
                    'communication_rating' => 5,
                    'timeliness_rating' => 4,
                ]
            );
        }

        // Skip orders for now - can be created through the UI
        // The admin module is ready to create and manage orders

        echo "\n✅ Database seeded successfully!\n";
        echo "📧 Admin: admin@courierxpress.com (password: 123456)\n";
        echo "📧 Agent: agent@courierxpress.com (password: 123456)\n";
        echo "📧 Shipper: shipper@courierxpress.com (password: 123456)\n";
        echo "📧 Customer: customer@courierxpress.com (password: 123456)\n";
        echo "🔗 Access admin panel at: http://localhost/courierxpress/admin/dashboard\n";
    }
}
