<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    /**
     * Get user's orders with filtering and pagination
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        
        $query = Order::where('user_id', $user->id)
                     ->with(['shipper:id,name,phone']);
        
        // Apply filters
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        
        if ($request->filled('dateRange')) {
            $dateRange = $request->dateRange;
            $now = now();
            
            switch ($dateRange) {
                case 'today':
                    $query->whereDate('created_at', $now->toDateString());
                    break;
                case 'last7days':
                    $query->where('created_at', '>=', $now->subDays(7));
                    break;
                case 'last30days':
                    $query->where('created_at', '>=', $now->subDays(30));
                    break;
                case 'thisMonth':
                    $query->whereMonth('created_at', $now->month)
                          ->whereYear('created_at', $now->year);
                    break;
                case 'lastMonth':
                    $query->whereMonth('created_at', $now->subMonth()->month)
                          ->whereYear('created_at', $now->year);
                    break;
            }
        }
        
        // Apply sorting
        if ($request->filled('sort')) {
            switch ($request->sort) {
                case 'oldest':
                    $query->orderBy('created_at', 'asc');
                    break;
                case 'highest':
                    $query->orderBy('value', 'desc');
                    break;
                case 'lowest':
                    $query->orderBy('value', 'asc');
                    break;
                default: // newest
                    $query->orderBy('created_at', 'desc');
                    break;
            }
        } else {
            $query->orderBy('created_at', 'desc');
        }
        
        $orders = $query->get()->map(function ($order) {
            return [
                'id' => $order->id,
                'created_at' => $order->created_at,
                'delivery_address' => $order->delivery_address,
                'shipper' => $order->shipper ? [
                    'name' => $order->shipper->name,
                    'phone' => $order->shipper->phone
                ] : null,
                'value' => $order->value ?? 0,
                'shipping_fee' => $order->shipping_fee ?? 0,
                'status' => $order->status
            ];
        });
        
        return response()->json(['data' => $orders]);
    }
    
    /**
     * Create a new order
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'pickup_name' => 'required|string|max:255',
            'pickup_phone' => 'required|string|max:20',
            'pickup_address' => 'required|string',
            'delivery_name' => 'required|string|max:255',
            'delivery_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'package_type' => 'required|string',
            'weight' => 'required|numeric|min:0',
            'value' => 'nullable|numeric|min:0',
            'cod_amount' => 'nullable|numeric|min:0',
            'notes' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $validator->errors()
            ], 422);
        }

        // Calculate shipping fee based on service
        $shippingFee = $this->calculateShippingFee($request->shipping_service, $request->weight, $request->value);
        
        // Generate tracking number
        $trackingNumber = 'CX' . date('Ymd') . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT);
        
        $order = Order::create([
            'user_id' => Auth::id(),
            'tracking_number' => $trackingNumber,
            'pickup_name' => $request->pickup_name,
            'pickup_phone' => $request->pickup_phone,
            'pickup_address' => $request->pickup_address,
            'pickup_ward' => $request->pickup_ward ?? 'Phường 1',
            'pickup_district' => $request->pickup_district ?? 'Quận 1',
            'pickup_city' => $request->pickup_city ?? 'TP. Hồ Chí Minh',
            'pickup_latitude' => $request->pickup_lat ?? null,
            'pickup_longitude' => $request->pickup_lng ?? null,
            'delivery_name' => $request->delivery_name,
            'delivery_phone' => $request->delivery_phone,
            'delivery_address' => $request->delivery_address,
            'delivery_ward' => $request->delivery_ward ?? 'Phường 1',
            'delivery_district' => $request->delivery_district ?? 'Quận 1',
            'delivery_city' => $request->delivery_city ?? 'TP. Hồ Chí Minh',
            'delivery_latitude' => $request->delivery_lat ?? null,
            'delivery_longitude' => $request->delivery_lng ?? null,
            'package_type' => $request->package_type,
            'weight' => $request->weight,
            'value' => $request->value ?? 0,
            'cod_amount' => $request->cod_amount ?? 0,
            'notes' => $request->notes,
            'shipping_fee' => $shippingFee,
            'insurance_fee' => 0,
            'total_fee' => $shippingFee + ($request->cod_amount ?? 0),
            'payment_method' => $request->payment_method ?? 'cash',
            'payment_status' => 'pending',
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Đơn hàng được tạo thành công',
            'data' => [
                'id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'order' => $order
            ]
        ], 201);
    }
    
    /**
     * Get order details
     */
    public function show($id)
    {
        $order = Order::where('user_id', Auth::id())
                     ->with(['shipper:id,name,phone'])
                     ->findOrFail($id);
        
        return response()->json($order);
    }
    
    /**
     * Cancel order
     */
    public function cancel($id)
    {
        $order = Order::where('user_id', Auth::id())
                     ->where('status', 'pending')
                     ->findOrFail($id);
        
        $order->update(['status' => 'cancelled']);
        
        return response()->json([
            'message' => 'Order cancelled successfully'
        ]);
    }
    
    /**
     * Calculate shipping fee based on service type, weight, and value
     */
    private function calculateShippingFee($serviceType, $weight, $value)
    {
        $baseFees = [
            'standard' => 30000,
            'fast' => 50000,
            'express' => 80000
        ];
        
        $baseFee = $baseFees[$serviceType] ?? $baseFees['standard'];
        
        // Add weight-based fee (10,000 VND per kg over 1kg)
        if ($weight > 1) {
            $baseFee += ($weight - 1) * 10000;
        }
        
        // Add value-based insurance fee (0.5% of value over 1,000,000 VND)
        if ($value > 1000000) {
            $baseFee += ($value - 1000000) * 0.005;
        }
        
        return round($baseFee);
    }
}