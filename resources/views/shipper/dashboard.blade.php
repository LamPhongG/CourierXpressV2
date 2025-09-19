@extends('layouts.unified')

@section('title', 'Shipper Dashboard - CourierXpress')

@section('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('navigation')
    <a href="/shipper/dashboard" class="text-red-600 font-medium">Dashboard</a>
    <a href="/shipper/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/shipper/history" class="text-gray-700 hover:text-red-600">Lịch sử</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
    <!-- Welcome Section -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
            <h1 class="text-2xl font-bold mb-2">Shipper Dashboard</h1>
            <p class="text-green-100">Chào mừng, {{ auth()->user()->name }}! Quản lý giao hàng và cập nhật trạng thái đơn hàng</p>
            <p class="text-sm text-green-100 mt-2">{{ auth()->user()->email }} • Shipper • {{ auth()->user()->city ?? 'Chưa cập nhật' }}</p>
            <div class="mt-4 flex items-center space-x-4">
                <div class="flex items-center space-x-2">
                    <i class="fas fa-clock"></i>
                    <span id="currentTime">--:--</span>
                </div>
                <div class="flex items-center space-x-2">
                    <i class="fas fa-map-marker-alt"></i>
                    <span id="currentLocation">Đang lấy vị trí...</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-tasks text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đơn cần giao</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $pendingOrders ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-route text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đang vận chuyển</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $inProgressOrders ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Hoàn thành hôm nay</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ $completedToday ?? 0 }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-star text-yellow-500 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đánh giá</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($averageRating ?? 5.0, 1) }}★</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders and Actions Section -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-8">
        <div class="lg:col-span-2 bg-white shadow overflow-hidden sm:rounded-md">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Đơn hàng hiện tại</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Đơn hàng được giao cho bạn</p>
                </div>
                <a href="/shipper/orders" class="text-green-600 hover:text-green-500 text-sm font-medium">
                    Xem tất cả →
                </a>
            </div>
            <div class="border-t border-gray-200">
                <div id="currentOrdersList" class="divide-y divide-gray-200">
                    @if($currentOrders && $currentOrders->count() > 0)
                        @foreach($currentOrders->take(5) as $order)
                        <div class="px-4 py-4 hover:bg-gray-50 border-b border-gray-100">
                            <div class="flex items-center justify-between">
                                <div class="flex-1">
                                    <h4 class="text-sm font-medium text-gray-900">#{{ $order->tracking_number }}</h4>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-arrow-up text-green-500 mr-1"></i>
                                        {{ $order->pickup_address }}
                                    </p>
                                    <p class="text-sm text-gray-600">
                                        <i class="fas fa-arrow-down text-red-500 mr-1"></i>
                                        {{ $order->delivery_address }}
                                    </p>
                                    <div class="flex items-center justify-between mt-2">
                                        @php
                                            $statusColors = [
                                                'assigned' => 'bg-yellow-100 text-yellow-800',
                                                'pickup' => 'bg-blue-100 text-blue-800',
                                                'picked_up' => 'bg-purple-100 text-purple-800',
                                                'in_transit' => 'bg-indigo-100 text-indigo-800',
                                                'delivering' => 'bg-orange-100 text-orange-800',
                                                'delivered' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800'
                                            ];
                                            $statusTexts = [
                                                'assigned' => 'Đã phân công',
                                                'pickup' => 'Đang lấy hàng',
                                                'picked_up' => 'Đã lấy hàng',
                                                'in_transit' => 'Đang vận chuyển',
                                                'delivering' => 'Đang giao hàng',
                                                'delivered' => 'Đã giao',
                                                'failed' => 'Thất bại'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusTexts[$order->status] ?? $order->status }}
                                        </span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <p class="text-sm font-medium text-gray-900">{{ number_format($order->cod_amount ?? 0, 0, ',', '.') }} ₫</p>
                                    <p class="text-xs text-gray-500">COD</p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    @else
                        <div class="px-4 py-4 text-center text-gray-500">
                            <i class="fas fa-inbox text-3xl mb-2"></i>
                            <p>Hiện tại không có đơn hàng nào</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Thao tác nhanh</h3>
                <div class="space-y-3">
                    <button onclick="updateLocation()" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-map-marker-alt mr-2"></i>
                        Cập nhật vị trí
                    </button>
                    <a href="/shipper/orders" class="block w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-list mr-2"></i>
                        Xem đơn hàng
                    </a>
                    <a href="/tracking" class="block w-full bg-purple-600 hover:bg-purple-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-search mr-2"></i>
                        Tra cứu đơn hàng
                    </a>
                    <button onclick="toggleStatus()" class="w-full bg-orange-600 hover:bg-orange-700 text-white font-bold py-3 px-4 rounded-lg text-center transition-colors">
                        <i class="fas fa-power-off mr-2"></i>
                        <span id="statusToggleText">Đang rảnh</span>
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Activity -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Hoạt động gần đây</h3>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoạt động</th>
                        <th class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200" id="activityTable">
                    @if($recentActivities && $recentActivities->count() > 0)
                        @foreach($recentActivities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $activity['time'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">{{ $activity['order_id'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">{{ $activity['action'] }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'assigned' => 'bg-yellow-100 text-yellow-800',
                                        'pickup' => 'bg-blue-100 text-blue-800',
                                        'picked_up' => 'bg-purple-100 text-purple-800',
                                        'in_transit' => 'bg-indigo-100 text-indigo-800',
                                        'delivering' => 'bg-orange-100 text-orange-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800'
                                    ];
                                    $statusTexts = [
                                        'assigned' => 'Đã phân công',
                                        'pickup' => 'Đang lấy hàng',
                                        'picked_up' => 'Đã lấy hàng',
                                        'in_transit' => 'Đang vận chuyển',
                                        'delivering' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao',
                                        'failed' => 'Thất bại'
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$activity['status']] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusTexts[$activity['status']] ?? $activity['status'] }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="4" class="px-6 py-4 text-center text-gray-500">
                                <i class="fas fa-clock text-2xl mb-2"></i>
                                <p>Chưa có hoạt động nào hôm nay</p>
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let currentPosition = null;
    let shipperStatus = 'active';
    
    // Laravel session authentication check
    @if(auth()->check())
    const userData = {
        id: {{ auth()->user()->id }},
        name: "{{ auth()->user()->name }}",
        email: "{{ auth()->user()->email }}",
        role: "{{ auth()->user()->role }}",
        status: "{{ auth()->user()->status ?? 'active' }}"
    };
    
    localStorage.setItem('user_data', JSON.stringify(userData));
    localStorage.setItem('auth_token', 'laravel_session_' + userData.id);
    
    document.addEventListener('DOMContentLoaded', function() {
        updateCurrentTime();
        setInterval(updateCurrentTime, 1000);
        getCurrentLocation();
        // Auto refresh page every 30 seconds for updated data
        setInterval(function() {
            window.location.reload();
        }, 30000);
    });
    @else
    window.location.href = '/login';
    @endif
    
    // Update current time
    function updateCurrentTime() {
        const now = new Date();
        const timeString = now.toLocaleTimeString('vi-VN', {
            hour: '2-digit',
            minute: '2-digit',
            second: '2-digit'
        });
        const element = document.getElementById('currentTime');
        if (element) {
            element.textContent = timeString;
        }
    }
    
    // Utility functions
    function getStatusColor(status) {
        const colors = {
            'assigned': 'bg-yellow-100 text-yellow-800',
            'pickup': 'bg-blue-100 text-blue-800',
            'picked_up': 'bg-purple-100 text-purple-800',
            'in_transit': 'bg-indigo-100 text-indigo-800',
            'delivering': 'bg-orange-100 text-orange-800',
            'delivered': 'bg-green-100 text-green-800',
            'failed': 'bg-red-100 text-red-800'
        };
        return colors[status] || 'bg-gray-100 text-gray-800';
    }

    function getStatusText(status) {
        const texts = {
            'assigned': 'Đã phân công',
            'pickup': 'Đang lấy hàng',
            'picked_up': 'Đã lấy hàng',
            'in_transit': 'Đang vận chuyển',
            'delivering': 'Đang giao hàng',
            'delivered': 'Đã giao',
            'failed': 'Thất bại'
        };
        return texts[status] || 'Không xác định';
    }
    
    // Status buttons removed - status updates only available in orders page
    function getStatusButtons(order) {
        // No buttons - read-only status display
        return '';
    }
    
    function formatCurrency(amount) {
        return new Intl.NumberFormat('vi-VN', { 
            style: 'currency', 
            currency: 'VND' 
        }).format(amount);
    }
    
    function showOrderDetails(orderId) {
        window.location.href = `/shipper/orders?highlight=${orderId}`;
    }
    

    function updateLocation() {
        if (navigator.geolocation) {
            // Show loading state
            document.getElementById('currentLocation').textContent = 'Đang lấy vị trí...';
            
            navigator.geolocation.getCurrentPosition(
                async function(position) {
                    currentPosition = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    
                    // Update display
                    document.getElementById('currentLocation').textContent = 
                        `${currentPosition.latitude.toFixed(6)}, ${currentPosition.longitude.toFixed(6)}`;
                    
                    // Send to server
                    const success = await updateLocationOnServer(currentPosition.latitude, currentPosition.longitude);
                    
                    if (success) {
                        alert('Vị trí đã được cập nhật thành công!');
                    } else {
                        alert('Đã lấy vị trí nhưng không thể cập nhật lên server.');
                    }
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    document.getElementById('currentLocation').textContent = 'Không thể lấy vị trí';
                    
                    let errorMsg = 'Không thể lấy vị trí. ';
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg += 'Vui lòng cho phép truy cập vị trí.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg += 'Thông tin vị trí không khả dụng.';
                            break;
                        case error.TIMEOUT:
                            errorMsg += 'Hết thời gian chờ lấy vị trí.';
                            break;
                        default:
                            errorMsg += 'Lỗi không xác định.';
                            break;
                    }
                    alert(errorMsg);
                },
                {
                    enableHighAccuracy: true,
                    timeout: 15000,
                    maximumAge: 60000
                }
            );
        } else {
            alert('Trình duyệt không hỗ trợ định vị GPS.');
        }
    }
    
    function toggleStatus() {
        shipperStatus = shipperStatus === 'active' ? 'offline' : 'active';
        const toggleText = document.getElementById('statusToggleText');
        if (toggleText) {
            toggleText.textContent = shipperStatus === 'active' ? 'Đang rảnh' : 'Đang bận';
        }
        alert(`Trạng thái đã chuyển thành: ${shipperStatus === 'active' ? 'Đang rảnh' : 'Đang bận'}`);
    }
    
    // Load recent activities
    async function loadRecentActivities() {
        // This function is now handled by server-side rendering
        // Keeping for API compatibility if needed
    }
    
    // Display recent activities
    function displayRecentActivities(activities) {
        // This function is now handled by server-side rendering
        // Keeping for API compatibility if needed
    }
    
    // Get current location
    function getCurrentLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    currentPosition = {
                        latitude: position.coords.latitude,
                        longitude: position.coords.longitude
                    };
                    
                    // Update display
                    document.getElementById('currentLocation').textContent = 
                        `${currentPosition.latitude.toFixed(6)}, ${currentPosition.longitude.toFixed(6)}`;
                    
                    // Send to server
                    updateLocationOnServer(currentPosition.latitude, currentPosition.longitude);
                },
                function(error) {
                    console.error('Geolocation error:', error);
                    document.getElementById('currentLocation').textContent = 'Không thể lấy vị trí';
                },
                {
                    enableHighAccuracy: true,
                    timeout: 10000,
                    maximumAge: 300000
                }
            );
        } else {
            console.error('Geolocation is not supported');
            document.getElementById('currentLocation').textContent = 'Không hỗ trợ GPS';
        }
    }
    
    // Update location on server
    async function updateLocationOnServer(latitude, longitude) {
        try {
            const response = await fetch('/shipper/api/location/update', {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    latitude: latitude,
                    longitude: longitude
                })
            });
            
            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    console.log('Vị trí đã được cập nhật trên server');
                    return true;
                } else {
                    console.error('Lỗi cập nhật vị trí:', result.message);
                    return false;
                }
            } else {
                console.error('Lỗi API cập nhật vị trí');
                return false;
            }
        } catch (error) {
            console.error('Lỗi kết nối khi cập nhật vị trí:', error);
            return false;
        }
    }
</script>
@endsection