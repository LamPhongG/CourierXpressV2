<?php
// Script kiểm tra database sau khi thiết lập

require_once __DIR__ . '/vendor/autoload.php';

try {
    // Create Laravel application instance
    $app = require_once __DIR__.'/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "🗄️ KIỂM TRA DATABASE COURIERXPRESS\n";
    echo "====================================\n\n";
    
    // Kiểm tra Users
    $totalUsers = \App\Models\User::count();
    $admins = \App\Models\User::where('role', 'admin')->get();
    $agents = \App\Models\User::where('role', 'agent')->get();
    $shippers = \App\Models\User::where('role', 'shipper')->get();
    $customers = \App\Models\User::where('role', 'user')->get();
    
    echo "👥 USERS ({$totalUsers} tổng cộng):\n";
    echo "   📋 Admins ({$admins->count()}):\n";
    foreach ($admins as $admin) {
        echo "      - {$admin->name} ({$admin->email})\n";
    }
    
    echo "   🏢 Agents ({$agents->count()}):\n";
    foreach ($agents as $agent) {
        echo "      - {$agent->name} ({$agent->email}) - {$agent->city}\n";
    }
    
    echo "   🚚 Shippers ({$shippers->count()}):\n";
    foreach ($shippers as $shipper) {
        $status = $shipper->is_online ? 'Online' : 'Offline';
        echo "      - {$shipper->name} ({$shipper->email}) - {$status}\n";
    }
    
    echo "   👤 Customers ({$customers->count()}):\n";
    foreach ($customers->take(5) as $customer) {
        echo "      - {$customer->name} ({$customer->email})\n";
    }
    if ($customers->count() > 5) {
        echo "      ... và " . ($customers->count() - 5) . " customers khác\n";
    }
    
    // Kiểm tra Services
    $services = \App\Models\ShippingService::all();
    echo "\n📦 SHIPPING SERVICES ({$services->count()}):\n";
    foreach ($services as $service) {
        $status = $service->is_active ? 'Active' : 'Inactive';
        echo "   - {$service->name} ({$service->code}) - {$status}\n";
        echo "     Giá cơ bản: " . number_format($service->base_price, 0, ',', '.') . " VND\n";
    }
    
    // Kiểm tra Orders
    $totalOrders = \App\Models\Order::count();
    $ordersByStatus = \App\Models\Order::select('status', \DB::raw('COUNT(*) as count'))
        ->groupBy('status')
        ->get();
        
    echo "\n📋 ORDERS ({$totalOrders} tổng cộng):\n";
    foreach ($ordersByStatus as $status) {
        echo "   - {$status->status}: {$status->count} đơn\n";
    }
    
    // Thống kê doanh thu
    $deliveredOrders = \App\Models\Order::where('status', 'delivered');
    $totalRevenue = $deliveredOrders->sum('total_fee');
    $avgRevenue = $deliveredOrders->avg('total_fee');
    
    echo "\n💰 THỐNG KÊ DOANH THU:\n";
    echo "   - Tổng doanh thu: " . number_format($totalRevenue, 0, ',', '.') . " VND\n";
    echo "   - Trung bình/đơn: " . number_format($avgRevenue, 0, ',', '.') . " VND\n";
    
    // Đơn hàng gần nhất
    $recentOrders = \App\Models\Order::orderBy('created_at', 'desc')->take(5)->get();
    echo "\n📋 ĐỚN HÀNG GẦN NHẤT:\n";
    foreach ($recentOrders as $order) {
        echo "   - {$order->tracking_number} - {$order->status} - " . number_format($order->total_fee, 0, ',', '.') . " VND\n";
    }
    
    echo "\n✅ DATABASE ĐÃ SẴN SÀNG!\n";
    echo "====================================\n";
    echo "🎯 TÀI KHOẢN TEST:\n";
    echo "   Admin: admin@courierxpress.com (123456)\n";
    echo "   Agent: agent@courierxpress.com (123456)\n";
    echo "   Shipper: shipper@courierxpress.com (123456)\n";
    echo "   Customer: customer@courierxpress.com (123456)\n\n";
    
    echo "🚀 NEXT STEPS:\n";
    echo "   1. Khởi động server: php artisan serve\n";
    echo "   2. Truy cập ứng dụng tại: http://localhost:8000\n";
    echo "   3. Test các chức năng với các tài khoản trên\n";
    
} catch (Exception $e) {
    echo "❌ Lỗi: " . $e->getMessage() . "\n";
}
?>