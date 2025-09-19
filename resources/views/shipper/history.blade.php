@extends('layouts.unified')

@section('title', 'Lịch Sử Giao Hàng - Shipper | CourierXpress')

@section('head')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        .status-delivered { background: #d4edda; color: #155724; }
        .status-failed { background: #f8d7da; color: #721c24; }
        .status-returned { background: #fff3cd; color: #856404; }
        .rating-stars { color: #fbbf24; }
    </style>
@endsection

@section('navigation')
    <a href="/shipper/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/shipper/orders" class="text-gray-700 hover:text-red-600">Đơn hàng</a>
    <a href="/shipper/history" class="text-red-600 font-medium">Lịch sử</a>
    <a href="/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-green-500 to-teal-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Lịch Sử Giao Hàng</h1>
                    <p class="text-green-100">Xem lại toàn bộ lịch sử giao hàng và thống kê hiệu suất</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="exportHistory()" class="bg-white text-green-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-file-excel mr-2"></i>Xuất Excel
                    </button>
                    <button onclick="refreshHistory()" class="bg-green-800 hover:bg-green-900 text-white font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-refresh mr-2"></i>Làm mới
                    </button>
                </div>
            </div>
        </div>
    </div>
        <!-- Stats Summary -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-check-circle text-green-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Tổng giao thành công</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ $summaryStats['total_deliveries'] ?? 0 }}</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-percentage text-blue-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Tỷ lệ thành công</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($summaryStats['success_rate'] ?? 100, 1) }}%</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-money-bill text-purple-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Tổng thu nhập</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($summaryStats['total_earnings'] ?? 0, 0, ',', '.') }} ₫</p>
                    </div>
                </div>
            </div>
            <div class="bg-white rounded-lg shadow-sm p-6">
                <div class="flex items-center">
                    <i class="fas fa-star text-yellow-600 text-2xl mr-4"></i>
                    <div>
                        <p class="text-sm text-gray-500">Đánh giá trung bình</p>
                        <p class="text-2xl font-semibold text-gray-900">{{ number_format($summaryStats['average_rating'] ?? 5.0, 1) }}★</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabs Navigation -->
        <div class="bg-white rounded-lg shadow-sm mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8 px-6">
                    <div class="py-4 px-1 border-b-2 font-medium text-sm border-blue-500 text-blue-600">
                        <i class="fas fa-history mr-2"></i>Lịch Sử Giao Hàng
                    </div>
                </nav>
            </div>
        </div>

        <!-- History Tab -->
        <div id="historyContent" class="tab-content">
            <!-- Filters -->
            <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
                <h2 class="text-lg font-semibold text-gray-900 mb-4">Lọc Lịch Sử</h2>
                <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tháng</label>
                        <select id="monthFilter" class="w-full rounded-md border-gray-300">
                            <option value="">Tất cả</option>
                            <option value="1" {{ ($month ?? '') == '1' ? 'selected' : '' }}>Tháng 1</option>
                            <option value="2" {{ ($month ?? '') == '2' ? 'selected' : '' }}>Tháng 2</option>
                            <option value="3" {{ ($month ?? '') == '3' ? 'selected' : '' }}>Tháng 3</option>
                            <option value="4" {{ ($month ?? '') == '4' ? 'selected' : '' }}>Tháng 4</option>
                            <option value="5" {{ ($month ?? '') == '5' ? 'selected' : '' }}>Tháng 5</option>
                            <option value="6" {{ ($month ?? '') == '6' ? 'selected' : '' }}>Tháng 6</option>
                            <option value="7" {{ ($month ?? '') == '7' ? 'selected' : '' }}>Tháng 7</option>
                            <option value="8" {{ ($month ?? '') == '8' ? 'selected' : '' }}>Tháng 8</option>
                            <option value="9" {{ ($month ?? '') == '9' ? 'selected' : '' }}>Tháng 9</option>
                            <option value="10" {{ ($month ?? '') == '10' ? 'selected' : '' }}>Tháng 10</option>
                            <option value="11" {{ ($month ?? '') == '11' ? 'selected' : '' }}>Tháng 11</option>
                            <option value="12" {{ ($month ?? '') == '12' ? 'selected' : '' }}>Tháng 12</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Năm</label>
                        <select id="yearFilter" class="w-full rounded-md border-gray-300">
                            <option value="">Tất cả</option>
                            <option value="2024" {{ ($year ?? '') == '2024' ? 'selected' : '' }}>2024</option>
                            <option value="2023" {{ ($year ?? '') == '2023' ? 'selected' : '' }}>2023</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Trạng thái</label>
                        <select id="statusHistoryFilter" class="w-full rounded-md border-gray-300">
                            <option value="">Tất cả</option>
                            <option value="delivered" {{ ($status ?? '') == 'delivered' ? 'selected' : '' }}>Đã giao thành công</option>
                            <option value="failed" {{ ($status ?? '') == 'failed' ? 'selected' : '' }}>Giao hàng thất bại</option>
                            <option value="returned" {{ ($status ?? '') == 'returned' ? 'selected' : '' }}>Đã trả về</option>
                        </select>
                    </div>
                    <div class="flex items-end">
                        <button onclick="applyHistoryFilters()" class="w-full bg-blue-600 text-white px-4 py-2 rounded-md hover:bg-blue-700">
                            <i class="fas fa-search mr-2"></i>Tìm kiếm
                        </button>
                    </div>
                </div>
            </div>

            <!-- History List -->
            <div class="bg-white rounded-lg shadow-sm">
                <div class="p-6 border-b">
                    <h2 class="text-lg font-semibold text-gray-900">Danh Sách Lịch Sử</h2>
                </div>
                
                @if($deliveryHistory && $deliveryHistory->count() > 0)
                    <div class="divide-y divide-gray-200">
                        @foreach($deliveryHistory as $delivery)
                        <div class="p-6 hover:bg-gray-50">
                            <div class="flex justify-between items-start">
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-lg font-semibold text-blue-600">#{{ $delivery->tracking_number }}</h3>
                                        @php
                                            $statusColors = [
                                                'delivered' => 'bg-green-100 text-green-800',
                                                'failed' => 'bg-red-100 text-red-800',
                                                'returned' => 'bg-yellow-100 text-yellow-800'
                                            ];
                                            $statusTexts = [
                                                'delivered' => 'Đã giao thành công',
                                                'failed' => 'Giao hàng thất bại',
                                                'returned' => 'Đã trả về'
                                            ];
                                        @endphp
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusColors[$delivery->status] ?? 'bg-gray-100 text-gray-800' }}">
                                            {{ $statusTexts[$delivery->status] ?? $delivery->status }}
                                        </span>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-3">
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Lấy hàng:</p>
                                            <p class="text-sm text-gray-600">{{ $delivery->pickup_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $delivery->pickup_address }}, {{ $delivery->pickup_district }}</p>
                                        </div>
                                        <div>
                                            <p class="text-sm font-medium text-gray-900">Giao hàng:</p>
                                            <p class="text-sm text-gray-600">{{ $delivery->delivery_name }}</p>
                                            <p class="text-sm text-gray-500">{{ $delivery->delivery_address }}, {{ $delivery->delivery_district }}</p>
                                        </div>
                                    </div>
                                    
                                    <div class="flex items-center space-x-6 text-sm text-gray-500">
                                        <span><i class="fas fa-user mr-1"></i>{{ $delivery->customer->name ?? 'Khách hàng' }}</span>
                                        <span><i class="fas fa-clock mr-1"></i>{{ $delivery->completed_at ? \Carbon\Carbon::parse($delivery->completed_at)->format('d/m/Y H:i') : 'Chưa hoàn thành' }}</span>
                                        @if($delivery->status === 'delivered')
                                            <span><i class="fas fa-hourglass mr-1"></i>Thời gian: {{ $delivery->assigned_at && $delivery->completed_at ? \Carbon\Carbon::parse($delivery->assigned_at)->diffInMinutes(\Carbon\Carbon::parse($delivery->completed_at)) : 0 }} phút</span>
                                        @endif
                                    </div>
                                </div>
                                
                                <div class="text-right ml-6">
                                    <p class="text-lg font-semibold text-gray-900">{{ number_format($delivery->cod_amount ?? 0, 0, ',', '.') }} ₫</p>
                                    <p class="text-sm text-gray-500">COD</p>
                                    <p class="text-sm text-green-600">Phí: {{ number_format($delivery->shipping_fee ?? 0, 0, ',', '.') }} ₫</p>
                                    <p class="text-sm text-blue-600">Thu nhập: {{ number_format(($delivery->shipping_fee ?? 0) * 0.8, 0, ',', '.') }} ₫</p>
                                    
                                    @if($delivery->status === 'delivered')
                                        <div class="mt-2 text-yellow-400">
                                            @for($i = 1; $i <= 5; $i++)
                                                <i class="fas fa-star{{ $i <= 5 ? '' : '-o' }}"></i>
                                            @endfor
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <!-- Pagination -->
                    @if($deliveryHistory->hasPages())
                        <div class="px-6 py-4 border-t">
                            {{ $deliveryHistory->links() }}
                        </div>
                    @endif
                @else
                    <div class="p-8 text-center">
                        <i class="fas fa-history text-gray-400 text-4xl mb-4"></i>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Chưa có lịch sử giao hàng</h3>
                        <p class="text-gray-500">Lịch sử giao hàng sẽ xuất hiện ở đây sau khi bạn hoàn thành các đơn hàng.</p>
                    </div>
                @endif
            </div>
        </div>




@endsection

@section('scripts')
<script>
    @if(auth()->check())
    const userData = {
        id: {{ auth()->user()->id }},
        name: "{{ auth()->user()->name }}",
        email: "{{ auth()->user()->email }}",
        role: "{{ auth()->user()->role }}"
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        // Auto refresh page every 5 minutes for updated data
        setInterval(function() {
            window.location.reload();
        }, 300000);
    });
    @else
    window.location.href = '/login';
    @endif
    
    // Tab switching
    function switchTab(tab) {
        // Update tab buttons
        document.querySelectorAll('.tab-button').forEach(btn => {
            btn.classList.remove('border-blue-500', 'text-blue-600');
            btn.classList.add('border-transparent', 'text-gray-500');
        });
        
        document.getElementById(tab + 'Tab').classList.remove('border-transparent', 'text-gray-500');
        document.getElementById(tab + 'Tab').classList.add('border-blue-500', 'text-blue-600');
        
        // Update content
        document.querySelectorAll('.tab-content').forEach(content => {
            content.classList.add('hidden');
        });
        
        document.getElementById(tab + 'Content').classList.remove('hidden');
    }
    
    function applyHistoryFilters() {
        const month = document.getElementById('monthFilter').value;
        const year = document.getElementById('yearFilter').value;
        const status = document.getElementById('statusHistoryFilter').value;
        
        const params = new URLSearchParams();
        if (month) params.append('month', month);
        if (year) params.append('year', year);
        if (status) params.append('status', status);
        
        window.location.href = `/shipper/history?${params.toString()}`;
    }

    function refreshHistory() {
        window.location.reload();
    }

    function exportHistory() {
        alert('Tính năng xuất Excel đang được phát triển');
    }
</script>
@endsection