@extends('layouts.unified')

@section('title', 'User Dashboard - CourierXpress')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('navigation')
    <a href="/user/dashboard" class="text-red-600 font-medium">Dashboard</a>
    <a href="/user/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/user/create-order" class="text-gray-700 hover:text-red-600">Tạo đơn</a>
    <a href="/user/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
    <a href="/user/profile" class="text-gray-700 hover:text-red-600">Hồ sơ</a>
@endsection

@section('header_actions')
    <div class="relative">
        <button id="notificationBtn" class="text-gray-500 hover:text-gray-700 relative">
            <i class="fas fa-bell text-xl"></i>
            <span id="notificationBadge" class="absolute -top-1 -right-1 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
        </button>
    </div>
@endsection

@section('content')
        
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <h1 class="text-2xl font-bold mb-2">Chào mừng trở lại!</h1>
                <p class="text-blue-100">Quản lý đơn hàng và theo dõi giao hàng một cách dễ dàng</p>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-box text-blue-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Tổng đơn hàng</dt>
                                <dd class="text-lg font-medium text-gray-900" id="totalOrders">{{ $totalOrders ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Đang vận chuyển</dt>
                                <dd class="text-lg font-medium text-gray-900" id="inTransitOrders">{{ $inTransitOrders ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-check text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Đã giao</dt>
                                <dd class="text-lg font-medium text-gray-900" id="deliveredOrders">{{ $completedOrders ?? 0 }}</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="p-5">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <i class="fas fa-dollar-sign text-green-600 text-2xl"></i>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Tổng chi phí</dt>
                                <dd class="text-lg font-medium text-gray-900" id="totalSpent">{{ number_format($totalSpent ?? 0, 0, ',', '.') }} VNĐ</dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Thao tác nhanh</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
                    <a href="/user/create-order" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tạo đơn hàng
                    </a>
                    <a href="/tracking" class="bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Tra cứu đơn hàng
                    </a>
                    <a href="/user/orders" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-history mr-2"></i>
                        Lịch sử đơn hàng
                    </a>
                    <a href="/user/profile" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-user mr-2"></i>
                        Hồ sơ cá nhân
                    </a>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Đơn hàng gần đây</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Các đơn hàng mới nhất của bạn</p>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200" id="recentOrdersTable">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Mã đơn hàng
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Người nhận
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Trạng thái
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Giá trị
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Ngày tạo
                            </th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Thao tác
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($recentOrders) && $recentOrders->count() > 0)
                            @foreach($recentOrders as $order)
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                    <a href="/tracking?tracking_number={{ $order->tracking_number }}" class="hover:text-blue-800">
                                        {{ $order->tracking_number }}
                                    </a>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ Str::limit($order->delivery_address ?? 'N/A', 30) }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    @php
                                        $statusColors = [
                                            'pending' => 'bg-yellow-100 text-yellow-800',
                                            'confirmed' => 'bg-blue-100 text-blue-800',
                                            'picked_up' => 'bg-purple-100 text-purple-800',
                                            'in_transit' => 'bg-indigo-100 text-indigo-800',
                                            'out_for_delivery' => 'bg-orange-100 text-orange-800',
                                            'delivered' => 'bg-green-100 text-green-800',
                                            'failed' => 'bg-red-100 text-red-800',
                                            'cancelled' => 'bg-gray-100 text-gray-800'
                                        ];
                                        $statusTexts = [
                                            'pending' => 'Chờ xử lý',
                                            'confirmed' => 'Đã xác nhận',
                                            'picked_up' => 'Đã lấy hàng',
                                            'in_transit' => 'Đang vận chuyển',
                                            'out_for_delivery' => 'Đang giao hàng',
                                            'delivered' => 'Đã giao',
                                            'failed' => 'Thất bại',
                                            'cancelled' => 'Đã hủy'
                                        ];
                                    @endphp
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                        {{ $statusTexts[$order->status] ?? $order->status }}
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ number_format($order->total_fee ?? 0, 0, ',', '.') }} VNĐ
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <a href="/tracking?tracking_number={{ $order->tracking_number }}" class="text-indigo-600 hover:text-indigo-900">
                                        Theo dõi
                                    </a>
                                </td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                                    Chưa có đơn hàng nào
                                </td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Đơn hàng theo tháng</h3>
                <canvas id="ordersChart" width="400" height="200"></canvas>
            </div>
            
            <div class="bg-white p-6 rounded-lg shadow">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi phí theo tháng</h3>
                <canvas id="spendingChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Notifications -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Thông báo</h3>
                <div id="notificationsList" class="space-y-3">
                    <div class="text-center text-gray-500 py-4">
                        Đang tải thông báo...
                    </div>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script>
    // Dashboard đã sử dụng server-side rendering, chỉ cần initialize charts
    
    function initCharts(chartsData) {
        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        new Chart(ordersCtx, {
            type: 'line',
            data: {
                labels: chartsData.months || ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
                datasets: [{
                    label: 'Đơn hàng',
                    data: chartsData.orders || [2, 4, 1, 3, 5, 2],
                    borderColor: '#2563eb',
                    backgroundColor: 'rgba(37, 99, 235, 0.1)',
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Spending Chart
        const spendingCtx = document.getElementById('spendingChart').getContext('2d');
        new Chart(spendingCtx, {
            type: 'bar',
            data: {
                labels: chartsData.months || ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
                datasets: [{
                    label: 'Chi phí',
                    data: chartsData.spending || [120000, 230000, 85000, 180000, 310000, 150000],
                    backgroundColor: '#10b981',
                    borderColor: '#059669',
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        display: false
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return value.toLocaleString() + ' VNĐ';
                            }
                        }
                    }
                }
            }
        });
    }

    // Load data on page load
    document.addEventListener('DOMContentLoaded', function() {
        console.log('DOM loaded, using server-side data only...');
        
        // Only initialize charts, no data loading
        console.log('Initializing charts...');
        initCharts({});
        
        // Data đã được load từ server-side, không cần JavaScript load thêm
        console.log('Dashboard loaded with server-side data successfully!');
    });
</script>
@endsection
