@extends('layouts.agent')

@section('title', 'Chi tiết đơn hàng - CourierXpress')

@section('head')
    <link href="https://unpkg.com/leaflet@1.7.1/dist/leaflet.css" rel="stylesheet">
    <script src="https://unpkg.com/leaflet@1.7.1/dist/leaflet.js"></script>
@endsection

@section('content')
    <!-- Page Header -->
    <div class="bg-white shadow">
        <div class="px-4 sm:px-6 lg:max-w-7xl lg:mx-auto lg:px-8">
            <div class="py-6 md:flex md:items-center md:justify-between">
                <div class="flex-1 min-w-0">
                    <div class="flex items-center">
                        <a href="/agent/orders" class="mr-4 text-blue-600 hover:text-blue-800">
                            <i class="fas fa-arrow-left"></i>
                        </a>
                        <div>
                            <h2 class="text-2xl font-bold leading-7 text-gray-900 sm:text-3xl sm:truncate">
                                Đơn hàng #{{ $order->id }}
                            </h2>
                            <p class="mt-1 text-sm text-gray-500">
                                Tạo lúc: {{ $order->created_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex space-x-3 md:mt-0 md:ml-4">
                    @if($order->status == 'pending')
                    <button type="button" onclick="assignShipper({{ $order->id }})" class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700">
                        Phân công shipper
                    </button>
                    @endif
                    <button type="button" onclick="printOrder({{ $order->id }})" class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                        <i class="fas fa-print mr-2"></i>
                        In đơn hàng
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2">
            <!-- Order Information -->
            <div class="space-y-6">
                <!-- Status Card -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Trạng thái đơn hàng</h3>
                    <div class="flex items-center space-x-2">
                        <div class="flex-shrink-0">
                            {!! getStatusIcon($order->status) !!}
                        </div>
                        <div>
                            <p class="text-sm font-medium text-gray-900">
                                {!! getStatusBadge($order->status) !!}
                            </p>
                            <p class="text-sm text-gray-500">
                                Cập nhật lúc: {{ $order->updated_at->format('d/m/Y H:i') }}
                            </p>
                        </div>
                    </div>
                    
                    <!-- Timeline -->
                    <div class="mt-6 flow-root">
                        <ul class="-mb-8">
                            @foreach($order->statusHistory as $history)
                            <li>
                                <div class="relative pb-8">
                                    @if(!$loop->last)
                                    <span class="absolute top-4 left-4 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                                    @endif
                                    <div class="relative flex space-x-3">
                                        <div>
                                            <span class="h-8 w-8 rounded-full flex items-center justify-center ring-8 ring-white bg-blue-500">
                                                <i class="fas fa-check text-white"></i>
                                            </span>
                                        </div>
                                        <div class="min-w-0 flex-1 pt-1.5 flex justify-between space-x-4">
                                            <div>
                                                <p class="text-sm text-gray-500">{{ $history->status_text }}</p>
                                            </div>
                                            <div class="text-right text-sm whitespace-nowrap text-gray-500">
                                                <time datetime="{{ $history->created_at }}">{{ $history->created_at->format('d/m/Y H:i') }}</time>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            @endforeach
                        </ul>
                    </div>
                </div>

                <!-- Customer Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin khách hàng</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Họ tên</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_name }}</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Số điện thoại</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->customer_phone }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Địa chỉ lấy hàng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->pickup_address }}</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Địa chỉ giao hàng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->delivery_address }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Package Information -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin kiện hàng</h3>
                    <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Khối lượng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->weight }} kg</dd>
                        </div>
                        <div class="sm:col-span-1">
                            <dt class="text-sm font-medium text-gray-500">Giá trị hàng</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($order->value, 0, ',', '.') }} đ</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Phí vận chuyển</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ number_format($order->shipping_fee, 0, ',', '.') }} đ</dd>
                        </div>
                        <div class="sm:col-span-2">
                            <dt class="text-sm font-medium text-gray-500">Ghi chú</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->notes ?? 'Không có' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <!-- Map and Delivery Info -->
            <div class="space-y-6">
                <!-- Delivery Map -->
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Bản đồ giao hàng</h3>
                    <div id="map" class="h-96 rounded-lg"></div>
                </div>

                <!-- Shipper Information -->
                @if($order->shipper)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Thông tin shipper</h3>
                    <div class="flex items-center">
                        <div class="flex-shrink-0 h-12 w-12">
                            <img class="h-12 w-12 rounded-full" src="{{ $order->shipper->avatar ?? '/images/default-avatar.png' }}" alt="Shipper avatar">
                        </div>
                        <div class="ml-4">
                            <h4 class="text-lg font-medium text-gray-900">{{ $order->shipper->name }}</h4>
                            <p class="text-sm text-gray-500">{{ $order->shipper->phone }}</p>
                        </div>
                    </div>
                    <div class="mt-4">
                        <dl class="grid grid-cols-1 gap-x-4 gap-y-6 sm:grid-cols-2">
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Số đơn đã giao</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ $order->shipper->completed_orders_count }}</dd>
                            </div>
                            <div class="sm:col-span-1">
                                <dt class="text-sm font-medium text-gray-500">Tỷ lệ thành công</dt>
                                <dd class="mt-1 text-sm text-gray-900">{{ number_format($order->shipper->success_rate, 1) }}%</dd>
                            </div>
                        </dl>
                    </div>
                </div>
                @endif

                <!-- Photos -->
                @if($order->photos && count($order->photos) > 0)
                <div class="bg-white shadow rounded-lg p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Hình ảnh</h3>
                    <div class="grid grid-cols-2 gap-4">
                        @foreach($order->photos as $photo)
                        <div>
                            <img src="{{ $photo->url }}" alt="Order photo" class="rounded-lg">
                            <p class="mt-2 text-sm text-gray-500">{{ $photo->created_at->format('d/m/Y H:i') }}</p>
                        </div>
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Assign Shipper Modal -->
    <div id="assignShipperModal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
        <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
            <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true"></div>

            <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

            <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">
                        Phân công shipper
                    </h3>
                    <div class="space-y-4">
                        <div>
                            <label for="shipper" class="block text-sm font-medium text-gray-700">Chọn shipper</label>
                            <select id="shipper" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                                <option value="">Chọn shipper</option>
                                @foreach($availableShippers as $shipper)
                                <option value="{{ $shipper->id }}">{{ $shipper->name }} - {{ $shipper->phone }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div>
                            <label for="note" class="block text-sm font-medium text-gray-700">Ghi chú cho shipper</label>
                            <textarea id="note" rows="3" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="button" onclick="submitAssignShipper()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Xác nhận
                    </button>
                    <button type="button" onclick="closeAssignShipperModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script>
    let map;
    
    $(document).ready(function() {
        initializeMap();
    });

    function initializeMap() {
        // Initialize map
        map = L.map('map').setView([{{ $order->delivery_lat }}, {{ $order->delivery_lng }}], 13);
        
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add markers
        const pickupMarker = L.marker([{{ $order->pickup_lat }}, {{ $order->pickup_lng }}])
            .bindPopup('Điểm lấy hàng')
            .addTo(map);

        const deliveryMarker = L.marker([{{ $order->delivery_lat }}, {{ $order->delivery_lng }}])
            .bindPopup('Điểm giao hàng')
            .addTo(map);

        // Draw route if available
        @if($order->route)
        const route = L.polyline({{ $order->route }}, {color: 'blue'}).addTo(map);
        map.fitBounds(route.getBounds());
        @else
        const bounds = L.latLngBounds([
            [{{ $order->pickup_lat }}, {{ $order->pickup_lng }}],
            [{{ $order->delivery_lat }}, {{ $order->delivery_lng }}]
        ]);
        map.fitBounds(bounds);
        @endif
    }

    function assignShipper(orderId) {
        document.getElementById('assignShipperModal').classList.remove('hidden');
    }

    function closeAssignShipperModal() {
        document.getElementById('assignShipperModal').classList.add('hidden');
    }

    function submitAssignShipper() {
        const shipperId = document.getElementById('shipper').value;
        const note = document.getElementById('note').value;

        if (!shipperId) {
            alert('Vui lòng chọn shipper');
            return;
        }

        fetch(`/api/agent/orders/{{ $order->id }}/assign`, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                shipper_id: shipperId,
                note: note
            })
        })
        .then(response => response.json())
        .then(data => {
            closeAssignShipperModal();
            location.reload();
        })
        .catch(error => {
            console.error('Error:', error);
            alert('Có lỗi xảy ra khi phân công shipper');
        });
    }

    function printOrder(orderId) {
        window.open(`/agent/orders/${orderId}/print`, '_blank');
    }
</script>
@endsection
