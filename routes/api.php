<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application.
*/

Route::middleware(['web', 'auth'])->group(function () {
    
    // ===== ADMIN API ROUTES =====
    Route::middleware(['role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
        // Dashboard stats
        Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('stats');
        
        // User management - Removed (UserController no longer exists)
        
        // Agent management
        Route::apiResource('/agents', \App\Http\Controllers\Admin\AgentController::class);
        Route::put('/agents/{id}/toggle-status', [\App\Http\Controllers\Admin\AgentController::class, 'toggleStatus'])->name('agents.toggle-status');
        
        // Shipper management
        Route::apiResource('/shippers', \App\Http\Controllers\Admin\ShipperController::class);
        Route::put('/shippers/{id}/toggle-status', [\App\Http\Controllers\Admin\ShipperController::class, 'toggleStatus'])->name('shippers.toggle-status');
        Route::get('/shippers/{id}/details', [\App\Http\Controllers\Admin\ShipperController::class, 'details'])->name('shippers.details');
        
        // Order management
        Route::apiResource('/orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::get('/agents', [\App\Http\Controllers\Admin\OrderController::class, 'getAgents'])->name('agents');
        Route::get('/shippers', [\App\Http\Controllers\Admin\OrderController::class, 'getShippers'])->name('shippers');
        
        // Reports
        Route::get('/reports', [\App\Http\Controllers\Admin\ReportController::class, 'index'])->name('reports');
        Route::get('/reports/export', [\App\Http\Controllers\Admin\ReportController::class, 'export'])->name('reports.export');
    });
    
    // ===== AGENT API ROUTES =====
    Route::middleware(['role:agent'])->prefix('agent')->name('api.agent.')->group(function () {
        // Dashboard stats
        Route::get('/stats', [\App\Http\Controllers\Agent\AgentController::class, 'getStats'])->name('stats');
        Route::get('/pending-orders', [\App\Http\Controllers\Agent\AgentController::class, 'getPendingOrders'])->name('pending-orders');
        Route::get('/available-shippers', [\App\Http\Controllers\Agent\AgentController::class, 'getAvailableShippers'])->name('available-shippers');
        Route::get('/shipper-status', [\App\Http\Controllers\Agent\AgentController::class, 'getShipperStatus'])->name('shipper-status');
        
        // Order management
        Route::get('/orders', [\App\Http\Controllers\Agent\OrderController::class, 'getOrders'])->name('orders');
        Route::get('/orders/{id}', [\App\Http\Controllers\Agent\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/confirm', [\App\Http\Controllers\Agent\AgentController::class, 'confirmOrder'])->name('orders.confirm');
        Route::post('/orders/{id}/assign-shipper', [\App\Http\Controllers\Agent\AgentController::class, 'assignShipper'])->name('orders.assign-shipper');
        Route::put('/orders/{id}/status', [\App\Http\Controllers\Agent\OrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::get('/orders/statistics', [\App\Http\Controllers\Agent\OrderController::class, 'getStatistics'])->name('orders.statistics');
        Route::post('/orders/batch-operation', [\App\Http\Controllers\Agent\OrderController::class, 'batchOperation'])->name('orders.batch-operation');
        Route::post('/orders/export-report', [\App\Http\Controllers\Agent\OrderController::class, 'exportReport'])->name('orders.export-report');
        
        // Shipper management
        Route::get('/shippers', [\App\Http\Controllers\Agent\ShipperController::class, 'getShippers'])->name('shippers.list');
        Route::post('/shippers', [\App\Http\Controllers\Agent\ShipperController::class, 'store'])->name('shippers.store');
        Route::get('/shippers/{id}', [\App\Http\Controllers\Agent\ShipperController::class, 'show'])->name('shippers.show');
        Route::put('/shippers/{id}', [\App\Http\Controllers\Agent\ShipperController::class, 'update'])->name('shippers.update');
        Route::post('/shippers/{id}/toggle-online', [\App\Http\Controllers\Agent\ShipperController::class, 'toggleOnlineStatus'])->name('shippers.toggle-online');
        Route::get('/shippers/performance/stats', [\App\Http\Controllers\Agent\ShipperController::class, 'getPerformanceStats'])->name('shippers.performance');
    });
    
    // ===== USER API ROUTES =====
    Route::middleware(['role:user'])->prefix('user')->name('api.user.')->group(function () {
        Route::get('/data', [\App\Http\Controllers\DashboardController::class, 'getUserData'])->name('data');
        Route::get('/recent-orders', [\App\Http\Controllers\DashboardController::class, 'getRecentOrders'])->name('recent-orders');
        
        // User order management
        Route::get('/orders', [\App\Http\Controllers\User\OrderController::class, 'index'])->name('orders');
        Route::post('/orders', [\App\Http\Controllers\User\OrderController::class, 'store'])->name('orders.store');
        Route::get('/orders/{id}', [\App\Http\Controllers\User\OrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{id}/cancel', [\App\Http\Controllers\User\OrderController::class, 'cancel'])->name('orders.cancel');
    });
    
    // ===== SHARED API ROUTES (for shipping calculation) =====
    Route::middleware(['web'])->group(function () {
        Route::post('/shipping/calculate', function (\Illuminate\Http\Request $request) {
            // Simple shipping calculation service
            $services = [
                [
                    'id' => 'standard',
                    'name' => 'Giao chuẩn',
                    'description' => 'Giao hàng trong 1-2 ngày làm việc',
                    'fee' => 30000,
                    'estimated_time' => '1-2 ngày',
                    'recommended' => true
                ],
                [
                    'id' => 'fast',
                    'name' => 'Giao nhanh',
                    'description' => 'Giao hàng trong ngày',
                    'fee' => 50000,
                    'estimated_time' => 'Trong ngày',
                    'recommended' => false
                ],
                [
                    'id' => 'express',
                    'name' => 'Giao hỏa tốc',
                    'description' => 'Giao hàng trong 2-4 giờ',
                    'fee' => 80000,
                    'estimated_time' => '2-4 giờ',
                    'recommended' => false
                ]
            ];
            
            // Add weight-based surcharge
            $weight = $request->input('weight', 1);
            if ($weight > 1) {
                foreach ($services as &$service) {
                    $service['fee'] += ($weight - 1) * 10000;
                }
            }
            
            return response()->json(['services' => $services]);
        })->name('api.shipping.calculate');
    });
    
    // ===== SHIPPER API ROUTES =====
    Route::middleware(['role:shipper'])->prefix('shipper/api')->name('api.shipper.')->group(function () {
        // Dashboard data
        Route::get('/dashboard-data', [\App\Http\Controllers\Shipper\DashboardController::class, 'getDashboardData'])->name('dashboard-data');
        Route::get('/statistics', [\App\Http\Controllers\Shipper\DashboardController::class, 'getStatistics'])->name('statistics');
        Route::get('/recent-activities', [\App\Http\Controllers\Shipper\DashboardController::class, 'getRecentActivities'])->name('recent-activities');
        Route::get('/daily-performance', [\App\Http\Controllers\Shipper\DashboardController::class, 'getDailyPerformance'])->name('daily-performance');
        
        // Order management
        Route::get('/current-orders', [\App\Http\Controllers\Shipper\DashboardController::class, 'getCurrentOrders'])->name('current-orders');
        Route::get('/orders/list', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrders'])->name('orders.list');
        Route::get('/orders/{id}', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrderDetails'])->name('orders.show');
        Route::post('/orders/{id}/update-status', [\App\Http\Controllers\Shipper\OrderController::class, 'updateStatus'])->name('orders.update-status');
        
        // Location and status updates
        Route::post('/location/update', [\App\Http\Controllers\Shipper\DashboardController::class, 'updateLocation'])->name('location.update');
        Route::post('/status/update', [\App\Http\Controllers\Shipper\DashboardController::class, 'updateStatus'])->name('status.update');
        
        // History
        Route::get('/delivery-history', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryHistory'])->name('delivery-history');
        Route::get('/delivery-statistics', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryStatistics'])->name('delivery-statistics');
        Route::get('/delivery-details/{id}', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryDetails'])->name('delivery-details');
    });
});

// ===== DEVELOPMENT/DEBUG API ROUTES =====
if (app()->environment(['local', 'testing', 'development'])) {
    Route::prefix('dev')->name('api.dev.')->group(function () {
        
        // User management helpers
        Route::get('/debug-users', function () {
            $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'status']);
            return response()->json($users);
        })->name('debug-users');
        
        Route::get('/test-password/{email?}', function ($email = 'admin@courierxpress.com') {
            $user = \App\Models\User::where('email', $email)->first();
            if ($user) {
                $testPassword = '123456';
                $hashCheck = \Illuminate\Support\Facades\Hash::check($testPassword, $user->password);
                return response()->json([
                    'user_found' => true,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'password_hash' => substr($user->password, 0, 20) . '...',
                    'test_password' => $testPassword,
                    'password_matches' => $hashCheck,
                ]);
            }
            return response()->json(['user_found' => false]);
        })->name('test-password');
        
        Route::post('/reset-user-passwords', function () {
            $users = \App\Models\User::all();
            $updated = [];
            
            foreach ($users as $user) {
                $user->password = \Illuminate\Support\Facades\Hash::make('123456');
                $user->status = 'active';
                $user->save();
                
                $updated[] = [
                    'email' => $user->email,
                    'name' => $user->name,
                    'role' => $user->role
                ];
            }
            
            return response()->json([
                'message' => 'All user passwords reset successfully',
                'updated_users' => $updated,
                'total_updated' => count($updated),
                'note' => 'All passwords are now: 123456'
            ]);
        })->name('reset-passwords');
        
        Route::get('/debug-auth-status', function () {
            return response()->json([
                'authenticated' => auth()->check(),
                'user' => auth()->user(),
                'session_id' => session()->getId(),
                'auth_guard' => config('auth.defaults.guard'),
                'session_driver' => config('session.driver')
            ]);
        })->name('debug-auth');
        
        // Quick debug for current user role
        Route::get('/debug-current-user', function () {
            if (auth()->check()) {
                $user = auth()->user();
                return response()->json([
                    'authenticated' => true,
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'status' => $user->status
                    ]
                ]);
            } else {
                return response()->json([
                    'authenticated' => false,
                    'message' => 'User not logged in'
                ]);
            }
        })->name('debug-current-user');
        
        // Simple test route
        Route::get('/test-simple', function () {
            return response()->json([
                'success' => true,
                'message' => 'API hoạt động bình thường',
                'timestamp' => now()
            ]);
        })->name('test-simple');
        
        // Test database connection
        Route::get('/test-db', function () {
            try {
                $userCount = \App\Models\User::count();
                return response()->json([
                    'success' => true,
                    'message' => 'Kết nối database thành công',
                    'user_count' => $userCount
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối database: ' . $e->getMessage()
                ], 500);
            }
        })->name('test-db');
        
        // Simple login test
        Route::get('/login-user-simple', function () {
            try {
                // Find a user with role 'user'
                $user = \App\Models\User::where('role', 'user')->first();
                
                if (!$user) {
                    // Try to find any user and update their role
                    $user = \App\Models\User::first();
                    if ($user) {
                        $user->role = 'user';
                        $user->save();
                    } else {
                        return response()->json([
                            'success' => false,
                            'message' => 'Không tìm thấy user nào trong database'
                        ], 404);
                    }
                }
                
                // Simple login without session stuff
                auth()->login($user, false);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập thành công!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ]
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        })->name('login-user-simple');
        
        // Login with session persistence
        Route::get('/login-user-with-session', function () {
            try {
                $user = \App\Models\User::where('role', 'user')->first();
                
                if (!$user) {
                    $user = \App\Models\User::first();
                    if ($user) {
                        $user->role = 'user';
                        $user->save();
                    }
                }
                
                if (!$user) {
                    return response()->json(['success' => false, 'message' => 'Không tìm thấy user'], 404);
                }
                
                // Login with session
                auth()->login($user, false);
                request()->session()->regenerate();
                
                // Store user data in session
                session()->put('user_data', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status ?? 'active'
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập và tạo session thành công!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role
                    ],
                    'session_id' => session()->getId(),
                    'instructions' => 'Bây giờ bạn có thể truy cập /user/create-order'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage()
                ], 500);
            }
        })->name('login-user-with-session');
        
        // Test order creation directly
        Route::post('/test-create-order', function (\Illuminate\Http\Request $request) {
            try {
                // Login user first
                $user = \App\Models\User::where('role', 'user')->first();
                if (!$user) {
                    return response()->json(['success' => false, 'message' => 'Không tìm thấy user'], 404);
                }
                
                auth()->login($user, false);
                
                // Test data
                $testData = [
                    'pickup_name' => 'Người gửi test',
                    'pickup_phone' => '0123456789',
                    'pickup_address' => 'Số 1 Nguyễn Trãi, Q1, TP.HCM',
                    'delivery_name' => 'Người nhận test',
                    'delivery_phone' => '0987654321',
                    'delivery_address' => 'Số 2 Lê Lợi, Q1, TP.HCM',
                    'package_type' => 'document',
                    'weight' => 1.5,
                    'value' => 100000,
                    'shipping_service' => 'standard'
                ];
                
                // Call the actual OrderController
                $controller = new \App\Http\Controllers\User\OrderController();
                $request->merge($testData);
                
                $response = $controller->store($request);
                return $response;
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi khi tạo đơn hàng: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        })->name('test-create-order');
        
        // Test Order model
        Route::get('/test-order-model', function () {
            try {
                // Test if Order model works
                $orderCount = \App\Models\Order::count();
                return response()->json([
                    'success' => true,
                    'message' => 'Order model hoạt động tốt',
                    'order_count' => $orderCount
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi Order model: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        })->name('test-order-model');
        
        // Quick login for testing shipper role
        Route::get('/quick-login-shipper', function () {
            try {
                // Find or create a user with role 'shipper'
                $user = \App\Models\User::where('role', 'shipper')->first();
                
                if (!$user) {
                    // Create a test shipper with only required fields
                    $user = \App\Models\User::create([
                        'name' => 'Test Shipper',
                        'email' => 'shipper@courierxpress.com',
                        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                        'role' => 'shipper',
                        'status' => 'active',
                        'city' => 'TP. Hồ Chí Minh'
                    ]);
                }
                
                // Login the user
                auth()->login($user, false);
                request()->session()->regenerate();
                
                // Store user data in session
                request()->session()->put('user_data', [
                    'id' => $user->id,
                    'name' => $user->name,
                    'email' => $user->email,
                    'role' => $user->role,
                    'status' => $user->status,
                    'city' => $user->city
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Đăng nhập Shipper thành công!',
                    'user' => [
                        'id' => $user->id,
                        'name' => $user->name,
                        'email' => $user->email,
                        'role' => $user->role,
                        'status' => $user->status
                    ],
                    'session_id' => session()->getId(),
                    'instructions' => 'Bây giờ bạn có thể truy cập /shipper/dashboard'
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi: ' . $e->getMessage(),
                    'trace' => $e->getTraceAsString()
                ], 500);
            }
        })->name('quick-login-shipper');
        
        // Create test shippers for testing
        Route::get('/create-test-shippers', function () {
            try {
                $testShippers = [
                    [
                        'name' => 'Nguyễn Văn Shipper 1',
                        'email' => 'shipper1@courierxpress.com',
                        'phone' => '0123456789',
                        'city' => 'TP. Hồ Chí Minh',
                        'is_online' => true
                    ],
                    [
                        'name' => 'Trần Thị Shipper 2', 
                        'email' => 'shipper2@courierxpress.com',
                        'phone' => '0987654321',
                        'city' => 'TP. Hồ Chí Minh',
                        'is_online' => true
                    ],
                    [
                        'name' => 'Lê Văn Shipper 3',
                        'email' => 'shipper3@courierxpress.com', 
                        'phone' => '0369258147',
                        'city' => 'TP. Hồ Chí Minh',
                        'is_online' => false
                    ]
                ];
                
                $created = [];
                
                foreach ($testShippers as $shipperData) {
                    $existing = \App\Models\User::where('email', $shipperData['email'])->first();
                    if (!$existing) {
                        $shipper = \App\Models\User::create([
                            'name' => $shipperData['name'],
                            'email' => $shipperData['email'],
                            'phone' => $shipperData['phone'],
                            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                            'role' => 'shipper',
                            'status' => 'active',
                            'city' => $shipperData['city'],
                            'is_online' => $shipperData['is_online']
                        ]);
                        $created[] = $shipper->name;
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Tạo shipper test thành công!',
                    'created_shippers' => $created,
                    'total_shippers' => \App\Models\User::where('role', 'shipper')->count()
                ]);
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi tạo shipper: ' . $e->getMessage()
                ], 500);
            }
        })->name('create-test-shippers');
    });
}