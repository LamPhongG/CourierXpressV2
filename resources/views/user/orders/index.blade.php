@extends('layouts.unified')

@section('title', 'Đơn hàng của tôi - CourierXpress')

@section('navigation')
    <a href="/user/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/user/orders" class="text-red-600 font-medium">Đơn hàng</a>
    <a href="/user/create-order" class="text-gray-700 hover:text-red-600">Tạo đơn</a>
    <a href="/user/tracking" class="text-gray-700 hover:text-red-600">Tra cứu</a>
    <a href="/user/profile" class="text-gray-700 hover:text-red-600">Hồ sơ</a>
@endsection

@section('head')
    <!-- Không cần DataTables, dùng server-side rendering -->
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">Đơn hàng của tôi</h1>
                    <p class="text-blue-100">Quản lý và theo dõi tất cả đơn hàng của bạn</p>
                </div>
                <div>
                    <a href="/user/create-order" class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Tạo đơn hàng mới
                    </a>
                </div>
            </div>
        </div>
    </div>
        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Bộ lọc tìm kiếm</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Trạng thái</label>
                    <select id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả</option>
                        <option value="pending">Chờ xử lý</option>
                        <option value="pickup">Đang lấy hàng</option>
                        <option value="delivering">Đang giao</option>
                        <option value="completed">Hoàn thành</option>
                        <option value="failed">Thất bại</option>
                    </select>
                </div>
                <div>
                    <label for="dateRange" class="block text-sm font-medium text-gray-700">Thời gian</label>
                    <select id="dateRange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">Tất cả</option>
                        <option value="today">Hôm nay</option>
                        <option value="last7days">7 ngày qua</option>
                        <option value="last30days">30 ngày qua</option>
                        <option value="thisMonth">Tháng này</option>
                        <option value="lastMonth">Tháng trước</option>
                    </select>
                </div>
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sắp xếp</label>
                    <select id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="newest">Mới nhất trước</option>
                        <option value="oldest">Cũ nhất trước</option>
                        <option value="highest">Giá trị cao nhất</option>
                        <option value="lowest">Giá trị thấp nhất</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Danh sách đơn hàng</h3>
            <table id="ordersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Mã đơn
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Ngày tạo
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Địa chỉ giao
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Shipper
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Giá trị
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Phí ship
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Trạng thái
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Thao tác
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @if(isset($orders) && $orders->count() > 0)
                        @foreach($orders as $order)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-blue-600">
                                <a href="/tracking?tracking_number={{ $order->tracking_number }}" class="hover:text-blue-800">
                                    {{ $order->tracking_number }}
                                </a>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ $order->created_at->format('d/m/Y') }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ Str::limit($order->delivery_address ?? 'N/A', 30) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                @if($order->shipper)
                                    <div>
                                        <div class="text-sm font-medium text-gray-900">{{ $order->shipper->name }}</div>
                                        <div class="text-sm text-gray-500">{{ $order->shipper->phone ?? 'N/A' }}</div>
                                    </div>
                                @else
                                    <span class="text-gray-400">Chưa phân công</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($order->cod_amount ?? 0, 0, ',', '.') }} ₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                {{ number_format($order->total_fee ?? 0, 0, ',', '.') }} ₫
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusColors = [
                                        'pending' => 'bg-yellow-100 text-yellow-800',
                                        'confirmed' => 'bg-blue-100 text-blue-800',
                                        'assigned' => 'bg-purple-100 text-purple-800',
                                        'pickup' => 'bg-indigo-100 text-indigo-800',
                                        'picked_up' => 'bg-indigo-100 text-indigo-800',
                                        'in_transit' => 'bg-indigo-100 text-indigo-800',
                                        'delivering' => 'bg-orange-100 text-orange-800',
                                        'delivered' => 'bg-green-100 text-green-800',
                                        'failed' => 'bg-red-100 text-red-800',
                                        'cancelled' => 'bg-gray-100 text-gray-800'
                                    ];
                                    $statusTexts = [
                                        'pending' => 'Chờ xử lý',
                                        'confirmed' => 'Đã xác nhận',
                                        'assigned' => 'Đã phân công',
                                        'pickup' => 'Đang lấy hàng',
                                        'picked_up' => 'Đã lấy hàng',
                                        'in_transit' => 'Đang vận chuyển',
                                        'delivering' => 'Đang giao hàng',
                                        'delivered' => 'Đã giao',
                                        'failed' => 'Thất bại',
                                        'cancelled' => 'Đã hủy'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusTexts[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/tracking?tracking_number={{ $order->tracking_number }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    Chi tiết
                                </a>
                                @if($order->status === 'pending')
                                    <button onclick="cancelOrder({{ $order->id }})" class="text-red-600 hover:text-red-900">
                                        Hủy
                                    </button>
                                @endif
                                @if($order->status === 'delivered')
                                    <button onclick="rateOrder({{ $order->id }})" class="text-yellow-600 hover:text-yellow-900">
                                        Đánh giá
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                Chưa có đơn hàng nào
                            </td>
                        </tr>
                    @endif
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    function cancelOrder(orderId) {
        if (confirm('Bạn có chắc chắn muốn hủy đơn hàng này?')) {
            fetch(`/api/user/orders/${orderId}/cancel`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert('Đã hủy đơn hàng thành công!');
                    location.reload();
                } else {
                    alert('Có lỗi xảy ra: ' + (data.message || 'Không thể hủy đơn hàng'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra khi hủy đơn hàng!');
            });
        }
    }

    function rateOrder(orderId) {
        // TODO: Implement rating modal and functionality
        alert('Đánh giá đơn hàng sẽ được thêm vào sau!');
    }
</script>
@endsection
