<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\OrderStatus;
use App\Models\Rating;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    /**
     * Show the shipper dashboard.
     */
    public function index()
    {
        try {
            $shipperId = Auth::id();
            
            // Get dashboard statistics
            $pendingOrders = Order::where('shipper_id', $shipperId)->where('status', 'assigned')->count();
            $inProgressOrders = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['pickup', 'picked_up', 'in_transit', 'delivering'])->count();
            $completedToday = Order::where('shipper_id', $shipperId)
                ->where('status', 'delivered')
                ->whereDate('completed_at', Carbon::today())
                ->count();
            $todayEarnings = Order::where('shipper_id', $shipperId)
                ->where('status', 'delivered')
                ->whereDate('completed_at', Carbon::today())
                ->sum('shipping_fee') * 0.8;
            $averageRating = Rating::where('shipper_id', $shipperId)->avg('rating') ?? 5.0;
            
            // Get current orders
            $currentOrders = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                ->with(['customer'])
                ->orderBy('assigned_at', 'asc')
                ->limit(5)
                ->get();
            
            // Get recent activities
            $recentActivities = Order::where('shipper_id', $shipperId)
                ->whereNotNull('assigned_at')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($order) {
                    $action = '';
                    $time = '';
                    
                    switch ($order->status) {
                        case 'assigned':
                            $action = 'Nhận đơn hàng mới';
                            $time = $order->assigned_at?->format('H:i');
                            break;
                        case 'pickup':
                            $action = 'Bắt đầu lấy hàng';
                            $time = $order->pickup_at?->format('H:i');
                            break;
                        case 'picked_up':
                            $action = 'Đã lấy hàng thành công';
                            $time = $order->picked_up_at?->format('H:i');
                            break;
                        case 'in_transit':
                            $action = 'Đang vận chuyển';
                            $time = $order->in_transit_at?->format('H:i');
                            break;
                        case 'delivering':
                            $action = 'Đang giao hàng';
                            $time = $order->delivering_at?->format('H:i');
                            break;
                        case 'delivered':
                            $action = 'Giao hàng thành công';
                            $time = $order->completed_at?->format('H:i');
                            break;
                        default:
                            $action = 'Cập nhật trạng thái';
                            $time = $order->updated_at->format('H:i');
                    }
                    
                    return [
                        'time' => $time ?: $order->updated_at->format('H:i'),
                        'order_id' => $order->tracking_number,
                        'action' => $action,
                        'status' => $order->status,
                        'customer' => $order->pickup_name
                    ];
                });
            
            return view('shipper.dashboard', compact(
                'pendingOrders', 'inProgressOrders', 'completedToday', 
                'todayEarnings', 'averageRating', 'currentOrders', 'recentActivities'
            ));
        } catch (\Exception $e) {
            // Fallback with default values if there's an error
            return view('shipper.dashboard', [
                'pendingOrders' => 0,
                'inProgressOrders' => 0,
                'completedToday' => 0,
                'todayEarnings' => 0,
                'averageRating' => 5.0,
                'currentOrders' => collect([]),
                'recentActivities' => collect([])
            ]);
        }
    }

    /**
     * Get dashboard data for authenticated shipper
     */
    public function getDashboardData()
    {
        try {
            $shipperId = Auth::id();
            
            $stats = [
                'orders' => [
                    'assigned' => Order::where('shipper_id', $shipperId)->where('status', 'assigned')->count(),
                    'in_transit' => Order::where('shipper_id', $shipperId)->whereIn('status', ['pickup', 'picked_up', 'in_transit', 'delivering'])->count(),
                    'completed_today' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', Carbon::today())
                        ->count(),
                    'total_earnings_today' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', Carbon::today())
                        ->sum('shipping_fee') * 0.8 // Giả sử shipper nhận 80% phí ship
                ],
                'rating' => [
                    'average' => Rating::where('shipper_id', $shipperId)->avg('rating') ?? 5.0,
                    'total_reviews' => Rating::where('shipper_id', $shipperId)->count()
                ],
                'location' => [
                    'latitude' => null,
                    'longitude' => null,
                    'last_updated' => null
                ]
            ];
            
            return response()->json([
                'success' => true,
                'data' => $stats
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải dữ liệu dashboard: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get current orders for the shipper.
     */
    public function getCurrentOrders()
    {
        try {
            $shipperId = Auth::id();
            
            $orders = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                ->with(['customer'])
                ->orderBy('assigned_at', 'asc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'tracking_number' => $order->tracking_number,
                        'pickup_name' => $order->pickup_name,
                        'pickup_phone' => $order->pickup_phone,
                        'pickup_address' => $order->pickup_address,
                        'pickup_ward' => $order->pickup_ward,
                        'pickup_district' => $order->pickup_district,
                        'pickup_city' => $order->pickup_city,
                        'delivery_name' => $order->delivery_name,
                        'delivery_phone' => $order->delivery_phone,
                        'delivery_address' => $order->delivery_address,
                        'delivery_ward' => $order->delivery_ward,
                        'delivery_district' => $order->delivery_district,
                        'delivery_city' => $order->delivery_city,
                        'customer_name' => $order->customer->name,
                        'customer_phone' => $order->customer->phone,
                        'status' => $order->status,
                        'package_type' => $order->package_type,
                        'weight' => $order->weight,
                        'cod_amount' => $order->cod_amount,
                        'shipping_fee' => $order->shipping_fee,
                        'notes' => $order->notes,
                        'assigned_at' => $order->assigned_at?->format('d/m/Y H:i'),
                        'pickup_latitude' => $order->pickup_latitude,
                        'pickup_longitude' => $order->pickup_longitude,
                        'delivery_latitude' => $order->delivery_latitude,
                        'delivery_longitude' => $order->delivery_longitude,
                        'priority' => $this->getOrderPriority($order)
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }
    
    /**
     * Xác định độ ưu tiên của đơn hàng
     */
    private function getOrderPriority($order)
    {
        // Đơn hàng COD có độ ưu tiên cao
        if ($order->cod_amount > 0) {
            return 'high';
        }
        
        // Đơn hàng có giá trị cao
        if ($order->value > 1000000) {
            return 'medium';
        }
        
        return 'normal';
    }

    /**
     * Get shipper statistics.
     */
    public function getStatistics()
    {
        try {
            $shipperId = Auth::id();
            
            $stats = [
                'today' => [
                    'assigned' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'assigned')
                        ->whereDate('assigned_at', Carbon::today())
                        ->count(),
                    'completed' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', Carbon::today())
                        ->count(),
                    'earnings' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', Carbon::today())
                        ->sum('shipping_fee') * 0.8,
                    'distance' => 0 // Sẽ tính toán dựa trên GPS tracking
                ],
                'week' => [
                    'completed' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->count(),
                    'earnings' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereBetween('completed_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])
                        ->sum('shipping_fee') * 0.8
                ],
                'month' => [
                    'completed' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereBetween('completed_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                        ->count(),
                    'earnings' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereBetween('completed_at', [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()])
                        ->sum('shipping_fee') * 0.8
                ],
                'rating' => [
                    'average' => Rating::where('shipper_id', $shipperId)->avg('rating') ?? 5.0,
                    'total' => Rating::where('shipper_id', $shipperId)->count()
                ]
            ];

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
     * Get recent activities.
     */
    public function getRecentActivities()
    {
        try {
            $shipperId = Auth::id();
            
            // Lấy các hoạt động gần đây từ các thay đổi trạng thái đơn hàng
            $recentOrders = Order::where('shipper_id', $shipperId)
                ->whereNotNull('assigned_at')
                ->orderBy('updated_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function ($order) {
                    $action = '';
                    $time = '';
                    
                    switch ($order->status) {
                        case 'assigned':
                            $action = 'Nhận đơn hàng mới';
                            $time = $order->assigned_at?->format('H:i');
                            break;
                        case 'pickup':
                            $action = 'Bắt đầu lấy hàng';
                            $time = $order->pickup_at?->format('H:i');
                            break;
                        case 'picked_up':
                            $action = 'Đã lấy hàng thành công';
                            $time = $order->picked_up_at?->format('H:i');
                            break;
                        case 'in_transit':
                            $action = 'Đang vận chuyển';
                            $time = $order->in_transit_at?->format('H:i');
                            break;
                        case 'delivering':
                            $action = 'Đang giao hàng';
                            $time = $order->delivering_at?->format('H:i');
                            break;
                        case 'delivered':
                            $action = 'Giao hàng thành công';
                            $time = $order->completed_at?->format('H:i');
                            break;
                        default:
                            $action = 'Cập nhật trạng thái';
                            $time = $order->updated_at->format('H:i');
                    }
                    
                    return [
                        'time' => $time ?: $order->updated_at->format('H:i'),
                        'order_id' => $order->tracking_number,
                        'action' => $action,
                        'status' => $order->status,
                        'customer' => $order->pickup_name
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $recentOrders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải hoạt động gần đây: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật vị trí shipper
     */
    public function updateLocation(Request $request)
    {
        $request->validate([
            'latitude' => 'required|numeric|between:-90,90',
            'longitude' => 'required|numeric|between:-180,180'
        ]);

        try {
            $user = Auth::user();
            
            // Cập nhật vị trí trong database (có thể thêm bảng locations riêng)
            // Hiện tại chúng ta lưu trong session hoặc cache
            session([
                'shipper_location' => [
                    'latitude' => $request->latitude,
                    'longitude' => $request->longitude,
                    'updated_at' => now()
                ]
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật vị trí thành công'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật vị trí: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái online/offline
     */
    public function updateStatus(Request $request)
    {
        $request->validate([
            'is_online' => 'required|boolean'
        ]);

        try {
            $user = Auth::user();
            $user->update([
                'is_online' => $request->is_online
            ]);

            return response()->json([
                'success' => true,
                'message' => $request->is_online ? 'Đã chuyển sang trạng thái online' : 'Đã chuyển sang trạng thái offline',
                'is_online' => $user->is_online
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get daily performance statistics.
     */
    public function getDailyPerformance()
    {
        try {
            $shipperId = Auth::id();
            
            $performance = [];
            
            // Lấy dữ liệu 7 ngày qua
            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                
                $dailyStats = [
                    'date' => $date->format('d/m'),
                    'completed' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', $date)
                        ->count(),
                    'earnings' => Order::where('shipper_id', $shipperId)
                        ->where('status', 'delivered')
                        ->whereDate('completed_at', $date)
                        ->sum('shipping_fee') * 0.8
                ];
                
                $performance[] = $dailyStats;
            }

            return response()->json([
                'success' => true,
                'data' => $performance
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải hiệu suất: ' . $e->getMessage()
            ], 500);
        }
    }
}
