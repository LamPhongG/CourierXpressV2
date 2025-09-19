<?php

namespace App\Http\Controllers\Shipper;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class OrderController extends Controller
{
    /**
     * Show the order management page.
     */
    public function index(Request $request)
    {
        try {
            $shipperId = Auth::id();
            
            // Lấy các filter từ request
            $status = $request->get('status');
            $date = $request->get('date');
            $area = $request->get('area');
            $trackingNumber = $request->get('tracking_number');
            
            // Query đơn hàng với filter
            $query = Order::where('shipper_id', $shipperId)
                ->with(['user' => function($q) {
                    $q->select('id', 'name', 'phone', 'email');
                }]);
                
            // Áp dụng filter status
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }
            
            // Áp dụng filter ngày
            if ($date) {
                $query->whereDate('created_at', Carbon::parse($date));
            }
            
            // Áp dụng filter mã vận đơn
            if ($trackingNumber) {
                $query->where('tracking_number', 'like', "%{$trackingNumber}%");
            }
            
            // Áp dụng filter khu vực (tìm kiếm toàn diện)
            if ($area) {
                $query->where(function($q) use ($area) {
                    $q->where('delivery_district', 'like', "%{$area}%")
                      ->orWhere('delivery_city', 'like', "%{$area}%")
                      ->orWhere('pickup_district', 'like', "%{$area}%")
                      ->orWhere('pickup_city', 'like', "%{$area}%")
                      ->orWhere('delivery_address', 'like', "%{$area}%")
                      ->orWhere('pickup_address', 'like', "%{$area}%")
                      ->orWhere('delivery_ward', 'like', "%{$area}%")
                      ->orWhere('pickup_ward', 'like', "%{$area}%");
                });
            }
                
            $orders = $query->orderBy('created_at', 'desc')
                ->paginate(15)
                ->withQueryString(); // Giữ lại query parameters khi phân trang
                
            // Thống kê nhanh từ database thực
            $quickStats = [
                'total_orders' => Order::where('shipper_id', $shipperId)->count(),
                'pending_orders' => Order::where('shipper_id', $shipperId)
                    ->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                    ->count(),
                'completed_today' => Order::where('shipper_id', $shipperId)
                    ->where('status', 'delivered')
                    ->whereDate('completed_at', today())
                    ->count(),
                'earnings_today' => Order::where('shipper_id', $shipperId)
                    ->where('status', 'delivered')
                    ->whereDate('completed_at', today())
                    ->sum('shipping_fee') * 0.7 // 70% hoa hồng cho shipper
            ];
            
            // Các trạng thái có sẵn
            $statusOptions = [
                'all' => 'Tất cả',
                'assigned' => 'Đã phân công',
                'pickup' => 'Đang lấy hàng',
                'picked_up' => 'Đã lấy hàng',
                'in_transit' => 'Đang vận chuyển',
                'delivering' => 'Đang giao hàng',
                'delivered' => 'Đã giao thành công',
                'failed' => 'Giao hàng thất bại',
                'returned' => 'Đã trả về'
            ];
            
            return view('shipper.orders', compact('orders', 'quickStats', 'status', 'date', 'area', 'trackingNumber', 'statusOptions'));
        } catch (\Exception $e) {
            \Log::error('Shipper orders index error: ' . $e->getMessage());
            
            // Fallback data nếu có lỗi
            $orders = new \Illuminate\Pagination\LengthAwarePaginator([], 0, 15);
            $quickStats = [
                'total_orders' => 0,
                'pending_orders' => 0,
                'completed_today' => 0,
                'earnings_today' => 0
            ];
            
            $statusOptions = [
                'all' => 'Tất cả',
                'assigned' => 'Đã phân công',
                'pickup' => 'Đang lấy hàng',
                'picked_up' => 'Đã lấy hàng',
                'in_transit' => 'Đang vận chuyển',
                'delivering' => 'Đang giao hàng',
                'delivered' => 'Đã giao thành công',
                'failed' => 'Giao hàng thất bại',
                'returned' => 'Đã trả về'
            ];
            
            session()->flash('error', 'Có lỗi xảy ra khi tải dữ liệu đơn hàng');
            return view('shipper.orders', compact('orders', 'quickStats', 'status', 'date', 'area', 'trackingNumber', 'statusOptions'));
        }
    }

    /**
     * Get orders for the shipper with filters (API endpoint).
     */
    public function getOrders(Request $request)
    {
        try {
            $shipperId = Auth::id();
            $perPage = $request->get('per_page', 15);
            $status = $request->get('status');
            $date = $request->get('date');
            $area = $request->get('area');
            $trackingNumber = $request->get('tracking_number');
            
            $query = Order::where('shipper_id', $shipperId)
                ->with(['user' => function($q) {
                    $q->select('id', 'name', 'phone', 'email');
                }]);

            // Apply filters
            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            if ($date) {
                $query->whereDate('created_at', Carbon::parse($date));
            }

            if ($trackingNumber) {
                $query->where('tracking_number', 'like', "%{$trackingNumber}%");
            }

            if ($area) {
                $query->where(function($q) use ($area) {
                    $q->where('delivery_district', 'like', "%{$area}%")
                      ->orWhere('delivery_city', 'like', "%{$area}%")
                      ->orWhere('pickup_district', 'like', "%{$area}%")
                      ->orWhere('pickup_city', 'like', "%{$area}%")
                      ->orWhere('delivery_address', 'like', "%{$area}%")
                      ->orWhere('pickup_address', 'like', "%{$area}%")
                      ->orWhere('delivery_ward', 'like', "%{$area}%")
                      ->orWhere('pickup_ward', 'like', "%{$area}%");
                });
            }

            $orders = $query->orderBy('created_at', 'desc')
                ->paginate($perPage);
                
            $formattedOrders = $orders->getCollection()->map(function ($order) {
                return [
                    'id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'pickup_name' => $order->pickup_name,
                    'pickup_phone' => $order->pickup_phone,
                    'pickup_address' => $order->pickup_address,
                    'pickup_ward' => $order->pickup_ward,
                    'pickup_district' => $order->pickup_district,
                    'pickup_city' => $order->pickup_city,
                    'delivery_name' => $order->delivery_name,
                    'delivery_phone' => $order->delivery_phone,
                    'delivery_address' => $order->delivery_address,
                    'delivery_ward' => $order->delivery_ward,
                    'delivery_district' => $order->delivery_district,
                    'delivery_city' => $order->delivery_city,
                    'customer_name' => $order->user ? $order->user->name : 'N/A',
                    'customer_phone' => $order->user ? $order->user->phone : 'N/A',
                    'status' => $order->status,
                    'status_text' => $this->getStatusText($order->status),
                    'package_type' => $order->package_type,
                    'weight' => $order->weight,
                    'cod_amount' => $order->cod_amount,
                    'shipping_fee' => $order->shipping_fee,
                    'total_fee' => $order->total_fee,
                    'notes' => $order->notes,
                    'assigned_at' => $order->assigned_at?->format('d/m/Y H:i'),
                    'created_at' => $order->created_at->format('d/m/Y H:i'),
                    'pickup_latitude' => $order->pickup_latitude,
                    'pickup_longitude' => $order->pickup_longitude,
                    'delivery_latitude' => $order->delivery_latitude,
                    'delivery_longitude' => $order->delivery_longitude,
                    'estimated_delivery' => $this->calculateEstimatedDelivery($order),
                    'priority' => $this->getOrderPriority($order),
                    'can_update_status' => $this->canUpdateStatus($order->status)
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
                ],
                'filters' => [
                    'status' => $status,
                    'date' => $date,
                    'area' => $area,
                    'tracking_number' => $trackingNumber
                ]
            ]);
        } catch (\Exception $e) {
            \Log::error('Get orders API error: ' . $e->getMessage());
            
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get details of a specific order.
     */
    public function getOrderDetails($id)
    {
        try {
            $shipperId = Auth::id();
            
            $order = Order::where('shipper_id', $shipperId)
                ->where('id', $id)
                ->with(['customer'])
                ->firstOrFail();

            $orderDetails = [
                'id' => $order->id,
                'tracking_number' => $order->tracking_number,
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
                    'latitude' => $order->pickup_latitude,
                    'longitude' => $order->pickup_longitude,
                    'full_address' => $order->pickup_address . ', ' . $order->pickup_ward . ', ' . $order->pickup_district . ', ' . $order->pickup_city
                ],
                'delivery' => [
                    'name' => $order->delivery_name,
                    'phone' => $order->delivery_phone,
                    'address' => $order->delivery_address,
                    'ward' => $order->delivery_ward,
                    'district' => $order->delivery_district,
                    'city' => $order->delivery_city,
                    'latitude' => $order->delivery_latitude,
                    'longitude' => $order->delivery_longitude,
                    'full_address' => $order->delivery_address . ', ' . $order->delivery_ward . ', ' . $order->delivery_district . ', ' . $order->delivery_city
                ],
                'package' => [
                    'type' => $order->package_type,
                    'weight' => $order->weight,
                    'length' => $order->length,
                    'width' => $order->width,
                    'height' => $order->height
                ],
                'payment' => [
                    'cod_amount' => $order->cod_amount,
                    'shipping_fee' => $order->shipping_fee,
                    'total_fee' => $order->total_fee
                ],
                'status' => $order->status,
                'notes' => $order->notes,
                'assigned_at' => $order->assigned_at?->format('d/m/Y H:i'),
                'pickup_at' => $order->pickup_at?->format('d/m/Y H:i'),
                'picked_up_at' => $order->picked_up_at?->format('d/m/Y H:i'),
                'completed_at' => $order->completed_at?->format('d/m/Y H:i'),
                'created_at' => $order->created_at->format('d/m/Y H:i'),
                'estimated_delivery' => $this->calculateEstimatedDelivery($order),
                'priority' => $this->getOrderPriority($order),
                'timeline' => $this->getOrderTimeline($order)
            ];

            return response()->json([
                'success' => true,
                'data' => $orderDetails
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải thông tin đơn hàng: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Update order status.
     */
    public function updateStatus(Request $request, $id)
    {
        \Log::info('=== SHIPPER UPDATE STATUS DEBUG ===', [
            'order_id' => $id,
            'shipper_id' => Auth::id(),
            'request_data' => $request->all(),
            'headers' => $request->headers->all(),
            'method' => $request->method(),
            'url' => $request->fullUrl()
        ]);
        
        try {
            $shipperId = Auth::id();
            
            // Validate input
            $request->validate([
                'status' => 'required|string',
                'note' => 'nullable|string|max:500',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'reason' => 'nullable|string|max:200'
            ]);
            
            \Log::info('Validation passed successfully');
            
            $order = Order::where('shipper_id', $shipperId)
                ->where('id', $id)
                ->first();
                
            if (!$order) {
                \Log::warning('Order not found or access denied', [
                    'order_id' => $id,
                    'shipper_id' => $shipperId,
                    'order_exists' => Order::where('id', $id)->exists(),
                    'shipper_orders_count' => Order::where('shipper_id', $shipperId)->count()
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Không tìm thấy đơn hàng hoặc bạn không có quyền truy cập'
                ], 404);
            }

            $validStatuses = [
                'assigned' => 'Đã phân công',
                'pickup' => 'Đang lấy hàng',
                'picked_up' => 'Đã lấy hàng',
                'in_transit' => 'Đang vận chuyển',
                'delivering' => 'Đang giao hàng',
                'delivered' => 'Đã giao thành công',
                'failed' => 'Giao hàng thất bại',
                'returned' => 'Đã trả về'
            ];
            
            $newStatus = $request->status;
            
            if (!array_key_exists($newStatus, $validStatuses)) {
                return response()->json([
                    'success' => false,
                    'message' => 'Trạng thái không hợp lệ: ' . $newStatus
                ], 400);
            }

            $oldStatus = $order->status;
            
            // Kiểm tra quy trình cập nhật trạng thái hợp lệ
            if (!$this->isValidStatusTransition($oldStatus, $newStatus)) {
                \Log::warning('Invalid status transition', [
                    'order_id' => $id,
                    'old_status' => $oldStatus,
                    'new_status' => $newStatus
                ]);
                
                return response()->json([
                    'success' => false,
                    'message' => 'Không thể chuyển trạng thái từ "' . $validStatuses[$oldStatus] . '" sang "' . $validStatuses[$newStatus] . '"',
                    'current_status' => $oldStatus,
                    'requested_status' => $newStatus,
                    'valid_transitions' => $this->getValidTransitions($oldStatus)
                ], 400);
            }

            DB::transaction(function () use ($order, $request, $newStatus, $oldStatus) {
                // Cập nhật trạng thái đơn hàng
                $order->status = $newStatus;
                
                // Cập nhật thời gian dựa trên trạng thái
                switch ($newStatus) {
                    case 'pickup':
                        if (!$order->pickup_at) {
                            $order->pickup_at = now();
                        }
                        break;
                    case 'picked_up':
                        if (!$order->picked_up_at) {
                            $order->picked_up_at = now();
                        }
                        break;
                    case 'in_transit':
                        if (!$order->in_transit_at) {
                            $order->in_transit_at = now();
                        }
                        break;
                    case 'delivering':
                        if (!$order->delivering_at) {
                            $order->delivering_at = now();
                        }
                        break;
                    case 'delivered':
                        if (!$order->completed_at) {
                            $order->completed_at = now();
                        }
                        // Cập nhật payment status khi giao thành công
                        if ($order->payment_status === 'pending') {
                            $order->payment_status = 'paid';
                        }
                        break;
                    case 'failed':
                    case 'returned':
                        if (!$order->completed_at) {
                            $order->completed_at = now();
                        }
                        // Lưu lý do thất bại
                        if ($request->reason) {
                            $order->notes = ($order->notes ? $order->notes . '\n' : '') . 'Lý do: ' . $request->reason;
                        }
                        break;
                }
                
                $order->save();

                // Lưu lịch sử trạng thái
                $this->createStatusHistory($order, $oldStatus, $newStatus, $request->note, $request->latitude, $request->longitude);
            });
            
            \Log::info('=== ORDER STATUS UPDATE SUCCESS ===', [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'shipper_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Cập nhật trạng thái thành công',
                'data' => [
                    'order_id' => $order->id,
                    'tracking_number' => $order->tracking_number,
                    'old_status' => $oldStatus,
                    'old_status_text' => $validStatuses[$oldStatus],
                    'new_status' => $newStatus,
                    'new_status_text' => $validStatuses[$newStatus],
                    'updated_at' => $order->updated_at->format('d/m/Y H:i:s'),
                    'can_update_again' => $this->canUpdateStatus($newStatus),
                    'next_valid_statuses' => $this->getValidTransitions($newStatus)
                ]
            ]);
        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::warning('=== VALIDATION ERROR ===', [
                'order_id' => $id,
                'errors' => $e->errors(),
                'input' => $request->all()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Dữ liệu không hợp lệ',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('=== UPDATE ORDER STATUS ERROR ===', [
                'order_id' => $id,
                'shipper_id' => Auth::id(),
                'status' => $request->status ?? 'unknown',
                'error_message' => $e->getMessage(),
                'error_line' => $e->getLine(),
                'error_file' => $e->getFile(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => 'Có lỗi xảy ra khi cập nhật trạng thái. Vui lòng thử lại.',
                'error_details' => app()->environment('local') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Get current orders for the shipper dashboard.
     */
    public function getCurrentOrders()
    {
        try {
            $shipperId = Auth::id();
            
            $orders = Order::where('shipper_id', $shipperId)
                ->whereIn('status', ['assigned', 'pickup', 'picked_up', 'in_transit', 'delivering'])
                ->with(['customer'])
                ->orderByRaw("FIELD(status, 'pickup', 'picked_up', 'in_transit', 'delivering', 'assigned')")
                ->orderBy('assigned_at', 'asc')
                ->get()
                ->map(function ($order) {
                    return [
                        'id' => $order->id,
                        'tracking_number' => $order->tracking_number,
                        'pickup_name' => $order->pickup_name,
                        'pickup_phone' => $order->pickup_phone,
                        'pickup_address' => $order->pickup_address,
                        'pickup_ward' => $order->pickup_ward,
                        'pickup_district' => $order->pickup_district,
                        'pickup_city' => $order->pickup_city,
                        'delivery_name' => $order->delivery_name,
                        'delivery_phone' => $order->delivery_phone,
                        'delivery_address' => $order->delivery_address,
                        'delivery_ward' => $order->delivery_ward,
                        'delivery_district' => $order->delivery_district,
                        'delivery_city' => $order->delivery_city,
                        'customer_name' => $order->customer->name,
                        'customer_phone' => $order->customer->phone,
                        'status' => $order->status,
                        'package_type' => $order->package_type,
                        'weight' => $order->weight,
                        'cod_amount' => $order->cod_amount,
                        'shipping_fee' => $order->shipping_fee,
                        'notes' => $order->notes,
                        'assigned_at' => $order->assigned_at?->format('d/m/Y H:i'),
                        'pickup_latitude' => $order->pickup_latitude,
                        'pickup_longitude' => $order->pickup_longitude,
                        'delivery_latitude' => $order->delivery_latitude,
                        'delivery_longitude' => $order->delivery_longitude,
                        'estimated_delivery' => $this->calculateEstimatedDelivery($order),
                        'priority' => $this->getOrderPriority($order),
                        'distance_to_pickup' => $this->calculateDistance($order, 'pickup'),
                        'distance_to_delivery' => $this->calculateDistance($order, 'delivery')
                    ];
                });

            return response()->json([
                'success' => true,
                'data' => $orders
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Không thể tải danh sách đơn hàng hiện tại: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Kiểm tra quy trình cập nhật trạng thái hợp lệ
     */
    private function isValidStatusTransition($oldStatus, $newStatus)
    {
        $validTransitions = [
            'assigned' => ['pickup', 'failed', 'in_transit'], // Cho phép bỏ qua pickup
            'pickup' => ['picked_up', 'failed', 'assigned'], // Cho phép quay lại
            'picked_up' => ['in_transit', 'failed', 'delivering'], // Cho phép bỏ qua in_transit
            'in_transit' => ['delivering', 'delivered', 'failed'], // Cho phép chuyển thẳng sang delivered
            'delivering' => ['delivered', 'failed', 'returned'],
            'delivered' => [], // Không cho phép thay đổi từ delivered
            'failed' => ['pickup', 'assigned', 'in_transit'], // Cho phép thử lại
            'returned' => ['assigned'] // Cho phép gửi lại
        ];

        return in_array($newStatus, $validTransitions[$oldStatus] ?? []);
    }

    /**
     * Tạo lịch sử trạng thái
     */
    private function createStatusHistory($order, $oldStatus, $newStatus, $notes = null, $latitude = null, $longitude = null)
    {
        try {
            return DB::table('order_status_history')->insert([
                'order_id' => $order->id,
                'user_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $notes,
                'latitude' => $latitude,
                'longitude' => $longitude,
                'created_at' => now(),
                'updated_at' => now()
            ]);
        } catch (\Exception $e) {
            // Nếu bảng order_status_history chưa tồn tại, log vào file
            \Log::info('Order status change', [
                'order_id' => $order->id,
                'tracking_number' => $order->tracking_number,
                'shipper_id' => Auth::id(),
                'old_status' => $oldStatus,
                'new_status' => $newStatus,
                'notes' => $notes,
                'timestamp' => now()
            ]);
            return true;
        }
    }

    /**
     * Lưu ảnh xác nhận giao hàng
     */
    private function saveDeliveryPhoto($order, $photo)
    {
        $fileName = 'delivery_' . $order->id . '_' . time() . '.' . $photo->getClientOriginalExtension();
        $photo->storeAs('delivery_photos', $fileName, 'public');
        
        // Lưu thông tin ảnh vào notes hoặc tạo bảng riêng
        // Vì không có cột delivery_photo trong bảng orders
        $order->update([
            'notes' => $order->notes . "\n[Photo: delivery_photos/{$fileName}]"
        ]);
    }

    /**
     * Lưu chữ ký xác nhận giao hàng
     */
    private function saveDeliverySignature($order, $signature)
    {
        $fileName = 'signature_' . $order->id . '_' . time() . '.png';
        $signatureData = base64_decode(preg_replace('#^data:image/\w+;base64,#i', '', $signature));
        Storage::disk('public')->put('delivery_signatures/' . $fileName, $signatureData);
        
        // Lưu thông tin chữ ký vào notes hoặc tạo bảng riêng
        // Vì không có cột delivery_signature trong bảng orders
        $order->update([
            'notes' => $order->notes . "\n[Signature: delivery_signatures/{$fileName}]"
        ]);
    }

    /**
     * Tính toán thời gian giao hàng dự kiến
     */
    private function calculateEstimatedDelivery($order)
    {
        if (!$order->assigned_at) {
            return null;
        }

        $baseDeliveryTime = 120; // 2 giờ mặc định
        
        // Điều chỉnh dựa trên loại gói hàng
        switch ($order->package_type) {
            case 'express':
                $baseDeliveryTime = 60; // 1 giờ
                break;
            case 'standard':
                $baseDeliveryTime = 120; // 2 giờ
                break;
            case 'economy':
                $baseDeliveryTime = 240; // 4 giờ
                break;
        }
        
        // Điều chỉnh dựa trên trọng lượng
        if ($order->weight > 5) {
            $baseDeliveryTime += 30;
        }
        
        // Điều chỉnh dựa trên khoảng cách
        $distance = $this->calculateDistance($order, 'delivery');
        if ($distance > 10) {
            $baseDeliveryTime += ($distance - 10) * 5;
        }

        return $order->assigned_at->addMinutes($baseDeliveryTime);
    }

    /**
     * Xác định độ ưu tiên của đơn hàng
     */
    private function getOrderPriority($order)
    {
        $priority = 'normal';
        
        // Đơn hàng COD có độ ưu tiên cao
        if ($order->cod_amount > 0) {
            $priority = 'high';
        }
        
        // Đơn hàng express có độ ưu tiên cao nhất
        if ($order->package_type === 'express') {
            $priority = 'urgent';
        }
        
        // Đơn hàng quá hạn có độ ưu tiên cao
        $estimatedDelivery = $this->calculateEstimatedDelivery($order);
        if ($estimatedDelivery && $estimatedDelivery->isPast()) {
            $priority = 'urgent';
        }
        
        return $priority;
    }

    /**
     * Tính khoảng cách từ vị trí hiện tại đến điểm pickup hoặc delivery
     */
    private function calculateDistance($order, $type = 'pickup')
    {
        // Tạm thời trả về giá trị mặc định
        // Trong thực tế có thể sử dụng Google Maps API hoặc các dịch vụ khác
        return rand(1, 15); // km
    }

    /**
     * Lấy timeline của đơn hàng
     */
    private function getOrderTimeline($order)
    {
        $timeline = [];
        
        // Đơn hàng được tạo
        $timeline[] = [
            'status' => 'created',
            'title' => 'Đơn hàng được tạo',
            'description' => 'Khách hàng đã tạo đơn hàng',
            'time' => $order->created_at,
            'completed' => true
        ];
        
        // Đơn hàng được phân công
        if ($order->assigned_at) {
            $timeline[] = [
                'status' => 'assigned',
                'title' => 'Đơn hàng được phân công',
                'description' => 'Shipper đã nhận đơn hàng',
                'time' => $order->assigned_at,
                'completed' => true
            ];
        }
        
        // Lấy hàng
        if ($order->picked_up_at) {
            $timeline[] = [
                'status' => 'picked_up',
                'title' => 'Đã lấy hàng',
                'description' => 'Shipper đã lấy hàng từ người gửi',
                'time' => $order->picked_up_at,
                'completed' => true
            ];
        }
        
        // Giao hàng
        if ($order->completed_at && $order->status === 'delivered') {
            $timeline[] = [
                'status' => 'delivered',
                'title' => 'Đã giao hàng',
                'description' => 'Gói hàng đã được giao thành công',
                'time' => $order->completed_at,
                'completed' => true
            ];
        }
        
        return $timeline;
    }

    /**
     * Lấy text trạng thái tiếng Việt
     */
    private function getStatusText($status)
    {
        $statusTexts = [
            'assigned' => 'Đã phân công',
            'pickup' => 'Đang lấy hàng',
            'picked_up' => 'Đã lấy hàng',
            'in_transit' => 'Đang vận chuyển',
            'delivering' => 'Đang giao hàng',
            'delivered' => 'Đã giao thành công',
            'failed' => 'Giao hàng thất bại',
            'returned' => 'Đã trả về'
        ];
        
        return $statusTexts[$status] ?? $status;
    }

    /**
     * Kiểm tra có thể cập nhật trạng thái hay không
     */
    private function canUpdateStatus($status)
    {
        return !in_array($status, ['delivered', 'cancelled']);
    }

    /**
     * Lấy các trạng thái có thể chuyển từ trạng thái hiện tại
     */
    private function getValidTransitions($status)
    {
        $validTransitions = [
            'assigned' => ['pickup', 'failed', 'in_transit'],
            'pickup' => ['picked_up', 'failed', 'assigned'],
            'picked_up' => ['in_transit', 'failed', 'delivering'],
            'in_transit' => ['delivering', 'delivered', 'failed'],
            'delivering' => ['delivered', 'failed', 'returned'],
            'delivered' => [],
            'failed' => ['pickup', 'assigned', 'in_transit'],
            'returned' => ['assigned']
        ];

        return $validTransitions[$status] ?? [];
    }
}
