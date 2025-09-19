@extends('layouts.unified')

@section('title', 'Admin - Quản lý Agent | CourierXpress')

@section('head')
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/admin/agents" class="text-red-600 font-medium">Chi nhánh</a>
    <a href="/admin/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Báo cáo</a>
    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Cài đặt</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Quản lý Agent</h2>
                <p class="mt-2 text-gray-600">Quản lý danh sách agent và phân quyền</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="showAddAgentModal()" class="bg-primary hover:bg-red-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-plus mr-2"></i> Thêm Agent
                </button>
                <button onclick="exportAgents()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i> Xuất Excel
                </button>
            </div>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-blue-100 rounded-full">
                    <i class="fas fa-user-tie text-blue-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Tổng Agent</p>
                    <p id="totalAgents" class="text-2xl font-bold text-blue-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-green-100 rounded-full">
                    <i class="fas fa-check-circle text-green-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Đang hoạt động</p>
                    <p id="activeAgents" class="text-2xl font-bold text-green-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-yellow-100 rounded-full">
                    <i class="fas fa-clock text-yellow-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Chờ xác nhận</p>
                    <p id="pendingAgents" class="text-2xl font-bold text-yellow-600">-</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-xl shadow-sm border p-6">
            <div class="flex items-center">
                <div class="p-3 bg-red-100 rounded-full">
                    <i class="fas fa-times-circle text-red-600 text-xl"></i>
                </div>
                <div class="ml-4">
                    <p class="text-gray-600 text-sm">Tạm khóa</p>
                    <p id="blockedAgents" class="text-2xl font-bold text-red-600">-</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Area -->
    <div class="bg-white shadow-sm rounded-lg overflow-hidden">
        <div class="p-6">
            <table id="agentsTable" class="w-full">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tên chi nhánh</th>
                        <th>Địa chỉ</th>
                        <th>Quản lý</th>
                        <th>SĐT</th>
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

    <!-- Add/Edit Agent Modal -->
    <div id="agentModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden items-center justify-center">
        <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
            <div class="p-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-lg font-medium text-gray-900" id="modalTitle">Thêm Agent mới</h3>
                    <button onclick="hideAgentModal()" class="text-gray-400 hover:text-gray-500">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <form id="agentForm" onsubmit="handleAgentSubmit(event)">
                    <div class="space-y-4">
                        <input type="hidden" id="agentId">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tên chi nhánh</label>
                            <input type="text" id="agentName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Địa chỉ</label>
                            <input type="text" id="agentAddress" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Tên quản lý</label>
                            <input type="text" id="managerName" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Số điện thoại</label>
                            <input type="tel" id="agentPhone" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Email</label>
                            <input type="email" id="agentEmail" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Trạng thái</label>
                            <select id="agentStatus" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-primary focus:ring-primary">
                                <option value="active">Hoạt động</option>
                                <option value="pending">Chờ xác nhận</option>
                                <option value="blocked">Tạm khóa</option>
                            </select>
                        </div>
                    </div>
                    <div class="mt-6">
                        <button type="submit" class="w-full bg-primary hover:bg-red-700 text-white py-2 px-4 rounded-md transition-colors">
                            Lưu thay đổi
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let agents = [];
    let table;

    $(document).ready(function() {
        // Initialize DataTable
        table = $('#agentsTable').DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
            },
            columns: [
                { 
                    data: 'id',
                    title: 'ID'
                },
                { 
                    data: 'name',
                    title: 'Tên chi nhánh',
                    defaultContent: 'Chưa cập nhật'
                },
                { 
                    data: 'address',
                    title: 'Địa chỉ',
                    defaultContent: 'Chưa cập nhật'
                },
                { 
                    data: 'manager',
                    title: 'Quản lý',
                    defaultContent: 'Chưa cập nhật'
                },
                { 
                    data: 'phone',
                    title: 'SĐT',
                    defaultContent: 'Chưa cập nhật'
                },
                { 
                    data: 'status',
                    title: 'Trạng thái',
                    render: function(data, type, row) {
                        if (type === 'display') {
                            const statusClasses = {
                                active: 'bg-green-100 text-green-800',
                                pending: 'bg-yellow-100 text-yellow-800',
                                blocked: 'bg-red-100 text-red-800'
                            };
                            const statusText = {
                                active: 'Hoạt động',
                                pending: 'Chờ xác nhận',
                                blocked: 'Tạm khóa'
                            };
                            const badgeClass = statusClasses[data] || 'bg-gray-100 text-gray-800';
                            const text = statusText[data] || data;
                            return `<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${badgeClass}">${text}</span>`;
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    title: 'Thao tác',
                    orderable: false,
                    render: function(data, type, row) {
                        if (type === 'display') {
                            return `
                                <div class="flex space-x-2">
                                    <button onclick="editAgent('${row.id}')" class="text-blue-600 hover:text-blue-900" title="Chỉnh sửa">
                                        <i class="fas fa-edit"></i>
                                    </button>
                                    <button onclick="toggleAgentStatus('${row.id}')" class="text-yellow-600 hover:text-yellow-900" title="Thay đổi trạng thái">
                                        <i class="fas fa-power-off"></i>
                                    </button>
                                    <button onclick="deleteAgent('${row.id}')" class="text-red-600 hover:text-red-900" title="Xóa">
                                        <i class="fas fa-trash"></i>
                                    </button>
                                </div>
                            `;
                        }
                        return '';
                    }
                }
            ],
            order: [[0, 'desc']], // Sắp xếp theo ID giảm dần
            pageLength: 10,
            responsive: true
        });

        // Load initial data
        loadAgents();
    });

    // Load agents data
    async function loadAgents() {
        try {
            const response = await fetch('/dev/test-agents-api', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Content-Type': 'application/json'
                }
            });
            
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            
            const data = await response.json();
            
            // Kiểm tra nếu data là object có thuộc tính success
            if (data.success === false) {
                console.error('API Error:', data.message);
                agents = [];
            } else {
                // Nếu data là array trực tiếp hoặc object có data
                agents = Array.isArray(data) ? data : (data.data || []);
            }
            
            console.log('Loaded agents:', agents);
            
            // Update table
            table.clear();
            if (agents.length > 0) {
                table.rows.add(agents);
            }
            table.draw();

            // Update statistics
            updateStats();
        } catch (error) {
            console.error('Error loading agents:', error);
            agents = [];
            table.clear().draw();
            alert('Có lỗi khi tải dữ liệu agents. Vui lòng kiểm tra console để xem chi tiết.');
        }
    }

    // Update statistics
    function updateStats() {
        if (!agents || !Array.isArray(agents)) {
            console.warn('Agents data is not an array:', agents);
            // Set default values
            document.getElementById('totalAgents').textContent = '0';
            document.getElementById('activeAgents').textContent = '0';
            document.getElementById('pendingAgents').textContent = '0';
            document.getElementById('blockedAgents').textContent = '0';
            return;
        }
        
        const stats = agents.reduce((acc, agent) => {
            const status = agent.status || 'active';
            if (acc[status] !== undefined) {
                acc[status]++;
            }
            return acc;
        }, { active: 0, pending: 0, blocked: 0 });

        document.getElementById('totalAgents').textContent = agents.length;
        document.getElementById('activeAgents').textContent = stats.active;
        document.getElementById('pendingAgents').textContent = stats.pending;
        document.getElementById('blockedAgents').textContent = stats.blocked;
        
        console.log('Stats updated:', stats);
    }

    // Show add agent modal
    function showAddAgentModal() {
        document.getElementById('modalTitle').textContent = 'Thêm Agent mới';
        document.getElementById('agentId').value = '';
        document.getElementById('agentForm').reset();
        document.getElementById('agentModal').classList.remove('hidden');
        document.getElementById('agentModal').classList.add('flex');
    }

    // Hide agent modal
    function hideAgentModal() {
        document.getElementById('agentModal').classList.remove('flex');
        document.getElementById('agentModal').classList.add('hidden');
    }

    // Edit agent
    function editAgent(id) {
        const agent = agents.find(a => a.id === id);
        if (!agent) return;

        document.getElementById('modalTitle').textContent = 'Chỉnh sửa Agent';
        document.getElementById('agentId').value = agent.id;
        document.getElementById('agentName').value = agent.name;
        document.getElementById('agentAddress').value = agent.address;
        document.getElementById('managerName').value = agent.manager;
        document.getElementById('agentPhone').value = agent.phone;
        document.getElementById('agentEmail').value = agent.email;
        document.getElementById('agentStatus').value = agent.status;

        document.getElementById('agentModal').classList.remove('hidden');
        document.getElementById('agentModal').classList.add('flex');
    }

    // Handle form submission
    async function handleAgentSubmit(event) {
        event.preventDefault();

        const formData = {
            id: document.getElementById('agentId').value,
            name: document.getElementById('agentName').value,
            address: document.getElementById('agentAddress').value,
            manager: document.getElementById('managerName').value,
            phone: document.getElementById('agentPhone').value,
            email: document.getElementById('agentEmail').value,
            status: document.getElementById('agentStatus').value
        };

        try {
            const url = formData.id ? `/api/admin/agents/${formData.id}` : '/api/admin/agents';
            const method = formData.id ? 'PUT' : 'POST';

            const response = await fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                },
                body: JSON.stringify(formData)
            });

            if (!response.ok) throw new Error('Network response was not ok');

            const result = await response.json();
            
            if (result.success) {
                hideAgentModal();
                loadAgents();
                alert(formData.id ? 'Cập nhật thành công!' : 'Thêm mới thành công!');
            } else {
                alert(result.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Toggle agent status
    async function toggleAgentStatus(id) {
        if (!confirm('Bạn có chắc muốn thay đổi trạng thái của agent này?')) return;

        try {
            const response = await fetch(`/api/admin/agents/${id}/toggle-status`, {
                method: 'PUT',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            
            if (result.success) {
                loadAgents();
                alert('Thay đổi trạng thái thành công!');
            } else {
                alert(result.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Delete agent
    async function deleteAgent(id) {
        if (!confirm('Bạn có chắc muốn xóa agent này?')) return;

        try {
            const response = await fetch(`/api/admin/agents/${id}`, {
                method: 'DELETE',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                }
            });

            if (!response.ok) throw new Error('Network response was not ok');
            
            const result = await response.json();
            
            if (result.success) {
                loadAgents();
                alert('Xóa agent thành công!');
            } else {
                alert(result.message || 'Có lỗi xảy ra');
            }
        } catch (error) {
            console.error('Error:', error);
            alert('Có lỗi xảy ra. Vui lòng thử lại.');
        }
    }

    // Export to Excel
    function exportAgents() {
        // Add export functionality here
        alert('Tính năng đang được phát triển');
    }

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkAuth('admin');
    });
</script>
@endsection
