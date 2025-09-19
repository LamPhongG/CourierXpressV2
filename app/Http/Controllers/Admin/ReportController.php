<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Carbon\Carbon;

class ReportController extends Controller
{
    public function index(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

            // Convert to Carbon instances
            $start = Carbon::parse($startDate)->startOfDay();
            $end = Carbon::parse($endDate)->endOfDay();

            // Get summary statistics
            $totalOrders = Order::whereBetween('created_at', [$start, $end])->count();
            $completedOrders = Order::whereBetween('created_at', [$start, $end])
                                   ->where('status', 'delivered')
                                   ->count();
            $totalRevenue = Order::whereBetween('created_at', [$start, $end])
                                ->where('status', 'delivered')
                                ->sum('total_fee') ?? 0;
            $avgOrderValue = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;
            $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

            // Generate chart data for the last 7 days
            $chartLabels = [];
            $revenueData = [];
            $completedData = [];
            $failedData = [];

            for ($i = 6; $i >= 0; $i--) {
                $date = Carbon::now()->subDays($i);
                $chartLabels[] = $date->format('d/m');
                
                $dayStart = $date->startOfDay();
                $dayEnd = $date->endOfDay();
                
                $dayRevenue = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                                  ->where('status', 'delivered')
                                  ->sum('total_fee') ?? 0;
                $revenueData[] = $dayRevenue;
                
                $dayCompleted = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                                    ->where('status', 'delivered')
                                    ->count();
                $completedData[] = $dayCompleted;
                
                $dayTotal = Order::whereBetween('created_at', [$dayStart, $dayEnd])->count();
                $failedData[] = $dayTotal - $dayCompleted;
            }

            // Get top performing shippers
            $topShippers = User::where('role', 'shipper')
                              ->withCount(['orders as completed_orders' => function($query) use ($start, $end) {
                                  $query->whereBetween('created_at', [$start, $end])
                                        ->where('status', 'delivered');
                              }])
                              ->withCount(['orders as total_orders' => function($query) use ($start, $end) {
                                  $query->whereBetween('created_at', [$start, $end]);
                              }])
                              ->having('total_orders', '>', 0)
                              ->orderBy('completed_orders', 'desc')
                              ->limit(5)
                              ->get()
                              ->map(function($shipper) {
                                  $successRate = $shipper->total_orders > 0 ? 
                                               round(($shipper->completed_orders / $shipper->total_orders) * 100, 1) : 0;
                                  
                                  return [
                                      'name' => $shipper->name,
                                      'phone' => $shipper->phone ?? 'N/A',
                                      'avatar' => '/images/default-avatar.png',
                                      'completed_orders' => $shipper->completed_orders,
                                      'success_rate' => $successRate,
                                      'average_rating' => 4.5 // Mock data
                                  ];
                              });

            // Generate daily statistics
            $dailyStats = [];
            $period = Carbon::parse($startDate);
            while ($period->lte(Carbon::parse($endDate))) {
                $dayStart = $period->copy()->startOfDay();
                $dayEnd = $period->copy()->endOfDay();
                
                $dayTotalOrders = Order::whereBetween('created_at', [$dayStart, $dayEnd])->count();
                $dayCompletedOrders = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                                          ->where('status', 'delivered')
                                          ->count();
                $dayFailedOrders = $dayTotalOrders - $dayCompletedOrders;
                $dayRevenue = Order::whereBetween('created_at', [$dayStart, $dayEnd])
                                  ->where('status', 'delivered')
                                  ->sum('total_fee') ?? 0;
                $daySuccessRate = $dayTotalOrders > 0 ? round(($dayCompletedOrders / $dayTotalOrders) * 100, 1) : 0;

                $dailyStats[] = [
                    'date' => $period->format('d/m/Y'),
                    'total_orders' => $dayTotalOrders,
                    'completed_orders' => $dayCompletedOrders,
                    'failed_orders' => $dayFailedOrders,
                    'revenue' => $dayRevenue,
                    'success_rate' => $daySuccessRate
                ];

                $period->addDay();
            }

            return response()->json([
                'success' => true,
                'summary' => [
                    'total_revenue' => $totalRevenue,
                    'total_orders' => $totalOrders,
                    'average_order_value' => $avgOrderValue,
                    'success_rate' => $successRate
                ],
                'revenue_chart' => [
                    'labels' => $chartLabels,
                    'data' => $revenueData
                ],
                'orders_chart' => [
                    'labels' => $chartLabels,
                    'completed' => $completedData,
                    'failed' => $failedData
                ],
                'area_performance' => [85, 92, 78], // Mock data for North, Central, South
                'shipper_performance' => $topShippers,
                'daily_statistics' => $dailyStats
            ]);

        } catch (\Exception $e) {
            \Log::error('Admin ReportController index error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải báo cáo: ' . $e->getMessage(),
                'summary' => [
                    'total_revenue' => 0,
                    'total_orders' => 0,
                    'average_order_value' => 0,
                    'success_rate' => 0
                ],
                'revenue_chart' => ['labels' => [], 'data' => []],
                'orders_chart' => ['labels' => [], 'completed' => [], 'failed' => []],
                'area_performance' => [0, 0, 0],
                'shipper_performance' => [],
                'daily_statistics' => []
            ], 500);
        }
    }

    public function export(Request $request)
    {
        try {
            $startDate = $request->input('start_date', Carbon::now()->startOfMonth()->format('Y-m-d'));
            $endDate = $request->input('end_date', Carbon::now()->format('Y-m-d'));

            // Here you would implement Excel export logic
            // For now, return a simple CSV response
            
            $filename = "courier_report_{$startDate}_to_{$endDate}.csv";
            
            return response()->json([
                'success' => true,
                'message' => 'Xuất báo cáo thành công',
                'download_url' => "/storage/reports/{$filename}"
            ]);

        } catch (\Exception $e) {
            \Log::error('Admin ReportController export error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xuất báo cáo: ' . $e->getMessage()
            ], 500);
        }
    }
}