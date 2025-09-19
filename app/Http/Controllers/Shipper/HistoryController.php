<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Rating;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class HistoryController extends Controller
{
    /**
     * Show the delivery history page.
     */
    public function index(Request $request)
    {
        try {
            $shipperId = Auth::id();
            
            // Lấy các filter từ request
            $month = $request->get('month');
            $year = $request->get('year');
            $status = $request->get('status');
            
            // Query lịch sử giao hàng với filter
            $query = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['delivered', 'failed', 'returned'])
                ->with(['customer']);
                
            // Áp dụng filter
            if ($month && $year) {
                $query->whereYear('completed_at', $year)
                      ->whereMonth('completed_at', $month);
            } elseif ($year) {
                $query->whereYear('completed_at', $year);
            }
            
            if ($status) {
                $query->where('status', $status);
            }
                
            $deliveryHistory = $query->orderBy('completed_at', 'desc')
                ->paginate(20);
                
            // Thống kê tổng hợp
            $summaryStats = [
                'total_deliveries' => Order::where('shipper_id', $shipperId)->where('status', 'delivered')->count(),
                'total_failed' => Order::where('shipper_id', $shipperId)->where('status', 'failed')->count(),
                'total_earnings' => Order::where('shipper_id', $shipperId)
                    ->where('status', 'delivered')
                    ->sum('shipping_fee') * 0.8,
                'success_rate' => $this->calculateSuccessRate($shipperId),
                'average_rating' => Rating::where('shipper_id', $shipperId)->avg('rating') ?? 5.0,
                'deliveries_this_month' => Order::where('shipper_id', $shipperId)
                    ->where('status', 'delivered')
                    ->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year)
                    ->count(),
                'earnings_this_month' => Order::where('shipper_id', $shipperId)
                    ->where('status', 'delivered')
                    ->whereMonth('completed_at', now()->month)
                    ->whereYear('completed_at', now()->year)
                    ->sum('shipping_fee') * 0.8
            ];
            
            return view('shipper.history', compact('deliveryHistory', 'summaryStats', 'month', 'year', 'status'));
        } catch (\Exception $e) {
            // Fallback data nếu có lỗi
            $deliveryHistory = collect();
            $summaryStats = [
                'total_deliveries' => 0,
                'total_failed' => 0,
                'total_earnings' => 0,
                'success_rate' => 100,
                'average_rating' => 5.0,
                'deliveries_this_month' => 0,
                'earnings_this_month' => 0
            ];
            
            return view('shipper.history', compact('deliveryHistory', 'summaryStats', 'month', 'year', 'status'));
        }
    }

    /**
     * Get delivery history for the shipper.
     */
    public function getDeliveryHistory(Request $request)
    {
        try {
            $shipperId = Auth::id();
            $perPage = $request->get('per_page', 20);
            $month = $request->get('month');
            $year = $request->get('year');
            $status = $request->get('status');
            
            $query = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['delivered', 'failed', 'returned'])
                ->with(['customer']);

            // Apply filters
            if ($month && $year) {
                $query->whereYear('completed_at', $year)
                      ->whereMonth('completed_at', $month);
            } elseif ($year) {
                $query->whereYear('completed_at', $year);
            }

            if ($status) {
                $query->where('status', $status);
            }

            $orders = $query->orderBy('completed_at', 'desc')
                ->paginate($perPage);
                
            $formattedOrders = $orders->getCollection()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'pickup_name' => $order->pickup_name,
                    'pickup_address' => $order->pickup_address . ', ' . $order->pickup_district,
                    'delivery_name' => $order->delivery_name,
                    'delivery_address' => $order->delivery_address . ', ' . $order->delivery_district,
                    'customer_name' => $order->customer->name,
                    'status' => $order->status,
                    'cod_amount' => $order->cod_amount,
                    'shipping_fee' => $order->shipping_fee,
                    'shipper_fee' => $order->shipping_fee * 0.8, // Giả sử shipper nhận 80%
                    'assigned_at' => $order->assigned_at?->format('d/m/Y H:i'),
                    'pickup_time' => $order->pickup_time?->format('d/m/Y H:i'),
                    'delivery_time' => $order->delivery_time?->format('d/m/Y H:i'),
                    'completed_at' => $order->completed_at?->format('d/m/Y H:i'),
                    'delivery_duration' => $this->calculateDeliveryDuration($order),
                    'rating' => $this->getOrderRating($order->id)
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $formattedOrders,
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
                'message' => 'Không thể tải lịch sử giao hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get delivery statistics for the shipper.
     */
    public function getDeliveryStatistics(Request $request)
    {
        try {
            $shipperId = Auth::id();
            $period = $request->get('period', 'month'); // day, week, month, year
            
            $stats = [
                'summary' => $this->getSummaryStats($shipperId),
                'performance' => $this->getPerformanceStats($shipperId, $period),
                'earnings' => $this->getEarningsStats($shipperId, $period),
                'ratings' => $this->getRatingStats($shipperId)
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
     * Get detailed information about a specific delivery.
     */
    public function getDeliveryDetails($id)
    {
        try {
            $shipperId = Auth::id();
            
            $order = Order::where('shipper_id', $shipperId)
                ->where('id', $id)
                ->with(['customer'])
                ->firstOrFail();

            $details = [
                'order_info' => [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'status' => $order->status,
                    'package_type' => $order->package_type,
                    'weight' => $order->weight,
                    'dimensions' => $order->dimensions,
                    'description' => $order->description,
                    'notes' => $order->notes
                ],
                'customer' => [
                    'name' => $order->customer->name,
                    'phone' => $order->customer->phone,
                    'email' => $order->customer->email
                ],
                'pickup' => [
                    'name' => $order->pickup_name,
                    'phone' => $order->pickup_phone,
                    'address' => $order->pickup_address,
                    'ward' => $order->pickup_ward,
                    'district' => $order->pickup_district,
                    'city' => $order->pickup_city,
                    'full_address' => $order->pickup_address . ', ' . $order->pickup_ward . ', ' . $order->pickup_district . ', ' . $order->pickup_city
                ],
                'delivery' => [
                    'name' => $order->delivery_name,
                    'phone' => $order->delivery_phone,
                    'address' => $order->delivery_address,
                    'ward' => $order->delivery_ward,
                    'district' => $order->delivery_district,
                    'city' => $order->delivery_city,
                    'full_address' => $order->delivery_address . ', ' . $order->delivery_ward . ', ' . $order->delivery_district . ', ' . $order->delivery_city
                ],
                'payment' => [
                    'cod_amount' => $order->cod_amount,
                    'shipping_fee' => $order->shipping_fee,
                    'shipper_fee' => $order->shipping_fee * 0.8,
                    'total_amount' => $order->total_amount
                ],
                'timeline' => [
                    'assigned_at' => $order->assigned_at,
                    'pickup_time' => $order->pickup_time,
                    'delivery_time' => $order->delivery_time,
                    'completed_at' => $order->completed_at,
                    'duration' => $this->calculateDeliveryDuration($order)
                ],
                'rating' => $this->getOrderRating($order->id),
                'delivery_photos' => $this->getDeliveryPhotos($order->id),
                'status_history' => $this->getStatusHistory($order->id)
            ];

            return response()->json([
                'success' => true,
                'data' => $details
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin chi tiết: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get summary statistics.
     */
    private function getSummaryStats($shipperId)
    {
        return [
            'total_deliveries' => Order::where('shipper_id', $shipperId)->where('status', 'delivered')->count(),
            'total_failed' => Order::where('shipper_id', $shipperId)->where('status', 'failed')->count(),
            'total_earnings' => Order::where('shipper_id', $shipperId)
                ->where('status', 'delivered')
                ->sum('shipping_fee') * 0.8,
            'success_rate' => $this->calculateSuccessRate($shipperId),
            'average_rating' => Rating::where('shipper_id', $shipperId)->avg('rating') ?? 5.0,
            'total_ratings' => Rating::where('shipper_id', $shipperId)->count()
        ];
    }

    /**
     * Get performance statistics for a period.
     */
    private function getPerformanceStats($shipperId, $period)
    {
        $dateRange = $this->getDateRange($period);
        
        $deliveries = Order::where('shipper_id', $shipperId)
            ->whereBetween('completed_at', $dateRange)
            ->where('status', 'delivered')
            ->get();

        return [
            'period' => $period,
            'total_deliveries' => $deliveries->count(),
            'average_delivery_time' => $this->calculateAverageDeliveryTime($deliveries),
            'fastest_delivery' => $this->getFastestDelivery($deliveries),
            'busiest_day' => $this->getBusiestDay($deliveries),
            'delivery_by_day' => $this->getDeliveryByDay($deliveries, $period)
        ];
    }

    /**
     * Get earnings statistics for a period.
     */
    private function getEarningsStats($shipperId, $period)
    {
        $dateRange = $this->getDateRange($period);
        
        $orders = Order::where('shipper_id', $shipperId)
            ->whereBetween('completed_at', $dateRange)
            ->where('status', 'delivered')
            ->get();

        $totalEarnings = $orders->sum('shipping_fee') * 0.8;
        $averagePerDelivery = $orders->count() > 0 ? $totalEarnings / $orders->count() : 0;

        return [
            'period' => $period,
            'total_earnings' => $totalEarnings,
            'average_per_delivery' => $averagePerDelivery,
            'highest_earning_day' => $this->getHighestEarningDay($orders),
            'earnings_by_day' => $this->getEarningsByDay($orders, $period)
        ];
    }

    /**
     * Get rating statistics.
     */
    private function getRatingStats($shipperId)
    {
        $ratings = Rating::where('shipper_id', $shipperId)->get();
        
        return [
            'average_rating' => $ratings->avg('rating') ?? 5.0,
            'total_ratings' => $ratings->count(),
            'rating_distribution' => [
                '5_star' => $ratings->where('rating', 5)->count(),
                '4_star' => $ratings->where('rating', 4)->count(),
                '3_star' => $ratings->where('rating', 3)->count(),
                '2_star' => $ratings->where('rating', 2)->count(),
                '1_star' => $ratings->where('rating', 1)->count()
            ],
            'recent_reviews' => $this->getRecentReviews($shipperId)
        ];
    }

    /**
     * Calculate delivery duration in minutes.
     */
    private function calculateDeliveryDuration($order)
    {
        if (!$order->assigned_at || !$order->completed_at) {
            return null;
        }
        
        return $order->assigned_at->diffInMinutes($order->completed_at);
    }

    /**
     * Get order rating.
     */
    private function getOrderRating($orderId)
    {
        $rating = Rating::where('order_id', $orderId)->first();
        
        if (!$rating) {
            return null;
        }
        
        return [
            'rating' => $rating->rating,
            'comment' => $rating->comment,
            'created_at' => $rating->created_at->format('d/m/Y H:i')
        ];
    }

    /**
     * Calculate success rate.
     */
    private function calculateSuccessRate($shipperId)
    {
        $total = Order::where('shipper_id', $shipperId)
            ->whereIn('status', ['delivered', 'failed'])
            ->count();
            
        if ($total === 0) {
            return 100;
        }
        
        $successful = Order::where('shipper_id', $shipperId)
            ->where('status', 'delivered')
            ->count();
            
        return round(($successful / $total) * 100, 2);
    }

    /**
     * Get date range for period.
     */
    private function getDateRange($period)
    {
        switch ($period) {
            case 'day':
                return [Carbon::today(), Carbon::tomorrow()];
            case 'week':
                return [Carbon::now()->startOfWeek(), Carbon::now()->endOfWeek()];
            case 'month':
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
            case 'year':
                return [Carbon::now()->startOfYear(), Carbon::now()->endOfYear()];
            default:
                return [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        }
    }

    /**
     * Calculate average delivery time.
     */
    private function calculateAverageDeliveryTime($deliveries)
    {
        if ($deliveries->isEmpty()) {
            return 0;
        }
        
        $totalMinutes = $deliveries->sum(function ($delivery) {
            return $this->calculateDeliveryDuration($delivery) ?? 0;
        });
        
        return round($totalMinutes / $deliveries->count(), 2);
    }

    /**
     * Get fastest delivery.
     */
    private function getFastestDelivery($deliveries)
    {
        $fastest = null;
        $minTime = null;
        
        foreach ($deliveries as $delivery) {
            $duration = $this->calculateDeliveryDuration($delivery);
            if ($duration && ($minTime === null || $duration < $minTime)) {
                $minTime = $duration;
                $fastest = $delivery;
            }
        }
        
        return $fastest ? [
            'tracking_number' => $fastest->tracking_number,
            'duration' => $minTime,
            'completed_at' => $fastest->completed_at->format('d/m/Y H:i')
        ] : null;
    }

    /**
     * Get busiest day.
     */
    private function getBusiestDay($deliveries)
    {
        $days = $deliveries->groupBy(function ($delivery) {
            return $delivery->completed_at->format('Y-m-d');
        });
        
        $busiestDay = $days->sortByDesc(function ($dayDeliveries) {
            return $dayDeliveries->count();
        })->first();
        
        return $busiestDay ? [
            'date' => $busiestDay->first()->completed_at->format('d/m/Y'),
            'deliveries' => $busiestDay->count()
        ] : null;
    }

    /**
     * Get delivery count by day.
     */
    private function getDeliveryByDay($deliveries, $period)
    {
        return $deliveries->groupBy(function ($delivery) {
            return $delivery->completed_at->format('Y-m-d');
        })->map(function ($dayDeliveries) {
            return $dayDeliveries->count();
        });
    }

    /**
     * Get highest earning day.
     */
    private function getHighestEarningDay($orders)
    {
        $days = $orders->groupBy(function ($order) {
            return $order->completed_at->format('Y-m-d');
        });
        
        $highestDay = $days->sortByDesc(function ($dayOrders) {
            return $dayOrders->sum('shipping_fee') * 0.8;
        })->first();
        
        return $highestDay ? [
            'date' => $highestDay->first()->completed_at->format('d/m/Y'),
            'earnings' => $highestDay->sum('shipping_fee') * 0.8
        ] : null;
    }

    /**
     * Get earnings by day.
     */
    private function getEarningsByDay($orders, $period)
    {
        return $orders->groupBy(function ($order) {
            return $order->completed_at->format('Y-m-d');
        })->map(function ($dayOrders) {
            return $dayOrders->sum('shipping_fee') * 0.8;
        });
    }

    /**
     * Get recent reviews.
     */
    private function getRecentReviews($shipperId)
    {
        return Rating::where('shipper_id', $shipperId)
            ->with(['order', 'customer'])
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get()
            ->map(function ($rating) {
                return [
                    'rating' => $rating->rating,
                    'comment' => $rating->comment,
                    'customer_name' => $rating->customer->name ?? 'Khách hàng',
                    'order_tracking' => $rating->order->tracking_number ?? '',
                    'created_at' => $rating->created_at->format('d/m/Y H:i')
                ];
            });
    }

    /**
     * Get delivery photos.
     */
    private function getDeliveryPhotos($orderId)
    {
        // Trong thực tế, có thể lưu trong bảng riêng
        $order = Order::find($orderId);
        return $order->delivery_photo ? [$order->delivery_photo] : [];
    }

    /**
     * Get status history.
     */
    private function getStatusHistory($orderId)
    {
        // Giả sử có bảng order_status_history
        return DB::table('order_status_history')
            ->where('order_id', $orderId)
            ->orderBy('created_at', 'asc')
            ->get()
            ->map(function ($status) {
                return [
                    'status' => $status->status,
                    'note' => $status->note,
                    'created_at' => Carbon::parse($status->created_at)->format('d/m/Y H:i'),
                    'latitude' => $status->latitude,
                    'longitude' => $status->longitude
                ];
            });
    }
}