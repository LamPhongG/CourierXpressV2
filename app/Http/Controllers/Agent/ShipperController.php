<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Carbon\Carbon;

class ShipperController extends Controller
{
    /**
     * Hiển thị trang quản lý shipper
     */
    public function index()
    {
        return view('agent.shippers.index');
    }

    /**
     * Lấy danh sách shipper theo khu vực agent
     */
    public function getShippers(Request $request)
    {
        try {
            $agentCity = Auth::user()->city;
            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $search = $request->get('search');
            $isOnline = $request->get('is_online');

            $query = User::where('role', 'shipper')
                ->when($agentCity, function($q) use ($agentCity) {
                    return $q->where('city', $agentCity);
                })
                ->withCount(['shipperOrders as active_orders' => function($q) {
                    $q->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering']);
                }])
                ->withCount(['shipperOrders as completed_orders' => function($q) {
                    $q->where('status', 'delivered')
                      ->whereDate('completed_at', Carbon::today());
                }]);

            // Filter theo status
            if ($status) {
                $query->where('status', $status);
            }

            // Filter theo online status
            if ($isOnline !== null) {
                $query->where('is_online', $isOnline);
            }

            // Tìm kiếm theo tên hoặc email
            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('name', 'like', "%{$search}%")
                      ->orWhere('email', 'like', "%{$search}%")
                      ->orWhere('phone', 'like', "%{$search}%");
                });
            }

            $shippers = $query->orderBy('is_online', 'desc')
                            ->orderBy('name')
                            ->paginate($perPage);

            // Format dữ liệu
            $formattedShippers = $shippers->getCollection()->map(function($shipper) {
                return [
                    'id' => $shipper->id,
                    'name' => $shipper->name,
                    'email' => $shipper->email,
                    'phone' => $shipper->phone,
                    'city' => $shipper->city,
                    'status' => $shipper->status,
                    'is_online' => $shipper->is_online,
                    'active_orders' => $shipper->active_orders,
                    'completed_orders' => $shipper->completed_orders,
                    'online_status' => $shipper->is_online ? 
                        ($shipper->active_orders > 0 ? 'busy' : 'available') : 'offline',
                    'created_at' => $shipper->created_at->format('d/m/Y'),
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedShippers,
                'pagination' => [
                    'current_page' => $shippers->currentPage(),
                    'last_page' => $shippers->lastPage(),
                    'per_page' => $shippers->perPage(),
                    'total' => $shippers->total()
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Tạo shipper mới
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:20',
            'password' => 'required|string|min:6',
            'city' => 'nullable|string|max:100'
        ]);

        try {
            $agentCity = Auth::user()->city;
            
            $shipper = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->password),
                'role' => 'shipper',
                'status' => 'active',
                'city' => $request->city ?: $agentCity,
                'is_online' => false
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã tạo shipper mới thành công',
                'data' => [
                    'id' => $shipper->id,
                    'name' => $shipper->name,
                    'email' => $shipper->email,
                    'phone' => $shipper->phone,
                    'city' => $shipper->city
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tạo shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật thông tin shipper
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'phone' => 'required|string|max:20',
            'city' => 'nullable|string|max:100',
            'status' => 'required|in:active,inactive,suspended'
        ]);

        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);
            
            // Kiểm tra quyền
            $agentCity = Auth::user()->city;
            if ($agentCity && $shipper->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật shipper này'
                ], 403);
            }

            $shipper->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city ?: $shipper->city,
                'status' => $request->status
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Đã cập nhật thông tin shipper',
                'data' => [
                    'id' => $shipper->id,
                    'name' => $shipper->name,
                    'email' => $shipper->email,
                    'phone' => $shipper->phone,
                    'city' => $shipper->city,
                    'status' => $shipper->status
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể cập nhật shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Xem chi tiết shipper
     */
    public function show($id)
    {
        try {
            $shipper = User::where('role', 'shipper')
                ->withCount(['shipperOrders as total_orders'])
                ->withCount(['shipperOrders as completed_orders' => function($q) {
                    $q->where('status', 'delivered');
                }])
                ->withCount(['shipperOrders as active_orders' => function($q) {
                    $q->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering']);
                }])
                ->findOrFail($id);

            // Kiểm tra quyền
            $agentCity = Auth::user()->city;
            if ($agentCity && $shipper->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền xem thông tin shipper này'
                ], 403);
            }

            // Lấy đơn hàng gần đây
            $recentOrders = Order::where('shipper_id', $id)
                ->with(['customer'])
                ->orderBy('created_at', 'desc')
                ->limit(10)
                ->get()
                ->map(function($order) {
                    return [
                        'id' => $order->id,
                        'tracking_number' => $order->tracking_number,
                        'customer_name' => $order->customer->name,
                        'status' => $order->status,
                        'total_fee' => $order->total_fee,
                        'created_at' => $order->created_at->format('d/m/Y H:i')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => [
                    'shipper' => [
                        'id' => $shipper->id,
                        'name' => $shipper->name,
                        'email' => $shipper->email,
                        'phone' => $shipper->phone,
                        'city' => $shipper->city,
                        'status' => $shipper->status,
                        'is_online' => $shipper->is_online,
                        'created_at' => $shipper->created_at->format('d/m/Y'),
                        'total_orders' => $shipper->total_orders,
                        'completed_orders' => $shipper->completed_orders,
                        'active_orders' => $shipper->active_orders
                    ],
                    'recent_orders' => $recentOrders
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Cập nhật trạng thái online của shipper
     */
    public function toggleOnlineStatus(Request $request, $id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);
            
            // Kiểm tra quyền
            $agentCity = Auth::user()->city;
            if ($agentCity && $shipper->city !== $agentCity) {
                return response()->json([
                    'success' => false,
                    'message' => 'Bạn không có quyền cập nhật shipper này'
                ], 403);
            }

            $shipper->update([
                'is_online' => !$shipper->is_online
            ]);

            return response()->json([
                'success' => true,
                'message' => $shipper->is_online ? 'Shipper đã online' : 'Shipper đã offline',
                'data' => [
                    'id' => $shipper->id,
                    'is_online' => $shipper->is_online
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
     * Lấy thống kê hiệu suất shipper
     */
    public function getPerformanceStats(Request $request)
    {
        try {
            $agentCity = Auth::user()->city;
            $dateFrom = $request->get('date_from', Carbon::now()->subDays(7));
            $dateTo = $request->get('date_to', Carbon::now());

            $stats = User::where('role', 'shipper')
                ->when($agentCity, function($q) use ($agentCity) {
                    return $q->where('city', $agentCity);
                })
                ->withCount(['shipperOrders as total_orders' => function($q) use ($dateFrom, $dateTo) {
                    $q->whereBetween('created_at', [$dateFrom, $dateTo]);
                }])
                ->withCount(['shipperOrders as completed_orders' => function($q) use ($dateFrom, $dateTo) {
                    $q->where('status', 'delivered')
                      ->whereBetween('completed_at', [$dateFrom, $dateTo]);
                }])
                ->get()
                ->map(function($shipper) {
                    $completionRate = $shipper->total_orders > 0 ? 
                        round(($shipper->completed_orders / $shipper->total_orders) * 100, 2) : 0;
                    
                    return [
                        'name' => $shipper->name,
                        'total_orders' => $shipper->total_orders,
                        'completed_orders' => $shipper->completed_orders,
                        'completion_rate' => $completionRate,
                        'is_online' => $shipper->is_online
                    ];
                });

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
}