<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OrderController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled by route groups in web.php
    }

    /**
     * Hiển thị trang quản lý đơn hàng
     */
    public function index()
    {
        return view('agent.orders.index');
    }

    /**
     * Lấy danh sách đơn hàng với filter
     */
    public function getOrders(Request $request)
    {
        try {
            $agentCity = Auth::user()->city;
            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $search = $request->get('search');
            $dateFrom = $request->get('date_from');
            $dateTo = $request->get('date_to');

            $query = Order::with(['customer', 'shipper', 'agent'])
                ->whereHas('customer', function($q) use ($agentCity) {
                    if ($agentCity) {
                        $q->where('city', $agentCity);
                    }
                });

            // Filter theo status
            if ($status) {
                $query->where('status', $status);
            }

            // Tìm kiếm theo tracking number hoặc tên khách hàng
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('tracking_number', 'like', "%{$search}%")
                      ->orWhereHas('customer', function($subQ) use ($search) {
                          $subQ->where('name', 'like', "%{$search}%")
                               ->orWhere('phone', 'like', "%{$search}%");
                      });
                });
            }

            // Filter theo ngày
            if ($dateFrom) {
                $query->whereDate('created_at', '>=', $dateFrom);
            }
            if ($dateTo) {
                $query->whereDate('created_at', '<=', $dateTo);
            }

            $orders = $query->orderBy('created_at', 'desc')->paginate($perPage);

            return response()->json([
                'success' => true,
                'data' => $orders->items(),
                'pagination' => [
                    'current_page' => $orders->currentPage(),
                    'last_page' => $orders->lastPage(),
                    'per_page' => $orders->perPage(),
                    'total' => $orders->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết đơn hàng
     */
    public function show($id)
    {
        try {
            $order = Order::with(['customer', 'shipper', 'agent', 'status_history'])
                ->findOrFail($id);

            // Kiểm tra quyền truy cập
            $agentCity = Auth::user()->city;
            if ($agentCity && $order->customer->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem đơn hàng này'
                ], 403);
            }

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái đơn hàng
     */
    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,assigned,pickup,picked_up,in_transit,delivering,delivered,failed,returned,cancelled',
            'notes' => 'nullable|string|max:500'
        ]);

        try {
            $order = Order::findOrFail($id);
            
            // Kiểm tra quyền
            $agentCity = Auth::user()->city;
            if ($agentCity && $order->customer->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật đơn hàng này'
                ], 403);
            }

            $oldStatus = $order->status;
            $newStatus = $request->status;

            // Validate transition logic
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                return response()->json([
                    'success' => false,
                    'message' => "Không thể chuyển từ trạng thái '{$oldStatus}' sang '{$newStatus}'"
                ], 400);
            }

            // Cập nhật đơn hàng
            $updateData = [
                'status' => $newStatus
            ];

            // Cập nhật timestamp tương ứng
            switch ($newStatus) {
                case 'confirmed':
                    $updateData['confirmed_at'] = now();
                    $updateData['agent_id'] = Auth::id();
                    break;
                case 'assigned':
                    $updateData['assigned_at'] = now();
                    break;
                case 'pickup':
                    $updateData['pickup_at'] = now();
                    break;
                case 'picked_up':
                    $updateData['picked_up_at'] = now();
                    break;
                case 'in_transit':
                    $updateData['in_transit_at'] = now();
                    break;
                case 'delivering':
                    $updateData['delivering_at'] = now();
                    break;
                case 'delivered':
                    $updateData['completed_at'] = now();
                    break;
                case 'cancelled':
                    $updateData['cancelled_at'] = now();
                    break;
            }

            $order->update($updateData);

            // Ghi log status history (sẽ implement sau)
            // $this->logStatusHistory($order, $oldStatus, $newStatus, $request->notes);

            return response()->json([
                'success' => true,
                'message' => "Đã cập nhật trạng thái đơn hàng thành '{$newStatus}'",
                'data' => [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra tính hợp lệ của việc chuyển trạng thái
     */
    private function isValidStatusTransition($from, $to)
    {
        $validTransitions = [
            'pending' => ['confirmed', 'cancelled'],
            'confirmed' => ['assigned', 'cancelled'],
            'assigned' => ['pickup', 'cancelled'],
            'pickup' => ['picked_up', 'failed'],
            'picked_up' => ['in_transit'],
            'in_transit' => ['delivering'],
            'delivering' => ['delivered', 'failed'],
            'failed' => ['pickup', 'returned'],
            'returned' => [],
            'delivered' => [],
            'cancelled' => []
        ];

        return isset($validTransitions[$from]) && in_array($to, $validTransitions[$from]);
    }

    /**
     * Lấy thống kê đơn hàng
     */
    public function getStatistics(Request $request)
    {
        try {
            $agentCity = Auth::user()->city;
            $dateFrom = $request->get('date_from', Carbon::now()->startOfMonth());
            $dateTo = $request->get('date_to', Carbon::now()->endOfMonth());

            $baseQuery = Order::whereHas('customer', function($q) use ($agentCity) {
                if ($agentCity) {
                    $q->where('city', $agentCity);
                }
            })->whereBetween('created_at', [$dateFrom, $dateTo]);

            $stats = [
                'total_orders' => (clone $baseQuery)->count(),
                'pending_orders' => (clone $baseQuery)->where('status', 'pending')->count(),
                'confirmed_orders' => (clone $baseQuery)->where('status', 'confirmed')->count(),
                'assigned_orders' => (clone $baseQuery)->where('status', 'assigned')->count(),
                'in_progress_orders' => (clone $baseQuery)->whereIn('status', ['pickup', 'picked_up', 'in_transit', 'delivering'])->count(),
                'completed_orders' => (clone $baseQuery)->where('status', 'delivered')->count(),
                'cancelled_orders' => (clone $baseQuery)->where('status', 'cancelled')->count(),
                'failed_orders' => (clone $baseQuery)->where('status', 'failed')->count(),
                'total_revenue' => (clone $baseQuery)->where('status', 'delivered')->sum('total_fee'),
                'completion_rate' => 0
            ];

            // Tính tỷ lệ hoàn thành
            if ($stats['total_orders'] > 0) {
                $stats['completion_rate'] = round(($stats['completed_orders'] / $stats['total_orders']) * 100, 2);
            }

            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thống kê: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xuất báo cáo đơn hàng
     */
    public function exportReport(Request $request)
    {
        // Implementation sẽ được thêm sau
        return response()->json([
            'success' => false,
            'message' => 'Chức năng xuất báo cáo đang được phát triển'
        ]);
    }

    /**
     * Batch operations (xử lý nhiều đơn hàng cùng lúc)
     */
    public function batchOperation(Request $request)
    {
        $request->validate([
            'action' => 'required|in:confirm,assign,cancel',
            'order_ids' => 'required|array|min:1',
            'order_ids.*' => 'exists:orders,id',
            'shipper_id' => 'required_if:action,assign|exists:users,id'
        ]);

        try {
            $agentCity = Auth::user()->city;
            $orderIds = $request->order_ids;
            $action = $request->action;

            // Validate orders belong to agent's city
            $orders = Order::with('customer')->whereIn('id', $orderIds)->get();
            
            foreach ($orders as $order) {
                if ($agentCity && $order->customer->city !== $agentCity) {
                    return response()->json([
                        'success' => false,
                        'message' => "Bạn không có quyền xử lý đơn hàng #{$order->tracking_number}"
                    ], 403);
                }
            }

            $results = [];
            $successCount = 0;

            foreach ($orders as $order) {
                try {
                    switch ($action) {
                        case 'confirm':
                            if ($order->status === 'pending') {
                                $order->update([
                                    'status' => 'confirmed',
                                    'agent_id' => Auth::id(),
                                    'confirmed_at' => now()
                                ]);
                                $successCount++;
                                $results[] = "Đã xác nhận #{$order->tracking_number}";
                            } else {
                                $results[] = "Bỏ qua #{$order->tracking_number} (không ở trạng thái pending)";
                            }
                            break;

                        case 'assign':
                            if (in_array($order->status, ['confirmed', 'assigned'])) {
                                $order->update([
                                    'shipper_id' => $request->shipper_id,
                                    'status' => 'assigned',
                                    'assigned_at' => now()
                                ]);
                                $successCount++;
                                $results[] = "Đã phân công #{$order->tracking_number}";
                            } else {
                                $results[] = "Bỏ qua #{$order->tracking_number} (không thể phân công)";
                            }
                            break;

                        case 'cancel':
                            if (!in_array($order->status, ['delivered', 'cancelled'])) {
                                $order->update([
                                    'status' => 'cancelled',
                                    'cancelled_at' => now()
                                ]);
                                $successCount++;
                                $results[] = "Đã hủy #{$order->tracking_number}";
                            } else {
                                $results[] = "Bỏ qua #{$order->tracking_number} (không thể hủy)";
                            }
                            break;
                    }
                } catch (\Exception $e) {
                    $results[] = "Lỗi #{$order->tracking_number}: " . $e->getMessage();
                }
            }

            return response()->json([
                'success' => true,
                'message' => "Đã xử lý thành công {$successCount}/{count($orderIds)} đơn hàng",
                'results' => $results
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xử lý hàng loạt: ' . $e->getMessage()
            ], 500);
        }
    }
}