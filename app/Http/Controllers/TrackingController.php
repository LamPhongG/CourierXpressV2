<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class TrackingController extends Controller
{
    /**
     * Display the tracking page
     */
    public function index()
    {
        return view('tracking.index');
    }

    /**
     * Check tracking by tracking ID (simplified for homepage)
     */
    public function checkTracking(Request $request)
    {
        $request->validate([
            'tracking_id' => 'required|string|min:3'
        ]);

        $trackingId = $request->tracking_id;
        
        // Find order by tracking number
        $order = Order::where('tracking_number', $trackingId)
                     ->with(['user', 'shipper', 'agent'])
                     ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng với mã vận đơn này'
            ]);
        }

        // Return simplified order data for homepage
        return response()->json([
            'success' => true,
            'message' => 'Tìm thấy đơn hàng!',
            'order' => [
                'tracking_number' => $order->tracking_number,
                'status' => $order->status,
                'pickup_address' => $order->pickup_address,
                'pickup_city' => $order->pickup_city,
                'delivery_address' => $order->delivery_address,
                'delivery_city' => $order->delivery_city,
                'created_at' => $order->created_at->format('Y-m-d H:i:s'),
                'package_type' => $order->package_type,
                'weight' => $order->weight,
                'shipping_fee' => $order->shipping_fee
            ]
        ]);
    }
    public function track(Request $request)
    {
        $request->validate([
            'tracking_number' => 'required|string|min:3'
        ]);

        $trackingNumber = $request->tracking_number;
        
        // Find order by tracking number
        $order = Order::where('tracking_number', $trackingNumber)
                     ->with(['user', 'shipper', 'agent', 'orderTrackings'])
                     ->first();

        if (!$order) {
            return response()->json([
                'success' => false,
                'message' => 'Không tìm thấy đơn hàng với mã vận đơn này'
            ], 404);
        }

        // Prepare tracking data
        $trackingData = [
            'order' => [
                'id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'status' => $order->status,
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'pickup_name' => $order->pickup_name,
                'pickup_phone' => $order->pickup_phone,
                'pickup_address' => $order->pickup_address,
                'delivery_name' => $order->delivery_name,
                'delivery_phone' => $order->delivery_phone,
                'delivery_address' => $order->delivery_address,
                'package_type' => $order->package_type,
                'weight' => $order->weight,
                'value' => $order->value,
                'cod_amount' => $order->cod_amount,
                'shipping_fee' => $order->shipping_fee,
                'notes' => $order->notes,
            ],
            'customer' => $order->user ? [
                'name' => $order->user->name,
                'email' => $order->user->email,
                'phone' => $order->user->phone,
            ] : null,
            'shipper' => $order->shipper ? [
                'name' => $order->shipper->name,
                'phone' => $order->shipper->phone,
            ] : null,
            'agent' => $order->agent ? [
                'name' => $order->agent->name,
                'address' => $order->agent->address,
                'phone' => $order->agent->phone,
            ] : null,
            'timeline' => $this->getOrderTimeline($order),
            'status_text' => $this->getStatusText($order->status),
            'status_icon' => $this->getStatusIcon($order->status),
            'status_color' => $this->getStatusColor($order->status),
        ];

        return response()->json([
            'success' => true,
            'data' => $trackingData
        ]);
    }

    /**
     * Get order timeline from order_trackings table
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];

        // Default timeline based on order status
        $statuses = [
            'pending' => ['title' => 'Đơn hàng được tạo', 'description' => 'Đơn hàng đã được tạo và chờ xử lý'],
            'confirmed' => ['title' => 'Đã xác nhận', 'description' => 'Đơn hàng đã được xác nhận bởi agent'],
            'assigned' => ['title' => 'Đã phân công', 'description' => 'Đã phân công shipper'],
            'pickup' => ['title' => 'Đang lấy hàng', 'description' => 'Shipper đang đến lấy hàng'],
            'picked_up' => ['title' => 'Đã lấy hàng', 'description' => 'Đã lấy hàng thành công'],
            'in_transit' => ['title' => 'Đang vận chuyển', 'description' => 'Hàng đang được vận chuyển'],
            'delivering' => ['title' => 'Đang giao hàng', 'description' => 'Shipper đang giao hàng cho khách'],
            'delivered' => ['title' => 'Đã giao thành công', 'description' => 'Đơn hàng đã được giao thành công'],
            'failed' => ['title' => 'Giao hàng thất bại', 'description' => 'Không thể giao hàng'],
            'cancelled' => ['title' => 'Đã hủy', 'description' => 'Đơn hàng đã bị hủy'],
        ];

        // Add created timestamp
        $timeline[] = [
            'status' => 'pending',
            'title' => 'Đơn hàng được tạo',
            'description' => 'Đơn hàng đã được tạo và chờ xử lý',
            'timestamp' => $order->created_at->format('d/m/Y H:i'),
            'is_completed' => true,
        ];

        // Get order trackings if available
        if ($order->orderTrackings && $order->orderTrackings->count() > 0) {
            foreach ($order->orderTrackings as $tracking) {
                $timeline[] = [
                    'status' => $tracking->status,
                    'title' => $statuses[$tracking->status]['title'] ?? ucfirst($tracking->status),
                    'description' => $tracking->notes ?: $statuses[$tracking->status]['description'] ?? '',
                    'timestamp' => $tracking->created_at->format('d/m/Y H:i'),
                    'is_completed' => true,
                ];
            }
        } else {
            // If no tracking records, create timeline based on current status
            $statusOrder = ['pending', 'confirmed', 'assigned', 'pickup', 'picked_up', 'in_transit', 'delivering', 'delivered'];
            $currentStatusIndex = array_search($order->status, $statusOrder);
            
            foreach ($statusOrder as $index => $status) {
                if ($status === 'pending') continue; // Already added above
                
                $timeline[] = [
                    'status' => $status,
                    'title' => $statuses[$status]['title'],
                    'description' => $statuses[$status]['description'],
                    'timestamp' => $index <= $currentStatusIndex ? $order->updated_at->format('d/m/Y H:i') : null,
                    'is_completed' => $index <= $currentStatusIndex,
                ];
            }
        }

        return $timeline;
    }

    /**
     * Get status text in Vietnamese
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'pending' => 'Chờ xử lý',
            'confirmed' => 'Đã xác nhận',
            'assigned' => 'Đã phân công',
            'pickup' => 'Đang lấy hàng',
            'picked_up' => 'Đã lấy hàng',
            'in_transit' => 'Đang vận chuyển',
            'delivering' => 'Đang giao hàng',
            'delivered' => 'Đã giao thành công',
            'failed' => 'Giao hàng thất bại',
            'cancelled' => 'Đã hủy',
        ];

        return $statusTexts[$status] ?? ucfirst($status);
    }

    /**
     * Get status icon
     */
    private function getStatusIcon($status)
    {
        $statusIcons = [
            'pending' => 'fas fa-clock',
            'confirmed' => 'fas fa-check',
            'assigned' => 'fas fa-user-tag',
            'pickup' => 'fas fa-hand-paper',
            'picked_up' => 'fas fa-box',
            'in_transit' => 'fas fa-truck',
            'delivering' => 'fas fa-shipping-fast',
            'delivered' => 'fas fa-check-circle',
            'failed' => 'fas fa-exclamation-triangle',
            'cancelled' => 'fas fa-times-circle',
        ];

        return $statusIcons[$status] ?? 'fas fa-question-circle';
    }

    /**
     * Get status color
     */
    private function getStatusColor($status)
    {
        $statusColors = [
            'pending' => 'yellow',
            'confirmed' => 'blue',
            'assigned' => 'purple',
            'pickup' => 'indigo',
            'picked_up' => 'green',
            'in_transit' => 'blue',
            'delivering' => 'orange',
            'delivered' => 'green',
            'failed' => 'red',
            'cancelled' => 'gray',
        ];

        return $statusColors[$status] ?? 'gray';
    }
}