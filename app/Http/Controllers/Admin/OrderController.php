<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OrderController extends Controller
{
    public function index()
    {
        try {
            $orders = Order::with(['customer:id,name', 'agent:id,name', 'shipper:id,name'])
                          ->select(['id', 'tracking_number', 'user_id', 'agent_id', 'shipper_id', 'pickup_name', 'delivery_name', 
                                   'pickup_address', 'delivery_address', 'status', 'total_fee', 'created_at'])
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->map(function ($order) {
                              return [
                                  'id' => $order->id,
                                  'tracking_number' => $order->tracking_number ?? 'CX' . str_pad($order->id, 6, '0', STR_PAD_LEFT),
                                  'customer_name' => $order->customer ? $order->customer->name : $order->pickup_name,
                                  'pickup_name' => $order->pickup_name,
                                  'delivery_name' => $order->delivery_name,
                                  'pickup_address' => $order->pickup_address,
                                  'delivery_address' => $order->delivery_address,
                                  'agent' => $order->agent ? $order->agent->name : 'Chưa phân công',
                                  'shipper' => $order->shipper ? $order->shipper->name : 'Chưa phân công',
                                  'status' => $order->status,
                                  'total_fee' => $order->total_fee ?? 0,
                                  'created_at' => $order->created_at->format('d/m/Y H:i'),
                              ];
                          });

            return response()->json($orders);
        } catch (\Exception $e) {
            \Log::error('Admin OrderController index error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải danh sách đơn hàng: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'pickup_name' => 'required|string|max:255',
                'pickup_phone' => 'required|string|max:20',
                'pickup_address' => 'required|string',
                'delivery_name' => 'required|string|max:255',
                'delivery_phone' => 'required|string|max:20',
                'delivery_address' => 'required|string',
                'package_type' => 'required|string',
                'weight' => 'required|numeric|min:0',
                'cod_amount' => 'nullable|numeric|min:0',
                'notes' => 'nullable|string',
                'user_id' => 'required|exists:users,id'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $order = Order::create([
                'user_id' => $request->user_id,
                'tracking_number' => 'CX' . str_pad(Order::count() + 1, 6, '0', STR_PAD_LEFT),
                'pickup_name' => $request->pickup_name,
                'pickup_phone' => $request->pickup_phone,
                'pickup_address' => $request->pickup_address,
                'delivery_name' => $request->delivery_name,
                'delivery_phone' => $request->delivery_phone,
                'delivery_address' => $request->delivery_address,
                'package_type' => $request->package_type,
                'weight' => $request->weight,
                'cod_amount' => $request->cod_amount ?? 0,
                'notes' => $request->notes,
                'status' => 'pending',
                'shipping_fee' => 25000, // Default shipping fee
                'total_fee' => ($request->cod_amount ?? 0) + 25000,
            ]);

            return response()->json([
                'message' => 'Order created successfully',
                'order' => $order
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Admin OrderController store error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tạo đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $order = Order::with(['customer:id,name', 'agent:id,name', 'shipper:id,name'])
                         ->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $order
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin OrderController show error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải thông tin đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        $order = Order::findOrFail($id);

        $validator = Validator::make($request->all(), [
            'status' => 'sometimes|in:pending,processing,in_transit,delivered,cancelled,issue',
            'agent_id' => 'sometimes|nullable|exists:users,id',
            'shipper_id' => 'sometimes|nullable|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }

        $order->update($request->only(['status', 'agent_id', 'shipper_id']));

        return response()->json([
            'message' => 'Order updated successfully',
            'order' => $order
        ]);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->delete();

        return response()->json([
            'message' => 'Order deleted successfully'
        ]);
    }

    public function getStatistics()
    {
        $stats = [
            'total' => Order::count(),
            'pending' => Order::where('status', 'pending')->count(),
            'processing' => Order::where('status', 'processing')->count(),
            'in_transit' => Order::where('status', 'in_transit')->count(),
            'delivered' => Order::where('status', 'delivered')->count(),
            'cancelled' => Order::where('status', 'cancelled')->count(),
            'issue' => Order::where('status', 'issue')->count(),
        ];

        return response()->json($stats);
    }

    public function getAgents()
    {
        $agents = User::where('role', 'agent')
                     ->where('status', 'active')
                     ->select(['id', 'name'])
                     ->get();

        return response()->json($agents);
    }

    public function getShippers()
    {
        $shippers = User::where('role', 'shipper')
                       ->where('status', 'active')
                       ->select(['id', 'name'])
                       ->get();

        return response()->json($shippers);
    }
}