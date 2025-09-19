@extends('layouts.unified')

@section('title', 'Tra cứu đơn hàng - CourierXpress')

@section('navigation')
    <a href="/user/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/user/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/user/create-order" class="text-gray-700 hover:text-red-600">Tạo đơn</a>
    <a href="/user/tracking" class="text-red-600 font-medium">Tra cứu</a>
    <a href="/user/profile" class="text-gray-700 hover:text-red-600">Hồ sơ</a>
@endsection

@section('head')
    <style>
        .search-section {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .tracking-step {
            position: relative;
        }
        .tracking-step::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 2px;
            height: 100%;
            background: #e5e7eb;
            z-index: 1;
        }
        .tracking-step:last-child::before {
            display: none;
        }
        .tracking-step.completed::before {
            background: #10b981;
        }
        .step-icon {
            position: relative;
            z-index: 2;
            width: 3rem;
            height: 3rem;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            border: 3px solid #e5e7eb;
            background: white;
        }
        .tracking-step.completed .step-icon {
            border-color: #10b981;
            background: #10b981;
            color: white;
        }
        .result-card {
            animation: fadeInUp 0.6s ease-out;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(30px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .pulse-animation {
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0.4);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(59, 130, 246, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(59, 130, 246, 0);
            }
        }
    </style>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Tra cứu đơn hàng</h1>
                    <p class="text-blue-100">Theo dõi tình trạng gói hàng của bạn một cách dễ dàng</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-search text-4xl text-blue-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- My Orders Quick Access -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Đơn hàng của tôi</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="myOrders">
            <!-- My orders will be loaded here -->
            <div class="text-center text-gray-500 py-4">
                <i class="fas fa-spinner fa-spin mb-2"></i>
                <p>Đang tải đơn hàng...</p>
            </div>
        </div>
    </div>

    <!-- Search Form -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tra cứu mã vận đơn</h3>
        <form id="trackingForm" class="flex gap-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    id="trackingNumber" 
                    name="tracking_number"
                    placeholder="Nhập mã vận đơn (ví dụ: CX000001)"
                    class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500"
                    required
                >
            </div>
            <button 
                type="submit" 
                id="searchBtn"
                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors pulse-animation"
            >
                <span id="searchText">Tra cứu</span>
            </button>
        </form>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">Đang tìm kiếm thông tin đơn hàng...</p>
    </div>

    <!-- Error State -->
    <div id="errorState" class="hidden bg-red-50 border border-red-200 rounded-lg p-6">
        <div class="flex items-center">
            <i class="fas fa-exclamation-triangle text-red-500 text-xl mr-3"></i>
            <div>
                <h3 class="text-red-800 font-medium">Không tìm thấy đơn hàng</h3>
                <p class="text-red-600" id="errorMessage">Vui lòng kiểm tra lại mã vận đơn và thử lại.</p>
            </div>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" class="hidden space-y-6">
        <!-- Order Info -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Sender & Receiver Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-user text-green-500 mr-2"></i>
                    Thông tin gửi/nhận
                </h3>
                <div id="senderReceiverInfo">
                    <!-- Details will be inserted here -->
                </div>
            </div>

            <!-- Package Info -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">
                    <i class="fas fa-box text-blue-500 mr-2"></i>
                    Thông tin gói hàng
                </h3>
                <div id="packageInfo">
                    <!-- Package details will be inserted here -->
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-lg p-6">
            <h3 class="text-xl font-semibold text-gray-900 mb-6">
                <i class="fas fa-route text-purple-500 mr-2"></i>
                Lịch sử vận chuyển
            </h3>
            <div id="timeline" class="space-y-8">
                <!-- Timeline steps will be inserted here -->
            </div>
        </div>

        <!-- Actions for User -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-cogs text-orange-500 mr-2"></i>
                Thao tác
            </h3>
            <div class="flex flex-wrap gap-3" id="orderActions">
                <!-- Action buttons will be inserted here -->
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadMyOrders();
        
        const form = document.getElementById('trackingForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            
            if (!trackingNumber) {
                alert('Vui lòng nhập mã vận đơn');
                return;
            }
            
            searchOrder(trackingNumber);
        });

        // Check if there's a tracking number in URL
        const urlParams = new URLSearchParams(window.location.search);
        const trackingNumber = urlParams.get('tracking_number');
        if (trackingNumber) {
            document.getElementById('trackingNumber').value = trackingNumber;
            searchOrder(trackingNumber);
        }
    });
    
    function loadMyOrders() {
        fetch('/user/api/recent-orders', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('myOrders');
            
            if (data.success && data.data.length > 0) {
                container.innerHTML = '';
                data.data.slice(0, 6).forEach(order => {
                    const orderCard = createOrderCard(order);
                    container.appendChild(orderCard);
                });
            } else {
                container.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-8">
                        <i class="fas fa-box-open text-4xl mb-4"></i>
                        <p>Bạn chưa có đơn hàng nào</p>
                        <a href="/user/create-order" class="text-blue-600 hover:text-blue-800 font-medium">Tạo đơn hàng đầu tiên →</a>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading my orders:', error);
            document.getElementById('myOrders').innerHTML = `
                <div class="col-span-full text-center text-red-500 py-4">
                    <p>Không thể tải đơn hàng</p>
                </div>
            `;
        });
    }

    function createOrderCard(order) {
        const div = document.createElement('div');
        div.className = 'border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow cursor-pointer';
        div.onclick = () => searchOrder(order.tracking_number);
        
        const statusColors = {
            'pending': 'bg-yellow-100 text-yellow-800',
            'confirmed': 'bg-blue-100 text-blue-800',
            'assigned': 'bg-purple-100 text-purple-800',
            'pickup': 'bg-indigo-100 text-indigo-800',
            'picked_up': 'bg-indigo-100 text-indigo-800',
            'in_transit': 'bg-indigo-100 text-indigo-800',
            'delivering': 'bg-orange-100 text-orange-800',
            'delivered': 'bg-green-100 text-green-800',
            'failed': 'bg-red-100 text-red-800',
            'cancelled': 'bg-gray-100 text-gray-800'
        };
        
        const statusTexts = {
            'pending': 'Chờ xử lý',
            'confirmed': 'Đã xác nhận',
            'assigned': 'Đã phân công',
            'pickup': 'Đang lấy hàng',
            'picked_up': 'Đã lấy hàng',
            'in_transit': 'Đang vận chuyển',
            'delivering': 'Đang giao hàng',
            'delivered': 'Đã giao',
            'failed': 'Thất bại',
            'cancelled': 'Đã hủy'
        };
        
        div.innerHTML = `
            <div class="flex justify-between items-start mb-2">
                <span class="text-sm font-medium text-blue-600">${order.tracking_number}</span>
                <span class="px-2 py-1 text-xs rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">
                    ${statusTexts[order.status] || order.status}
                </span>
            </div>
            <p class="text-sm text-gray-600 truncate">${order.delivery_address || 'Địa chỉ giao hàng'}</p>
            <p class="text-xs text-gray-500 mt-1">${new Date(order.created_at).toLocaleDateString('vi-VN')}</p>
        `;
        
        return div;
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
            
            if (data.success) {
                displayResults(data.data);
            } else {
                showError(data.message);
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error:', error);
            showError('Có lỗi xảy ra khi tìm kiếm. Vui lòng thử lại.');
        });
    }
    
    function showLoading() {
        document.getElementById('searchBtn').disabled = true;
        document.getElementById('searchText').textContent = 'Đang tìm...';
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('errorState').classList.add('hidden');
        document.getElementById('resultsSection').classList.add('hidden');
    }
    
    function hideLoading() {
        document.getElementById('searchBtn').disabled = false;
        document.getElementById('searchText').textContent = 'Tra cứu';
        document.getElementById('loadingState').classList.add('hidden');
    }
    
    function showError(message) {
        document.getElementById('errorMessage').textContent = message;
        document.getElementById('errorState').classList.remove('hidden');
    }
    
    function displayResults(data) {
        // Display sender/receiver info
        const senderReceiverInfo = document.getElementById('senderReceiverInfo');
        senderReceiverInfo.innerHTML = `
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div class="bg-green-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-green-800 mb-2">Người gửi</h4>
                    <p class="text-sm"><strong>Tên:</strong> ${data.order.pickup_name}</p>
                    <p class="text-sm"><strong>SĐT:</strong> ${data.order.pickup_phone}</p>
                    <p class="text-sm"><strong>Địa chỉ:</strong> ${data.order.pickup_address}</p>
                </div>
                <div class="bg-blue-50 p-4 rounded-lg">
                    <h4 class="font-semibold text-blue-800 mb-2">Người nhận</h4>
                    <p class="text-sm"><strong>Tên:</strong> ${data.order.delivery_name}</p>
                    <p class="text-sm"><strong>SĐT:</strong> ${data.order.delivery_phone}</p>
                    <p class="text-sm"><strong>Địa chỉ:</strong> ${data.order.delivery_address}</p>
                </div>
            </div>
        `;
        
        // Display package info
        const packageInfo = document.getElementById('packageInfo');
        packageInfo.innerHTML = `
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <p class="text-sm"><strong>Loại:</strong> ${data.order.package_type || 'Không xác định'}</p>
                    <p class="text-sm"><strong>Trọng lượng:</strong> ${data.order.weight || 'N/A'} kg</p>
                </div>
                <div>
                    <p class="text-sm"><strong>Giá trị COD:</strong> ${Number(data.order.cod_amount || 0).toLocaleString('vi-VN')} ₫</p>
                    <p class="text-sm"><strong>Phí vận chuyển:</strong> ${Number(data.order.shipping_fee || 0).toLocaleString('vi-VN')} ₫</p>
                </div>
            </div>
        `;
        
        // Display timeline
        displayTimeline(data.timeline);
        
        // Display user actions
        displayUserActions(data.order);
        
        document.getElementById('resultsSection').classList.remove('hidden');
    }
    
    function displayTimeline(timeline) {
        const timelineContainer = document.getElementById('timeline');
        
        if (!timeline || timeline.length === 0) {
            timelineContainer.innerHTML = `
                <div class="text-center text-gray-500 py-8">
                    <i class="fas fa-clock text-2xl mb-2"></i>
                    <p>Chưa có thông tin vận chuyển</p>
                </div>
            `;
            return;
        }
        
        timelineContainer.innerHTML = timeline.map((step, index) => `
            <div class="tracking-step ${step.completed ? 'completed' : ''} flex items-center">
                <div class="step-icon">
                    <i class="fas ${step.icon || 'fa-circle'} ${step.completed ? 'text-white' : 'text-gray-400'}"></i>
                </div>
                <div class="ml-4">
                    <h4 class="font-semibold text-gray-900">${step.status}</h4>
                    <p class="text-gray-600">${step.description}</p>
                    <p class="text-sm text-gray-500">${step.timestamp}</p>
                </div>
            </div>
        `).join('');
    }
    
    function displayUserActions(order) {
        const actionsContainer = document.getElementById('orderActions');
        const actions = [];
        
        // Always show view details
        actions.push(`
            <a href="/user/orders" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-list mr-2"></i>Xem tất cả đơn hàng
            </a>
        `);
        
        // Cancel button for pending orders
        if (order.status === 'pending') {
            actions.push(`
                <button onclick="cancelOrder('${order.id}')" class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 transition-colors">
                    <i class="fas fa-times mr-2"></i>Hủy đơn hàng
                </button>
            `);
        }
        
        // Rate button for delivered orders
        if (order.status === 'delivered') {
            actions.push(`
                <button onclick="rateOrder('${order.id}')" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors">
                    <i class="fas fa-star mr-2"></i>Đánh giá
                </button>
            `);
        }
        
        // Contact support
        actions.push(`
            <button onclick="contactSupport('${order.tracking_number}')" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700 transition-colors">
                <i class="fas fa-headset mr-2"></i>Hỗ trợ
            </button>
        `);
        
        actionsContainer.innerHTML = actions.join('');
    }
    
    function cancelOrder(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            // Implement cancel order
            alert('Chức năng hủy đơn hàng sẽ được thêm vào sau!');
        }
    }
    
    function rateOrder(orderId) {
        // Implement rating
        alert('Chức năng đánh giá sẽ được thêm vào sau!');
    }
    
    function contactSupport(trackingNumber) {
        alert(`Liên hệ hỗ trợ cho đơn hàng ${trackingNumber}. Chức năng này sẽ được phát triển sau!`);
    }
</script>
@endsection