@extends('layouts.unified')

@section('title', 'Admin - Báo cáo Thống kê | CourierXpress')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.tailwindcss.min.css">
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.tailwindcss.min.js"></script>
@endsection

@section('navigation')
    <a href="/admin/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/admin/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/admin/agents" class="text-gray-700 hover:text-red-600">Chi nhánh</a>
    <a href="/admin/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/admin/reports" class="text-red-600 font-medium">Báo cáo</a>
    <a href="/admin/settings" class="text-gray-700 hover:text-red-600">Cài đặt</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-6">
        <div class="flex justify-between items-center">
            <div>
                <h2 class="text-3xl font-bold text-gray-900">Báo cáo Thống kê</h2>
                <p class="mt-2 text-gray-600">Theo dõi hiệu suất hoạt động và doanh thu</p>
            </div>
            <div class="flex space-x-3">
                <button onclick="exportReport()" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-download mr-2"></i> Xuất báo cáo
                </button>
            </div>
        </div>
    </div>

    <!-- Date Range Filter -->
    <div class="bg-white shadow-sm rounded-lg p-6 mb-6">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
            <div>
                <h3 class="text-lg font-medium text-gray-900 mb-4">Thời gian</h3>
            </div>
            <div class="md:col-span-3">
                <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                    <div>
                        <label for="period" class="block text-sm font-medium text-gray-700">Kỳ báo cáo</label>
                        <select id="period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-primary focus:border-primary sm:text-sm rounded-md">
                            <option value="today">Hôm nay</option>
                            <option value="yesterday">Hôm qua</option>
                            <option value="week">7 ngày qua</option>
                            <option value="month">30 ngày qua</option>
                            <option value="custom">Tùy chọn</option>
                        </select>
                    </div>
                    <div>
                        <label for="startDate" class="block text-sm font-medium text-gray-700">Từ ngày</label>
                        <input type="date" id="startDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                    <div>
                        <label for="endDate" class="block text-sm font-medium text-gray-700">Đến ngày</label>
                        <input type="date" id="endDate" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm focus:ring-primary focus:border-primary sm:text-sm">
                    </div>
                </div>
            </div>
        </div>
    </div>

        <!-- Summary Cards -->
        <div class="grid grid-cols-1 gap-5 sm:grid-cols-2 lg:grid-cols-4 mb-6">
            <!-- Total Revenue -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-dollar-sign text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Tổng doanh thu</p>
                        <p class="text-2xl font-bold text-green-600" id="totalRevenue">0 đ</p>
                    </div>
                </div>
            </div>

            <!-- Total Orders -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-blue-100 rounded-full">
                        <i class="fas fa-box text-blue-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Tổng đơn hàng</p>
                        <p class="text-2xl font-bold text-blue-600" id="totalOrders">0</p>
                    </div>
                </div>
            </div>

            <!-- Average Order Value -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-yellow-100 rounded-full">
                        <i class="fas fa-chart-line text-yellow-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Giá trị TB/đơn</p>
                        <p class="text-2xl font-bold text-yellow-600" id="avgOrderValue">0 đ</p>
                    </div>
                </div>
            </div>

            <!-- Success Rate -->
            <div class="bg-white rounded-xl shadow-sm border p-6">
                <div class="flex items-center">
                    <div class="p-3 bg-green-100 rounded-full">
                        <i class="fas fa-check-circle text-green-600 text-xl"></i>
                    </div>
                    <div class="ml-4">
                        <p class="text-gray-600 text-sm">Tỷ lệ thành công</p>
                        <p class="text-2xl font-bold text-green-600" id="successRate">0%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts -->
        <div class="grid grid-cols-1 gap-5 lg:grid-cols-2 mb-6">
            <!-- Revenue Chart -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Doanh thu theo thời gian</h3>
                <canvas id="revenueChart"></canvas>
            </div>

            <!-- Orders Chart -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Số lượng đơn hàng</h3>
                <canvas id="ordersChart"></canvas>
            </div>

            <!-- Area Performance -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Hiệu suất theo khu vực</h3>
                <canvas id="areaChart"></canvas>
            </div>

            <!-- Shipper Performance -->
            <div class="bg-white shadow-sm rounded-lg p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Top Shippers</h3>
                <div class="overflow-hidden">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Shipper
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đơn hoàn thành
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Tỷ lệ thành công
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Đánh giá TB
                                </th>
                            </tr>
                        </thead>
                        <tbody id="shipperPerformance" class="bg-white divide-y divide-gray-200">
                            <!-- Shipper rows will be added here -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Detailed Statistics -->
        <div class="bg-white shadow-sm rounded-lg overflow-hidden">
            <div class="p-6">
                <h3 class="text-lg font-medium text-gray-900 mb-4">Chi tiết thống kê</h3>
                <table id="statsTable" class="w-full">
                    <thead>
                        <tr>
                            <th>Ngày</th>
                            <th>Tổng đơn</th>
                            <th>Hoàn thành</th>
                            <th>Thất bại</th>
                            <th>Doanh thu</th>
                            <th>Tỷ lệ thành công</th>
                        </tr>
                    </thead>
                    <tbody>
                        <!-- Statistics rows will be added here -->
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let revenueChart, ordersChart, areaChart;
    let statsTable;

    // Initialize DataTable
    $(document).ready(function() {
        statsTable = $('#statsTable').DataTable({
            order: [[0, 'desc']],
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.6/i18n/vi.json'
            }
        });

        // Initialize charts
        initializeCharts();

        // Load initial data
        loadReportData();

        // Add event listeners
        document.getElementById('period').addEventListener('change', handlePeriodChange);
        document.getElementById('startDate').addEventListener('change', loadReportData);
        document.getElementById('endDate').addEventListener('change', loadReportData);
    });

    // Initialize charts
    function initializeCharts() {
        // Revenue Chart
        const revenueCtx = document.getElementById('revenueChart').getContext('2d');
        revenueChart = new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Doanh thu',
                    data: [],
                    borderColor: 'rgb(34, 197, 94)',
                    tension: 0.1
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            callback: function(value) {
                                return new Intl.NumberFormat('vi-VN', {
                                    style: 'currency',
                                    currency: 'VND'
                                }).format(value);
                            }
                        }
                    }
                }
            }
        });

        // Orders Chart
        const ordersCtx = document.getElementById('ordersChart').getContext('2d');
        ordersChart = new Chart(ordersCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [
                    {
                        label: 'Hoàn thành',
                        data: [],
                        backgroundColor: 'rgb(34, 197, 94)',
                    },
                    {
                        label: 'Thất bại',
                        data: [],
                        backgroundColor: 'rgb(239, 68, 68)',
                    }
                ]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        beginAtZero: true
                    }
                }
            }
        });

        // Area Chart
        const areaCtx = document.getElementById('areaChart').getContext('2d');
        areaChart = new Chart(areaCtx, {
            type: 'radar',
            data: {
                labels: ['Miền Bắc', 'Miền Trung', 'Miền Nam'],
                datasets: [{
                    label: 'Tỷ lệ thành công',
                    data: [],
                    backgroundColor: 'rgba(59, 130, 246, 0.2)',
                    borderColor: 'rgb(59, 130, 246)',
                    pointBackgroundColor: 'rgb(59, 130, 246)',
                    pointBorderColor: '#fff',
                    pointHoverBackgroundColor: '#fff',
                    pointHoverBorderColor: 'rgb(59, 130, 246)'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    r: {
                        beginAtZero: true,
                        max: 100
                    }
                }
            }
        });
    }

    // Handle period change
    function handlePeriodChange(event) {
        const period = event.target.value;
        const startDate = document.getElementById('startDate');
        const endDate = document.getElementById('endDate');

        const today = new Date();
        let start = new Date();

        switch (period) {
            case 'today':
                start = today;
                break;
            case 'yesterday':
                start.setDate(today.getDate() - 1);
                break;
            case 'week':
                start.setDate(today.getDate() - 7);
                break;
            case 'month':
                start.setDate(today.getDate() - 30);
                break;
            case 'custom':
                // Enable date inputs
                startDate.disabled = false;
                endDate.disabled = false;
                return;
        }

        startDate.value = start.toISOString().split('T')[0];
        endDate.value = today.toISOString().split('T')[0];
        startDate.disabled = period !== 'custom';
        endDate.disabled = period !== 'custom';

        loadReportData();
    }

    // Load report data
    async function loadReportData() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;

        try {
            const response = await fetch(`/dev/test-reports-api?start_date=${startDate}&end_date=${endDate}`);
            const data = await response.json();
            
            // Update summary cards
            document.getElementById('totalRevenue').textContent = formatCurrency(data.summary?.total_revenue || 0);
            document.getElementById('totalOrders').textContent = data.summary?.total_orders || 0;
            document.getElementById('avgOrderValue').textContent = formatCurrency(data.summary?.average_order_value || 0);
            document.getElementById('successRate').textContent = `${data.summary?.success_rate || 0}%`;

            // Update charts
            updateCharts(data);

            // Update shipper performance table
            updateShipperPerformance(data.shipper_performance || []);

            // Update statistics table
            updateStatisticsTable(data.daily_statistics || []);
        } catch (error) {
            console.error('Error loading report data:', error);
            alert('Có lỗi khi tải dữ liệu báo cáo. Vui lòng thử lại.');
        }
    }

    // Update charts
    function updateCharts(data) {
        // Revenue Chart
        if (data.revenue_chart) {
            revenueChart.data.labels = data.revenue_chart.labels || [];
            revenueChart.data.datasets[0].data = data.revenue_chart.data || [];
            revenueChart.update();
        }

        // Orders Chart
        if (data.orders_chart) {
            ordersChart.data.labels = data.orders_chart.labels || [];
            ordersChart.data.datasets[0].data = data.orders_chart.completed || [];
            ordersChart.data.datasets[1].data = data.orders_chart.failed || [];
            ordersChart.update();
        }

        // Area Chart
        if (data.area_performance) {
            areaChart.data.datasets[0].data = data.area_performance;
            areaChart.update();
        }
    }

    // Update shipper performance table
    function updateShipperPerformance(data) {
        const tbody = document.getElementById('shipperPerformance');
        tbody.innerHTML = data.map(shipper => `
            <tr>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-10 w-10">
                            <img class="h-10 w-10 rounded-full" src="${shipper.avatar || '/images/default-avatar.png'}" alt="">
                        </div>
                        <div class="ml-4">
                            <div class="text-sm font-medium text-gray-900">${shipper.name}</div>
                            <div class="text-sm text-gray-500">${shipper.phone}</div>
                        </div>
                    </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${shipper.completed_orders || 0}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${shipper.success_rate || 0}%</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm text-gray-900">${getRatingStars(shipper.average_rating)}</div>
                </td>
            </tr>
        `).join('');
    }

    // Update statistics table
    function updateStatisticsTable(data) {
        statsTable.clear();
        data.forEach(row => {
            statsTable.row.add([
                row.date,
                row.total_orders || 0,
                row.completed_orders || 0,
                row.failed_orders || 0,
                formatCurrency(row.revenue || 0),
                `${row.success_rate || 0}%`
            ]);
        });
        statsTable.draw();
    }

    // Helper function for rating stars
    function getRatingStars(rating) {
        if (!rating) return 'Chưa có đánh giá';
        
        const fullStars = Math.floor(rating);
        const hasHalfStar = rating % 1 >= 0.5;
        const emptyStars = 5 - Math.ceil(rating);
        
        return Array(fullStars).fill('<i class="fas fa-star text-yellow-400"></i>')
            .concat(hasHalfStar ? ['<i class="fas fa-star-half-alt text-yellow-400"></i>'] : [])
            .concat(Array(emptyStars).fill('<i class="far fa-star text-yellow-400"></i>'))
            .join('');
    }

    // Helper function to format currency
    function formatCurrency(value) {
        return new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(value);
    }

    // Export report
    function exportReport() {
        const startDate = document.getElementById('startDate').value;
        const endDate = document.getElementById('endDate').value;
        window.location.href = `/api/admin/reports/export?start_date=${startDate}&end_date=${endDate}`;
    }

    // Check authentication on page load
    document.addEventListener('DOMContentLoaded', function() {
        checkAuth('admin');
    });
</script>
@endsection
