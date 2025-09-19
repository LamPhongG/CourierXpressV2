@extends('layouts.unified')

@section('title', 'Quản lý Shipper - Agent')

@section('head')
    <meta name="csrf-token" content="{{ csrf_token() }}">
@endsection

@section('navigation')
    <a href="/agent/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/agent/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/agent/shippers" class="text-red-600 font-medium">Shipper</a>
    <a href="/agent/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
        <h1 class="text-2xl font-bold mb-2">Quản lý Shipper</h1>
        <p class="text-green-100">Xem và quản lý các shipper trong khu vực của bạn</p>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-motorcycle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Shipper Online</dt>
                            <dd class="text-lg font-medium text-gray-900" id="onlineShippers">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-users text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tổng Shipper</dt>
                            <dd class="text-lg font-medium text-gray-900" id="totalShippers">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đang bận</dt>
                            <dd class="text-lg font-medium text-gray-900" id="busyShippers">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Sẵn sàng</dt>
                            <dd class="text-lg font-medium text-gray-900" id="availableShippers">0</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters & Actions -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg font-medium text-gray-900">Danh sách Shipper</h3>
            <div class="flex space-x-2">
                <button onclick="showCreateShipperModal()" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                    <i class="fas fa-plus mr-2"></i>Thêm Shipper mới
                </button>
                <button onclick="exportShippers()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-download mr-2"></i>Xuất danh sách
                </button>
            </div>
        </div>
        
        <!-- Filters -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4">
            <select id="statusFilter" class="border rounded-lg px-3 py-2">
                <option value="">Tất cả trạng thái</option>
                <option value="active">Hoạt động</option>
                <option value="inactive">Không hoạt động</option>
                <option value="suspended">Đình chỉ</option>
            </select>
            
            <select id="onlineFilter" class="border rounded-lg px-3 py-2">
                <option value="">Tất cả</option>
                <option value="1">Online</option>
                <option value="0">Offline</option>
            </select>
            
            <input type="text" id="searchInput" class="border rounded-lg px-3 py-2" placeholder="Tìm kiếm tên, email...">
            
            <div class="flex space-x-2">
                <button onclick="applyFilters()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                    <i class="fas fa-search mr-2"></i>Tìm kiếm
                </button>
                <button onclick="resetFilters()" class="bg-gray-600 text-white px-4 py-2 rounded-lg hover:bg-gray-700">
                    <i class="fas fa-undo mr-2"></i>Đặt lại
                </button>
            </div>
        </div>
    </div>

    <!-- Shipper Table -->
    <div class="bg-white rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Liên hệ</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Trạng thái</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Đơn hàng</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Khu vực</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                    </tr>
                </thead>
                <tbody id="shippersTableBody" class="bg-white divide-y divide-gray-200">
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

<!-- Create Shipper Modal -->
<div id="createShipperModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden">
    <div class="flex items-center justify-center min-h-screen p-4">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Thêm Shipper mới</h3>
            </div>
            <form id="createShipperForm" class="px-6 py-4 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Tên đầy đủ *</label>
                    <input type="text" name="name" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Email *</label>
                    <input type="email" name="email" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Số điện thoại *</label>
                    <input type="text" name="phone" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Mật khẩu *</label>
                    <input type="password" name="password" required class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Khu vực</label>
                    <input type="text" name="city" class="mt-1 block w-full border border-gray-300 rounded-md px-3 py-2">
                </div>
            </form>
            <div class="px-6 py-4 border-t border-gray-200 flex justify-end space-x-2">
                <button type="button" onclick="hideCreateShipperModal()" class="bg-gray-600 text-white px-4 py-2 rounded-md hover:bg-gray-700">
                    Hủy
                </button>
                <button type="button" onclick="createShipper()" class="bg-green-600 text-white px-4 py-2 rounded-md hover:bg-green-700">
                    Tạo Shipper
                </button>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
let currentPage = 1;

// Load shippers on page load
document.addEventListener('DOMContentLoaded', function() {
    loadShippers();
    loadShipperStats();
    checkAuth('agent');
});

async function loadShippers(page = 1) {
    try {
        const statusFilter = document.getElementById('statusFilter').value;
        const onlineFilter = document.getElementById('onlineFilter').value;
        const search = document.getElementById('searchInput').value;
        
        const params = new URLSearchParams({
            page: page,
            per_page: 15
        });
        
        if (statusFilter) params.append('status', statusFilter);
        if (onlineFilter !== '') params.append('is_online', onlineFilter);
        if (search) params.append('search', search);
        
        const response = await fetch(`/api/agent/shippers?${params}`);
        const result = await response.json();
        
        if (result.success) {
            renderShippersTable(result.data);
            renderPagination(result.pagination);
            currentPage = page;
        } else {
            throw new Error(result.message);
        }
    } catch (error) {
        console.error('Error loading shippers:', error);
        document.getElementById('shippersTableBody').innerHTML = `
            <tr><td colspan="6" class="text-center text-red-500 py-4">Lỗi tải dữ liệu: ${error.message}</td></tr>
        `;
    }
}

async function loadShipperStats() {
    try {
        const response = await fetch('/api/agent/shipper-status');
        const shippers = await response.json();
        
        if (shippers.error) {
            console.error('Lỗi tải thống kê:', shippers.error);
            return;
        }
        
        const stats = {
            total: shippers.length,
            online: shippers.filter(s => s.status === 'online').length,
            busy: shippers.filter(s => s.status === 'busy').length,
            available: shippers.filter(s => s.status === 'online').length
        };
        
        document.getElementById('totalShippers').textContent = stats.total;
        document.getElementById('onlineShippers').textContent = stats.online + stats.busy;
        document.getElementById('busyShippers').textContent = stats.busy;
        document.getElementById('availableShippers').textContent = stats.available;
    } catch (error) {
        console.error('Error loading shipper stats:', error);
    }
}

function renderShippersTable(shippers) {
    const tbody = document.getElementById('shippersTableBody');
    
    if (shippers.length === 0) {
        tbody.innerHTML = `
            <tr><td colspan="6" class="text-center text-gray-500 py-8">Không có shipper nào</td></tr>
        `;
        return;
    }
    
    tbody.innerHTML = shippers.map(shipper => `
        <tr class="hover:bg-gray-50">
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                    <div class="flex-shrink-0 h-10 w-10">
                        <div class="h-10 w-10 rounded-full bg-gray-300 flex items-center justify-center">
                            <i class="fas fa-user text-gray-600"></i>
                        </div>
                    </div>
                    <div class="ml-4">
                        <div class="text-sm font-medium text-gray-900">${shipper.name}</div>
                        <div class="text-sm text-gray-500">ID: ${shipper.id}</div>
                    </div>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="text-sm text-gray-900">${shipper.email}</div>
                <div class="text-sm text-gray-500">${shipper.phone}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex flex-col space-y-1">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getStatusBadgeClass(shipper.status)}">
                        ${getStatusText(shipper.status)}
                    </span>
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${getOnlineBadgeClass(shipper.online_status)}">
                        ${getOnlineStatusText(shipper.online_status)}
                    </span>
                </div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                <div>Hoạt động: ${shipper.active_orders}</div>
                <div>Hoàn thành: ${shipper.completed_orders}</div>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                ${shipper.city || 'Chưa cập nhật'}
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium space-x-2">
                <button onclick="viewShipperDetails(${shipper.id})" class="text-blue-600 hover:text-blue-900">
                    Chi tiết
                </button>
                <button onclick="editShipper(${shipper.id})" class="text-indigo-600 hover:text-indigo-900">
                    Sửa
                </button>
                <button onclick="toggleShipperOnline(${shipper.id})" class="text-${shipper.is_online ? 'red' : 'green'}-600 hover:text-${shipper.is_online ? 'red' : 'green'}-900">
                    ${shipper.is_online ? 'Offline' : 'Online'}
                </button>
            </td>
        </tr>
    `).join('');
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
            <button onclick="loadShippers(${pagination.current_page - 1})" 
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
            <button onclick="loadShippers(${i})" 
                    class="px-3 py-2 text-sm ${isActive ? 'bg-blue-500 text-white' : 'bg-white border border-gray-300 text-gray-500 hover:bg-gray-50'} rounded-md">
                ${i}
            </button>
        `;
    }
    
    // Next button
    if (pagination.current_page < pagination.last_page) {
        paginationHTML += `
            <button onclick="loadShippers(${pagination.current_page + 1})" 
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

function getStatusBadgeClass(status) {
    const classes = {
        'active': 'bg-green-100 text-green-800',
        'inactive': 'bg-gray-100 text-gray-800',
        'suspended': 'bg-red-100 text-red-800'
    };
    return classes[status] || 'bg-gray-100 text-gray-800';
}

function getStatusText(status) {
    const texts = {
        'active': 'Hoạt động',
        'inactive': 'Không hoạt động',
        'suspended': 'Đình chỉ'
    };
    return texts[status] || status;
}

function getOnlineBadgeClass(onlineStatus) {
    const classes = {
        'available': 'bg-green-100 text-green-800',
        'busy': 'bg-yellow-100 text-yellow-800',
        'offline': 'bg-gray-100 text-gray-800'
    };
    return classes[onlineStatus] || 'bg-gray-100 text-gray-800';
}

function getOnlineStatusText(onlineStatus) {
    const texts = {
        'available': 'Sẵn sàng',
        'busy': 'Bận',
        'offline': 'Offline'
    };
    return texts[onlineStatus] || onlineStatus;
}

function applyFilters() {
    currentPage = 1;
    loadShippers(currentPage);
}

function resetFilters() {
    document.getElementById('statusFilter').value = '';
    document.getElementById('onlineFilter').value = '';
    document.getElementById('searchInput').value = '';
    applyFilters();
}

// Modal functions
function showCreateShipperModal() {
    document.getElementById('createShipperModal').classList.remove('hidden');
}

function hideCreateShipperModal() {
    document.getElementById('createShipperModal').classList.add('hidden');
    document.getElementById('createShipperForm').reset();
}

async function createShipper() {
    const form = document.getElementById('createShipperForm');
    const formData = new FormData(form);
    
    try {
        const response = await fetch('/api/agent/shippers', {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: formData
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert('Tạo shipper thành công!');
            hideCreateShipperModal();
            loadShippers(currentPage);
            loadShipperStats();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        alert('Có lỗi xảy ra: ' + error.message);
    }
}

// Other action functions
function viewShipperDetails(shipperId) {
    alert(`Xem chi tiết shipper ID: ${shipperId}`);
    // Implementation sẽ được thêm sau
}

function editShipper(shipperId) {
    alert(`Chỉnh sửa shipper ID: ${shipperId}`);
    // Implementation sẽ được thêm sau
}

async function toggleShipperOnline(shipperId) {
    try {
        const response = await fetch(`/api/agent/shippers/${shipperId}/toggle-online`, {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            }
        });
        
        const result = await response.json();
        
        if (result.success) {
            alert(result.message);
            loadShippers(currentPage);
            loadShipperStats();
        } else {
            alert('Lỗi: ' + result.message);
        }
    } catch (error) {
        alert('Có lỗi xảy ra: ' + error.message);
    }
}

function exportShippers() {
    alert('Chức năng xuất danh sách đang được phát triển');
}
</script>
@endsection