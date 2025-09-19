<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\ShippingService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        return view('admin.dashboard');
    }

    public function users()
    {
        return view('admin.users.index');
    }

    public function orders()
    {
        return view('admin.orders.index');
    }

    public function settings()
    {
        return view('admin.settings.index');
    }

    public function reports()
    {
        return view('admin.reports.index');
    }

    public function getStats()
    {
        try {
            $stats = [
                'users' => [
                    'total' => User::count(),
                    'active' => User::where('status', 'active')->count(),
                    'agents' => User::where('role', 'agent')->count(),
                    'shippers' => User::where('role', 'shipper')->count(),
                    'customers' => User::where('role', 'user')->count(),
                    'online_shippers' => User::where('role', 'shipper')->where('is_online', true)->count(),
                    'new_today' => User::whereDate('created_at', Carbon::today())->count(),
                ],
                'orders' => [
                    'total' => Order::count(),
                    'pending' => Order::where('status', 'pending')->count(),
                    'confirmed' => Order::where('status', 'confirmed')->count(),
                    'assigned' => Order::where('status', 'assigned')->count(),
                    'in_transit' => Order::where('status', 'in_transit')->count(),
                    'delivered' => Order::where('status', 'delivered')->count(),
                    'cancelled' => Order::where('status', 'cancelled')->count(),
                    'failed' => Order::where('status', 'failed')->count(),
                    'today' => Order::whereDate('created_at', Carbon::today())->count(),
                    'this_week' => Order::whereBetween('created_at', [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()])->count(),
                ],
                'revenue' => [
                    'total' => Order::where('status', 'delivered')->sum('total_fee') ?? 0,
                    'today' => Order::where('status', 'delivered')->whereDate('completed_at', Carbon::today())->sum('total_fee') ?? 0,
                    'this_month' => Order::where('status', 'delivered')->whereMonth('completed_at', Carbon::now()->month)->sum('total_fee') ?? 0,
                ],
                'performance' => [
                    'delivery_rate' => $this->getDeliveryRate(),
                    'avg_delivery_time' => $this->getAverageDeliveryTime(),
                    'customer_satisfaction' => $this->getCustomerSatisfaction(),
                ]
            ];

            return response()->json($stats);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard getStats error: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải thống kê: ' . $e->getMessage(),
                'error_code' => 'STATS_LOAD_FAILED'
            ], 500);
        }
    }

    public function getRecentOrders()
    {
        try {
            $orders = Order::with(['customer:id,name', 'shipper:id,name', 'agent:id,name'])
                           ->select(['id', 'tracking_number', 'user_id', 'shipper_id', 'agent_id', 'status', 'total_fee', 'created_at'])
                           ->orderBy('created_at', 'desc')
                           ->limit(10)
                           ->get()
                           ->map(function ($order) {
                               return [
                                   'id' => $order->id,
                                   'tracking_number' => $order->tracking_number,
                                   'customer_name' => $order->customer->name ?? 'N/A',
                                   'shipper_name' => $order->shipper->name ?? 'Chưa phân công',
                                   'agent_name' => $order->agent->name ?? 'N/A',
                                   'status' => $order->status,
                                   'total_fee' => $order->total_fee,
                                   'created_at' => $order->created_at->format('d/m/Y H:i'),
                               ];
                           });

            return response()->json($orders);
        } catch (\Exception $e) {
            \Log::error('Admin Dashboard getRecentOrders error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải đơn hàng gần đây: ' . $e->getMessage()
            ], 500);
        }
    }

    public function getSystemActivity()
    {
        // Get recent system activities - we'll implement audit logs later
        $activities = collect([
            [
                'type' => 'order_created',
                'message' => 'Đơn hàng mới được tạo',
                'user' => 'Nguyễn Văn A',
                'time' => Carbon::now()->subMinutes(5)->format('H:i'),
                'icon' => 'fas fa-plus-circle',
                'color' => 'text-green-600'
            ],
            [
                'type' => 'user_registered',
                'message' => 'Người dùng mới đăng ký',
                'user' => 'Trần Thị B',
                'time' => Carbon::now()->subMinutes(10)->format('H:i'),
                'icon' => 'fas fa-user-plus',
                'color' => 'text-blue-600'
            ],
            [
                'type' => 'order_delivered',
                'message' => 'Đơn hàng được giao thành công',
                'user' => 'Shipper C',
                'time' => Carbon::now()->subMinutes(15)->format('H:i'),
                'icon' => 'fas fa-check-circle',
                'color' => 'text-green-600'
            ]
        ]);

        return response()->json($activities);
    }

    private function getDeliveryRate()
    {
        $totalOrders = Order::whereIn('status', ['delivered', 'failed', 'cancelled'])->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        
        return $totalOrders > 0 ? round(($deliveredOrders / $totalOrders) * 100, 1) : 0;
    }

    private function getAverageDeliveryTime()
    {
        $deliveredOrders = Order::where('status', 'delivered')
                                ->whereNotNull('completed_at')
                                ->whereNotNull('created_at')
                                ->get();
        
        if ($deliveredOrders->isEmpty()) {
            return 0;
        }
        
        $totalHours = $deliveredOrders->sum(function ($order) {
            return $order->created_at->diffInHours($order->completed_at);
        });
        
        return round($totalHours / $deliveredOrders->count(), 1);
    }

    private function getCustomerSatisfaction()
    {
        // This would be based on ratings - for now return a demo value
        return 4.5;
    }
}