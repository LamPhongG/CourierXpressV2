@extends('layouts.unified')

@section('title', 'Admin - Quản lý đơn hàng | CourierXpress')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-red-600 font-medium">Đơn hàng</a>
    <a href="/admin/agents" class="text-gray-700 hover:text-red-600">Chi nhánh</a>
    <a href="/admin/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Cài đặt</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Quản lý đơn hàng</h2>
                <p class="mt-2 text-gray-600">Xem và quản lý tất cả đơn hàng trong hệ thống</p>
            </div>
            <button onclick="showCreateOrderModal()" class="bg-primary hover:bg-red-700 text-white px-6 py-3 rounded-lg font-medium transition-colors">
                <i class="fas fa-plus mr-2"></i> Tạo đơn hàng
            </button>
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
                    <p class="text-gray-600 text-sm">Tổng đơn hàng</p>
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
                    <p class="text-gray-600 text-sm">Chờ xử lý</p>
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
                    <p class="text-gray-600 text-sm">Đang giao</p>
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
                    <p class="text-gray-600 text-sm">Đã giao</p>
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
                    <p class="text-gray-600 text-sm">Có vấn đề</p>
                    <p id="issueOrders" class="text-2xl font-bold text-red-600">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters -->
    <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                <select id="statusFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Tất cả trạng thái</option>
                    <option value="pending">Chờ xử lý</option>
                    <option value="processing">Đang xử lý</option>
                    <option value="in_transit">Đang giao</option>
                    <option value="delivered">Đã giao</option>
                    <option value="cancelled">Đã hủy</option>
                    <option value="issue">Có vấn đề</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Chi nhánh</label>
                <select id="agentFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Tất cả chi nhánh</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Shipper</label>
                <select id="shipperFilter" class="w-full rounded-lg border-gray-300 focus:border-primary focus:ring-primary">
                    <option value="">Tất cả shipper</option>
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Ngày tạo</label>
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
                        <th>Mã đơn</th>
                        <th>Khách hàng</th>
                        <th>Từ</th>
                        <th>Đến</th>
                        <th>Chi nhánh</th>
                        <th>Shipper</th>
                        <th>Trạng thái</th>
                        <th>Thao tác</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>

    <!-- Create/Edit Order Modal -->
    <div id="orderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Tạo đơn hàng mới</h3>
                    <button onclick="hideOrderModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="orderForm" onsubmit="handleOrderSubmit(event)">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Thông tin người gửi</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Họ tên</label>
                                    <input type="text" id="senderName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                    <input type="tel" id="senderPhone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                    <textarea id="senderAddress" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                </div>
                            </div>
                        </div>
                        <div>
                            <h4 class="font-medium text-gray-900 mb-4">Thông tin người nhận</h4>
                            <div class="space-y-4">
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Họ tên</label>
                                    <input type="text" id="receiverName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                                    <input type="tel" id="receiverPhone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                </div>
                                <div>
                                    <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                                    <textarea id="receiverAddress" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="mt-6">
                        <h4 class="font-medium text-gray-900 mb-4">Thông tin đơn hàng</h4>
                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Loại hàng</label>
                                <select id="packageType" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                    <option value="document">Tài liệu</option>
                                    <option value="parcel">Bưu kiện</option>
                                    <option value="food">Thực phẩm</option>
                                    <option value="fragile">Hàng dễ vỡ</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Khối lượng (kg)</label>
                                <input type="number" id="weight" step="0.1" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">COD (VNĐ)</label>
                                <input type="number" id="cod" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                            </div>
                        </div>
                        <div class="mt-4">
                            <label class="block text-sm font-medium text-gray-700">Ghi chú</label>
                            <textarea id="note" rows="2" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary"></textarea>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors">
                            Tạo đơn hàng
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Order Modal -->
    <div id="viewOrderModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-4xl w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900">Chi tiết đơn hàng #<span id="viewOrderId"></span></h3>
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
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
            },
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
                            pending: 'Chờ xử lý',
                            processing: 'Đang xử lý',
                            in_transit: 'Đang giao',
                            delivered: 'Đã giao',
                            cancelled: 'Đã hủy',
                            issue: 'Có vấn đề'
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
                                <button onclick="editOrder('${data.id}')" class="text-yellow-600 hover:text-yellow-900">
                                    <i class="fas fa-edit"></i>
                                </button>
                                <button onclick="deleteOrder('${data.id}')" class="text-red-600 hover:text-red-900">
                                    <i class="fas fa-trash"></i>
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

    // Show create order modal
    function showCreateOrderModal() {
        $('#modalTitle').text('Tạo đơn hàng mới');
        $('#orderForm')[0].reset();
        $('#orderModal').removeClass('hidden').addClass('flex');
    }

    // Hide order modal
    function hideOrderModal() {
        $('#orderModal').removeClass('flex').addClass('hidden');
    }

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

    // Edit order
    function editOrder(id) {
        const order = orders.find(o => o.id === id);
        if (!order) return;

        $('#modalTitle').text('Chỉnh sửa đơn hàng #' + id);
        $('#senderName').val(order.sender_name);
        $('#senderPhone').val(order.sender_phone);
        $('#senderAddress').val(order.pickup_address);
        $('#receiverName').val(order.receiver_name);
        $('#receiverPhone').val(order.receiver_phone);
        $('#receiverAddress').val(order.delivery_address);
        $('#packageType').val(order.package_type);
        $('#weight').val(order.weight);
        $('#cod').val(order.cod);
        $('#note').val(order.note);

        $('#orderModal').removeClass('hidden').addClass('flex');
    }

    // Handle order form submission
    async function handleOrderSubmit(event) {
        event.preventDefault();

        const formData = {
            sender_name: $('#senderName').val(),
            sender_phone: $('#senderPhone').val(),
            pickup_address: $('#senderAddress').val(),
            receiver_name: $('#receiverName').val(),
            receiver_phone: $('#receiverPhone').val(),
            delivery_address: $('#receiverAddress').val(),
            package_type: $('#packageType').val(),
            weight: $('#weight').val(),
            cod: $('#cod').val(),
            note: $('#note').val()
        };

        try {
            const response = await fetch('/api/admin/orders', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('Network response was not ok');

            hideOrderModal();
            loadOrders();
            alert('Tạo đơn hàng thành công!');
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Delete order
    async function deleteOrder(id) {
        if (!confirm('Bạn có chắc muốn xóa đơn hàng này?')) return;

        try {
            const response = await fetch(`/api/admin/orders/${id}`, {
                method: 'DELETE'
            });

            if (!response.ok) throw new Error('Network response was not ok');

            loadOrders();
            alert('Xóa đơn hàng thành công!');
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkAuth('admin');
    });
</script>
@endsection
