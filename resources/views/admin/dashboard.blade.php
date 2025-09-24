<!DOCTYPE html>
@extends('layouts.unified')

@section('title', 'Admin Dashboard - CourierXpress')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-red-600 font-medium">Dashboard</a>
    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Orders</a>
    <a href="/admin/agents" class="text-gray-700 hover:text-red-600">Agents</a>
    <a href="/admin/shippers" class="text-gray-700 hover:text-red-600">Shippers</a>
    <a href="/admin/reports" class="text-gray-700 hover:text-red-600">Reports</a>
    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Settings</a>
@endsection

@section('content')
        <!-- Welcome Section -->
        <div class="mb-8">
            <div class="bg-gradient-to-r from-blue-600 to-purple-700 rounded-lg shadow-lg p-6 text-white">
                <h1 class="text-3xl font-bold mb-2">Admin Dashboard</h1>
                <p class="text-blue-100">Welcome, {{ auth()->check() ? auth()->user()->name : 'Guest' }}! Manage the entire CourierXpress system</p>
                <p class="text-sm text-blue-100 mt-2">{{ auth()->check() ? auth()->user()->email : 'Not logged in' }} • {{ auth()->check() ? ucfirst(auth()->user()->role) : 'Guest' }}</p>
            </div>
        </div>

        <!-- Quick Actions -->
        <div class="bg-white shadow-lg rounded-xl border border-gray-100 mb-8">
            <div class="px-6 py-5 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Quick actions</h3>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                    <a href="/admin/orders" class="bg-green-600 hover:bg-green-700 text-white font-bold py-4 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-box text-2xl mb-2"></i>
                        <p>Manage orders</p>
                    </a>
                    <a href="/admin/shippers" class="bg-yellow-600 hover:bg-yellow-700 text-white font-bold py-4 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-motorcycle text-2xl mb-2"></i>
                        <p>Manage shippers</p>
                    </a>
                    <a href="/admin/reports" class="bg-purple-600 hover:bg-purple-700 text-white font-bold py-4 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-chart-bar text-2xl mb-2"></i>
                        <p>Reports</p>
                    </a>
                    <a href="/admin/audit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-4 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-history text-2xl mb-2"></i>
                        <p>Audit Logs</p>
                    </a>
                    <a href="/admin/settings" class="bg-gray-600 hover:bg-gray-700 text-white font-bold py-4 px-6 rounded-lg text-center transition-colors">
                        <i class="fas fa-cog text-2xl mb-2"></i>
                        <p>Settings</p>
                    </a>
                </div>
            </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-users text-blue-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total users</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalUsers">{{ $totalUsers ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-box text-green-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Total orders</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalOrders">{{ $totalOrders ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-motorcycle text-yellow-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Active shippers</p>
                            <p class="text-2xl font-bold text-gray-900" id="activeShippers">{{ $activeShippers ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-white overflow-hidden shadow-lg rounded-xl">
                <div class="p-6">
                    <div class="flex items-center">
                        <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                            <i class="fas fa-store text-purple-600 text-xl"></i>
                        </div>
                        <div class="ml-4">
                            <p class="text-sm text-gray-500">Agents</p>
                            <p class="text-2xl font-bold text-gray-900" id="totalAgents">{{ $totalAgents ?? 0 }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <div class="bg-white shadow-lg rounded-xl">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Order status</h3>
                </div>
                <div class="p-6">
                    <canvas id="orderStatusChart"></canvas>
                </div>
            </div>
            <div class="bg-white shadow-lg rounded-xl">
                <div class="p-6 border-b">
                    <h3 class="text-lg font-semibold text-gray-900">Recent orders</h3>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Order</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Customer</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                            </tr>
                        </thead>
                        <tbody id="recentOrdersTable">
                            <tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
@endsection

@section('scripts')
<script>
        // Sync Laravel session with localStorage for dashboard compatibility
        @if(auth()->check())
        const userData = {
            id: {{ auth()->user()->id }},
            name: "{{ auth()->user()->name }}",
            email: "{{ auth()->user()->email }}",
            role: "{{ auth()->user()->role }}",
            status: "{{ auth()->user()->status }}"
        };
        
        // Store in localStorage for compatibility with updated dashboards
        localStorage.setItem('user_data', JSON.stringify(userData));
        localStorage.setItem('auth_token', 'laravel_session_' + userData.id); // Placeholder token
        @else
        // Clear localStorage if not authenticated
        localStorage.removeItem('user_data');
        localStorage.removeItem('auth_token');
        // Redirect to login if not authenticated
        window.location.href = '/login';
        @endif
        
        function logout() {
            if (confirm('Are you sure you want to logout?')) {
                // Clear localStorage
                localStorage.removeItem('user_data');
                localStorage.removeItem('auth_token');
                
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

        async function loadDashboardStats() {
            try {
                console.log('Loading admin dashboard stats...');
                const response = await fetch('/admin/api/stats');
                
                if (response.ok) {
                    const data = await response.json();
                    console.log('Admin stats loaded:', data);
                    
                    // Update data from API
                    updateDashboardCards(data);
                    updateOrderStatusChart(data.orders);
                } else {
                    console.warn('API unavailable, using server-rendered data');
                }
                
                // Load other components
                loadRecentOrders();
                loadSystemActivity();
            } catch (error) {
                console.error('Error loading stats:', error);
                console.warn('Using server-rendered data due to API error');
            }
        }

        function updateDashboardCards(data) {
            // Only update if API returns data
            if (data && data.users) {
                document.getElementById('totalUsers').textContent = data.users.total || 0;
                document.getElementById('totalOrders').textContent = data.orders.total || 0;
                document.getElementById('activeShippers').textContent = data.users.online_shippers || 0;
                document.getElementById('totalAgents').textContent = data.users.agents || 0;
                console.log('Dashboard cards updated with API data');
            } else {
                console.log('Using server-side data for dashboard cards');
            }
        }

        function updateOrderStatusChart(orderStats) {
            const ctx = document.getElementById('orderStatusChart').getContext('2d');
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Pending', 'Confirmed', 'In transit', 'Delivered', 'Cancelled'],
                    datasets: [{
                        data: [
                            orderStats.pending || 0, 
                            orderStats.confirmed || 0,
                            orderStats.in_transit || 0, 
                            orderStats.delivered || 0, 
                            orderStats.cancelled || 0
                        ],
                        backgroundColor: ['#FCD34D', '#3B82F6', '#8B5CF6', '#10B981', '#EF4444']
                    }]
                },
                options: {
                    responsive: true,
                    plugins: {
                        legend: { position: 'bottom' }
                    }
                }
            });
        }

        async function loadRecentOrders() {
            try {
                const response = await fetch('/admin/api/recent-orders');
                const orders = await response.json();
                const tableBody = document.getElementById('recentOrdersTable');
                if (orders && orders.length > 0) {
                    tableBody.innerHTML = orders.map(order => `
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 text-sm font-medium text-blue-600">#${order.tracking_number || order.id}</td>
                            <td class="px-6 py-4 text-sm text-gray-900">${order.customer_name}</td>
                            <td class="px-6 py-4">${getStatusBadge(order.status)}</td>
                        </tr>
                    `).join('');
                } else {
                    tableBody.innerHTML = '<tr><td colspan="3" class="px-6 py-4 text-center text-gray-500">No orders</td></tr>';
                }
            } catch (error) {
                console.error('Error loading orders:', error);
            }
        }

        async function loadSystemActivity() {
            try {
                const response = await fetch('/admin/api/activity');
                const activities = await response.json();
                // This would update an activity feed - we'll add this section next
            } catch (error) {
                console.error('Error loading activity:', error);
            }
        }

        function getStatusBadge(status) {
            const badges = {
                'pending': 'bg-yellow-100 text-yellow-800',
                'confirmed': 'bg-blue-100 text-blue-800',
                'assigned': 'bg-purple-100 text-purple-800',
                'in_transit': 'bg-indigo-100 text-indigo-800',
                'delivered': 'bg-green-100 text-green-800',
                'cancelled': 'bg-red-100 text-red-800',
                'failed': 'bg-red-100 text-red-800'
            };
            const statusText = {
                'pending': 'Pending',
                'confirmed': 'Confirmed',
                'assigned': 'Assigned',
                'in_transit': 'In transit',
                'delivered': 'Delivered',
                'cancelled': 'Cancelled',
                'failed': 'Failed'
            };
            const badgeClass = badges[status] || 'bg-gray-100 text-gray-800';
            const text = statusText[status] || status;
            return `<span class="px-3 py-1 text-xs rounded-full ${badgeClass}">${text}</span>`;
        }

        document.addEventListener('DOMContentLoaded', loadDashboardStats);
</script>
@endsection