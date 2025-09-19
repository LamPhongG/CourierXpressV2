<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Audit Logs - Admin - CourierXpress</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: "#3B82F6",
                        secondary: "#F97316", 
                        accent: "#10B981"
                    }
                }
            }
        }
    </script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <meta name="csrf-token" content="{{ csrf_token() }}">
</head>
<body class="bg-gray-50">
    <!-- Header -->
    <header class="bg-white shadow-lg sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center h-16">
                <div class="flex items-center">
                    <a href="/admin/dashboard" class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-shipping-fast text-white"></i>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">CourierXpress</span>
                    </a>
                    <span class="ml-4 px-3 py-1 text-sm font-medium rounded-full bg-red-100 text-red-800">
                        Audit Logs
                    </span>
                </div>
                <nav class="hidden md:flex items-center space-x-6">
                    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
                    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
                    <a href="/admin/users" class="text-gray-700 hover:text-red-600">Người dùng</a>
                    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Cài đặt</a>
                    <a href="/admin/audit" class="text-red-600 font-medium">Audit Logs</a>
                </nav>
                <div class="flex items-center space-x-4">
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user-shield text-red-600 text-sm"></i>
                        </div>
                        <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <button onclick="logout()" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors">
                        <i class="fas fa-sign-out-alt mr-2"></i>Đăng xuất
                    </button>
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <!-- Breadcrumb -->
        <nav class="flex mb-6" aria-label="Breadcrumb">
            <ol class="inline-flex items-center space-x-1 md:space-x-3">
                <li class="inline-flex items-center">
                    <a href="/admin/dashboard" class="text-gray-700 hover:text-blue-600">
                        <i class="fas fa-home"></i>
                    </a>
                </li>
                <li>
                    <div class="flex items-center">
                        <i class="fas fa-chevron-right text-gray-400 mx-3"></i>
                        <span class="text-gray-500">Audit Logs</span>
                    </div>
                </li>
            </ol>
        </nav>

        <!-- Page Header -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 rounded-lg shadow-lg p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Audit Logs</h1>
                <p class="text-indigo-100">Theo dõi và kiểm tra tất cả các hoạt động trong hệ thống</p>
            </div>
        </div>

        <!-- Statistics -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-list text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Tổng hoạt động</p>
                        <p class="text-2xl font-bold text-gray-900" id="totalActions">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-calendar-day text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Hôm nay</p>
                        <p class="text-2xl font-bold text-gray-900" id="todayActions">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-users text-purple-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Người dùng hoạt động</p>
                        <p class="text-2xl font-bold text-gray-900" id="uniqueUsers">-</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-lg p-6">
                <div class="flex items-center">
                    <div class="w-12 h-12 bg-orange-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-orange-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-sm text-gray-600">Hoạt động cao nhất</p>
                        <p class="text-lg font-bold text-gray-900" id="topAction">-</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filters and Actions -->
        <div class="bg-white rounded-lg shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-4 lg:mb-0">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Loại hoạt động</label>
                        <select id="actionFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                            <option value="">Tất cả hoạt động</option>
                            <option value="login">Đăng nhập</option>
                            <option value="user">Quản lý người dùng</option>
                            <option value="order">Quản lý đơn hàng</option>
                            <option value="settings">Cài đặt hệ thống</option>
                            <option value="system">Hệ thống</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Từ ngày</label>
                        <input type="date" id="dateFromFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Đến ngày</label>
                        <input type="date" id="dateToFilter" class="w-full px-3 py-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div class="flex items-end">
                        <button onclick="applyFilters()" class="w-full bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                            <i class="fas fa-filter mr-2"></i>Lọc
                        </button>
                    </div>
                </div>
                <div class="flex space-x-2">
                    <button onclick="exportLogs()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-download mr-2"></i>Xuất CSV
                    </button>
                    <button onclick="refreshLogs()" class="bg-gray-600 hover:bg-gray-700 text-white px-4 py-2 rounded-md font-medium transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>

        <!-- Logs Table -->
        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Lịch sử hoạt động</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thời gian</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Người dùng</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoạt động</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mô tả</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody id="logsTableBody" class="bg-white divide-y divide-gray-200">
                        <!-- Data will be loaded here -->
                    </tbody>
                </table>
            </div>
            <div class="bg-gray-50 px-6 py-3">
                <div class="flex items-center justify-between">
                    <div class="text-sm text-gray-700">
                        Hiển thị <span id="showingFrom">1</span> đến <span id="showingTo">15</span> của <span id="totalLogs">0</span> bản ghi
                    </div>
                    <div class="flex space-x-2" id="pagination">
                        <!-- Pagination will be loaded here -->
                    </div>
                </div>
            </div>
        </div>
    </main>

    <!-- Log Detail Modal -->
    <div id="logDetailModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden z-50">
        <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 transition-opacity">
                <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
            </div>
            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiết hoạt động</h3>
                    <div id="logDetailContent">
                        <!-- Detail content will be loaded here -->
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button onclick="hideLogDetail()" class="w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 sm:w-auto sm:text-sm">
                        Đóng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        let currentPage = 1;
        let currentFilters = {};

        document.addEventListener('DOMContentLoaded', function() {
            loadStatistics();
            loadLogs();
        });

        async function loadStatistics() {
            try {
                const response = await fetch('/admin/api/audit/statistics');
                const stats = await response.json();
                
                document.getElementById('totalActions').textContent = stats.total_actions;
                document.getElementById('todayActions').textContent = stats.today_actions;
                document.getElementById('uniqueUsers').textContent = stats.unique_users;
                
                if (stats.top_actions && stats.top_actions.length > 0) {
                    document.getElementById('topAction').textContent = stats.top_actions[0].action;
                }
            } catch (error) {
                console.error('Error loading statistics:', error);
            }
        }

        async function loadLogs(page = 1) {
            try {
                const params = new URLSearchParams({
                    page: page,
                    ...currentFilters
                });
                
                const response = await fetch(`/admin/api/audit/logs?${params}`);
                const data = await response.json();
                
                renderLogsTable(data.data);
                renderPagination(data);
                updateShowingInfo(data);
            } catch (error) {
                console.error('Error loading logs:', error);
                showNotification('Lỗi khi tải dữ liệu audit logs', 'error');
            }
        }

        function renderLogsTable(logs) {
            const tbody = document.getElementById('logsTableBody');
            if (!logs || logs.length === 0) {
                tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Không có dữ liệu</td></tr>';
                return;
            }

            tbody.innerHTML = logs.map(log => `
                <tr class="hover:bg-gray-50">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${new Date(log.created_at).toLocaleString('vi-VN')}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                        ${log.user_name || 'System'}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        ${getActionBadge(log.action)}
                    </td>
                    <td class="px-6 py-4 text-sm text-gray-900 max-w-xs truncate">
                        ${log.description}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                        ${log.ip_address}
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                        <button onclick="showLogDetail(${log.id})" class="text-blue-600 hover:text-blue-900">
                            <i class="fas fa-eye"></i>
                        </button>
                    </td>
                </tr>
            `).join('');
        }

        function getActionBadge(action) {
            const actionTypes = {
                'login': { color: 'bg-green-100 text-green-800', text: 'Đăng nhập' },
                'user': { color: 'bg-blue-100 text-blue-800', text: 'Người dùng' },
                'order': { color: 'bg-purple-100 text-purple-800', text: 'Đơn hàng' },
                'settings': { color: 'bg-orange-100 text-orange-800', text: 'Cài đặt' },
                'system': { color: 'bg-gray-100 text-gray-800', text: 'Hệ thống' }
            };

            const type = action.split('.')[0];
            const config = actionTypes[type] || { color: 'bg-gray-100 text-gray-800', text: type };
            
            return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${config.color}">${config.text}</span>`;
        }

        function renderPagination(data) {
            const pagination = document.getElementById('pagination');
            if (data.last_page <= 1) {
                pagination.innerHTML = '';
                return;
            }

            let paginationHTML = '';
            
            // Previous button
            if (data.current_page > 1) {
                paginationHTML += `<button onclick="loadLogs(${data.current_page - 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-l-md hover:bg-gray-50">Trước</button>`;
            }

            // Page numbers
            for (let i = Math.max(1, data.current_page - 2); i <= Math.min(data.last_page, data.current_page + 2); i++) {
                const isActive = i === data.current_page;
                paginationHTML += `<button onclick="loadLogs(${i})" class="px-3 py-2 text-sm font-medium ${isActive ? 'text-blue-600 bg-blue-50 border-blue-500' : 'text-gray-500 bg-white border-gray-300 hover:bg-gray-50'} border">${i}</button>`;
            }

            // Next button
            if (data.current_page < data.last_page) {
                paginationHTML += `<button onclick="loadLogs(${data.current_page + 1})" class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-r-md hover:bg-gray-50">Sau</button>`;
            }

            pagination.innerHTML = paginationHTML;
        }

        function updateShowingInfo(data) {
            const from = (data.current_page - 1) * data.per_page + 1;
            const to = Math.min(data.current_page * data.per_page, data.total);
            
            document.getElementById('showingFrom').textContent = from;
            document.getElementById('showingTo').textContent = to;
            document.getElementById('totalLogs').textContent = data.total;
        }

        function applyFilters() {
            currentFilters = {
                action: document.getElementById('actionFilter').value,
                date_from: document.getElementById('dateFromFilter').value,
                date_to: document.getElementById('dateToFilter').value
            };

            // Remove empty filters
            Object.keys(currentFilters).forEach(key => {
                if (!currentFilters[key]) {
                    delete currentFilters[key];
                }
            });

            currentPage = 1;
            loadLogs(1);
        }

        function refreshLogs() {
            loadLogs(currentPage);
        }

        async function exportLogs() {
            try {
                const params = new URLSearchParams(currentFilters);
                window.open(`/admin/api/audit/export?${params}`, '_blank');
            } catch (error) {
                console.error('Error exporting logs:', error);
                showNotification('Lỗi khi xuất dữ liệu', 'error');
            }
        }

        async function showLogDetail(logId) {
            try {
                const response = await fetch(`/admin/api/audit/logs/${logId}`);
                const log = await response.json();
                
                const content = `
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">ID</label>
                                <p class="text-sm text-gray-900">${log.id}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Thời gian</label>
                                <p class="text-sm text-gray-900">${new Date(log.created_at).toLocaleString('vi-VN')}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Người dùng</label>
                                <p class="text-sm text-gray-900">${log.user_name || 'System'}</p>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">IP Address</label>
                                <p class="text-sm text-gray-900">${log.ip_address}</p>
                            </div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Hoạt động</label>
                            <p class="text-sm text-gray-900">${log.action}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Mô tả</label>
                            <p class="text-sm text-gray-900">${log.description}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">User Agent</label>
                            <p class="text-sm text-gray-900 break-all">${log.user_agent}</p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Chi tiết</label>
                            <pre class="text-sm text-gray-900 bg-gray-100 p-3 rounded overflow-auto">${JSON.stringify(log.details, null, 2)}</pre>
                        </div>
                    </div>
                `;
                
                document.getElementById('logDetailContent').innerHTML = content;
                document.getElementById('logDetailModal').classList.remove('hidden');
            } catch (error) {
                console.error('Error loading log detail:', error);
                showNotification('Lỗi khi tải chi tiết', 'error');
            }
        }

        function hideLogDetail() {
            document.getElementById('logDetailModal').classList.add('hidden');
        }

        function showNotification(message, type) {
            const colors = {
                success: 'bg-green-600',
                error: 'bg-red-600',
                info: 'bg-blue-600'
            };
            
            const notification = document.createElement('div');
            notification.className = `fixed top-4 right-4 z-50 px-6 py-3 rounded-lg shadow-lg text-white ${colors[type] || colors.info}`;
            notification.innerHTML = `
                <div class="flex items-center">
                    <i class="fas ${type === 'success' ? 'fa-check-circle' : type === 'error' ? 'fa-exclamation-circle' : 'fa-info-circle'} mr-2"></i>
                    ${message}
                </div>
            `;
            
            document.body.appendChild(notification);
            setTimeout(() => document.body.removeChild(notification), 3000);
        }

        function logout() {
            if (confirm('Bạn có chắc muốn đăng xuất?')) {
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = '/logout';
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
</body>
</html>