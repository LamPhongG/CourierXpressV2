@extends('layouts.unified')

@section('title', 'Quản Lý Đơn Hàng - Shipper | CourierXpress')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        .status-assigned { background: #fef3c7; color: #92400e; }
        .status-pickup { background: #dbeafe; color: #1e40af; }
        .status-picked_up { background: #d1fae5; color: #059669; }
        .status-in_transit { background: #e0e7ff; color: #5b21b6; }
        .status-delivering { background: #fed7d7; color: #c53030; }
        .status-delivered { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .priority-urgent { border-left: 4px solid #ef4444; }
        .priority-high { border-left: 4px solid #f59e0b; }
        .priority-normal { border-left: 4px solid #10b981; }
    </style>
@endsection

@section('navigation')
    <a href="/shipper/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/shipper/orders" class="text-red-600 font-medium">Đơn hàng</a>
    <a href="/shipper/history" class="text-gray-700 hover:text-red-600">Lịch sử</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Quản Lý Đơn Hàng</h1>
                    <p class="text-green-100">Quản lý và cập nhật trạng thái các đơn hàng được giao</p>
                </div>
                <div>
                    <button onclick="refreshOrders()" class="bg-white text-green-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>
    </div>
        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Lọc Đơn Hàng</h2>
            <form id="filterForm" method="GET" action="{{ route('shipper.orders') }}">
                <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                    <select name="status" id="statusFilter" class="rounded-md border-gray-300">
                        @foreach($statusOptions as $key => $label)
                            <option value="{{ $key }}" {{ ($status ?? '') == $key ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>
                    <input type="date" name="date" id="dateFilter" class="rounded-md border-gray-300" value="{{ $date ?? '' }}">
                    <input type="text" name="area" id="areaFilter" placeholder="Khu vực/Địa chỉ..." class="rounded-md border-gray-300" value="{{ $area ?? '' }}">
                    <input type="text" name="tracking_number" id="trackingFilter" placeholder="Mã vận đơn..." class="rounded-md border-gray-300" value="{{ $trackingNumber ?? '' }}">
                    <div class="flex space-x-2">
                        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700 flex-1">
                            <i class="fas fa-search mr-2"></i>Tìm kiếm
                        </button>
                        <button type="button" onclick="clearFilters()" class="bg-gray-500 text-white px-4 py-2 rounded-md hover:bg-gray-600">
                            <i class="fas fa-times mr-2"></i>Xóa
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Quick Stats -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-clock text-yellow-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Chờ xử lý</p>
                        <p class="text-2xl font-semibold">{{ $quickStats['pending_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-truck text-blue-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Tổng đơn hàng</p>
                        <p class="text-2xl font-semibold">{{ $quickStats['total_orders'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-check text-green-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Hoàn thành hôm nay</p>
                        <p class="text-2xl font-semibold">{{ $quickStats['completed_today'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-money-bill text-purple-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Thu nhập hôm nay</p>
                        <p class="text-2xl font-semibold">{{ number_format($quickStats['earnings_today'] ?? 0, 0, ',', '.') }} ₫</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Orders List -->
        <div class="bg-white rounded-lg shadow-sm">
            <div class="p-6 border-b">
                <h2 class="text-lg font-semibold text-gray-900">Danh Sách Đơn Hàng</h2>
            </div>
            
            @if($orders && $orders->count() > 0)
                <div class="p-6 space-y-4">
                    @foreach($orders as $order)
                    <div class="border rounded-lg p-6 hover:shadow-md transition-shadow priority-{{ $order->package_type == 'express' ? 'urgent' : 'normal' }}">
                        <div class="flex justify-between items-start mb-4">
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-lg font-semibold text-blue-600">#{{ $order->tracking_number }}</h3>
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
                                    @if($order->package_type === 'express')
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            <i class="fas fa-bolt mr-1"></i>Express
                                        </span>
                                    @endif
                                </div>
                                
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-1">
                                            <i class="fas fa-arrow-up text-green-500 mr-2"></i>Lấy hàng
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $order->pickup_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->pickup_phone }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->pickup_address }}, {{ $order->pickup_district }}</p>
                                    </div>
                                    <div>
                                        <h4 class="font-medium text-gray-900 mb-1">
                                            <i class="fas fa-arrow-down text-red-500 mr-2"></i>Giao hàng
                                        </h4>
                                        <p class="text-sm text-gray-600">{{ $order->delivery_name }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->delivery_phone }}</p>
                                        <p class="text-sm text-gray-500">{{ $order->delivery_address }}, {{ $order->delivery_district }}</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-center justify-between text-sm text-gray-500">
                                    <span class="text-sm text-gray-500"><i class="fas fa-user mr-1"></i>{{ $order->user->name ?? 'Khách hàng' }}</span>
                                    <span><i class="fas fa-weight mr-1"></i>{{ $order->weight ?? 0 }}kg</span>
                                    <span><i class="fas fa-clock mr-1"></i>{{ $order->assigned_at ? $order->assigned_at->format('d/m/Y H:i') : 'Chưa phân công' }}</span>
                                </div>
                            </div>
                            
                            <div class="text-right ml-6">
                                <p class="text-lg font-semibold text-gray-900 mb-1">{{ number_format($order->cod_amount ?? 0, 0, ',', '.') }} ₫</p>
                                <p class="text-sm text-gray-500">COD</p>
                                <p class="text-sm text-green-600">Phí: {{ number_format($order->shipping_fee ?? 0, 0, ',', '.') }} ₫</p>
                                
                                <div class="mt-3 space-y-2">
                                    @switch($order->status)
                                        @case('assigned')
                                            <button onclick="startPickup('{{ $order->id }}')"
                                                    class="w-full text-xs bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-play mr-1"></i>Bắt đầu lấy
                                            </button>
                                            @break
                                        @case('pickup')
                                            <button onclick="confirmPickedUp('{{ $order->id }}')"
                                                    class="w-full text-xs bg-green-500 hover:bg-green-600 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-check mr-1"></i>Đã lấy xong
                                            </button>
                                            @break
                                        @case('picked_up')
                                            <button onclick="startTransit('{{ $order->id }}')"
                                                    class="w-full text-xs bg-purple-500 hover:bg-purple-600 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-truck mr-1"></i>Vận chuyển
                                            </button>
                                            @break
                                        @case('in_transit')
                                            <button onclick="startDelivery('{{ $order->id }}')"
                                                    class="w-full text-xs bg-orange-500 hover:bg-orange-600 text-white px-3 py-1 rounded mb-1 transition-colors">
                                                <i class="fas fa-shipping-fast mr-1"></i>Bắt đầu giao
                                            </button>
                                            <button onclick="confirmDelivered('{{ $order->id }}')"
                                                    class="w-full text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-check-circle mr-1"></i>Hoàn thành
                                            </button>
                                            @break
                                        @case('delivering')
                                            <button onclick="confirmDelivered('{{ $order->id }}')"
                                                    class="w-full text-xs bg-green-600 hover:bg-green-700 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-check-circle mr-1"></i>Hoàn thành
                                            </button>
                                            @break
                                        @case('delivered')
                                            <span class="w-full text-xs text-green-600 px-3 py-1 text-center block">
                                                <i class="fas fa-check-circle mr-1"></i>Đã hoàn thành
                                            </span>
                                            @break
                                        @case('failed')
                                            <button onclick="retryOrder('{{ $order->id }}')"
                                                    class="w-full text-xs bg-yellow-500 hover:bg-yellow-600 text-white px-3 py-1 rounded transition-colors">
                                                <i class="fas fa-redo mr-1"></i>Thử lại
                                            </button>
                                            @break
                                    @endswitch
                                    
                                    <button onclick="showOrderDetails('{{ $order->id }}')"
                                            class="w-full text-xs bg-gray-500 hover:bg-gray-600 text-white px-3 py-1 rounded transition-colors">
                                        <i class="fas fa-info-circle mr-1"></i>Chi tiết
                                    </button>
                                    
                                    @if(!in_array($order->status, ['delivered', 'failed']))
                                        <button onclick="openStatusModal('{{ $order->id }}', '{{ $order->status }}')"
                                                class="w-full text-xs bg-indigo-500 hover:bg-indigo-600 text-white px-3 py-1 rounded transition-colors">
                                            <i class="fas fa-edit mr-1"></i>Cập nhật
                                        </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
                
                <!-- Pagination -->
                @if($orders->hasPages())
                    <div class="px-6 py-4 border-t">
                        {{ $orders->links() }}
                    </div>
                @endif
            @else
                <div class="p-8 text-center">
                    <i class="fas fa-inbox text-gray-400 text-4xl mb-4"></i>
                    <h3 class="text-lg font-medium text-gray-900 mb-2">Không có đơn hàng nào</h3>
                    <p class="text-gray-500">Hiện tại không có đơn hàng phù hợp với bộ lọc của bạn.</p>
                </div>
            @endif
        </div>
    <!-- Status Update Modal -->
    <div id="statusUpdateModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen px-4">
            <div class="bg-white rounded-lg max-w-md w-full">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-medium text-gray-900">Cập Nhật Trạng Thái</h3>
                </div>
                <div class="p-6">
                    <form id="statusUpdateForm">
                        <input type="hidden" id="updateOrderId">
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái mới</label>
                            <select id="newStatus" required class="w-full rounded-md border-gray-300">
                                <option value="">Chọn trạng thái</option>
                                <option value="pickup">Đang lấy hàng</option>
                                <option value="picked_up">Đã lấy hàng</option>
                                <option value="in_transit">Đang vận chuyển</option>
                                <option value="delivering">Đang giao hàng</option>
                                <option value="delivered">Đã giao thành công</option>
                                <option value="failed">Giao hàng thất bại</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Ghi chú</label>
                            <textarea id="statusNote" rows="3" class="w-full rounded-md border-gray-300"></textarea>
                        </div>
                        <div class="flex justify-end space-x-3">
                            <button type="button" onclick="closeStatusModal()" class="px-4 py-2 bg-gray-300 rounded-md">Hủy</button>
                            <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md">Cập nhật</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<script>
// ===== JAVASCRIPT FOR ORDER STATUS UPDATES =====
// Simple function declarations without complex Blade integration

// Status update functions  
function startPickup(orderId) {
    console.log('🟡 Starting pickup for order:', orderId);
    if (confirm('Xác nhận bắt đầu lấy hàng?')) {
        updateOrderStatus(orderId, 'pickup', 'Bắt đầu quá trình lấy hàng');
    }
}

function confirmPickedUp(orderId) {
    console.log('🟢 Confirming picked up for order:', orderId);
    if (confirm('Xác nhận đã lấy hàng thành công?')) {
        updateOrderStatus(orderId, 'picked_up', 'Đã lấy hàng thành công');
    }
}

function startTransit(orderId) {
    console.log('🚛 Starting transit for order:', orderId);
    if (confirm('Bắt đầu vận chuyển đơn hàng này?')) {
        updateOrderStatus(orderId, 'in_transit', 'Bắt đầu vận chuyển');
    }
}

function startDelivery(orderId) {
    console.log('🚚 Starting delivery for order:', orderId);
    if (confirm('Bắt đầu giao hàng?')) {
        updateOrderStatus(orderId, 'delivering', 'Bắt đầu giao hàng đến khách hàng');
    }
}

function confirmDelivered(orderId) {
    console.log('✅ Confirming delivered for order:', orderId);
    if (confirm('Xác nhận đã giao hàng thành công?')) {
        updateOrderStatus(orderId, 'delivered', 'Giao hàng thành công');
    }
}

function retryOrder(orderId) {
    console.log('🔄 Retrying order:', orderId);
    if (confirm('Thử lại đơn hàng này?')) {
        updateOrderStatus(orderId, 'assigned', 'Thử lại đơn hàng');
    }
}

function showOrderDetails(orderId) {
    console.log('📋 Showing order details for:', orderId);
    alert('Tính năng xem chi tiết đang được phát triển. Đơn hàng ID: ' + orderId);
}

function clearFilters() {
    document.getElementById('statusFilter').value = 'all';
    document.getElementById('dateFilter').value = '';
    document.getElementById('areaFilter').value = '';
    document.getElementById('trackingFilter').value = '';
    window.location.href = '/shipper/orders';
}

function openStatusModal(orderId, currentStatus) {
    document.getElementById('updateOrderId').value = orderId;
    document.getElementById('newStatus').value = '';
    document.getElementById('statusNote').value = '';
    document.getElementById('statusUpdateModal').classList.remove('hidden');
}

function closeStatusModal() {
    document.getElementById('statusUpdateModal').classList.add('hidden');
}

// Main update function
async function updateOrderStatus(orderId, newStatus, note = '') {
    console.log('=== 🔄 BẮT ĐẦU CẬP NHẬT TRẠNG THÁI ===');
    console.log('📦 Order ID:', orderId);
    console.log('📝 New Status:', newStatus);
    console.log('💬 Note:', note);
    
    try {
        // Show loading state
        const button = event?.target;
        const originalText = button?.innerHTML;
        if (button) {
            button.disabled = true;
            button.innerHTML = '⏳ Đang xử lý...';
        }
        
        // Check CSRF token
        const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;
        console.log('🔒 CSRF Token:', csrfToken ? '✅ OK' : '❌ MISSING');
        
        if (!csrfToken) {
            throw new Error('❌ CSRF token không tìm thấy');
        }
        
        const requestData = {
            status: newStatus,
            note: note,
            latitude: null,
            longitude: null
        };
        
        console.log('📡 Request Data:', requestData);
        
        const url = `/shipper/api/orders/${orderId}/update-status`;
        console.log('🌐 Request URL:', url);
        
        const response = await fetch(url, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': csrfToken,
                'Content-Type': 'application/json',
                'Accept': 'application/json'
            },
            body: JSON.stringify(requestData)
        });
        
        console.log('📊 Response Status:', response.status);
        console.log('✅ Response OK:', response.ok);
        
        if (!response.ok) {
            const errorText = await response.text();
            console.error('❌ Response Error Text:', errorText);
            throw new Error(`HTTP ${response.status}: ${response.statusText}`);
        }
        
        const result = await response.json();
        console.log('📋 Response Data:', result);
        
        if (result.success) {
            const successMessage = `✅ Cập nhật thành công: ${newStatus}`;
            alert(successMessage);
            console.log('=== ✅ CẬP NHẬT THÀNH CÔNG ===');
            
            // Reload page to show updated data
            setTimeout(() => {
                window.location.reload();
            }, 1000);
            
            return true;
        } else {
            throw new Error(result.message || '❌ Không thể cập nhật trạng thái');
        }
    } catch (error) {
        console.error('=== ❌ LỖI CẬP NHẬT TRẠNG THÁI ===');
        console.error('Error:', error);
        
        let errorMessage = '❌ Có lỗi xảy ra khi cập nhật trạng thái';
        if (error.message) {
            errorMessage = error.message;
        }
        
        alert(errorMessage);
        
        // Restore button state
        if (button && originalText) {
            button.disabled = false;
            button.innerHTML = originalText;
        }
        
        return false;
    }
}

// Log that all functions are ready
console.log('🎯 ALL FUNCTIONS LOADED:', {
    startPickup: typeof startPickup,
    confirmPickedUp: typeof confirmPickedUp,
    startTransit: typeof startTransit,
    startDelivery: typeof startDelivery,
    confirmDelivered: typeof confirmDelivered,
    updateOrderStatus: typeof updateOrderStatus
});

console.log('🚀 Orders page JavaScript loaded successfully!');

// Test function availability immediately
document.addEventListener('DOMContentLoaded', function() {
    console.log('🔍 Checking function availability...');
    console.log('startPickup:', typeof startPickup);
    console.log('confirmDelivered:', typeof confirmDelivered);
    console.log('startDelivery:', typeof startDelivery);
    
    if (typeof startPickup === 'function' && typeof confirmDelivered === 'function') {
        console.log('✅ All functions are available!');
    } else {
        console.error('❌ Some functions are missing!');
    }
});
</script>