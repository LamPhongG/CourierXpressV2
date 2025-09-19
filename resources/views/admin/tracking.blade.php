@extends('layouts.unified')

@section('title', 'Quản lý theo dõi đơn hàng - Admin')

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/admin/tracking" class="text-red-600 font-medium">Theo dõi</a>
    <a href="/admin/users" class="text-gray-700 hover:text-red-600">Người dùng</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-purple-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Quản lý theo dõi đơn hàng</h1>
                    <p class="text-purple-100">Theo dõi và quản lý tất cả các đơn hàng trong hệ thống</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-chart-network text-4xl text-purple-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-boxes text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Tổng đơn hàng</p>
                    <p class="text-2xl font-bold text-gray-900" id="totalOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đang xử lý</p>
                    <p class="text-2xl font-bold text-gray-900" id="pendingOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg mr-4">
                    <i class="fas fa-truck text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Đang vận chuyển</p>
                    <p class="text-2xl font-bold text-gray-900" id="inTransitOrders">-</p>
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
    </div>

    <!-- Quick Search -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Tra cứu nhanh</h3>
        <form id="trackingForm" class="flex gap-3">
            <div class="flex-1">
                <input 
                    type="text" 
                    id="trackingNumber" 
                    name="tracking_number"
                    placeholder="Nhập mã vận đơn hoặc ID đơn hàng"
                    class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500"
                    required
                >
            </div>
            <button 
                type="submit" 
                id="searchBtn"
                class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors"
            >
                <span id="searchText">Tra cứu</span>
            </button>
            <button 
                type="button" 
                onclick="showAdvancedSearch()"
                class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 transition-colors"
            >
                Tìm kiếm nâng cao
            </button>
        </form>
    </div>

    <!-- Advanced Search Modal -->
    <div id="advancedSearchModal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50">
        <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Tìm kiếm nâng cao</h3>
                <button onclick="hideAdvancedSearch()" class="text-gray-400 hover:text-gray-600">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <form id="advancedSearchForm" class="space-y-4">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select id="statusFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả</option>
                            <option value="pending">Chờ xử lý</option>
                            <option value="confirmed">Đã xác nhận</option>
                            <option value="assigned">Đã phân công</option>
                            <option value="pickup">Đang lấy hàng</option>
                            <option value="picked_up">Đã lấy hàng</option>
                            <option value="in_transit">Đang vận chuyển</option>
                            <option value="delivering">Đang giao hàng</option>
                            <option value="delivered">Đã giao</option>
                            <option value="failed">Thất bại</option>
                            <option value="cancelled">Đã hủy</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Người dùng (Email)</label>
                        <input type="email" id="userEmail" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500" placeholder="user@example.com">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Shipper</label>
                        <select id="shipperFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả</option>
                            <!-- Shipper options will be loaded here -->
                        </select>
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                        <input type="date" id="fromDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                        <input type="date" id="toDate" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Thành phố</label>
                        <select id="cityFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả</option>
                            <option value="Hà Nội">Hà Nội</option>
                            <option value="TP.HCM">TP. Hồ Chí Minh</option>
                            <option value="Đà Nẵng">Đà Nẵng</option>
                            <option value="Hải Phòng">Hải Phòng</option>
                            <option value="Cần Thơ">Cần Thơ</option>
                        </select>
                    </div>
                </div>
                
                <div class="flex justify-end space-x-3 pt-4">
                    <button type="button" onclick="resetAdvancedSearch()" class="px-4 py-2 text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">
                        Đặt lại
                    </button>
                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                        Tìm kiếm
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Results Section -->
    <div id="resultsSection" class="space-y-6">
        <!-- Search Results Table -->
        <div class="bg-white shadow rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Kết quả tìm kiếm</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người dùng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng tiền</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="ordersList" class="bg-white divide-y divide-gray-200">
                        <!-- Orders will be loaded here -->
                    </tbody>
                </table>
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
    document.addEventListener('DOMContentLoaded', function() {
        loadStatistics();
        loadAllOrders();
        loadShippers();
        
        const form = document.getElementById('trackingForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            const trackingNumber = document.getElementById('trackingNumber').value.trim();
            if (trackingNumber) {
                searchSpecificOrder(trackingNumber);
            }
        });
        
        const advancedForm = document.getElementById('advancedSearchForm');
        advancedForm.addEventListener('submit', function(e) {
            e.preventDefault();
            performAdvancedSearch();
            hideAdvancedSearch();
        });
    });
    
    function loadStatistics() {
        fetch('/admin/api/stats', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                document.getElementById('totalOrders').textContent = data.data.total_orders || 0;
                document.getElementById('pendingOrders').textContent = data.data.pending_orders || 0;
                document.getElementById('inTransitOrders').textContent = data.data.in_transit_orders || 0;
                document.getElementById('completedOrders').textContent = data.data.completed_orders || 0;
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
    }
    
    function loadAllOrders() {
        showLoading();
        
        fetch('/admin/api/recent-orders', {
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
            console.error('Error loading orders:', error);
        });
    }
    
    function loadShippers() {
        fetch('/admin/api/shippers', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const shipperSelect = document.getElementById('shipperFilter');
            if (data.success && data.data) {
                data.data.forEach(shipper => {
                    const option = document.createElement('option');
                    option.value = shipper.id;
                    option.textContent = shipper.name;
                    shipperSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading shippers:', error);
        });
    }
    
    function searchSpecificOrder(trackingNumber) {
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
            }
        })
        .catch(error => {
            hideLoading();
            console.error('Error searching order:', error);
            displayOrders([]);
        });
    }
    
    function performAdvancedSearch() {
        const filters = {
            status: document.getElementById('statusFilter').value,
            user_email: document.getElementById('userEmail').value,
            shipper_id: document.getElementById('shipperFilter').value,
            from_date: document.getElementById('fromDate').value,
            to_date: document.getElementById('toDate').value,
            city: document.getElementById('cityFilter').value
        };
        
        showLoading();
        
        // This would typically call an advanced search API
        // For now, we'll filter the existing data
        loadAllOrders();
    }
    
    function displayOrders(orders) {
        const tbody = document.getElementById('ordersList');
        
        if (!orders || orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        Không tìm thấy đơn hàng nào
                    </td>
                </tr>
            `;
            return;
        }
        
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
        
        tbody.innerHTML = orders.map(order => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                    <a href="#" onclick="viewOrderDetails('${order.id}')" class="hover:text-blue-800">
                        ${order.tracking_number}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${order.user ? order.user.name : 'N/A'}<br>
                    <span class="text-xs text-gray-500">${order.user ? order.user.email : 'N/A'}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${order.shipper ? order.shipper.name : 'Chưa phân công'}<br>
                    <span class="text-xs text-gray-500">${order.shipper ? order.shipper.phone || 'N/A' : ''}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">
                        ${statusTexts[order.status] || order.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${new Date(order.created_at).toLocaleDateString('vi-VN')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${Number(order.total_fee || 0).toLocaleString('vi-VN')} ₫
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewOrderDetails('${order.id}')" class="text-blue-600 hover:text-blue-900" title="Xem chi tiết">
                            <i class="fas fa-eye"></i>
                        </button>
                        <button onclick="editOrder('${order.id}')" class="text-green-600 hover:text-green-900" title="Chỉnh sửa">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="assignShipper('${order.id}')" class="text-purple-600 hover:text-purple-900" title="Phân công">
                            <i class="fas fa-user-plus"></i>
                        </button>
                        <button onclick="updateStatus('${order.id}')" class="text-orange-600 hover:text-orange-900" title="Cập nhật trạng thái">
                            <i class="fas fa-sync-alt"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    function showAdvancedSearch() {
        document.getElementById('advancedSearchModal').classList.remove('hidden');
    }
    
    function hideAdvancedSearch() {
        document.getElementById('advancedSearchModal').classList.add('hidden');
    }
    
    function resetAdvancedSearch() {
        document.getElementById('advancedSearchForm').reset();
    }
    
    function showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
        document.getElementById('resultsSection').classList.add('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loadingState').classList.add('hidden');
        document.getElementById('resultsSection').classList.remove('hidden');
    }
    
    // Admin action functions
    function viewOrderDetails(orderId) {
        alert(`Xem chi tiết đơn hàng ${orderId}. Chức năng này sẽ được phát triển!`);
    }
    
    function editOrder(orderId) {
        alert(`Chỉnh sửa đơn hàng ${orderId}. Chức năng này sẽ được phát triển!`);
    }
    
    function assignShipper(orderId) {
        alert(`Phân công shipper cho đơn hàng ${orderId}. Chức năng này sẽ được phát triển!`);
    }
    
    function updateStatus(orderId) {
        alert(`Cập nhật trạng thái đơn hàng ${orderId}. Chức năng này sẽ được phát triển!`);
    }
</script>
@endsection