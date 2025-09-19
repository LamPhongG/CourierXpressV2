<?php

namespace App\Http\Controllers\Agent;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    /**
     * Display reports dashboard
     */
    public function index(Request $request)
    {
        try {
            $agentId = Auth::id();
            $agentCity = Auth::user()->city ?? null;
            
            // Get date range from request or default to last 30 days
            $startDate = $request->get('start_date', Carbon::now()->subDays(30)->format('Y-m-d'));
            $endDate = $request->get('end_date', Carbon::now()->format('Y-m-d'));
            $period = $request->get('period', 'daily'); // daily, weekly, monthly
            
            // Parse dates
            $startDateTime = Carbon::parse($startDate)->startOfDay();
            $endDateTime = Carbon::parse($endDate)->endOfDay();
            
            \Log::info('=== AGENT REPORT DEBUG ===', [
                'agent_id' => $agentId,
                'agent_city' => $agentCity,
                'start_date' => $startDateTime->format('Y-m-d H:i:s'),
                'end_date' => $endDateTime->format('Y-m-d H:i:s'),
                'period' => $period
            ]);
            
            // Base query for orders in agent's city
            $baseQuery = Order::query();
            if ($agentCity) {
                $baseQuery->where(function($q) use ($agentCity) {
                    $q->where('pickup_city', 'LIKE', "%{$agentCity}%")
                      ->orWhere('delivery_city', 'LIKE', "%{$agentCity}%");
                });
            }
            $baseQuery->whereBetween('created_at', [$startDateTime, $endDateTime]);
            
            // Log query count for debugging
            $totalQueryCount = (clone $baseQuery)->count();
            \Log::info('Base query found orders: ' . $totalQueryCount);
            
            // Get overview statistics
            $overview = $this->getOverviewStats($baseQuery, $startDateTime, $endDateTime);
            
            // Get performance metrics by shipper
            $shipperPerformance = $this->getShipperPerformance($agentCity, $startDateTime, $endDateTime);
            
            // Get daily/weekly/monthly trends
            $trends = $this->getTrendData($baseQuery, $period, $startDateTime, $endDateTime);
            
            // Get revenue breakdown
            $revenueBreakdown = $this->getRevenueBreakdown($baseQuery);
            
            // Get service type breakdown
            $serviceBreakdown = $this->getServiceBreakdown($baseQuery);
            
            // Get top performing shippers
            $topShippers = $this->getTopShippers($agentCity, $startDateTime, $endDateTime);
            
            // Get additional analytics
            $cityStats = $this->getCityPerformance($startDateTime, $endDateTime);
            $dailyStats = $this->getDailyStats($startDateTime, $endDateTime);
            
            \Log::info('Report data summary', [
                'overview' => $overview,
                'shipper_count' => $shipperPerformance->count(),
                'trends_count' => $trends->count(),
                'top_shippers_count' => $topShippers->count()
            ]);
            
            return view('agent.reports.index', compact(
                'overview',
                'shipperPerformance', 
                'trends',
                'revenueBreakdown',
                'serviceBreakdown',
                'topShippers',
                'cityStats',
                'dailyStats',
                'startDate',
                'endDate',
                'period'
            ));
        } catch (\Exception $e) {
            \Log::error('Agent report error: ' . $e->getMessage());
            
            // Return with empty data on error
            $overview = [
                'total_orders' => 0,
                'completed_orders' => 0,
                'pending_orders' => 0,
                'failed_orders' => 0,
                'total_revenue' => 0,
                'completion_rate' => 0,
                'average_delivery_time' => 0
            ];
            
            return view('agent.reports.index', compact('overview'))
                ->with('error', 'Có lỗi khi tải dữ liệu báo cáo');
        }
    }
    
    /**
     * Export report to CSV/PDF
     */
    public function export(Request $request)
    {
        $format = $request->get('format', 'csv'); // csv, pdf, excel
        $type = $request->get('type', 'overview'); // overview, performance, detailed
        
        // Generate export data based on type
        // This would be implemented later
        
        return response()->json([
            'success' => true,
            'message' => "Xuất báo cáo $format đang được phát triển"
        ]);
    }
    
    /**
     * Get overview statistics
     */
    private function getOverviewStats($baseQuery, $startDateTime, $endDateTime)
    {
        $totalOrders = (clone $baseQuery)->count();
        $completedOrders = (clone $baseQuery)->where('status', 'delivered')->count();
        $pendingOrders = (clone $baseQuery)->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])->count();
        $failedOrders = (clone $baseQuery)->where('status', 'failed')->count();
        
        $totalRevenue = (clone $baseQuery)->where('status', 'delivered')->sum('total_fee');
        $completionRate = $totalOrders > 0 ? ($completedOrders / $totalOrders) * 100 : 0;
        
        // Calculate average delivery time
        $avgDeliveryTime = (clone $baseQuery)
            ->where('status', 'delivered')
            ->whereNotNull('completed_at')
            ->selectRaw('AVG(TIMESTAMPDIFF(HOUR, created_at, completed_at)) as avg_time')
            ->value('avg_time') ?? 0;
            
        return [
            'total_orders' => $totalOrders,
            'completed_orders' => $completedOrders,
            'pending_orders' => $pendingOrders,
            'failed_orders' => $failedOrders,
            'total_revenue' => $totalRevenue,
            'completion_rate' => round($completionRate, 2),
            'average_delivery_time' => round($avgDeliveryTime, 2)
        ];
    }
    
    /**
     * Get shipper performance metrics
     */
    private function getShipperPerformance($agentCity, $startDateTime, $endDateTime)
    {
        return DB::table('orders')
            ->join('users', 'orders.shipper_id', '=', 'users.id')
            ->select([
                'users.id',
                'users.name',
                'users.email',
                'users.phone',
                DB::raw('COUNT(orders.id) as total_orders'),
                DB::raw('SUM(CASE WHEN orders.status = "delivered" THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN orders.status = "failed" THEN 1 ELSE 0 END) as failed_orders'),
                DB::raw('SUM(CASE WHEN orders.status = "delivered" THEN orders.total_fee ELSE 0 END) as total_revenue'),
                DB::raw('ROUND(AVG(CASE WHEN orders.status = "delivered" THEN TIMESTAMPDIFF(HOUR, orders.created_at, orders.completed_at) END), 2) as avg_delivery_time')
            ])
            ->where(function($q) use ($agentCity) {
                if ($agentCity) {
                    $q->where('orders.pickup_city', 'LIKE', "%{$agentCity}%")
                      ->orWhere('orders.delivery_city', 'LIKE', "%{$agentCity}%");
                }
            })
            ->whereBetween('orders.created_at', [$startDateTime, $endDateTime])
            ->whereNotNull('orders.shipper_id')
            ->groupBy('users.id', 'users.name', 'users.email', 'users.phone')
            ->orderByDesc('completed_orders')
            ->get()
            ->map(function ($item) {
                $item->completion_rate = $item->total_orders > 0 
                    ? round(($item->completed_orders / $item->total_orders) * 100, 2) 
                    : 0;
                $item->success_score = ($item->completion_rate * 0.7) + (($item->total_orders / 10) * 0.3);
                return $item;
            });
    }
    
    /**
     * Get trend data for charts
     */
    private function getTrendData($baseQuery, $period, $startDateTime, $endDateTime)
    {
        $format = match($period) {
            'weekly' => '%Y-%u',
            'monthly' => '%Y-%m',
            default => '%Y-%m-%d'
        };
        
        return (clone $baseQuery)
            ->select([
                DB::raw("DATE_FORMAT(created_at, '$format') as period"),
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN total_fee ELSE 0 END) as revenue')
            ])
            ->groupBy('period')
            ->orderBy('period')
            ->get();
    }
    
    /**
     * Get revenue breakdown
     */
    private function getRevenueBreakdown($baseQuery)
    {
        return [
            'shipping_fees' => (clone $baseQuery)->where('status', 'delivered')->sum('shipping_fee'),
            'cod_amounts' => (clone $baseQuery)->where('status', 'delivered')->sum('cod_amount'),
            'total_collected' => (clone $baseQuery)->where('status', 'delivered')->sum('total_fee')
        ];
    }
    
    /**
     * Get service type breakdown
     */
    private function getServiceBreakdown($baseQuery)
    {
        return (clone $baseQuery)
            ->select('package_type', DB::raw('COUNT(*) as count'))
            ->groupBy('package_type')
            ->get()
            ->pluck('count', 'package_type')
            ->toArray();
    }
    
    /**
     * Get top performing shippers
     */
    private function getTopShippers($agentCity, $startDateTime, $endDateTime, $limit = 10)
    {
        return DB::table('orders')
            ->join('users', 'orders.shipper_id', '=', 'users.id')
            ->select([
                'users.name',
                'users.email',
                DB::raw('COUNT(orders.id) as total_deliveries'),
                DB::raw('SUM(orders.total_fee) as total_revenue'),
                DB::raw('ROUND(AVG(CASE WHEN orders.status = "delivered" THEN 5 ELSE 0 END), 2) as avg_rating')
            ])
            ->where('orders.status', 'delivered')
            ->where(function($q) use ($agentCity) {
                if ($agentCity) {
                    $q->where('orders.pickup_city', 'LIKE', "%{$agentCity}%")
                      ->orWhere('orders.delivery_city', 'LIKE', "%{$agentCity}%");
                }
            })
            ->whereBetween('orders.created_at', [$startDateTime, $endDateTime])
            ->groupBy('users.id', 'users.name', 'users.email')
            ->orderByDesc('total_deliveries')
            ->limit($limit)
            ->get();
    }
    
    /**
     * Get city performance statistics
     */
    private function getCityPerformance($startDateTime, $endDateTime)
    {
        return DB::table('orders')
            ->select([
                'pickup_city',
                DB::raw('COUNT(*) as total_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN 1 ELSE 0 END) as completed_orders'),
                DB::raw('SUM(CASE WHEN status = "delivered" THEN total_fee ELSE 0 END) as revenue'),
                DB::raw('ROUND(AVG(CASE WHEN status = "delivered" THEN total_fee END), 0) as avg_order_value')
            ])
            ->whereBetween('created_at', [$startDateTime, $endDateTime])
            ->groupBy('pickup_city')
            ->orderByDesc('total_orders')
            ->get()
            ->map(function ($item) {
                $item->completion_rate = $item->total_orders > 0 
                    ? round(($item->completed_orders / $item->total_orders) * 100, 2) 
                    : 0;
                return $item;
            });
    }
    
    /**
     * Get daily statistics for the last 7 days
     */
    private function getDailyStats($startDateTime, $endDateTime)
    {
        $dailyStats = [];
        
        for ($i = 6; $i >= 0; $i--) {
            $date = Carbon::now()->subDays($i);
            $dayStart = $date->startOfDay();
            $dayEnd = $date->endOfDay();
            
            $dayOrders = Order::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $dayCompleted = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                                ->where('status', 'delivered')->count();
            $dayRevenue = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                              ->where('status', 'delivered')
                              ->sum('total_fee') ?? 0;
            
            $dailyStats[] = [
                'date' => $date->format('d/m'),
                'full_date' => $date->format('Y-m-d'),
                'day_name' => $date->format('l'),
                'total_orders' => $dayOrders,
                'completed_orders' => $dayCompleted,
                'revenue' => $dayRevenue,
                'completion_rate' => $dayOrders > 0 ? round(($dayCompleted / $dayOrders) * 100, 2) : 0
            ];
        }
        
        return collect($dailyStats);
    }
}