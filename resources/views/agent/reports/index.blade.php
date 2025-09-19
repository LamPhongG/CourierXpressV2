@extends('layouts.unified')

@section('title', 'Báo Cáo Agent - CourierXpress')

@section('head')
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/date-fns@2.29.3/index.min.js"></script>
@endsection

@section('navigation')
    <a href="/agent/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/agent/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/agent/shippers" class="text-gray-700 hover:text-red-600">Shipper</a>
    <a href="/agent/reports" class="text-red-600 font-medium">Báo cáo</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
<div class="space-y-6">
    <!-- Header & Filters -->
    <div class="bg-gradient-to-r from-purple-500 to-indigo-600 rounded-lg shadow-lg p-6 text-white">
        <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between">
            <div>
                <h1 class="text-2xl font-bold mb-2">Báo Cáo Agent</h1>
                <p class="text-purple-100">Theo dõi hiệu suất và phân tích dữ liệu chi nhánh</p>
            </div>
            
            <!-- Date Range Picker -->
            <div class="mt-4 lg:mt-0">
                <form id="reportForm" method="GET" class="flex flex-col sm:flex-row space-y-2 sm:space-y-0 sm:space-x-2">
                    <input type="date" name="start_date" value="{{ $startDate ?? '' }}" class="px-3 py-2 rounded-md text-gray-800 text-sm">
                    <input type="date" name="end_date" value="{{ $endDate ?? '' }}" class="px-3 py-2 rounded-md text-gray-800 text-sm">
                    <select name="period" class="px-3 py-2 rounded-md text-gray-800 text-sm">
                        <option value="daily" {{ ($period ?? '') == 'daily' ? 'selected' : '' }}>Theo ngày</option>
                        <option value="weekly" {{ ($period ?? '') == 'weekly' ? 'selected' : '' }}>Theo tuần</option>
                        <option value="monthly" {{ ($period ?? '') == 'monthly' ? 'selected' : '' }}>Theo tháng</option>
                    </select>
                    <button type="submit" class="bg-white text-purple-600 px-4 py-2 rounded-md font-medium hover:bg-gray-100 transition-colors">
                        <i class="fas fa-search mr-2"></i>Lọc
                    </button>
                </form>
            </div>
        </div>
    </div>

    @if(isset($error))
    <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded">
        <p class="font-bold">Lỗi:</p>
        <p>{{ $error }}</p>
    </div>
    @endif

    <!-- Overview Statistics -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-box text-blue-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Tổng đơn hàng</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overview['total_orders'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-check-circle text-green-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đã hoàn thành</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overview['completed_orders'] ?? 0) }}</dd>
                            <dd class="text-xs text-green-600">{{ $overview['completion_rate'] ?? 0 }}% tỷ lệ thành công</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-clock text-yellow-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Đang xử lý</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overview['pending_orders'] ?? 0) }}</dd>
                            <dd class="text-xs text-yellow-600">Thời gian TB: {{ $overview['average_delivery_time'] ?? 0 }}h</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-white overflow-hidden shadow-lg rounded-lg">
            <div class="p-5">
                <div class="flex items-center">
                    <div class="flex-shrink-0">
                        <i class="fas fa-dollar-sign text-purple-600 text-2xl"></i>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Doanh thu</dt>
                            <dd class="text-lg font-medium text-gray-900">{{ number_format($overview['total_revenue'] ?? 0, 0, ',', '.') }} ₫</dd>
                            <dd class="text-xs text-red-600">Thất bại: {{ number_format($overview['failed_orders'] ?? 0) }}</dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Orders Trend Chart -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Xu hướng đơn hàng</h3>
            </div>
            <div class="p-6">
                <canvas id="ordersTrendChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Revenue Breakdown -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Phân tích doanh thu</h3>
            </div>
            <div class="p-6">
                <canvas id="revenueChart" width="400" height="200"></canvas>
            </div>
        </div>
    </div>

    <!-- Performance Tables -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Shipper Performance -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Hiệu suất Shipper</h3>
                <button onclick="exportReport('shipper_performance')" class="text-sm bg-purple-600 text-white px-3 py-1 rounded hover:bg-purple-700">
                    <i class="fas fa-download mr-1"></i>Xuất
                </button>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Shipper</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tổng đơn</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Hoàn thành</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Tỷ lệ</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doanh thu</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @if(isset($shipperPerformance) && $shipperPerformance->count() > 0)
                            @foreach($shipperPerformance as $shipper)
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">{{ $shipper->name }}</div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shipper->total_orders }}</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $shipper->completed_orders }}</td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium 
                                        {{ $shipper->completion_rate >= 80 ? 'bg-green-100 text-green-800' : 
                                           ($shipper->completion_rate >= 60 ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800') }}">
                                        {{ $shipper->completion_rate }}%
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ number_format($shipper->total_revenue, 0, ',', '.') }} ₫</td>
                            </tr>
                            @endforeach
                        @else
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-gray-500">Không có dữ liệu</td>
                            </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Top Performers -->
        <div class="bg-white shadow-lg rounded-lg">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-medium text-gray-900">Top Shipper xuất sắc</h3>
            </div>
            <div class="p-6">
                @if(isset($topShippers) && $topShippers->count() > 0)
                    <div class="space-y-4">
                        @foreach($topShippers->take(5) as $index => $shipper)
                        <div class="flex items-center space-x-4 p-3 bg-gray-50 rounded-lg">
                            <div class="flex-shrink-0">
                                <div class="w-8 h-8 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ $index + 1 }}
                                </div>
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="text-sm font-medium text-gray-900 truncate">{{ $shipper->name }}</p>
                                <p class="text-xs text-gray-500">{{ $shipper->total_deliveries }} đơn • {{ number_format($shipper->total_revenue, 0, ',', '.') }} ₫</p>
                            </div>
                            <div class="flex-shrink-0">
                                <div class="flex items-center">
                                    <i class="fas fa-star text-yellow-400 mr-1"></i>
                                    <span class="text-sm text-gray-600">{{ $shipper->avg_rating }}</span>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center text-gray-500 py-8">
                        <i class="fas fa-user-friends text-3xl mb-2"></i>
                        <p>Chưa có dữ liệu shipper</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Quick Actions -->
    <div class="bg-white shadow-lg rounded-lg p-6">
        <h3 class="text-lg font-medium text-gray-900 mb-4">Thao tác nhanh</h3>
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
            <button onclick="exportReport('overview')" class="bg-blue-600 text-white p-4 rounded-lg hover:bg-blue-700 transition-colors">
                <i class="fas fa-file-export text-xl mb-2"></i>
                <div class="text-sm">Xuất tổng quan</div>
            </button>
            <button onclick="exportReport('detailed')" class="bg-green-600 text-white p-4 rounded-lg hover:bg-green-700 transition-colors">
                <i class="fas fa-table text-xl mb-2"></i>
                <div class="text-sm">Báo cáo chi tiết</div>
            </button>
            <button onclick="printReport()" class="bg-purple-600 text-white p-4 rounded-lg hover:bg-purple-700 transition-colors">
                <i class="fas fa-print text-xl mb-2"></i>
                <div class="text-sm">In báo cáo</div>
            </button>
            <button onclick="refreshReport()" class="bg-orange-600 text-white p-4 rounded-lg hover:bg-orange-700 transition-colors">
                <i class="fas fa-sync text-xl mb-2"></i>
                <div class="text-sm">Làm mới</div>
            </button>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
// Data for charts from PHP
const trendsData = @json($trends ?? []);
const revenueData = @json($revenueBreakdown ?? []);
const serviceData = @json($serviceBreakdown ?? []);

// Initialize charts when page loads
document.addEventListener('DOMContentLoaded', function() {
    initializeOrdersTrendChart();
    initializeRevenueChart();
    console.log('Agent Reports page loaded successfully');
});

// Orders Trend Chart
function initializeOrdersTrendChart() {
    const ctx = document.getElementById('ordersTrendChart');
    if (!ctx) return;
    
    const labels = trendsData.map(item => {
        // Format date labels based on period
        return item.period;
    });
    
    const totalOrders = trendsData.map(item => item.total_orders);
    const completedOrders = trendsData.map(item => item.completed_orders);
    
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Tổng đơn hàng',
                data: totalOrders,
                borderColor: 'rgb(99, 102, 241)',
                backgroundColor: 'rgba(99, 102, 241, 0.1)',
                tension: 0.4,
                fill: true
            }, {
                label: 'Đã hoàn thành',
                data: completedOrders,
                borderColor: 'rgb(34, 197, 94)',
                backgroundColor: 'rgba(34, 197, 94, 0.1)',
                tension: 0.4,
                fill: true
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                title: {
                    display: false
                },
                legend: {
                    position: 'top'
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    ticks: {
                        stepSize: 1
                    }
                }
            }
        }
    });
}

// Revenue Breakdown Chart
function initializeRevenueChart() {
    const ctx = document.getElementById('revenueChart');
    if (!ctx) return;
    
    const data = {
        labels: ['Phí giao hàng', 'Tiền thu hộ (COD)', 'Khác'],
        datasets: [{
            data: [
                revenueData.shipping_fees || 0,
                revenueData.cod_amounts || 0, 
                (revenueData.total_collected || 0) - (revenueData.shipping_fees || 0) - (revenueData.cod_amounts || 0)
            ],
            backgroundColor: [
                '#8B5CF6',
                '#06B6D4', 
                '#F59E0B'
            ],
            borderWidth: 2,
            borderColor: '#ffffff'
        }]
    };
    
    new Chart(ctx, {
        type: 'doughnut',
        data: data,
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: {
                    position: 'bottom'
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const value = context.parsed;
                            return context.label + ': ' + new Intl.NumberFormat('vi-VN', {
                                style: 'currency',
                                currency: 'VND'
                            }).format(value);
                        }
                    }
                }
            }
        }
    });
}

// Export report function
function exportReport(type) {
    console.log('Xuất báo cáo:', type);
    
    // Show loading
    const button = event.target;
    const originalText = button.innerHTML;
    button.innerHTML = '<i class="fas fa-spinner fa-spin mr-1"></i>Xuất...';
    button.disabled = true;
    
    // Get current form data
    const form = document.getElementById('reportForm');
    const formData = new FormData(form);
    
    // Add export parameters
    const params = new URLSearchParams(formData);
    params.append('type', type);
    params.append('format', 'csv');
    
    // Make export request
    fetch('/agent/reports/export?' + params.toString(), {
        method: 'GET',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Báo cáo đã được xuất thành công!');
        } else {
            alert('Lỗi: ' + (data.message || 'Không thể xuất báo cáo'));
        }
    })
    .catch(error => {
        console.error('Export error:', error);
        alert('Có lỗi xảy ra khi xuất báo cáo');
    })
    .finally(() => {
        // Restore button
        button.innerHTML = originalText;
        button.disabled = false;
    });
}

// Print report function
function printReport() {
    window.print();
}

// Refresh report function
function refreshReport() {
    window.location.reload();
}

// Auto-submit form when date changes
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('reportForm');
    const dateInputs = form.querySelectorAll('input[type="date"], select[name="period"]');
    
    dateInputs.forEach(input => {
        input.addEventListener('change', function() {
            // Auto-submit after a short delay
            setTimeout(() => {
                form.submit();
            }, 500);
        });
    });
});

// Format currency helper
function formatCurrency(amount) {
    return new Intl.NumberFormat('vi-VN', {
        style: 'currency',
        currency: 'VND'
    }).format(amount);
}

// Format number helper  
function formatNumber(num) {
    return new Intl.NumberFormat('vi-VN').format(num);
}

// Print styles for better print layout
const printStyles = `
<style media="print">
    @page { margin: 1in; }
    .no-print { display: none !important; }
    .bg-gradient-to-r { background: #8B5CF6 !important; }
    .text-white { color: white !important; }
    .shadow-lg { box-shadow: none !important; }
    canvas { max-width: 100% !important; height: auto !important; }
</style>
`;
document.head.insertAdjacentHTML('beforeend', printStyles);
</script>
@endsection