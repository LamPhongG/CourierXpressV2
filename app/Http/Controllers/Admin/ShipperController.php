<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class ShipperController extends Controller
{
    public function index()
    {
        try {
            $shippers = User::where('role', 'shipper')
                          ->select(['id', 'name', 'email', 'phone', 'status', 'created_at'])
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->map(function ($shipper) {
                              // Calculate statistics
                              $totalOrders = Order::where('shipper_id', $shipper->id)->count();
                              $completedOrders = Order::where('shipper_id', $shipper->id)
                                                    ->where('status', 'delivered')
                                                    ->count();
                              $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

                              return [
                                  'id' => $shipper->id,
                                  'name' => $shipper->name,
                                  'email' => $shipper->email,
                                  'phone' => $shipper->phone ?? 'Chưa cập nhật',
                                  'area' => 'Miền Nam', // Default area - should be from profile
                                  'rating' => 4.5, // Default rating - should be calculated
                                  'status' => $shipper->status ?? 'active',
                                  'total_orders' => $totalOrders,
                                  'success_rate' => $successRate,
                                  'vehicle_type' => 'motorcycle', // Default - should be from profile
                                  'vehicle_number' => 'Chưa cập nhật', // Should be from profile
                                  'avatar' => '/images/default-avatar.png'
                              ];
                          });

            return response()->json($shippers);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController index error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải danh sách shippers: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email',
                'area' => 'required|in:north,central,south',
                'vehicle_type' => 'required|in:motorcycle,car,truck',
                'vehicle_number' => 'required|string|max:20',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $shipper = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'role' => 'shipper',
                'status' => $request->status,
                'password' => Hash::make('123456'), // Default password
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm shipper thành công',
                'data' => $shipper
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController store error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tạo shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $shipper
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController show error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải thông tin shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email,' . $id,
                'area' => 'required|in:north,central,south',
                'vehicle_type' => 'required|in:motorcycle,car,truck',
                'vehicle_number' => 'required|string|max:20',
                'status' => 'required|in:active,inactive'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $shipper->update($request->only(['name', 'email', 'phone', 'status']));

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật shipper thành công',
                'data' => $shipper
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cập nhật shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);
            
            // Check if shipper has orders
            $hasOrders = Order::where('shipper_id', $id)->exists();
            if ($hasOrders) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa shipper đã có đơn hàng'
                ], 400);
            }

            $shipper->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa shipper thành công'
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController destroy error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xóa shipper: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);
            
            $newStatus = $shipper->status === 'active' ? 'inactive' : 'active';
            $shipper->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Thay đổi trạng thái thành công',
                'data' => $shipper
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController toggleStatus error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi thay đổi trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }

    public function details($id)
    {
        try {
            $shipper = User::where('role', 'shipper')->findOrFail($id);
            
            // Get shipper statistics
            $totalOrders = Order::where('shipper_id', $id)->count();
            $completedOrders = Order::where('shipper_id', $id)->where('status', 'delivered')->count();
            $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;
            
            // Get recent orders
            $recentOrders = Order::where('shipper_id', $id)
                                 ->orderBy('created_at', 'desc')
                                 ->limit(5)
                                 ->get(['id', 'tracking_number', 'delivery_address', 'status', 'created_at']);

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $shipper->id,
                    'name' => $shipper->name,
                    'email' => $shipper->email,
                    'phone' => $shipper->phone,
                    'area' => 'Miền Nam',
                    'status' => $shipper->status,
                    'statistics' => [
                        'total_orders' => $totalOrders,
                        'success_rate' => $successRate,
                        'average_rating' => 4.5,
                        'average_time' => 45
                    ],
                    'recent_orders' => $recentOrders->map(function($order) {
                        return [
                            'id' => $order->id,
                            'tracking_number' => $order->tracking_number,
                            'delivery_address' => $order->delivery_address,
                            'status' => $order->status,
                            'created_at' => $order->created_at->format('d/m/Y H:i')
                        ];
                    })
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin ShipperController details error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải chi tiết shipper: ' . $e->getMessage()
            ], 500);
        }
    }
}