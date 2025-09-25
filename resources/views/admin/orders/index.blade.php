@extends('layouts.unified')

@section('title', 'Admin - Orders Management | CourierXpress')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-red-600 font-medium">Orders</a>
    <a href="/admin/agents" class="text-gray-700 hover:text-red-600">Agents</a>
    <a href="/admin/shippers" class="text-gray-700 hover:text-red-600">Shippers</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Reports</a>
    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Settings</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Orders Management</h2>
                <p class="mt-2 text-gray-600">View and manage all orders in the system</p>
            </div>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-5 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-box text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Total orders</p>
                    <p id="totalOrders" class="text-2xl font-bold text-gray-900">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Pending</p>
                    <p id="pendingOrders" class="text-2xl font-bold text-yellow-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-truck text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">In transit</p>
                    <p id="inTransitOrders" class="text-2xl font-bold text-blue-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Delivered</p>
                    <p id="deliveredOrders" class="text-2xl font-bold text-green-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-exclamation-triangle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Issues</p>
                    <p id="issueOrders" class="text-2xl font-bold text-red-600">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                <select id="statusFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">All statuses</option>
                    <option value="pending">Pending</option>
                    <option value="processing">Processing</option>
                    <option value="in_transit">In transit</option>
                    <option value="delivered">Delivered</option>
                    <option value="cancelled">Cancelled</option>
                    <option value="issue">Issue</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Agent</label>
                <select id="agentFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">All agents</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipper</label>
                <select id="shipperFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">All shippers</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Created date</label>
                <input type="date" id="dateFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
            </div>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow-sm border overflow-hidden">
        <div class="p-6">
            <table id="ordersTable" class="w-full">
                <thead>
                    <tr>
                        <th>Order ID</th>
                        <th>Customer</th>
                        <th>From</th>
                        <th>To</th>
                        <th>Agent</th>
                        <th>Shipper</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Order Modal removed -->

    <!-- View Order Modal -->
    <div id="viewOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Order details #<span id="viewOrderId"></span></h3>
                    <button onclick="hideViewOrderModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div id="orderDetails" class="space-y-6">
                    <!-- Order details will be loaded dynamically -->
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let orders = [];
    let table;

    $(document).ready(function() {
        // Initialize DataTable
        table = $('#ordersTable').DataTable({
            columns: [
                { data: 'id' },
                { data: 'customer_name' },
                { data: 'pickup_address' },
                { data: 'delivery_address' },
                { data: 'agent' },
                { data: 'shipper' },
                {
                    data: 'status',
                    render: function(data) {
                        const statusClasses = {
                            pending: 'bg-yellow-100 text-yellow-800',
                            processing: 'bg-blue-100 text-blue-800',
                            in_transit: 'bg-purple-100 text-purple-800',
                            delivered: 'bg-green-100 text-green-800',
                            cancelled: 'bg-gray-100 text-gray-800',
                            issue: 'bg-red-100 text-red-800'
                        };
                        const statusText = {
                            pending: 'Pending',
                            processing: 'Processing',
                            in_transit: 'In transit',
                            delivered: 'Delivered',
                            cancelled: 'Cancelled',
                            issue: 'Issue'
                        };
                        return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${statusClasses[data]}">${statusText[data]}</span>`;
                    }
                },
                {
                    data: null,
                    render: function(data) {
                        return `
                            <div class="flex space-x-2">
                                <button onclick="viewOrder('${data.id}')" class="text-blue-600 hover:text-blue-900">
                                    <i class="fas fa-eye"></i>
                                </button>
                            </div>
                        `;
                    }
                }
            ]
        });

        // Load initial data
        loadOrders();
        loadAgents();
        loadShippers();

        // Setup filters
        $('#statusFilter, #agentFilter, #shipperFilter, #dateFilter').on('change', function() {
            table.draw();
        });

        // Setup DataTable filters
        $.fn.dataTable.ext.search.push(function(settings, data, dataIndex) {
            const statusFilter = $('#statusFilter').val();
            const agentFilter = $('#agentFilter').val();
            const shipperFilter = $('#shipperFilter').val();
            const dateFilter = $('#dateFilter').val();

            const status = $(data[6]).text().trim();
            const agent = data[4];
            const shipper = data[5];
            const date = orders[dataIndex].created_at;

            if (statusFilter && status !== statusFilter) return false;
            if (agentFilter && agent !== agentFilter) return false;
            if (shipperFilter && shipper !== shipperFilter) return false;
            if (dateFilter && !date.includes(dateFilter)) return false;

            return true;
        });
    });

    // Load orders data
    async function loadOrders() {
        try {
            const response = await fetch('/api/admin/orders');
            orders = await response.json();
            
            // Update table
            table.clear();
            table.rows.add(orders);
            table.draw();

            // Update statistics
            updateStats();
        } catch (error) {
            console.error('Error loading orders:', error);
            alert('Có lỗi khi tải dữ liệu. Vui lòng thử lại.');
        }
    }

    // Load agents for filter
    async function loadAgents() {
        try {
            const response = await fetch('/api/admin/agents');
            const agents = await response.json();
            
            const select = $('#agentFilter');
            agents.forEach(agent => {
                select.append(`<option value="${agent.name}">${agent.name}</option>`);
            });
        } catch (error) {
            console.error('Error loading agents:', error);
        }
    }

    // Load shippers for filter
    async function loadShippers() {
        try {
            const response = await fetch('/api/admin/shippers');
            const shippers = await response.json();
            
            const select = $('#shipperFilter');
            shippers.forEach(shipper => {
                select.append(`<option value="${shipper.name}">${shipper.name}</option>`);
            });
        } catch (error) {
            console.error('Error loading shippers:', error);
        }
    }

    // Update statistics
    function updateStats() {
        const stats = orders.reduce((acc, order) => {
            acc.total++;
            acc[order.status]++;
            return acc;
        }, {
            total: 0,
            pending: 0,
            processing: 0,
            in_transit: 0,
            delivered: 0,
            issue: 0
        });

        $('#totalOrders').text(stats.total);
        $('#pendingOrders').text(stats.pending);
        $('#inTransitOrders').text(stats.in_transit);
        $('#deliveredOrders').text(stats.delivered);
        $('#issueOrders').text(stats.issue);
    }

    // Removed create order modal trigger (showCreateOrderModal) as create action is disabled

    // Removed hideOrderModal (create/edit disabled)

    // Hide view order modal
    function hideViewOrderModal() {
        $('#viewOrderModal').removeClass('flex').addClass('hidden');
    }

    // View order details
    async function viewOrder(id) {
        try {
            const response = await fetch(`/api/admin/orders/${id}`);
            const order = await response.json();

            $('#viewOrderId').text(order.id);
            $('#orderDetails').html(`
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Thông tin người gửi</h4>
                        <p><span class="text-gray-600">Họ tên:</span> ${order.sender_name}</p>
                        <p><span class="text-gray-600">Số điện thoại:</span> ${order.sender_phone}</p>
                        <p><span class="text-gray-600">Địa chỉ:</span> ${order.pickup_address}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-900 mb-2">Thông tin người nhận</h4>
                        <p><span class="text-gray-600">Họ tên:</span> ${order.receiver_name}</p>
                        <p><span class="text-gray-600">Số điện thoại:</span> ${order.receiver_phone}</p>
                        <p><span class="text-gray-600">Địa chỉ:</span> ${order.delivery_address}</p>
                    </div>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Thông tin đơn hàng</h4>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <p><span class="text-gray-600">Loại hàng:</span> ${order.package_type}</p>
                        <p><span class="text-gray-600">Khối lượng:</span> ${order.weight}kg</p>
                        <p><span class="text-gray-600">COD:</span> ${formatCurrency(order.cod)}</p>
                    </div>
                    <p class="mt-2"><span class="text-gray-600">Ghi chú:</span> ${order.note || 'Không có'}</p>
                </div>
                <div>
                    <h4 class="font-medium text-gray-900 mb-2">Trạng thái vận chuyển</h4>
                    <div class="space-y-4">
                        ${order.tracking_history.map(history => `
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="w-2 h-2 bg-primary rounded-full mt-2"></div>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-900">${history.status}</p>
                                    <p class="text-sm text-gray-600">${history.location}</p>
                                    <p class="text-xs text-gray-500">${formatDate(history.timestamp)}</p>
                                </div>
                            </div>
                        `).join('')}
                    </div>
                </div>
            `);

            $('#viewOrderModal').removeClass('hidden').addClass('flex');
        } catch (error) {
            console.error('Error loading order details:', error);
            alert('Có lỗi khi tải thông tin đơn hàng. Vui lòng thử lại.');
        }
    }

    // Removed editOrder (create/edit disabled)

    // Removed handleOrderSubmit (create/edit disabled)

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkAuth('admin');
    });
</script>
@endsection
