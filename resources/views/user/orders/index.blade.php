@extends('layouts.unified')

@section('title', 'My Orders - CourierXpress')

@section('navigation')
    <a href="/user/dashboard" class="text-gray-700 hover:text-red-600">Dashboard</a>
    <a href="/user/orders" class="text-red-600 font-medium">Orders</a>
    <a href="/user/create-order" class="text-gray-700 hover:text-red-600">Create</a>
    <a href="/user/tracking" class="text-gray-700 hover:text-red-600">Track</a>
    <a href="/user/profile" class="text-gray-700 hover:text-red-600">Profile</a>
@endsection

@section('head')
    <!-- No DataTables needed; using server-side rendering -->
@endsection

@section('content')
    <!-- Page Header -->
    <div class="mb-8">
        <div class="bg-gradient-to-r from-blue-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-2xl font-bold mb-2">My Orders</h1>
                    <p class="text-blue-100">Manage and track all your orders</p>
                </div>
                <div>
                    <a href="/user/create-order" class="bg-white text-blue-600 hover:bg-gray-100 font-bold py-2 px-4 rounded-lg transition-colors">
                        <i class="fas fa-plus mr-2"></i>
                        Create new order
                    </a>
                </div>
            </div>
        </div>
    </div>
        <!-- Filters -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Search filters</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select id="status" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="pickup">Picking up</option>
                        <option value="delivering">Delivering</option>
                        <option value="completed">Completed</option>
                        <option value="failed">Failed</option>
                    </select>
                </div>
                <div>
                    <label for="dateRange" class="block text-sm font-medium text-gray-700">Date range</label>
                    <select id="dateRange" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="">All</option>
                        <option value="today">Today</option>
                        <option value="last7days">Last 7 days</option>
                        <option value="last30days">Last 30 days</option>
                        <option value="thisMonth">This month</option>
                        <option value="lastMonth">Last month</option>
                    </select>
                </div>
                <div>
                    <label for="sort" class="block text-sm font-medium text-gray-700">Sort</label>
                    <select id="sort" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                        <option value="newest">Newest first</option>
                        <option value="oldest">Oldest first</option>
                        <option value="highest">Highest value</option>
                        <option value="lowest">Lowest value</option>
                    </select>
                </div>
            </div>
        </div>

        <!-- Orders Table -->
        <div class="bg-white shadow rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Order list</h3>
            <table id="ordersTable" class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Order code
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Created at
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Delivery address
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Shipper
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Value
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Shipping fee
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Status
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Actions
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
                                    <span class="text-gray-400">Unassigned</span>
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
                                        'pending' => 'Pending',
                                        'confirmed' => 'Confirmed',
                                        'assigned' => 'Assigned',
                                        'pickup' => 'Picking up',
                                        'picked_up' => 'Picked up',
                                        'in_transit' => 'In transit',
                                        'delivering' => 'Delivering',
                                        'delivered' => 'Delivered',
                                        'failed' => 'Failed',
                                        'cancelled' => 'Cancelled'
                                    ];
                                @endphp
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $statusColors[$order->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    {{ $statusTexts[$order->status] ?? $order->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <a href="/tracking?tracking_number={{ $order->tracking_number }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                    Details
                                </a>
                                @if($order->status === 'pending')
                                    <button onclick="cancelOrder({{ $order->id }})" class="text-red-600 hover:text-red-900">
                                        Cancel
                                    </button>
                                @endif
                                @if($order->status === 'delivered')
                                    <button onclick="rateOrder({{ $order->id }})" class="text-yellow-600 hover:text-yellow-900">
                                        Rate
                                    </button>
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    @else
                        <tr>
                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                No orders yet
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
        if (confirm('Are you sure you want to cancel this order?')) {
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
                    alert('Order cancelled successfully!');
                    location.reload();
                } else {
                    alert('An error occurred: ' + (data.message || 'Unable to cancel the order'));
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('An error occurred while cancelling the order!');
            });
        }
    }

    function rateOrder(orderId) {
        // TODO: Implement rating modal and functionality
        alert('Order rating will be added later!');
    }
</script>
@endsection
