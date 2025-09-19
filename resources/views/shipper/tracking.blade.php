@extends('layouts.unified')

@section('title', 'Theo dõi đơn hàng - Shipper')

@section('navigation')
    <a href="/shipper/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/shipper/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/shipper/history" class="text-gray-700 hover:text-red-600">Lịch sử</a>
    <a href="/shipper/tracking" class="text-red-600 font-medium">Tra cứu</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-orange-500 to-red-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Theo dõi đơn hàng của tôi</h1>
                    <p class="text-orange-100">Quản lý và cập nhật trạng thái các đơn hàng được giao</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-route text-4xl text-orange-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Today's Summary -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-list text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đơn hàng hôm nay</p>
                    <p class="text-2xl font-bold text-gray-900" id="todayOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg mr-4">
                    <i class="fas fa-shipping-fast text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đang giao</p>
                    <p class="text-2xl font-bold text-gray-900" id="deliveringOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-lg mr-4">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Hoàn thành</p>
                    <p class="text-2xl font-bold text-gray-900" id="completedOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-dollar-sign text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Thu nhập hôm nay</p>
                    <p class="text-2xl font-bold text-gray-900" id="todayEarning">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thao tác nhanh</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button onclick="updateLocation()" class="p-4 bg-blue-50 border border-blue-200 rounded-lg hover:bg-blue-100 transition-colors text-center">
                <i class="fas fa-map-marker-alt text-blue-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-blue-600">Cập nhật vị trí</p>
            </button>
            
            <button onclick="reportIssue()" class="p-4 bg-red-50 border border-red-200 rounded-lg hover:bg-red-100 transition-colors text-center">
                <i class="fas fa-exclamation-triangle text-red-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-red-600">Báo cáo sự cố</p>
            </button>
            
            <button onclick="takeBreak()" class="p-4 bg-yellow-50 border border-yellow-200 rounded-lg hover:bg-yellow-100 transition-colors text-center">
                <i class="fas fa-pause text-yellow-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-yellow-600">Tạm nghỉ</p>
            </button>
            
            <button onclick="endShift()" class="p-4 bg-gray-50 border border-gray-200 rounded-lg hover:bg-gray-100 transition-colors text-center">
                <i class="fas fa-sign-out-alt text-gray-600 text-2xl mb-2"></i>
                <p class="text-sm font-medium text-gray-600">Kết thúc ca</p>
            </button>
        </div>
    </div>

    <!-- Search Order -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tìm kiếm đơn hàng</h3>
        <form id="trackingForm" class="flex gap-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    id="trackingNumber" 
                    name="tracking_number"
                    placeholder="Nhập mã vận đơn"
                    class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500"
                    required
                >
            </div>
            <button 
                type="submit" 
                id="searchBtn"
                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors"
            >
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
        </form>
    </div>

    <!-- Current Orders -->
    <div class="bg-white shadow rounded-lg mb-6">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Đơn hàng hiện tại</h3>
            <div class="flex space-x-2">
                <button onclick="refreshOrders()" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-sync-alt mr-1"></i>Làm mới
                </button>
                <span id="lastUpdate" class="text-sm text-gray-500 py-2"></span>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Địa chỉ giao</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">COD</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="ordersList" class="bg-white divide-y divide-gray-200">
                    <!-- Orders will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Order Details Modal -->
    <div id="orderModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Chi tiết đơn hàng</h3>
                <button onclick="closeOrderModal()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <div id="orderDetails" class="space-y-4">
                <!-- Order details will be loaded here -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button onclick="closeOrderModal()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                    Đóng
                </button>
                <button onclick="callCustomer()" class="px-4 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-phone mr-2"></i>Gọi khách hàng
                </button>
                <button onclick="updateOrderStatus()" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-edit mr-2"></i>Cập nhật trạng thái
                </button>
            </div>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">Đang tải dữ liệu...</p>
    </div>
@endsection

@section('scripts')
<script>
    let currentOrder = null;
    let refreshInterval;

    document.addEventListener('DOMContentLoaded', function() {
        loadStatistics();
        loadCurrentOrders();
        
        const form = document.getElementById('trackingForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            if (trackingNumber) {
                searchOrder(trackingNumber);
            }
        });

        // Auto refresh every 60 seconds
        refreshInterval = setInterval(refreshOrders, 60000);
        updateLastUpdate();
    });
    
    function loadStatistics() {
        fetch('/shipper/api/statistics', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('todayOrders').textContent = data.data.today_orders || 0;
                document.getElementById('deliveringOrders').textContent = data.data.delivering_orders || 0;
                document.getElementById('completedOrders').textContent = data.data.completed_orders || 0;
                document.getElementById('todayEarning').textContent = (data.data.today_earning || 0).toLocaleString('vi-VN') + ' ₫';
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
    }
    
    function loadCurrentOrders() {
        showLoading();
        
        fetch('/shipper/api/current-orders', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success) {
                displayOrders(data.data);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error loading current orders:', error);
        });
    }
    
    function searchOrder(trackingNumber) {
        showLoading();
        
        fetch('/api/tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ tracking_number: trackingNumber })
        })
        .then(response => response.json())
        .then(data => {
            hideLoading();
            if (data.success && data.data.order) {
                displayOrders([data.data.order]);
            } else {
                displayOrders([]);
                alert('Không tìm thấy đơn hàng với mã này');
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error searching order:', error);
            alert('Có lỗi xảy ra khi tìm kiếm');
        });
    }
    
    function displayOrders(orders) {
        const tbody = document.getElementById('ordersList');
        
        if (!orders || orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                        Không có đơn hàng nào
                    </td>
                </tr>
            `;
            return;
        }
        
        const statusColors = {
            'assigned': 'bg-purple-100 text-purple-800',
            'pickup': 'bg-blue-100 text-blue-800',
            'picked_up': 'bg-indigo-100 text-indigo-800',
            'in_transit': 'bg-yellow-100 text-yellow-800',
            'delivering': 'bg-orange-100 text-orange-800',
            'delivered': 'bg-green-100 text-green-800',
            'failed': 'bg-red-100 text-red-800'
        };
        
        const statusTexts = {
            'assigned': 'Đã phân công',
            'pickup': 'Đang lấy hàng',
            'picked_up': 'Đã lấy hàng',
            'in_transit': 'Đang vận chuyển',
            'delivering': 'Đang giao hàng',
            'delivered': 'Đã giao',
            'failed': 'Thất bại'
        };
        
        tbody.innerHTML = orders.map(order => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                    <a href="#" onclick="viewOrderDetails('${order.id}')" class="hover:text-blue-800">
                        ${order.tracking_number}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div>
                        <p class="font-medium">${order.delivery_name}</p>
                        <p class="text-xs text-gray-500">${order.delivery_phone}</p>
                    </div>
                </td>
                <td class="px-6 py-4 text-sm text-gray-900">
                    <p class="max-w-xs truncate" title="${order.delivery_address}">
                        ${order.delivery_address}
                    </p>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">
                        ${statusTexts[order.status] || order.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${Number(order.cod_amount || 0).toLocaleString('vi-VN')} ₫
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewOrderDetails('${order.id}')" class="text-blue-600 hover:text-blue-900" title="Chi tiết">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="callCustomer('${order.delivery_phone}')" class="text-green-600 hover:text-green-900" title="Gọi khách hàng">
                            <i class="fas fa-phone"></i>
                        </button>
                        <button onclick="getDirections('${encodeURIComponent(order.delivery_address)}')" class="text-purple-600 hover:text-purple-900" title="Chỉ đường">
                            <i class="fas fa-directions"></i>
                        </button>
                        ${getStatusActionButton(order)}
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    function getStatusActionButton(order) {
        switch(order.status) {
            case 'assigned':
                return `<button onclick="updateStatus('${order.id}', 'pickup')" class="text-orange-600 hover:text-orange-900" title="Bắt đầu lấy hàng">
                    <i class="fas fa-play"></i>
                </button>`;
            case 'pickup':
                return `<button onclick="updateStatus('${order.id}', 'picked_up')" class="text-blue-600 hover:text-blue-900" title="Đã lấy hàng">
                    <i class="fas fa-check"></i>
                </button>`;
            case 'picked_up':
                return `<button onclick="updateStatus('${order.id}', 'in_transit')" class="text-indigo-600 hover:text-indigo-900" title="Bắt đầu vận chuyển">
                    <i class="fas fa-truck"></i>
                </button>`;
            case 'in_transit':
                return `<button onclick="updateStatus('${order.id}', 'delivering')" class="text-yellow-600 hover:text-yellow-900" title="Bắt đầu giao hàng">
                    <i class="fas fa-shipping-fast"></i>
                </button>`;
            case 'delivering':
                return `<button onclick="confirmDelivery('${order.id}')" class="text-green-600 hover:text-green-900" title="Hoàn thành giao hàng">
                    <i class="fas fa-check-circle"></i>
                </button>`;
            default:
                return '';
        }
    }
    
    function viewOrderDetails(orderId) {
        // This would typically fetch full order details
        // For now, show a simple modal
        currentOrder = orderId;
        document.getElementById('orderModal').classList.remove('hidden');
        
        // Load order details
        fetch(`/shipper/api/orders/${orderId}`, {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayOrderDetails(data.data);
            }
        })
        .catch(error => {
            console.error('Error loading order details:', error);
        });
    }
    
    function displayOrderDetails(order) {
        const container = document.getElementById('orderDetails');
        container.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Thông tin người gửi</h4>
                    <p><strong>Tên:</strong> ${order.pickup_name}</p>
                    <p><strong>SĐT:</strong> ${order.pickup_phone}</p>
                    <p><strong>Địa chỉ:</strong> ${order.pickup_address}</p>
                </div>
                <div>
                    <h4 class="font-semibold text-gray-900 mb-2">Thông tin người nhận</h4>
                    <p><strong>Tên:</strong> ${order.delivery_name}</p>
                    <p><strong>SĐT:</strong> ${order.delivery_phone}</p>
                    <p><strong>Địa chỉ:</strong> ${order.delivery_address}</p>
                </div>
            </div>
            <div class="border-t pt-4 mt-4">
                <h4 class="font-semibold text-gray-900 mb-2">Thông tin gói hàng</h4>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <p><strong>Loại:</strong> ${order.package_type || 'N/A'}</p>
                        <p><strong>Trọng lượng:</strong> ${order.weight || 'N/A'} kg</p>
                    </div>
                    <div>
                        <p><strong>COD:</strong> ${Number(order.cod_amount || 0).toLocaleString('vi-VN')} ₫</p>
                        <p><strong>Phí ship:</strong> ${Number(order.shipping_fee || 0).toLocaleString('vi-VN')} ₫</p>
                    </div>
                </div>
            </div>
        `;
    }
    
    function closeOrderModal() {
        document.getElementById('orderModal').classList.add('hidden');
        currentOrder = null;
    }
    
    function refreshOrders() {
        loadStatistics();
        loadCurrentOrders();
        updateLastUpdate();
    }
    
    function updateLastUpdate() {
        const now = new Date();
        document.getElementById('lastUpdate').textContent = 
            `Cập nhật lúc: ${now.toLocaleTimeString('vi-VN')}`;
    }
    
    function showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loadingState').classList.add('hidden');
    }
    
    // Shipper action functions
    function updateStatus(orderId, newStatus) {
        if (confirm('Xác nhận cập nhật trạng thái đơn hàng?')) {
            fetch(`/shipper/api/orders/${orderId}/update-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ status: newStatus })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã cập nhật trạng thái thành công!');
                    refreshOrders();
                } else {
                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể cập nhật'));
                }
            })
            .catch(error => {
                console.error('Error updating status:', error);
                alert('Có lỗi xảy ra khi cập nhật trạng thái!');
            });
        }
    }
    
    function confirmDelivery(orderId) {
        const reason = prompt('Xác nhận giao hàng thành công. Ghi chú (nếu có):');
        if (reason !== null) {
            updateStatus(orderId, 'delivered');
        }
    }
    
    function callCustomer(phone = null) {
        if (!phone && currentOrder) {
            // Get phone from current order
            const orderRow = document.querySelector(`tr:has(a[onclick*="${currentOrder}"])`);
            if (orderRow) {
                phone = orderRow.querySelector('td:nth-child(2) .text-xs').textContent;
            }
        }
        
        if (phone) {
            if (confirm(`Gọi cho khách hàng ${phone}?`)) {
                window.location.href = `tel:${phone}`;
            }
        } else {
            alert('Không có thông tin số điện thoại');
        }
    }
    
    function getDirections(address) {
        if (address) {
            const googleMapsUrl = `https://www.google.com/maps/dir/?api=1&destination=${address}`;
            window.open(googleMapsUrl, '_blank');
        } else {
            alert('Không có thông tin địa chỉ');
        }
    }
    
    function updateLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                
                fetch('/shipper/api/update-location', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    body: JSON.stringify({ latitude: lat, longitude: lng })
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        alert('Đã cập nhật vị trí thành công!');
                    } else {
                        alert('Không thể cập nhật vị trí');
                    }
                })
                .catch(error => {
                    console.error('Error updating location:', error);
                    alert('Có lỗi xảy ra khi cập nhật vị trí');
                });
            }, function(error) {
                alert('Không thể lấy vị trí hiện tại: ' + error.message);
            });
        } else {
            alert('Trình duyệt không hỗ trợ định vị GPS');
        }
    }
    
    function reportIssue() {
        const issue = prompt('Mô tả vấn đề bạn gặp phải:');
        if (issue) {
            alert('Đã gửi báo cáo sự cố. Chức năng này sẽ được phát triển đầy đủ sau!');
        }
    }
    
    function takeBreak() {
        if (confirm('Bạn muốn tạm dừng nhận đơn hàng mới?')) {
            alert('Đã chuyển sang trạng thái nghỉ. Chức năng này sẽ được phát triển!');
        }
    }
    
    function endShift() {
        if (confirm('Bạn muốn kết thúc ca làm việc hôm nay?')) {
            alert('Kết thúc ca làm việc. Chức năng này sẽ được phát triển!');
        }
    }
    
    function updateOrderStatus() {
        if (currentOrder) {
            const newStatus = prompt('Nhập trạng thái mới (pickup, picked_up, in_transit, delivering, delivered, failed):');
            if (newStatus) {
                updateStatus(currentOrder, newStatus);
                closeOrderModal();
            }
        }
    }
</script>
@endsection