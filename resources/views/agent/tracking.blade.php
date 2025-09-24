@extends('layouts.unified')

@section('title', 'Order Tracking - Agent')

@section('navigation')
    <a href="/agent/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/agent/orders" class="text-gray-700 hover:text-red-600">Orders</a>
    <a href="/agent/tracking" class="text-red-600 font-medium">Tracking</a>
    <a href="/agent/shippers" class="text-gray-700 hover:text-red-600">Shippers</a>
    <a href="/agent/reports" class="text-gray-700 hover:text-red-600">Reports</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-blue-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Area Order Tracking</h1>
                    <p class="text-green-100">Manage and track orders within your responsible area</p>
                </div>
                <div class="hidden md:block">
                    <i class="fas fa-map-marked-alt text-4xl text-green-200"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Area Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-lg mr-4">
                    <i class="fas fa-clipboard-list text-blue-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Today's orders</p>
                    <p class="text-2xl font-bold text-gray-900" id="todayOrders">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-lg mr-4">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Pending assignment</p>
                    <p class="text-2xl font-bold text-gray-900" id="pendingAssignment">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-orange-100 rounded-lg mr-4">
                    <i class="fas fa-shipping-fast text-orange-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Delivering</p>
                    <p class="text-2xl font-bold text-gray-900" id="delivering">-</p>
                </div>
            </div>
        </div>
        
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-lg mr-4">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div>
                    <p class="text-gray-500 text-sm">Issues</p>
                    <p class="text-2xl font-bold text-gray-900" id="problemOrders">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Active Shippers Status -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Shipper status</h3>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4" id="shippersStatus">
            <!-- Shipper status cards will be loaded here -->
        </div>
    </div>

    <!-- Search and Filter -->
    <div class="bg-white shadow rounded-lg p-6 mb-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Search orders</h3>
        <form id="trackingForm" class="space-y-4">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <input 
                        type="text" 
                        id="trackingNumber" 
                        name="tracking_number"
                        placeholder="Tracking number"
                        class="w-full px-4 py-3 text-gray-900 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500"
                    >
                </div>
                <div>
                    <select id="statusFilter" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500">
                        <option value="">All statuses</option>
                        <option value="pending">Pending</option>
                        <option value="confirmed">Confirmed</option>
                        <option value="assigned">Assigned</option>
                        <option value="pickup">Picking up</option>
                        <option value="picked_up">Picked up</option>
                        <option value="in_transit">In transit</option>
                        <option value="delivering">Delivering</option>
                        <option value="delivered">Delivered</option>
                        <option value="failed">Failed</option>
                        <option value="cancelled">Cancelled</option>
                    </select>
                </div>
                <div>
                    <select id="shipperFilter" class="w-full px-4 py-3 bg-gray-50 border border-gray-300 rounded-lg focus:ring-4 focus:ring-blue-300 focus:border-blue-500">
                        <option value="">All shippers</option>
                    </select>
                </div>
            </div>
            <div class="flex space-x-3">
                <button 
                    type="submit" 
                    id="searchBtn"
                    class="px-6 py-3 bg-blue-600 text-white font-bold rounded-lg hover:bg-blue-700 focus:ring-4 focus:ring-blue-300 transition-colors"
                >
                    <i class="fas fa-search mr-2"></i>Search
                </button>
                <button 
                    type="button" 
                    onclick="resetFilters()"
                    class="px-6 py-3 bg-gray-600 text-white font-bold rounded-lg hover:bg-gray-700 focus:ring-4 focus:ring-gray-300 transition-colors"
                >
                    <i class="fas fa-undo mr-2"></i>Reset
                </button>
            </div>
        </form>
    </div>

    <!-- Orders List -->
    <div class="bg-white shadow rounded-lg">
        <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
            <h3 class="text-lg font-medium text-gray-900">Orders List</h3>
            <div class="flex space-x-2">
                <button onclick="refreshOrders()" class="px-3 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                    <i class="fas fa-sync-alt mr-1"></i>Refresh
                </button>
                <button onclick="exportData()" class="px-3 py-2 bg-green-600 text-white rounded-md hover:bg-green-700">
                    <i class="fas fa-download mr-1"></i>Export data
                </button>
            </div>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Order</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Customer</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Delivery address</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Time</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersList" class="bg-white divide-y divide-gray-200">
                    <!-- Orders will be loaded here -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Loading State -->
    <div id="loadingState" class="hidden bg-white rounded-lg shadow p-8 text-center">
        <i class="fas fa-spinner fa-spin text-4xl text-blue-500 mb-4"></i>
        <p class="text-gray-600">Loading data...</p>
    </div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        loadStatistics();
        loadShippersStatus();
        loadOrders();
        loadShipperOptions();
        
        const form = document.getElementById('trackingForm');
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            searchOrders();
        });

        setInterval(refreshOrders, 30000);
    });
    
    function loadStatistics() {
        fetch('/agent/api/stats', {
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
                document.getElementById('pendingAssignment').textContent = data.data.pending_assignment || 0;
                document.getElementById('delivering').textContent = data.data.delivering || 0;
                document.getElementById('problemOrders').textContent = data.data.problem_orders || 0;
            }
        })
        .catch(error => {
            console.error('Error loading statistics:', error);
        });
    }
    
    function loadShippersStatus() {
        fetch('/agent/api/shippers', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const container = document.getElementById('shippersStatus');
            
            if (data.success && data.data.length > 0) {
                container.innerHTML = data.data.map(shipper => `
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-lg transition-shadow">
                        <div class="flex items-center justify-between mb-2">
                            <span class="font-medium text-gray-900">${shipper.name}</span>
                            <span class="px-2 py-1 text-xs rounded-full ${shipper.status === 'active' ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-800'}">
                                ${shipper.status === 'active' ? 'Active' : 'Offline'}
                            </span>
                        </div>
                        <div class="text-sm text-gray-600">
                            <p>Current orders: <strong>${shipper.current_orders || 0}</strong></p>
                            <p>Completed today: <strong>${shipper.completed_today || 0}</strong></p>
                            <p>Rating: <strong>${shipper.rating || 'N/A'}/5</strong></p>
                        </div>
                        <div class="mt-3 flex space-x-2">
                            <button onclick="viewShipperLocation('${shipper.id}')" class="text-xs px-2 py-1 bg-blue-100 text-blue-600 rounded hover:bg-blue-200">
                                Location
                            </button>
                            <button onclick="assignNewOrder('${shipper.id}')" class="text-xs px-2 py-1 bg-green-100 text-green-600 rounded hover:bg-green-200">
                                Assign
                            </button>
                        </div>
                    </div>
                `).join('');
            } else {
                container.innerHTML = `
                    <div class="col-span-full text-center text-gray-500 py-8">
                        <i class="fas fa-users text-2xl mb-2"></i>
                        <p>No shippers in the area</p>
                    </div>
                `;
            }
        })
        .catch(error => {
            console.error('Error loading shippers status:', error);
        });
    }
    
    function loadOrders() {
        showLoading();
        
        fetch('/agent/api/pending-orders', {
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
    
    function loadShipperOptions() {
        fetch('/agent/api/shippers', {
            method: 'GET',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            }
        })
        .then(response => response.json())
        .then(data => {
            const select = document.getElementById('shipperFilter');
            if (data.success && data.data) {
                data.data.forEach(shipper => {
                    const option = document.createElement('option');
                    option.value = shipper.id;
                    option.textContent = shipper.name;
                    select.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error loading shipper options:', error);
        });
    }
    
    function searchOrders() {
        const trackingNumber = document.getElementById('trackingNumber').value.trim();
        const status = document.getElementById('statusFilter').value;
        const shipperId = document.getElementById('shipperFilter').value;
        
        if (trackingNumber) {
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
        } else {
            loadOrders();
        }
    }
    
    function displayOrders(orders) {
        const tbody = document.getElementById('ordersList');
        
        if (!orders || orders.length === 0) {
            tbody.innerHTML = `
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                        No orders found
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
            'pending': 'Pending',
            'confirmed': 'Confirmed',
            'assigned': 'Assigned',
            'pickup': 'Picking up',
            'picked_up': 'Picked up',
            'in_transit': 'In transit',
            'delivering': 'Delivering',
            'delivered': 'Delivered',
            'failed': 'Failed',
            'cancelled': 'Cancelled'
        };
        
        tbody.innerHTML = orders.map(order => `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                    <a href="#" onclick="viewOrderDetails('${order.id}')" class="hover:text-blue-800">
                        ${order.tracking_number}
                    </a>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${order.pickup_name}<br>
                    <span class="text-xs text-gray-500">${order.pickup_phone}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${order.shipper ? order.shipper.name : 
                        `<button onclick=\"assignShipper('${order.id}')\" class=\"text-blue-600 hover:text-blue-800 font-medium\">
                            Assign
                        </button>`
                    }
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusColors[order.status] || 'bg-gray-100 text-gray-800'}">
                        ${statusTexts[order.status] || order.status}
                    </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${order.delivery_address ? order.delivery_address.substring(0, 30) + '...' : 'N/A'}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${new Date(order.created_at).toLocaleDateString('en-US')}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <div class="flex space-x-2">
                        <button onclick="viewOrderDetails('${order.id}')" class="text-blue-600 hover:text-blue-900" title="Details">
                            <i class="fas fa-eye"></i>
                        </button>
                        ${!order.shipper ? `
                            <button onclick="assignShipper('${order.id}')" class="text-green-600 hover:text-green-900" title="Assign">
                                <i class="fas fa-user-plus"></i>
                            </button>
                        ` : ''}
                        <button onclick="updateOrderStatus('${order.id}')" class="text-orange-600 hover:text-orange-900" title="Update">
                            <i class="fas fa-edit"></i>
                        </button>
                        <button onclick="contactCustomer('${order.pickup_phone}')" class="text-purple-600 hover:text-purple-900" title="Contact">
                            <i class="fas fa-phone"></i>
                        </button>
                    </div>
                </td>
            </tr>
        `).join('');
    }
    
    function refreshOrders() {
        loadStatistics();
        loadShippersStatus();
        loadOrders();
    }
    
    function resetFilters() {
        document.getElementById('trackingForm').reset();
        loadOrders();
    }
    
    function showLoading() {
        document.getElementById('loadingState').classList.remove('hidden');
    }
    
    function hideLoading() {
        document.getElementById('loadingState').classList.add('hidden');
    }
    
    // Agent action functions
    function viewOrderDetails(orderId) {
        window.location.href = `/agent/orders/${orderId}`;
    }
    
    function assignShipper(orderId) {
        alert(`Assign shipper for order ${orderId}. This feature will be developed!`);
    }
    
    function updateOrderStatus(orderId) {
        alert(`Update status for order ${orderId}. This feature will be developed!`);
    }
    
    function contactCustomer(phone) {
        if (phone) {
            if (confirm(`Call customer ${phone}?`)) {
                window.location.href = `tel:${phone}`;
            }
        } else {
            alert('No phone number available');
        }
    }
    
    function viewShipperLocation(shipperId) {
        alert(`View shipper location ${shipperId}. GPS feature will be developed!`);
    }
    
    function assignNewOrder(shipperId) {
        alert(`Assign new order to shipper ${shipperId}. This feature will be developed!`);
    }
    
    function exportData() {
        alert('Export data to Excel. This feature will be developed!');
    }
</script>
@endsection