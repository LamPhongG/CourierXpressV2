@extends('layouts.unified')

@section('title', 'Agent Dashboard - CourierXpress')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('navigation')
    <a href="/agent/dashboard" class="text-red-600 font-medium">Dashboard</a>
    <a href="/agent/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/agent/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/agent/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Welcome Section -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Agent Dashboard</h1>
        <p class="text-green-100">Chào mừng, {{ auth()->user()->name }}! Quản lý chi nhánh {{ auth()->user()->city ?? 'CourierXpress' }}</p>
        <p class="text-sm text-green-100 mt-2">{{ auth()->user()->email }} • Agent • {{ auth()->user()->city ?? 'Chưa cập nhật' }}</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Chờ xử lý</dt>
                            <dd class="text-lg font-medium text-gray-900" id="pendingOrders">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-cogs text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đang xử lý</dt>
                            <dd class="text-lg font-medium text-gray-900" id="processingOrders">0</dd>
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
                            <dd class="text-lg font-medium text-gray-900" id="completedToday">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-motorcycle text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Shipper hoạt động</dt>
                            <dd class="text-lg font-medium text-gray-900" id="activeShippers">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Chức năng Agent</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
            <button onclick="confirmOrders()" class="bg-yellow-100 hover:bg-yellow-200 p-4 rounded-lg text-center transition-colors">
                <i class="fas fa-check text-yellow-600 text-2xl mb-2"></i>
                <p class="text-yellow-800 font-medium">Xác nhận đơn hàng</p>
            </button>
            <button onclick="assignShipper()" class="bg-blue-100 hover:bg-blue-200 p-4 rounded-lg text-center transition-colors">
                <i class="fas fa-user-plus text-blue-600 text-2xl mb-2"></i>
                <p class="text-blue-800 font-medium">Phân công shipper</p>
            </button>
            <button onclick="viewReports()" class="bg-green-100 hover:bg-green-200 p-4 rounded-lg text-center transition-colors">
                <i class="fas fa-chart-bar text-green-600 text-2xl mb-2"></i>
                <p class="text-green-800 font-medium">Báo cáo chi nhánh</p>
            </button>
            <button onclick="manageShippers()" class="bg-purple-100 hover:bg-purple-200 p-4 rounded-lg text-center transition-colors">
                <i class="fas fa-users text-purple-600 text-2xl mb-2"></i>
                <p class="text-purple-800 font-medium">Quản lý shipper</p>
            </button>
        </div>
    </div>

    <!-- Performance Charts & Order Management -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Performance Chart -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Hiệu suất chi nhánh</h3>
            <canvas id="performanceChart"></canvas>
        </div>

        <!-- Pending Orders Table -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Đơn hàng cần xử lý</h3>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead>
                        <tr>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                            <th class="px-4 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="pendingOrdersTable">
                        <!-- Data will be loaded dynamically -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Shipper Status -->    
    <div class="bg-white shadow rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Trạng thái Shipper</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="shipperStatusGrid">
            <!-- Shipper status cards will be loaded dynamically -->
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Load agent dashboard statistics
    async function loadAgentStats() {
        try {
            const response = await fetch('/api/agent/stats');
            const data = await response.json();
            
            if (data.error) {
                console.error('Lỗi API:', data.error);
                loadMockData();
                return;
            }
            
            // Update statistics
            document.getElementById('pendingOrders').textContent = data.orders.pending;
            document.getElementById('processingOrders').textContent = data.orders.processing;
            document.getElementById('completedToday').textContent = data.orders.completed_today;
            document.getElementById('activeShippers').textContent = data.shippers.active;
            
            // Update performance chart
            updatePerformanceChart(data.performance);
            
            // Load pending orders table
            loadPendingOrders();
            
            // Load shipper status
            loadShipperStatus();
        } catch (error) {
            console.error('Error loading agent stats:', error);
            // Use mock data if API fails
            loadMockData();
        }
    }

    // Load mock data for demonstration
    function loadMockData() {
        document.getElementById('pendingOrders').textContent = '8';
        document.getElementById('processingOrders').textContent = '12';
        document.getElementById('completedToday').textContent = '25';
        document.getElementById('activeShippers').textContent = '5';
        
        updatePerformanceChart({
            labels: ['T2', 'T3', 'T4', 'T5', 'T6'],
            orders: [15, 22, 18, 28, 25],
            completed: [12, 20, 16, 25, 23]
        });
        
        loadPendingOrders();
        loadShipperStatus();
    }

    // Update performance chart
    function updatePerformanceChart(performanceData) {
        const ctx = document.getElementById('performanceChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: performanceData.labels || ['T2', 'T3', 'T4', 'T5', 'T6'],
                datasets: [{
                    label: 'Đơn nhận',
                    data: performanceData.orders || [15, 22, 18, 28, 25],
                    borderColor: 'rgb(59, 130, 246)',
                    backgroundColor: 'rgba(59, 130, 246, 0.1)',
                    tension: 0.1
                }, {
                    label: 'Đơn hoàn thành',
                    data: performanceData.completed || [12, 20, 16, 25, 23],
                    borderColor: 'rgb(16, 185, 129)',
                    backgroundColor: 'rgba(16, 185, 129, 0.1)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    },
                    title: {
                        display: true,
                        text: 'Hiệu suất trong tuần'
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });
    }

    // Load pending orders
    async function loadPendingOrders() {
        try {
            const response = await fetch('/api/agent/pending-orders');
            const orders = await response.json();
            
            if (orders.error) {
                console.error('Lỗi tải đơn hàng:', orders.error);
                return;
            }
            
            const tableBody = document.getElementById('pendingOrdersTable');
            if (orders.length === 0) {
                tableBody.innerHTML = `
                    <tr>
                        <td colspan="3" class="px-4 py-4 text-center text-gray-500">Không có đơn hàng chờ xử lý</td>
                    </tr>
                `;
                return;
            }
            
            tableBody.innerHTML = orders.map(order => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                        <div class="font-medium">#${order.tracking_number}</div>
                        <div class="text-xs text-gray-500">${order.created_at}</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                        <div class="font-medium">${order.customer_name}</div>
                        <div class="text-xs">${order.customer_phone}</div>
                        <div class="text-xs text-blue-600">${order.total_fee.toLocaleString()} VNĐ</div>
                    </td>
                    <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                        <button onclick="confirmOrder('${order.id}')" class="text-green-600 hover:text-green-900 mr-2 px-3 py-1 bg-green-100 rounded-md transition-colors">
                            Xác nhận
                        </button>
                        <button onclick="viewOrderDetails('${order.id}')" class="text-blue-600 hover:text-blue-900 px-3 py-1 bg-blue-100 rounded-md transition-colors">
                            Chi tiết
                        </button>
                    </td>
                </tr>
            `).join('');
        } catch (error) {
            console.error('Error loading pending orders:', error);
            // Fallback to mock data
            loadMockPendingOrders();
        }
    }
    
    // Fallback mock pending orders
    function loadMockPendingOrders() {
        const mockOrders = [
            { id: 'DH001', tracking_number: 'DH001', customer_name: 'Nguyễn Văn A', customer_phone: '0123456789', total_fee: 50000, created_at: '05/09/2024 10:30' },
            { id: 'DH002', tracking_number: 'DH002', customer_name: 'Trần Thị B', customer_phone: '0987654321', total_fee: 75000, created_at: '05/09/2024 11:15' },
            { id: 'DH003', tracking_number: 'DH003', customer_name: 'Lê Văn C', customer_phone: '0369258147', total_fee: 60000, created_at: '05/09/2024 12:00' }
        ];
        
        const tableBody = document.getElementById('pendingOrdersTable');
        tableBody.innerHTML = mockOrders.map(order => `
            <tr class="hover:bg-gray-50">
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                    <div class="font-medium">#${order.tracking_number}</div>
                    <div class="text-xs text-gray-500">${order.created_at}</div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                    <div class="font-medium">${order.customer_name}</div>
                    <div class="text-xs">${order.customer_phone}</div>
                    <div class="text-xs text-blue-600">${order.total_fee.toLocaleString()} VNĐ</div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="confirmOrder('${order.id}')" class="text-green-600 hover:text-green-900 mr-2 px-3 py-1 bg-green-100 rounded-md transition-colors">
                        Xác nhận
                    </button>
                    <button onclick="viewOrderDetails('${order.id}')" class="text-blue-600 hover:text-blue-900 px-3 py-1 bg-blue-100 rounded-md transition-colors">
                        Chi tiết
                    </button>
                </td>
            </tr>
        `).join('');
    }

    // Load shipper status
    async function loadShipperStatus() {
        try {
            const response = await fetch('/api/agent/shipper-status');
            const shippers = await response.json();
            
            if (shippers.error) {
                console.error('Lỗi tải trạng thái shipper:', shippers.error);
                loadMockShipperStatus();
                return;
            }
            
            const shipperGrid = document.getElementById('shipperStatusGrid');
            
            if (shippers.length === 0) {
                shipperGrid.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-8">
                        Không có shipper nào trong khu vực
                    </div>
                `;
                return;
            }
            
            shipperGrid.innerHTML = shippers.map(shipper => {
                const statusConfig = {
                    'online': { bg: 'bg-green-100', text: 'text-green-800', label: 'Sẵn sàng' },
                    'busy': { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Bận' },
                    'offline': { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Offline' }
                };
                
                const config = statusConfig[shipper.status] || statusConfig.offline;
                
                return `
                    <div class="bg-gray-50 p-4 rounded-lg border hover:shadow-md transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <h4 class="text-sm font-medium text-gray-900 truncate">${shipper.name}</h4>
                            <span class="px-2 py-1 text-xs rounded-full ${config.bg} ${config.text} whitespace-nowrap">
                                ${config.label}
                            </span>
                        </div>
                        <p class="text-sm text-gray-600">${shipper.orders} đơn hàng</p>
                        <div class="mt-2">
                            <button onclick="viewShipperDetails('${shipper.id}')" class="text-xs text-blue-600 hover:text-blue-800">
                                Xem chi tiết
                            </button>
                        </div>
                    </div>
                `;
            }).join('');
        } catch (error) {
            console.error('Error loading shipper status:', error);
            loadMockShipperStatus();
        }
    }
    
    // Fallback mock shipper status
    function loadMockShipperStatus() {
        const mockShippers = [
            { id: 1, name: 'Nguyễn Văn Shipper 1', status: 'online', orders: 3 },
            { id: 2, name: 'Trần Văn Shipper 2', status: 'busy', orders: 2 },
            { id: 3, name: 'Lê Thị Shipper 3', status: 'offline', orders: 0 }
        ];
        
        const shipperGrid = document.getElementById('shipperStatusGrid');
        shipperGrid.innerHTML = mockShippers.map(shipper => {
            const statusConfig = {
                'online': { bg: 'bg-green-100', text: 'text-green-800', label: 'Sẵn sàng' },
                'busy': { bg: 'bg-yellow-100', text: 'text-yellow-800', label: 'Bận' },
                'offline': { bg: 'bg-gray-100', text: 'text-gray-800', label: 'Offline' }
            };
            
            const config = statusConfig[shipper.status] || statusConfig.offline;
            
            return `
                <div class="bg-gray-50 p-4 rounded-lg border hover:shadow-md transition-shadow">
                    <div class="flex items-center justify-between mb-2">
                        <h4 class="text-sm font-medium text-gray-900 truncate">${shipper.name}</h4>
                        <span class="px-2 py-1 text-xs rounded-full ${config.bg} ${config.text} whitespace-nowrap">
                            ${config.label}
                        </span>
                    </div>
                    <p class="text-sm text-gray-600">${shipper.orders} đơn hàng</p>
                    <div class="mt-2">
                        <button onclick="viewShipperDetails('${shipper.id}')" class="text-xs text-blue-600 hover:text-blue-800">
                            Xem chi tiết
                        </button>
                    </div>
                </div>
            `;
        }).join('');
    }

    // Enhanced quick action functions
    async function confirmOrders() {
        try {
            const pendingOrders = await fetch('/api/agent/pending-orders');
            const orders = await pendingOrders.json();
            
            if (orders.length === 0) {
                alert('Không có đơn hàng nào cần xác nhận');
                return;
            }
            
            const orderList = orders.map(order => `#${order.tracking_number} - ${order.customer_name}`).join('\n');
            
            if (confirm(`Bạn có muốn xác nhận tất cả ${orders.length} đơn hàng sau:\n\n${orderList}`)) {
                const orderIds = orders.map(order => order.id);
                const response = await fetch('/api/agent/orders/batch-operation', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    },
                    body: JSON.stringify({
                        action: 'confirm',
                        order_ids: orderIds
                    })
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    loadAgentStats(); // Refresh dashboard
                } else {
                    alert('Lỗi: ' + result.message);
                }
            }
        } catch (error) {
            console.error('Error confirming orders:', error);
            alert('Có lỗi xảy ra khi xác nhận đơn hàng');
        }
    }

    async function assignShipper() {
        try {
            // Get available shippers
            const shippersResponse = await fetch('/api/agent/available-shippers');
            const shippers = await shippersResponse.json();
            
            if (shippers.length === 0) {
                alert('Không có shipper nào khả dụng');
                return;
            }
            
            // Get confirmed orders that need assignment
            const ordersResponse = await fetch('/api/agent/orders?status=confirmed');
            const ordersData = await ordersResponse.json();
            
            if (!ordersData.success || ordersData.data.length === 0) {
                alert('Không có đơn hàng nào cần phân công shipper');
                return;
            }
            
            // Show assignment interface (simplified for now)
            const availableShippers = shippers.filter(s => s.is_online && s.active_orders < 3);
            if (availableShippers.length === 0) {
                alert('Tất cả shipper đang bận. Vui lòng thử lại sau.');
                return;
            }
            
            const shipperOptions = availableShippers.map(s => `${s.name} (${s.active_orders} đơn)`).join('\n');
            alert(`Shipper khả dụng:\n${shipperOptions}\n\nVui lòng sử dụng trang Quản lý đơn hàng để phân công chi tiết.`);
            
            // Redirect to orders management page
            window.location.href = '/agent/orders';
        } catch (error) {
            console.error('Error in assign shipper:', error);
            alert('Có lỗi xảy ra khi phân công shipper');
        }
    }

    function viewReports() {
        window.location.href = '/agent/reports';
    }

    function manageShippers() {
        window.location.href = '/agent/shippers';
    }

    // Enhanced order confirmation function
    async function confirmOrder(orderId) {
        try {
            if (confirm(`Bạn có chắc muốn xác nhận đơn hàng này?`)) {
                const response = await fetch(`/api/agent/orders/${orderId}/confirm`, {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || ''
                    }
                });
                
                const result = await response.json();
                
                if (result.success) {
                    alert(result.message);
                    loadPendingOrders(); // Refresh the table
                    loadAgentStats(); // Refresh stats
                } else {
                    alert('Lỗi: ' + result.message);
                }
            }
        } catch (error) {
            console.error('Error confirming order:', error);
            alert('Có lỗi xảy ra khi xác nhận đơn hàng');
        }
    }

    function viewOrderDetails(orderId) {
        window.location.href = `/agent/orders/${orderId}`;
    }

    function viewShipperDetails(shipperId) {
        // Implementation for viewing shipper details
        alert(`Xem chi tiết shipper ID: ${shipperId}`);
    }

    // Initialize dashboard
    document.addEventListener('DOMContentLoaded', () => {
        loadAgentStats();

        // Check authentication
        checkAuth('agent');
    });
</script>
@endsection
