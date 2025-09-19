<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        try {
            $user = Auth::user();
            
            if (!$user || $user->role !== 'agent') {
                return redirect('/login');
            }
            
            // Lấy thống kê cơ bản cho agent
            $agentCity = $user->city;
            
            $totalOrders = Order::when($agentCity, function($query) use ($agentCity) {
                                return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                            })->count();
            
            $pendingOrders = Order::where('status', 'pending')
                                 ->when($agentCity, function($query) use ($agentCity) {
                                     return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                 })->count();
            
            $completedToday = Order::where('status', 'delivered')
                                  ->whereDate('completed_at', Carbon::today())
                                  ->when($agentCity, function($query) use ($agentCity) {
                                      return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                  })->count();
            
            $activeShippers = User::where('role', 'shipper')
                                 ->where('status', 'active')
                                 ->when($agentCity, function($query) use ($agentCity) {
                                     return $query->where('city', $agentCity);
                                 })->count();
            
            return view('agent.dashboard', compact(
                'totalOrders', 'pendingOrders', 'completedToday', 'activeShippers'
            ));
        } catch (\Exception $e) {
            \Log::error('Agent Dashboard error: ' . $e->getMessage());
            return view('agent.dashboard', [
                'totalOrders' => 0,
                'pendingOrders' => 0,
                'completedToday' => 0,
                'activeShippers' => 0
            ]);
        }
    }

    public function getStats()
    {
        try {
            $user = Auth::user();
            $agentCity = $user->city;
            
            $stats = [
                'orders' => [
                    'pending' => Order::where('status', 'pending')
                                     ->when($agentCity, function($query) use ($agentCity) {
                                         return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                     })->count(),
                    'confirmed' => Order::where('status', 'confirmed')
                                       ->when($agentCity, function($query) use ($agentCity) {
                                           return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                       })->count(),
                    'in_transit' => Order::whereIn('status', ['pickup', 'picked_up', 'in_transit', 'delivering'])
                                        ->when($agentCity, function($query) use ($agentCity) {
                                            return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                        })->count(),
                    'completed_today' => Order::where('status', 'delivered')
                                             ->whereDate('completed_at', Carbon::today())
                                             ->when($agentCity, function($query) use ($agentCity) {
                                                 return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                             })->count(),
                    'total' => Order::when($agentCity, function($query) use ($agentCity) {
                                     return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                 })->count()
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
                'revenue' => [
                    'today' => Order::where('status', 'delivered')
                                   ->whereDate('completed_at', Carbon::today())
                                   ->when($agentCity, function($query) use ($agentCity) {
                                       return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                   })->sum('total_fee') ?? 0,
                    'this_month' => Order::where('status', 'delivered')
                                        ->whereMonth('completed_at', Carbon::now()->month)
                                        ->when($agentCity, function($query) use ($agentCity) {
                                            return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                                        })->sum('total_fee') ?? 0
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

    public function getPendingOrders()
    {
        try {
            $user = Auth::user();
            $agentCity = $user->city;
            
            $orders = Order::where('status', 'pending')
                          ->with(['user:id,name'])
                          ->when($agentCity, function($query) use ($agentCity) {
                              return $query->where('pickup_city', $agentCity)->orWhere('delivery_city', $agentCity);
                          })
                          ->limit(10)
                          ->get(['id', 'user_id', 'tracking_number', 'pickup_name', 'delivery_name', 'created_at'])
                          ->map(function ($order) {
                              return [
                                  'id' => $order->id,
                                  'tracking_number' => $order->tracking_number,
                                  'customer' => $order->user ? $order->user->name : $order->pickup_name,
                                  'pickup_name' => $order->pickup_name,
                                  'delivery_name' => $order->delivery_name,
                                  'created_at' => $order->created_at->format('d/m/Y H:i')
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

    public function getShippers()
    {
        try {
            $user = Auth::user();
            $agentCity = $user->city;
            
            $shippers = User::where('role', 'shipper')
                           ->when($agentCity, function($query) use ($agentCity) {
                               return $query->where('city', $agentCity);
                           })
                           ->select(['id', 'name', 'email', 'phone', 'is_online', 'status'])
                           ->get()
                           ->map(function ($shipper) {
                               // Get actual order count for this shipper
                               $orderCount = Order::where('shipper_id', $shipper->id)
                                                 ->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                                                 ->count();
                               
                               return [
                                   'id' => $shipper->id,
                                   'name' => $shipper->name,
                                   'email' => $shipper->email,
                                   'phone' => $shipper->phone,
                                   'status' => $shipper->is_online ? 'online' : 'offline',
                                   'orders' => $orderCount,
                                   'is_available' => $shipper->status === 'active' && $orderCount < 5
                               ];
                           });

            return response()->json([
                'success' => true,
                'data' => $shippers
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách shippers: ' . $e->getMessage()
            ], 500);
        }
    }
}