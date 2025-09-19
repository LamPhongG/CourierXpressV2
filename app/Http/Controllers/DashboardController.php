<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function adminDashboard()
    {
        try {
            // Lấy dữ liệu thống kê từ MySQL
            $totalUsers = User::count();
            $totalOrders = Order::count();
            $activeShippers = User::where('role', 'shipper')
                                            ->where('is_online', true)
                                            ->count();
            $totalAgents = User::where('role', 'agent')->count();
            
            // Truyền dữ liệu vào view
            return view('admin.dashboard', compact(
                'totalUsers', 'totalOrders', 'activeShippers', 'totalAgents'
            ));
        } catch (\Exception $e) {
            // Nếu có lỗi, sử dụng giá trị mặc định
            \Log::error('Admin Dashboard error: ' . $e->getMessage());
            
            return view('admin.dashboard', [
                'totalUsers' => 0,
                'totalOrders' => 0,
                'activeShippers' => 0,
                'totalAgents' => 0
            ]);
        }
    }

    public function userDashboard()
    {
        try {
            $user = auth()->user();
            
            if (!$user || $user->role !== 'user') {
                return redirect('/login');
            }
            
            // Lấy thống kê đơn hàng của user
            $totalOrders = Order::where('user_id', $user->id)->count();
            $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
            $inTransitOrders = Order::where('user_id', $user->id)->whereIn('status', ['confirmed', 'assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])->count();
            $completedOrders = Order::where('user_id', $user->id)->where('status', 'delivered')->count();
            $totalSpent = Order::where('user_id', $user->id)
                              ->where('status', 'delivered')
                              ->sum('total_fee') ?? 0;
            
            // Lấy đơn hàng gần đây
            $recentOrders = Order::where('user_id', $user->id)
                                ->orderBy('created_at', 'desc')
                                ->limit(5)
                                ->get();
            
            return view('user.dashboard', compact(
                'totalOrders', 'pendingOrders', 'inTransitOrders', 'completedOrders', 'totalSpent', 'recentOrders'
            ));
        } catch (\Exception $e) {
            \Log::error('User Dashboard error: ' . $e->getMessage());
            return view('user.dashboard', [
                'totalOrders' => 0,
                'pendingOrders' => 0,
                'inTransitOrders' => 0,
                'completedOrders' => 0,
                'totalSpent' => 0,
                'recentOrders' => collect([])
            ]);
        }
    }

    public function agentDashboard()
    {
        // Redirect to sophisticated agent dashboard
        return app(\App\Http\Controllers\Agent\DashboardController::class)->index();
    }

    public function shipperDashboard()
    {
        // Add logic for shipper dashboard - redirect to proper shipper routes
        return redirect()->route('shipper.dashboard');
    }

    // API endpoints for dashboard data
    public function getUserStats()
    {
        try {
            $user = auth()->user();
            \Log::info('User stats request - User ID: ' . ($user ? $user->id : 'null') . ', Role: ' . ($user ? $user->role : 'null'));
            
            if (!$user) {
                \Log::warning('No authenticated user found');
                return response()->json([
                    'success' => false,
                    'message' => 'User not authenticated'
                ], 401);
            }
            
            if ($user->role !== 'user') {
                \Log::warning('User role mismatch. Expected: user, Got: ' . $user->role);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized - Invalid role'
                ], 403);
            }
            
            // Get actual user order statistics
            $totalOrders = Order::where('user_id', $user->id)->count();
            $pendingOrders = Order::where('user_id', $user->id)->where('status', 'pending')->count();
            $inTransitOrders = Order::where('user_id', $user->id)
                                   ->whereIn('status', ['confirmed', 'assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                                   ->count();
            $completedOrders = Order::where('user_id', $user->id)->where('status', 'delivered')->count();
            $cancelledOrders = Order::where('user_id', $user->id)->where('status', 'cancelled')->count();
            
            // Calculate total spent
            $totalSpent = Order::where('user_id', $user->id)
                              ->where('status', 'delivered')
                              ->sum('total_fee') ?? 0;
            
            $statsData = [
                'user' => [
                    'name' => $user->name,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'address' => $user->address
                ],
                'stats' => [
                    'total_orders' => $totalOrders,
                    'pending_orders' => $pendingOrders,
                    'in_transit_orders' => $inTransitOrders,
                    'completed_orders' => $completedOrders,
                    'cancelled_orders' => $cancelledOrders,
                    'total_spent' => $totalSpent
                ]
            ];
            
            \Log::info('User stats data: ', $statsData);
            
            return response()->json([
                'success' => true,
                'data' => $statsData
            ]);
        } catch (\Exception $e) {
            \Log::error('User stats error: ' . $e->getMessage() . ' Trace: ' . $e->getTraceAsString());
            return response()->json([
                'success' => false,
                'message' => 'Error loading user statistics: ' . $e->getMessage()
            ], 500);
        }
    }
    
    public function getUserRecentOrders()
    {
        try {
            $user = auth()->user();
            
            if (!$user || $user->role !== 'user') {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized'
                ], 401);
            }
            
            $orders = Order::where('user_id', $user->id)
                          ->orderBy('created_at', 'desc')
                          ->limit(10)
                          ->get([
                              'id', 'tracking_number', 'status', 
                              'pickup_address', 'delivery_address', 
                              'total_fee', 'created_at', 'completed_at'
                          ])
                          ->map(function ($order) {
                              return [
                                  'id' => $order->id,
                                  'tracking_number' => $order->tracking_number,
                                  'status' => $order->status,
                                  'pickup_address' => $order->pickup_address,
                                  'delivery_address' => $order->delivery_address,
                                  'total_fee' => $order->total_fee,
                                  'created_at' => $order->created_at->format('d/m/Y H:i'),
                                  'completed_at' => $order->completed_at ? $order->completed_at->format('d/m/Y H:i') : null
                              ];
                          });
                          
            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            \Log::error('User recent orders error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Error loading recent orders'
            ], 500);
        }
    }

    public function getShipperData()
    {
        // Get current user or default shipper data
        $user = auth()->user();
        
        if ($user && $user->role === 'shipper') {
            return response()->json([
                'name' => $user->name,
                'online' => $user->is_online ?? true,
                'statistics' => [
                    'pending' => \App\Models\Order::where('shipper_id', $user->id)->where('status', 'pending')->count(),
                    'in_progress' => \App\Models\Order::where('shipper_id', $user->id)->where('status', 'in_transit')->count(),
                    'completed_today' => \App\Models\Order::where('shipper_id', $user->id)
                                                        ->where('status', 'delivered')
                                                        ->whereDate('updated_at', today())
                                                        ->count(),
                    'rating' => 4.8 // Mock rating for now
                ],
                'current_orders' => [
                    [
                        'id' => 'DH001',
                        'pickup_address' => '123 Nguyễn Văn Cừ, Q5',
                        'delivery_address' => '456 Lê Hồng Phong, Q10',
                        'status' => 'in_progress',
                        'estimated_time' => '30 phút',
                        'delivery_coordinates' => '10.762622,106.660172'
                    ]
                ]
            ]);
        }
        
        // Default shipper data for demonstration
        return response()->json([
            'name' => 'Trần Văn B',
            'online' => true,
            'statistics' => [
                'pending' => 5,
                'in_progress' => 2,
                'completed_today' => 8,
                'rating' => 4.8
            ],
            'current_orders' => [
                [
                    'id' => 'DH001',
                    'pickup_address' => '123 Nguyễn Văn Cừ, Q5',
                    'delivery_address' => '456 Lê Hồng Phong, Q10',
                    'status' => 'in_progress',
                    'estimated_time' => '30 phút',
                    'delivery_coordinates' => '10.762622,106.660172'
                ]
            ]
        ]);
    }

    public function getRecentActivities()
    {
        // Dummy data for demonstration
        return response()->json([
            [
                'time' => '10:30',
                'order_id' => 'DH001',
                'action' => 'Đã lấy hàng',
                'status' => 'in_progress'
            ],
            [
                'time' => '10:15',
                'order_id' => 'DH002',
                'action' => 'Đã giao hàng',
                'status' => 'completed'
            ]
        ]);
    }
}
