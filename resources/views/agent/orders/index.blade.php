@extends('layouts.unified')

@section('title', 'Quản lý đơn hàng - Agent')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('navigation')
    <a href="/agent/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/agent/orders" class="text-red-600 font-medium">Đơn hàng</a>
    <a href="/agent/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/agent/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Filters -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-bold text-gray-900">Quản lý đơn hàng</h1>
            <div class="flex space-x-2">
                <button onclick="exportOrders()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-download mr-2"></i>Xuất báo cáo
                </button>
                <button onclick="batchActions()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-tasks mr-2"></i>Xử lý hàng loạt
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
            <select id="statusFilter" class="border rounded-lg px-3 py-2">
                <option value="">Tất cả trạng thái</option>
                <option value="pending">Chờ xử lý</option>
                <option value="confirmed">Đã xác nhận</option>
                <option value="assigned">Đã phân công</option>
                <option value="pickup">Đang lấy hàng</option>
                <option value="picked_up">Đã lấy hàng</option>
                <option value="in_transit">Đang vận chuyển</option>
                <option value="delivering">Đang giao</option>
                <option value="delivered">Đã giao</option>
                <option value="cancelled">Đã hủy</option>
            </select>
            
            <input type="date" id="dateFrom" class="border rounded-lg px-3 py-2" placeholder="Từ ngày">
            <input type="date" id="dateTo" class="border rounded-lg px-3 py-2" placeholder="Đến ngày">
            <input type="text" id="searchInput" class="border rounded-lg px-3 py-2" placeholder="Tìm kiếm mã đơn, tên KH...">
        </div>
        
        <div class="mt-4 flex space-x-2">
            <button onclick="applyFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                <i class="fas fa-search mr-2"></i>Tìm kiếm
            </button>
            <button onclick="resetFilters()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                <i class="fas fa-undo mr-2"></i>Đặt lại
            </button>
        </div>
    </div>

    <!-- Orders Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            <input type="checkbox" id="selectAll" onchange="toggleSelectAll()">
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mã đơn</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khách hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Giá trị</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ngày tạo</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody" class="bg-white divide-y divide-gray-200">
                    <!-- Data loaded by JavaScript -->
                </tbody>
            </table>
        </div>
        
        <!-- Pagination -->
        <div id="pagination" class="bg-gray-50 px-4 py-3 flex items-center justify-between border-t border-gray-200">
            <!-- Pagination controls -->
        </div>
    </div>
</div>

<!-- Modals will be added here -->

<!-- Assign Shipper Modal -->
<div id="assignShipperModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-lg w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Phân công Shipper</h3>
                <p class="text-sm text-gray-500 mt-1">Chọn shipper cho đơn hàng <span id="assignOrderNumber"></span></p>
            </div>
            
            <div class="px-6 py-4">
                <div class="mb-4">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Chọn Shipper</label>
                    <select id="shipperSelect" class="w-full border border-gray-300 rounded-md px-3 py-2">
                        <option value="">Đang tải...</option>
                    </select>
                </div>
                
                <div id="shipperInfo" class="hidden bg-gray-50 p-3 rounded-md">
                    <h4 class="font-medium text-gray-900 mb-2">Thông tin Shipper</h4>
                    <div class="grid grid-cols-2 gap-2 text-sm">
                        <div>Tên: <span id="shipperName"></span></div>
                        <div>Điện thoại: <span id="shipperPhone"></span></div>
                        <div>Đơn đang xử lý: <span id="shipperActiveOrders"></span></div>
                        <div>Trạng thái: <span id="shipperStatus"></span></div>
                    </div>
                </div>
            </div>
            
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="hideAssignModal()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Hủy
                </button>
                <button type="button" onclick="confirmAssignShipper()" class="bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                    Phân công
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentPage = 1;
let selectedOrders = [];

// Load orders on page load
document.addEventListener('DOMContentLoaded', function() {
    loadOrders();
    checkAuth('agent');
});

async function loadOrders(page = 1) {
    try {
        const statusFilter = document.getElementById('statusFilter').value;
        const dateFrom = document.getElementById('dateFrom').value;
        const dateTo = document.getElementById('dateTo').value;
        const search = document.getElementById('searchInput').value;
        
        const params = new URLSearchParams({
            page: page,
            per_page: 15
        });
        
        if (statusFilter) params.append('status', statusFilter);
        if (dateFrom) params.append('date_from', dateFrom);
        if (dateTo) params.append('date_to', dateTo);
        if (search) params.append('search', search);
        
        const response = await fetch(`/api/agent/orders?${params}`);
        const result = await response.json();
        
        if (result.success) {
            renderOrdersTable(result.data);
            renderPagination(result.pagination);
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading orders:', error);
        document.getElementById('ordersTableBody').innerHTML = `
            <tr><td colspan="8" class="text-center text-red-500 py-4">Lỗi tải dữ liệu: ${error.message}</td></tr>
        `;
    }
}

function renderOrdersTable(orders) {
    const tbody = document.getElementById('ordersTableBody');
    tbody.innerHTML = orders.map(order => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4">
                <input type="checkbox" value="${order.id}" onchange="toggleOrderSelection(${order.id})">
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">#${order.tracking_number}</div>
                <div class="text-sm text-gray-500">${order.package_type}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm font-medium text-gray-900">${order.customer.name}</div>
                <div class="text-sm text-gray-500">${order.customer.phone}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(order.status)}">
                    ${getStatusText(order.status)}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${order.shipper ? order.shipper.name : 'Chưa phân công'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                ${order.total_fee.toLocaleString()} VNĐ
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${new Date(order.created_at).toLocaleDateString('vi-VN')}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                <div class="flex space-x-2">
                    <button onclick="viewOrderDetails(${order.id})" class="text-blue-600 hover:text-blue-900">
                        Chi tiết
                    </button>
                    ${getActionButtons(order)}
                </div>
            </td>
        </tr>
    `).join('');
}

function getStatusBadgeClass(status) {
    const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'confirmed': 'bg-blue-100 text-blue-800',
        'assigned': 'bg-purple-100 text-purple-800',
        'pickup': 'bg-orange-100 text-orange-800',
        'picked_up': 'bg-indigo-100 text-indigo-800',
        'in_transit': 'bg-cyan-100 text-cyan-800',
        'delivering': 'bg-green-100 text-green-800',
        'delivered': 'bg-green-100 text-green-800',
        'cancelled': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'pending': 'Chờ xử lý',
        'confirmed': 'Đã xác nhận',
        'assigned': 'Đã phân công',
        'pickup': 'Đang lấy',
        'picked_up': 'Đã lấy',
        'in_transit': 'Vận chuyển',
        'delivering': 'Đang giao',
        'delivered': 'Đã giao',
        'cancelled': 'Đã hủy'
    };
    return texts[status] || status;
}

function getActionButtons(order) {
    let buttons = '';
    
    if (order.status === 'pending') {
        buttons += `<button onclick="confirmOrder(${order.id})" class="text-green-600 hover:text-green-900">Xác nhận</button>`;
    }
    
    if (order.status === 'confirmed') {
        buttons += `<button onclick="assignShipperModal(${order.id})" class="text-purple-600 hover:text-purple-900">Phân công</button>`;
    }
    
    return buttons;
}

function renderPagination(pagination) {
    const paginationDiv = document.getElementById('pagination');
    
    if (!pagination || pagination.last_page <= 1) {
        paginationDiv.innerHTML = `
            <div class="text-sm text-gray-700">
                Hiển thị ${pagination?.total || 0} kết quả
            </div>
        `;
        return;
    }
    
    let paginationHTML = `
        <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
                Hiển thị ${(pagination.current_page - 1) * pagination.per_page + 1} - 
                ${Math.min(pagination.current_page * pagination.per_page, pagination.total)} 
                của ${pagination.total} kết quả
            </div>
            <div class="flex space-x-1">
    `;
    
    // Previous button
    if (pagination.current_page > 1) {
        paginationHTML += `
            <button onclick="loadOrders(${pagination.current_page - 1})" 
                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                Trước
            </button>
        `;
    }
    
    // Page numbers
    for (let i = Math.max(1, pagination.current_page - 2); 
         i <= Math.min(pagination.last_page, pagination.current_page + 2); 
         i++) {
        const isActive = i === pagination.current_page;
        paginationHTML += `
            <button onclick="loadOrders(${i})" 
                    class="px-3 py-2 text-sm ${isActive ? 'bg-blue-500 text-white' : 'bg-white border border-gray-300 text-gray-500 hover:bg-gray-50'} rounded-md">
                ${i}
            </button>
        `;
    }
    
    // Next button
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <button onclick="loadOrders(${pagination.current_page + 1})" 
                    class="px-3 py-2 text-sm bg-white border border-gray-300 text-gray-500 hover:bg-gray-50 rounded-md">
                Sau
            </button>
        `;
    }
    
    paginationHTML += `
            </div>
        </div>
    `;
    
    paginationDiv.innerHTML = paginationHTML;
}

function applyFilters() {
    currentPage = 1;
    loadOrders(currentPage);
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('dateFrom').value = '';
    document.getElementById('dateTo').value = '';
    document.getElementById('searchInput').value = '';
    applyFilters();
}

async function confirmOrder(orderId) {
    if (confirm('Bạn có chắc muốn xác nhận đơn hàng này?')) {
        try {
            const response = await fetch(`/api/agent/orders/${orderId}/confirm`, {
                method: 'POST',
                headers: {
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });
            
            const result = await response.json();
            if (result.success) {
                alert('Xác nhận thành công!');
                loadOrders(currentPage);
            } else {
                alert('Lỗi: ' + result.message);
            }
        } catch (error) {
            alert('Có lỗi xảy ra: ' + error.message);
        }
    }
}

function viewOrderDetails(orderId) {
    window.location.href = `/agent/orders/${orderId}`;
}

// Assign Shipper Functions
let currentOrderId = null;

async function assignShipperModal(orderId) {
    currentOrderId = orderId;
    
    // Hiện thị modal
    document.getElementById('assignShipperModal').classList.remove('hidden');
    
    // Lấy thông tin đơn hàng
    try {
        const orderResponse = await fetch(`/api/agent/orders/${orderId}`);
        const orderResult = await orderResponse.json();
        
        if (orderResult.success) {
            document.getElementById('assignOrderNumber').textContent = orderResult.data.tracking_number;
        }
    } catch (error) {
        console.error('Error loading order:', error);
    }
    
    // Load available shippers
    loadAvailableShippers();
}

async function loadAvailableShippers() {
    try {
        const response = await fetch('/api/agent/available-shippers');
        const shippers = await response.json();
        
        const select = document.getElementById('shipperSelect');
        
        if (shippers.error) {
            select.innerHTML = '<option value="">Không có shipper khả dụng</option>';
            return;
        }
        
        // Filter available shippers
        const availableShippers = shippers.filter(s => s.is_online && s.active_orders < 5);
        
        if (availableShippers.length === 0) {
            select.innerHTML = '<option value="">Không có shipper sẵn sàng</option>';
            return;
        }
        
        // Populate select options
        select.innerHTML = '<option value="">-- Chọn shipper --</option>' +
            availableShippers.map(shipper => 
                `<option value="${shipper.id}" data-shipper='${JSON.stringify(shipper)}'>
                    ${shipper.name} (${shipper.active_orders} đơn)
                </option>`
            ).join('');
        
        // Add change event listener
        select.onchange = function() {
            if (this.value) {
                const shipperData = JSON.parse(this.selectedOptions[0].dataset.shipper);
                showShipperInfo(shipperData);
            } else {
                hideShipperInfo();
            }
        };
        
    } catch (error) {
        console.error('Error loading shippers:', error);
        document.getElementById('shipperSelect').innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
    }
}

function showShipperInfo(shipper) {
    document.getElementById('shipperName').textContent = shipper.name;
    document.getElementById('shipperPhone').textContent = shipper.phone;
    document.getElementById('shipperActiveOrders').textContent = shipper.active_orders;
    document.getElementById('shipperStatus').textContent = shipper.status_text;
    document.getElementById('shipperInfo').classList.remove('hidden');
}

function hideShipperInfo() {
    document.getElementById('shipperInfo').classList.add('hidden');
}

function hideAssignModal() {
    document.getElementById('assignShipperModal').classList.add('hidden');
    document.getElementById('shipperSelect').innerHTML = '<option value="">Đang tải...</option>';
    hideShipperInfo();
    currentOrderId = null;
}

async function confirmAssignShipper() {
    const shipperId = document.getElementById('shipperSelect').value;
    
    if (!shipperId) {
        alert('Vui lòng chọn shipper');
        return;
    }
    
    if (!currentOrderId) {
        alert('Không tìm thấy thông tin đơn hàng');
        return;
    }
    
    try {
        const response = await fetch(`/api/agent/orders/${currentOrderId}/assign-shipper`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({
                shipper_id: shipperId
            })
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            hideAssignModal();
            loadOrders(currentPage); // Refresh table
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        alert('Có lỗi xảy ra: ' + error.message);
    }
}

function exportOrders() {
    alert('Chức năng xuất báo cáo đang được phát triển');
}

function batchActions() {
    alert('Chức năng xử lý hàng loạt đang được phát triển');
}
</script>
@endsection