<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AgentController extends Controller
{
    public function __construct()
    {
        // Middleware will be handled by route groups in web.php
    }

    /**
     * Agent dashboard
     */
    public function dashboard()
    {
        return view('agent.dashboard');
    }

    /**
     * Lấy thống kê cho Agent dashboard
     */
    public function getStats()
    {
        try {
            $agentCity = Auth::user()->city;
            
            // Lấy thống kê đơn hàng theo khu vực agent
            $ordersQuery = Order::whereHas('customer', function($query) use ($agentCity) {
                if ($agentCity) {
                    $query->where('city', $agentCity);
                }
            });

            $stats = [
                'orders' => [
                    'pending' => (clone $ordersQuery)->where('status', 'pending')->count(),
                    'processing' => (clone $ordersQuery)->whereIn('status', ['confirmed', 'assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])->count(),
                    'completed_today' => (clone $ordersQuery)->where('status', 'delivered')
                        ->whereDate('completed_at', Carbon::today())->count(),
                ],
                'shippers' => [
                    'active' => User::where('role', 'shipper')
                        ->where('status', 'active')
                        ->where('is_online', true)
                        ->when($agentCity, function($query) use ($agentCity) {
                            return $query->where('city', $agentCity);
                        })->count(),
                    'total' => User::where('role', 'shipper')
                        ->when($agentCity, function($query) use ($agentCity) {
                            return $query->where('city', $agentCity);
                        })->count()
                ],
                'performance' => $this->getPerformanceData($agentCity)
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể tải thống kê',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy dữ liệu hiệu suất 7 ngày qua
     */
    private function getPerformanceData($agentCity)
    {
        $dates = collect();
        $orders = collect();
        $completed = collect();

        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::today()->subDays($i);
            $dates->push($date->format('d/m'));
            
            // Đơn hàng được tạo trong ngày
            $dailyOrders = Order::whereHas('customer', function($query) use ($agentCity) {
                if ($agentCity) {
                    $query->where('city', $agentCity);
                }
            })->whereDate('created_at', $date)->count();
            
            // Đơn hàng hoàn thành trong ngày
            $dailyCompleted = Order::whereHas('customer', function($query) use ($agentCity) {
                if ($agentCity) {
                    $query->where('city', $agentCity);
                }
            })->where('status', 'delivered')
            ->whereDate('completed_at', $date)->count();
            
            $orders->push($dailyOrders);
            $completed->push($dailyCompleted);
        }

        return [
            'labels' => $dates->toArray(),
            'orders' => $orders->toArray(),
            'completed' => $completed->toArray()
        ];
    }

    /**
     * Lấy danh sách đơn hàng chờ xử lý
     */
    public function getPendingOrders()
    {
        try {
            $agentCity = Auth::user()->city;
            
            $orders = Order::with(['customer'])
                ->where('status', 'pending')
                ->whereHas('customer', function($query) use ($agentCity) {
                    if ($agentCity) {
                        $query->where('city', $agentCity);
                    }
                })
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'tracking_number' => $order->tracking_number,
                        'customer_name' => $order->customer->name,
                        'customer_phone' => $order->customer->phone,
                        'pickup_address' => $order->pickup_address,
                        'delivery_address' => $order->delivery_address,
                        'total_fee' => $order->total_fee,
                        'created_at' => $order->created_at->format('d/m/Y H:i'),
                        'package_type' => $order->package_type,
                        'weight' => $order->weight
                    ];
                });

            return response()->json($orders);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể tải danh sách đơn hàng',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xác nhận đơn hàng
     */
    public function confirmOrder(Request $request, $orderId)
    {
        try {
            $order = Order::findOrFail($orderId);
            
            // Kiểm tra quyền xử lý đơn hàng (theo khu vực)
            $agentCity = Auth::user()->city;
            if ($agentCity && $order->customer->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xử lý đơn hàng này'
                ], 403);
            }

            if ($order->status !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng đã được xử lý'
                ], 400);
            }

            $order->update([
                'status' => 'confirmed',
                'agent_id' => Auth::id(),
                'confirmed_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đơn hàng đã được xác nhận thành công',
                'order' => [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'status' => $order->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể xác nhận đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy danh sách shipper khả dụng
     */
    public function getAvailableShippers()
    {
        try {
            $agentCity = Auth::user()->city;
            
            $shippers = User::where('role', 'shipper')
                ->where('status', 'active')
                ->when($agentCity, function($query) use ($agentCity) {
                    return $query->where('city', $agentCity);
                })
                ->withCount(['shipperOrders as active_orders' => function($query) {
                    $query->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering']);
                }])
                ->get()
                ->map(function($shipper) {
                    return [
                        'id' => $shipper->id,
                        'name' => $shipper->name,
                        'phone' => $shipper->phone,
                        'city' => $shipper->city,
                        'is_online' => $shipper->is_online,
                        'active_orders' => $shipper->active_orders,
                        'status_text' => $shipper->is_online ? 
                            ($shipper->active_orders > 0 ? 'Bận' : 'Sẵn sàng') : 'Offline'
                    ];
                });

            return response()->json($shippers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể tải danh sách shipper',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    /**
     * Phân công shipper cho đơn hàng
     */
    public function assignShipper(Request $request, $orderId)
    {
        $request->validate([
            'shipper_id' => 'required|exists:users,id'
        ]);

        try {
            $order = Order::findOrFail($orderId);
            $shipper = User::findOrFail($request->shipper_id);

            // Kiểm tra shipper có phù hợp không
            if ($shipper->role !== 'shipper' || $shipper->status !== 'active') {
                return response()->json([
                    'success' => false,
                    'message' => 'Shipper không khả dụng'
                ], 400);
            }

            // Kiểm tra đơn hàng có thể assign không
            if (!in_array($order->status, ['confirmed', 'assigned'])) {
                return response()->json([
                    'success' => false,
                    'message' => 'Đơn hàng không thể phân công'
                ], 400);
            }

            $order->update([
                'shipper_id' => $request->shipper_id,
                'status' => 'assigned',
                'assigned_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => "Đã phân công shipper {$shipper->name} cho đơn hàng {$order->tracking_number}",
                'order' => [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'shipper' => [
                        'id' => $shipper->id,
                        'name' => $shipper->name,
                        'phone' => $shipper->phone
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể phân công shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Lấy trạng thái shipper
     */
    public function getShipperStatus()
    {
        try {
            $agentCity = Auth::user()->city;
            
            $shippers = User::where('role', 'shipper')
                ->when($agentCity, function($query) use ($agentCity) {
                    return $query->where('city', $agentCity);
                })
                ->withCount(['shipperOrders as active_orders' => function($query) {
                    $query->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering']);
                }])
                ->get()
                ->map(function($shipper) {
                    $status = 'offline';
                    if ($shipper->is_online) {
                        $status = $shipper->active_orders > 0 ? 'busy' : 'online';
                    }
                    
                    return [
                        'id' => $shipper->id,
                        'name' => $shipper->name,
                        'status' => $status,
                        'orders' => $shipper->active_orders
                    ];
                });

            return response()->json($shippers);
        } catch (\Exception $e) {
            return response()->json([
                'error' => 'Không thể tải trạng thái shipper',
                'message' => $e->getMessage()
            ], 500);
        }
    }
}