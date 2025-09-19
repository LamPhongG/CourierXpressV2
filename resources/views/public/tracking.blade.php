@extends('layouts.unified')

@section('title', 'Tra cứu đơn hàng - CourierXpress')

@section('navigation')
    <a href="/" class="text-gray-700 hover:text-red-600">Trang chủ</a>
    <a href="/tracking" class="text-red-600 font-medium">Tra cứu</a>
    <a href="/login" class="text-gray-700 hover:text-red-600">Đăng nhập</a>
    <a href="/register" class="text-gray-700 hover:text-red-600">Đăng ký</a>
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
    <!-- Search Section -->
    <div class="search-section py-16 mb-8">
        <div class="text-center text-white">
            <h1 class="text-4xl font-bold mb-4">Tra cứu đơn hàng</h1>
            <p class="text-xl text-blue-100 mb-8">Nhập mã vận đơn để theo dõi tình trạng gói hàng của bạn</p>
            
            <!-- Search Form -->
            <div class="max-w-xl mx-auto">
                <form id="trackingForm" class="flex gap-3">
                    <div class="flex-1">
                        <input 
                            type="text" 
                            id="trackingNumber" 
                            name="tracking_number"
                            placeholder="Nhập mã vận đơn (ví dụ: CX000001)"
                            class="w-full px-4 py-3 text-gray-900 bg-white rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-transparent"
                            required
                            value="{{ $trackingNumber ?? '' }}"
                        >
                    </div>
                    <button 
                        type="submit" 
                        id="searchBtn"
                        class="px-6 py-3 bg-white text-blue-600 font-bold rounded-lg hover:bg-gray-100 focus:ring-4 focus:ring-blue-300 transition-colors pulse-animation"
                    >
                        <span id="searchText">Tra cứu</span>
                    </button>
                </form>
            </div>

            <!-- Quick Search Examples -->
            <div class="mt-8">
                <p class="text-blue-100 mb-4">Ví dụ mã vận đơn:</p>
                <div class="flex flex-wrap justify-center gap-2">
                    <button onclick="quickSearch('CX000001')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000001
                    </button>
                    <button onclick="quickSearch('CX000002')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000002
                    </button>
                    <button onclick="quickSearch('CX000003')" class="px-3 py-1 bg-blue-500 bg-opacity-50 rounded-full text-sm hover:bg-opacity-70 transition-colors">
                        CX000003
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Login Prompt -->
    <div class="bg-blue-50 border border-blue-200 rounded-lg p-6 mb-6">
        <div class="flex items-center">
            <i class="fas fa-info-circle text-blue-500 text-xl mr-3"></i>
            <div>
                <h3 class="text-blue-800 font-medium">Đăng nhập để có trải nghiệm tốt hơn</h3>
                <p class="text-blue-600">Đăng nhập để xem lịch sử đơn hàng, quản lý profile và nhiều tính năng khác.</p>
                <div class="mt-2">
                    <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium mr-4">Đăng nhập →</a>
                    <a href="/register" class="text-blue-600 hover:text-blue-800 font-medium">Đăng ký tài khoản →</a>
                </div>
            </div>
        </div>
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

        <!-- Contact Support -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">
                <i class="fas fa-headset text-orange-500 mr-2"></i>
                Cần hỗ trợ?
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-phone text-blue-500 text-2xl mb-2"></i>
                    <p class="font-medium">Hotline</p>
                    <p class="text-sm text-gray-600">1900 1234</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fas fa-envelope text-green-500 text-2xl mb-2"></i>
                    <p class="font-medium">Email</p>
                    <p class="text-sm text-gray-600">support@courierxpress.com</p>
                </div>
                <div class="text-center p-4 bg-gray-50 rounded-lg">
                    <i class="fab fa-facebook-messenger text-purple-500 text-2xl mb-2"></i>
                    <p class="font-medium">Chat</p>
                    <p class="text-sm text-gray-600">Facebook Messenger</p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('trackingForm');
        const searchText = document.getElementById('searchText');
        const loadingState = document.getElementById('loadingState');
        const errorState = document.getElementById('errorState');
        const resultsSection = document.getElementById('resultsSection');
        
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            
            if (!trackingNumber) {
                alert('Vui lòng nhập mã vận đơn');
                return;
            }
            
            searchOrder(trackingNumber);
        });

        // Auto search if tracking number is provided in URL
        const trackingNumber = document.getElementById('trackingNumber').value;
        if (trackingNumber) {
            searchOrder(trackingNumber);
        }
    });
    
    function quickSearch(trackingNumber) {
        document.getElementById('trackingNumber').value = trackingNumber;
        searchOrder(trackingNumber);
    }
    
    function searchOrder(trackingNumber) {
        // Show loading state
        showLoading();
        
        // Make API request
        fetch('/api/tracking', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
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
        
        document.getElementById('resultsSection').classList.remove('hidden');
        
        // Update URL without page reload
        const newUrl = new URL(window.location);
        newUrl.searchParams.set('tracking_number', data.order.tracking_number);
        window.history.pushState({}, '', newUrl);
    }
    
    function displayTimeline(timeline) {
        const timelineContainer = document.getElementById('timeline');
        
        if (!timeline || timeline.length === 0) {
            // Create default timeline based on order status
            timeline = [
                {
                    status: 'Đặt hàng thành công',
                    description: 'Đơn hàng đã được tạo và đang chờ xử lý',
                    timestamp: new Date().toLocaleString('vi-VN'),
                    completed: true,
                    icon: 'fa-check'
                },
                {
                    status: 'Xác nhận đơn hàng',
                    description: 'Đơn hàng đã được xác nhận và chuẩn bị giao cho shipper',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-clipboard-check'
                },
                {
                    status: 'Đang lấy hàng',
                    description: 'Shipper đang trên đường đến địa chỉ lấy hàng',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-truck-pickup'
                },
                {
                    status: 'Đang vận chuyển',
                    description: 'Hàng hóa đang được vận chuyển đến địa chỉ giao hàng',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-shipping-fast'
                },
                {
                    status: 'Giao hàng thành công',
                    description: 'Hàng hóa đã được giao thành công đến người nhận',
                    timestamp: '',
                    completed: false,
                    icon: 'fa-check-circle'
                }
            ];
        }
        
        timelineContainer.innerHTML = timeline.map((step, index) => `
            <div class="tracking-step ${step.completed ? 'completed' : ''} flex items-center">
                <div class="step-icon">
                    <i class="fas ${step.icon || 'fa-circle'} ${step.completed ? 'text-white' : 'text-gray-400'}"></i>
                </div>
                <div class="ml-4 flex-1">
                    <h4 class="font-semibold text-gray-900">${step.status}</h4>
                    <p class="text-gray-600">${step.description}</p>
                    ${step.timestamp ? `<p class="text-sm text-gray-500">${step.timestamp}</p>` : ''}
                </div>
            </div>
        `).join('');
    }
</script>
@endsection