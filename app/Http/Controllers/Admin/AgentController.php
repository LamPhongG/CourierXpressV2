<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;

class AgentController extends Controller
{
    public function index()
    {
        try {
            $agents = User::where('role', 'agent')
                         ->select(['id', 'name', 'email', 'phone', 'address', 'status', 'city', 'created_at'])
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($agent) {
                             return [
                                 'id' => (string) $agent->id, // Đảm bảo là string để DataTables xử lý tốt
                                 'name' => $agent->name ?: 'Chưa cập nhật',
                                 'address' => $agent->address ?: ($agent->city ?: 'Chưa cập nhật'),
                                 'manager' => $agent->name ?: 'Chưa cập nhật', // Tên quản lý = tên agent
                                 'phone' => $agent->phone ?: 'Chưa cập nhật',
                                 'email' => $agent->email ?: 'Chưa cập nhật',
                                 'status' => $agent->status ?: 'active',
                                 'created_at' => $agent->created_at ? $agent->created_at->format('d/m/Y') : 'Chưa có'
                             ];
                         });

            return response()->json($agents);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController index error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải danh sách agents: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'manager' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email',
                'status' => 'required|in:active,pending,blocked'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $agent = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
                'role' => 'agent',
                'status' => $request->status,
                'password' => Hash::make('123456'), // Default password
                'email_verified_at' => now()
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Thêm agent thành công',
                'data' => $agent
            ], 201);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController store error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tạo agent: ' . $e->getMessage()
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            $agent = User::where('role', 'agent')->findOrFail($id);

            return response()->json([
                'success' => true,
                'data' => $agent
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController show error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi tải thông tin agent: ' . $e->getMessage()
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $agent = User::where('role', 'agent')->findOrFail($id);

            $validator = Validator::make($request->all(), [
                'name' => 'required|string|max:255',
                'address' => 'required|string|max:500',
                'manager' => 'required|string|max:255',
                'phone' => 'required|string|max:20',
                'email' => 'required|email|unique:users,email,' . $id,
                'status' => 'required|in:active,pending,blocked'
            ]);

            if ($validator->fails()) {
                return response()->json(['errors' => $validator->errors()], 422);
            }

            $agent->update($request->only(['name', 'email', 'phone', 'address', 'status']));

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật agent thành công',
                'data' => $agent
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController update error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi cập nhật agent: ' . $e->getMessage()
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $agent = User::where('role', 'agent')->findOrFail($id);
            
            // Check if agent has orders
            $hasOrders = Order::where('agent_id', $id)->exists();
            if ($hasOrders) {
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể xóa agent đã có đơn hàng'
                ], 400);
            }

            $agent->delete();

            return response()->json([
                'success' => true,
                'message' => 'Xóa agent thành công'
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController destroy error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi xóa agent: ' . $e->getMessage()
            ], 500);
        }
    }

    public function toggleStatus($id)
    {
        try {
            $agent = User::where('role', 'agent')->findOrFail($id);
            
            $newStatus = $agent->status === 'active' ? 'blocked' : 'active';
            $agent->update(['status' => $newStatus]);

            return response()->json([
                'success' => true,
                'message' => 'Thay đổi trạng thái thành công',
                'data' => $agent
            ]);
        } catch (\Exception $e) {
            \Log::error('Admin AgentController toggleStatus error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Lỗi thay đổi trạng thái: ' . $e->getMessage()
            ], 500);
        }
    }
}