<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
| Here is where you can register web routes for your application.
*/

Route::get('/', function () {
    return view('home');
});

// Shipper system status page
Route::get('/shipper-system-ready', function () {
    return view('shipper-system-ready');
});

// Test route for debugging user API calls
Route::get('/debug-user-api', function () {
    // Check authentication
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = auth()->user();
    if ($user->role !== 'user') {
        return response()->json(['error' => 'Not a user role']);
    }
    
    // Test calling the actual API controller method
    $controller = new \App\Http\Controllers\DashboardController();
    
    try {
        $statsResponse = $controller->getUserStats();
        $ordersResponse = $controller->getUserRecentOrders();
        
        return response()->json([
            'message' => 'Debug successful',
            'user' => $user->only(['id', 'name', 'email', 'role']),
            'stats_response' => json_decode($statsResponse->getContent(), true),
            'orders_response' => json_decode($ordersResponse->getContent(), true)
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Controller call failed',
            'message' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
    }
});

// Test route to check database and create sample data
Route::get('/test-db', function () {
    try {
        $userCount = \App\Models\User::count();
        $orderCount = \App\Models\Order::count();
        $userRoleCount = \App\Models\User::where('role', 'user')->count();
        
        // Create a test user if none exists
        if ($userRoleCount === 0) {
            $testUser = \App\Models\User::create([
                'name' => 'Test User',
                'email' => 'user@test.com',
                'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                'role' => 'user',
                'status' => 'active',
                'phone' => '0123456789'
            ]);
            
            // Create a test order for this user
            \App\Models\Order::create([
                'tracking_number' => 'TEST001',
                'user_id' => $testUser->id,
                'pickup_name' => 'Test Sender',
                'pickup_phone' => '0123456789',
                'pickup_address' => 'Test Pickup Address',
                'pickup_ward' => 'Ward 1',
                'pickup_district' => 'District 1',
                'pickup_city' => 'Ho Chi Minh',
                'delivery_name' => 'Test Receiver',
                'delivery_phone' => '0987654321',
                'delivery_address' => 'Test Delivery Address',
                'delivery_ward' => 'Ward 2',
                'delivery_district' => 'District 2',
                'delivery_city' => 'Ha Noi',
                'package_type' => 'document',
                'weight' => 1.0,
                'cod_amount' => 100000,
                'shipping_fee' => 25000,
                'total_fee' => 25000,
                'status' => 'pending',
                'payment_method' => 'cod',
                'payment_status' => 'pending'
            ]);
            
            return response()->json([
                'message' => 'Test user and order created successfully',
                'test_user' => [
                    'email' => 'user@test.com',
                    'password' => '123456'
                ],
                'total_users' => \App\Models\User::count(),
                'user_role_users' => \App\Models\User::where('role', 'user')->count(),
                'total_orders' => \App\Models\Order::count()
            ]);
        }
        
        return response()->json([
            'message' => 'Database connected successfully',
            'total_users' => $userCount,
            'user_role_users' => $userRoleCount,
            'total_orders' => $orderCount
        ]);
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Database connection failed: ' . $e->getMessage()
        ], 500);
    }
});

// Test route to check API without middleware
Route::get('/test-user-api', function () {
    try {
        // Get first user with role 'user'
        $user = \App\Models\User::where('role', 'user')->first();
        
        if (!$user) {
            return response()->json([
                'error' => 'No user found with role "user"',
                'total_users' => \App\Models\User::count(),
                'user_role_count' => \App\Models\User::where('role', 'user')->count()
            ]);
        }
        
        // Manually authenticate the user
        auth()->login($user);
        
        // Test the API endpoint
        $controller = new \App\Http\Controllers\DashboardController();
        $response = $controller->getUserStats();
        
        return $response;
    } catch (\Exception $e) {
        return response()->json([
            'error' => 'Test failed: ' . $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ], 500);
    }
});

// Public tracking route (redirects to appropriate role-based tracking)
Route::get('/tracking', function (Illuminate\Http\Request $request) {
    // Check if user is authenticated and redirect to role-specific tracking
    if (auth()->check()) {
        $role = auth()->user()->role;
        switch($role) {
            case 'admin':
                return redirect('/admin/tracking');
            case 'agent':
                return redirect('/agent/tracking');
            case 'shipper':
                return redirect('/shipper/tracking');
            case 'user':
                return redirect('/user/tracking');
            default:
                break;
        }
    }
    
    // For non-authenticated users, show basic tracking page
    $trackingNumber = $request->get('tracking_number') ?? $request->get('tracking_id');
    return view('public.tracking', ['trackingNumber' => $trackingNumber]);
})->name('tracking');

// Tracking API routes
Route::post('/api/tracking', [\App\Http\Controllers\TrackingController::class, 'track'])->name('api.tracking');
Route::post('/api/tracking/check', [\App\Http\Controllers\TrackingController::class, 'checkTracking'])->name('api.tracking.check');
Route::get('/api/tracking/{trackingNumber}', [\App\Http\Controllers\TrackingController::class, 'getOrderTimeline']);

// ===== AUTHENTICATION ROUTES =====
Route::middleware('guest')->group(function () {
    Route::get('/login', function () {
        return view('auth.login');
    })->name('login');

    Route::get('/register', function () {
        return view('auth.register');
    })->name('register');

    Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');
    Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');
});

Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])
    ->middleware('auth')
    ->name('logout');

// Test logout route for debugging
Route::get('/test-logout', function () {
    if (auth()->check()) {
        Auth::logout();
        request()->session()->invalidate();
        request()->session()->regenerateToken();
        return redirect('/')->with('success', 'Test logout successful!');
    }
    return redirect('/')->with('error', 'Not logged in');
});

// ===== LANGUAGE ROUTES =====
Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');

// ===== PAGE ROUTES =====
// Service routes
Route::prefix('dich-vu')->name('dich-vu.')->group(function () {
    Route::get('/giao-hang', [PageController::class, 'dichVuGiaoHang'])->name('giao-hang');
    Route::get('/xe-tai', [PageController::class, 'dichVuXeTai'])->name('xe-tai');
    Route::get('/chuyen-nha', [PageController::class, 'dichVuChuyenNha'])->name('chuyen-nha');
    Route::get('/doanh-nghiep', [PageController::class, 'dichVuDoanhNghiep'])->name('doanh-nghiep');
});

// Customer routes
Route::prefix('khach-hang')->name('khach-hang.')->group(function () {
    Route::get('/ca-nhan', [PageController::class, 'khachHangCaNhan'])->name('ca-nhan');
    Route::get('/doanh-nghiep', [PageController::class, 'khachHangDoanhNghiep'])->name('doanh-nghiep');
});

// Support routes
Route::prefix('ho-tro')->name('ho-tro.')->group(function () {
    Route::get('/khach-hang', [PageController::class, 'hoTroKhachHang'])->name('khach-hang');
    Route::get('/tai-xe', [PageController::class, 'hoTroTaiXe'])->name('tai-xe');
});

// Driver routes
Route::prefix('tai-xe')->name('tai-xe.')->group(function () {
    Route::get('/dang-ky', [PageController::class, 'dangKyTaiXe'])->name('dang-ky');
    Route::get('/cong-dong', [PageController::class, 'congDongTaiXe'])->name('cong-dong');
    Route::get('/cam-nang', [PageController::class, 'camNangTaiXe'])->name('cam-nang');
});

// Recruitment routes
Route::prefix('tuyen-dung')->name('tuyen-dung.')->group(function () {
    Route::get('/ve-chung-toi', [PageController::class, 'veChungToi'])->name('ve-chung-toi');
    Route::get('/cau-chuyen', [PageController::class, 'cauChuyenCourierXpress'])->name('cau-chuyen');
    Route::get('/gia-nhap', [PageController::class, 'giaNhapCourierXpress'])->name('gia-nhap');
});

// Community support
Route::get('/cong-dong/ho-tro', [PageController::class, 'congDongHoTro'])->name('cong-dong.ho-tro');

// ===== AUTHENTICATED DASHBOARD ROUTES =====
Route::middleware(['auth'])->group(function () {
    
    // General dashboard route (redirects based on role)
    Route::get('/dashboard', function () {
        $user = auth()->user();
        switch($user->role) {
            case 'admin':
                return redirect()->route('admin.dashboard');
            case 'agent':
                return redirect()->route('agent.dashboard');
            case 'shipper':
                return redirect()->route('shipper.dashboard');
            case 'user':
                return redirect()->route('user.dashboard');
            default:
                return redirect('/');
        }
    })->name('dashboard');
    
    // Admin Dashboard & Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/tracking', function() { return view('admin.tracking'); })->name('tracking');
        Route::get('/users', function() { return view('admin.users.index'); })->name('users');
        Route::get('/orders', function() { return view('admin.orders.index'); })->name('orders');
        Route::get('/agents', function() { return view('admin.agents.index'); })->name('agents');
        Route::get('/shippers', function() { return view('admin.shippers.index'); })->name('shippers');
        Route::get('/drivers', function() { return view('admin.drivers.index'); })->name('drivers');
        Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'index'])->name('settings');
        Route::get('/reports', function() { return view('admin.reports.index'); })->name('reports');
        Route::get('/audit', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit');
        
        // Admin API endpoints
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'getStats']);
            Route::get('/recent-orders', [\App\Http\Controllers\Admin\DashboardController::class, 'getRecentOrders']);
            Route::get('/activity', [\App\Http\Controllers\Admin\DashboardController::class, 'getSystemActivity']);
            Route::get('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'getSettings']);
            Route::post('/settings', [\App\Http\Controllers\Admin\SettingsController::class, 'updateSettings']);
            Route::post('/settings/reset', [\App\Http\Controllers\Admin\SettingsController::class, 'resetSettings']);
            Route::get('/settings/export', [\App\Http\Controllers\Admin\SettingsController::class, 'exportSettings']);
            Route::post('/settings/import', [\App\Http\Controllers\Admin\SettingsController::class, 'importSettings']);
            
            // User Management API - Removed (UserController no longer exists)
            
            // Audit Log API
            Route::get('/audit/logs', [\App\Http\Controllers\Admin\AuditLogController::class, 'getLogs']);
            Route::get('/audit/logs/{id}', [\App\Http\Controllers\Admin\AuditLogController::class, 'show']);
            Route::get('/audit/statistics', [\App\Http\Controllers\Admin\AuditLogController::class, 'getStatistics']);
            Route::get('/audit/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'exportLogs']);
        });
    });
    
    // Agent Dashboard & Routes
    Route::middleware(['role:agent'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Agent\AgentController::class, 'dashboard'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Agent\OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [\App\Http\Controllers\Agent\OrderController::class, 'show'])->name('orders.show');
        Route::get('/shippers', [\App\Http\Controllers\Agent\ShipperController::class, 'index'])->name('shippers');
        Route::get('/reports', [\App\Http\Controllers\Agent\ReportController::class, 'index'])->name('reports');
        Route::get('/reports/export', [\App\Http\Controllers\Agent\ReportController::class, 'export'])->name('reports.export');
    });
    
    // User Dashboard & Routes
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
        Route::get('/tracking', function() { return view('user.tracking'); })->name('tracking');
        Route::get('/create-order', function() { return view('user.create-order'); })->name('create-order');
        Route::get('/profile', function() { return view('user.profile'); })->name('profile');
        Route::get('/orders', function() { 
            $user = auth()->user();
            
            if (!$user || $user->role !== 'user') {
                return redirect('/login');
            }
            
            // Lấy tất cả đơn hàng của user với relationship shipper
            $orders = \App\Models\Order::where('user_id', $user->id)
                                     ->with('shipper:id,name,phone')
                                     ->orderBy('created_at', 'desc')
                                     ->get();
            
            return view('user.orders.index', compact('orders')); 
        })->name('orders');
        
        // User API routes
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/stats', [DashboardController::class, 'getUserStats']);
            Route::get('/recent-orders', [DashboardController::class, 'getUserRecentOrders']);
        });
    });
    
    // Agent Dashboard & Routes
    Route::middleware(['role:agent'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Agent\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tracking', function() { return view('agent.tracking'); })->name('tracking');
        Route::get('/orders', [\App\Http\Controllers\Agent\OrderController::class, 'index'])->name('orders');
        Route::get('/orders/{id}', [\App\Http\Controllers\Agent\OrderController::class, 'show'])->name('orders.show');
        Route::get('/shippers', [\App\Http\Controllers\Agent\ShipperController::class, 'index'])->name('shippers');
        Route::get('/reports', [\App\Http\Controllers\Agent\ReportController::class, 'index'])->name('reports');
        Route::get('/reports/export', [\App\Http\Controllers\Agent\ReportController::class, 'export'])->name('reports.export');
        
        // Agent API routes
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/stats', [\App\Http\Controllers\Agent\DashboardController::class, 'getStats']);
            Route::get('/pending-orders', [\App\Http\Controllers\Agent\DashboardController::class, 'getPendingOrders']);
            Route::get('/shippers', [\App\Http\Controllers\Agent\DashboardController::class, 'getShippers']);
        });
    });
    
    // Shipper Dashboard & Routes (imported from shipper.php)
    Route::middleware(['role:shipper'])->prefix('shipper')->name('shipper.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Shipper\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/tracking', function() { return view('shipper.tracking'); })->name('tracking');
        Route::get('/orders', [\App\Http\Controllers\Shipper\OrderController::class, 'index'])->name('orders');
        Route::get('/history', [\App\Http\Controllers\Shipper\HistoryController::class, 'index'])->name('history');
        
        // Shipper API routes
        Route::prefix('api')->name('api.')->group(function () {
            Route::get('/dashboard-data', [\App\Http\Controllers\Shipper\DashboardController::class, 'getDashboardData']);
            Route::get('/current-orders', [\App\Http\Controllers\Shipper\OrderController::class, 'getCurrentOrders']);
            Route::get('/statistics', [\App\Http\Controllers\Shipper\DashboardController::class, 'getStatistics']);
            Route::get('/recent-activities', [\App\Http\Controllers\Shipper\DashboardController::class, 'getRecentActivities']);
            Route::get('/daily-performance', [\App\Http\Controllers\Shipper\DashboardController::class, 'getDailyPerformance']);
            Route::post('/update-location', [\App\Http\Controllers\Shipper\DashboardController::class, 'updateLocation']);
            Route::post('/update-status', [\App\Http\Controllers\Shipper\DashboardController::class, 'updateStatus']);
            
            // Order management
            Route::get('/orders', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrders']);
            Route::get('/orders/list', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrders']);
            Route::get('/orders/{id}', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrderDetails']);
            Route::post('/orders/{id}/update-status', [\App\Http\Controllers\Shipper\OrderController::class, 'updateStatus']);
            
            // History API routes
            Route::get('/delivery-history', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryHistory']);
            Route::get('/delivery-statistics', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryStatistics']);
            Route::get('/delivery-details/{id}', [\App\Http\Controllers\Shipper\HistoryController::class, 'getDeliveryDetails']);
        });
    });
});

// ===== DEMO & DEVELOPMENT ROUTES =====
// Only include these routes in non-production environments
if (app()->environment(['local', 'testing', 'development'])) {
    // Test User Routes for Unified Layout
    // Test Agent Routes for Reports
    Route::get('/test/agent/reports', function() {
        // Simulate agent authentication
        $user = new \App\Models\User([
            'id' => 2,
            'name' => 'Agent Hà Nội',
            'email' => 'agent@test.com',
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Hà Nội'
        ]);
        
        // Manually authenticate the user for testing
        auth()->login($user);
        
        return app(\App\Http\Controllers\Agent\ReportController::class)->index(request());
    });
    
    Route::get('/test/agent/dashboard', function() {
        // Simulate user authentication
        $user = new \App\Models\User([
            'id' => 1,
            'name' => 'Nguyễn Văn Nam',
            'email' => 'user@test.com',
            'role' => 'user',
            'status' => 'active'
        ]);
        
        // Manually authenticate the user for testing
        auth()->login($user);
        
        return view('user.dashboard');
    });

    Route::get('/test/user/orders', function() {
        // Simulate user authentication
        $user = new \App\Models\User([
            'id' => 1,
            'name' => 'Nguyễn Văn Nam',
            'email' => 'user@test.com',
            'role' => 'user',
            'status' => 'active'
        ]);
        
        // Manually authenticate the user for testing
        auth()->login($user);
        
        return view('user.orders.index');
    });

    Route::get('/test/user/create-order', function() {
        // Simulate user authentication
        $user = new \App\Models\User([
            'id' => 1,
            'name' => 'Nguyễn Văn Nam',
            'email' => 'user@test.com',
            'role' => 'user',
            'status' => 'active'
        ]);
        
        // Manually authenticate the user for testing
        auth()->login($user);
        
        return view('user.create-order');
    });

    Route::get('/test/user/profile', function() {
        // Simulate user authentication
        $user = new \App\Models\User([
            'id' => 1,
            'name' => 'Nguyễn Văn Nam',
            'email' => 'user@test.com',
            'role' => 'user',
            'status' => 'active'
        ]);
        
        // Manually authenticate the user for testing
        auth()->login($user);
        
        return view('user.profile');
    });
    
    // Demo routes for language and design testing
    Route::prefix('demo')->name('demo.')->group(function () {
        Route::get('/language', function () { return view('language-demo'); })->name('language');
        Route::get('/full-demo', function () { return view('full-translation-demo'); })->name('full');
        Route::get('/complete-demo', function () { return view('services.complete-demo'); })->name('complete');
        Route::get('/individual-complete', function () { return view('customers.individual-complete'); })->name('individual');
        Route::get('/all-services', function () { return view('all-services-translated'); })->name('services');
        Route::get('/fedex-style', function () { return view('fedex-style-demo'); })->name('fedex');
        Route::get('/lalamove-style', function () { return view('lalamove-style'); })->name('lalamove');
        Route::get('/header-demo', function () { return view('header-demo'); })->name('header');
        Route::get('/dropdown-test', function () { return view('dropdown-test'); })->name('dropdown');
    });
    
    // Development helper routes
    Route::prefix('dev')->name('dev.')->group(function () {
        Route::get('/test', function () {
            return 'Server hoạt động tốt! CourierXpress đã sẵn sàng.';
        })->name('test');
        
        Route::get('/test-accounts', function () {
            return view('test-accounts');
        })->name('accounts');
        
        Route::get('/phpinfo', function () {
            return phpinfo();
        })->name('phpinfo');
        
        // Test route để tạo dữ liệu mẫu từ MySQL
        Route::get('/add-address-column', function () {
            try {
                // Check if address column exists
                if (!\Illuminate\Support\Facades\Schema::hasColumn('users', 'address')) {
                    \Illuminate\Support\Facades\Schema::table('users', function (\Illuminate\Database\Schema\Blueprint $table) {
                        $table->text('address')->nullable()->after('city');
                    });
                    return response()->json([
                        'success' => true,
                        'message' => 'Address column added successfully to users table'
                    ]);
                } else {
                    return response()->json([
                        'success' => true,
                        'message' => 'Address column already exists in users table'
                    ]);
                }
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error adding address column: ' . $e->getMessage()
                ]);
            }
        })->name('add-address-column');
        
        // API test routes cho admin data - không cần authentication
        Route::get('/test-shippers-api', function () {
            try {
                $shippers = \App\Models\User::where('role', 'shipper')
                          ->select(['id', 'name', 'email', 'phone', 'status', 'address', 'city', 'created_at'])
                          ->orderBy('created_at', 'desc')
                          ->get()
                          ->map(function ($shipper) {
                              // Calculate statistics từ MySQL
                              $totalOrders = \App\Models\Order::where('shipper_id', $shipper->id)->count();
                              $completedOrders = \App\Models\Order::where('shipper_id', $shipper->id)
                                                    ->where('status', 'delivered')
                                                    ->count();
                              $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

                              return [
                                  'id' => $shipper->id,
                                  'name' => $shipper->name,
                                  'email' => $shipper->email,
                                  'phone' => $shipper->phone ?? 'Chưa cập nhật',
                                  'area' => $shipper->city ?? 'Miền Nam', // Sử dụng city làm area
                                  'rating' => rand(40, 50) / 10, // Random rating 4.0-5.0
                                  'status' => $shipper->status ?? 'active',
                                  'total_orders' => $totalOrders,
                                  'success_rate' => $successRate,
                                  'vehicle_type' => 'motorcycle',
                                  'vehicle_number' => 'Chưa cập nhật',
                                  'avatar' => '/images/default-avatar.png'
                              ];
                          });

                return response()->json($shippers);
                
            } catch (\Exception $e) {
                \Log::error('Test Shippers API error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi tải danh sách shippers: ' . $e->getMessage(),
                    'data' => []
                ], 500);
            }
        })->name('test-shippers-api');
        
        Route::get('/test-agents-api', function () {
            try {
                $agents = \App\Models\User::where('role', 'agent')
                         ->select(['id', 'name', 'email', 'phone', 'address', 'status', 'city', 'created_at'])
                         ->orderBy('created_at', 'desc')
                         ->get()
                         ->map(function ($agent) {
                             return [
                                 'id' => $agent->id,
                                 'name' => $agent->name,
                                 'address' => $agent->address ?? 'Chưa cập nhật',
                                 'manager' => $agent->name, // Tên quản lý = tên agent
                                 'phone' => $agent->phone ?? 'Chưa cập nhật',
                                 'email' => $agent->email,
                                 'status' => $agent->status ?? 'active',
                                 'created_at' => $agent->created_at->format('d/m/Y')
                             ];
                         });

                return response()->json($agents);
                
            } catch (\Exception $e) {
                \Log::error('Test Agents API error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi tải danh sách agents: ' . $e->getMessage(),
                    'data' => []
                ], 500);
            }
        })->name('test-agents-api');
        
        Route::get('/test-reports-api', function () {
            try {
                // Lấy dữ liệu thống kê từ MySQL
                $totalOrders = \App\Models\Order::count();
                $completedOrders = \App\Models\Order::where('status', 'delivered')->count();
                $totalRevenue = \App\Models\Order::where('status', 'delivered')->sum('total_fee') ?? 0;
                $avgOrderValue = $completedOrders > 0 ? $totalRevenue / $completedOrders : 0;
                $successRate = $totalOrders > 0 ? round(($completedOrders / $totalOrders) * 100, 1) : 0;

                // Tạo dữ liệu chart cho 7 ngày qua
                $chartLabels = [];
                $revenueData = [];
                $completedData = [];
                $failedData = [];

                for ($i = 6; $i >= 0; $i--) {
                    $date = \Carbon\Carbon::now()->subDays($i);
                    $chartLabels[] = $date->format('d/m');
                    
                    $dayRevenue = rand(1000000, 5000000); // Mock data
                    $revenueData[] = $dayRevenue;
                    
                    $dayCompleted = rand(10, 50);
                    $completedData[] = $dayCompleted;
                    
                    $dayFailed = rand(2, 10);
                    $failedData[] = $dayFailed;
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
                    'area_performance' => [85, 92, 78],
                    'shipper_performance' => [],
                    'daily_statistics' => []
                ]);
                
            } catch (\Exception $e) {
                \Log::error('Test Reports API error: ' . $e->getMessage());
                
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi tải báo cáo: ' . $e->getMessage()
                ], 500);
            }
        })->name('test-reports-api');
        
        Route::get('/admin-data-summary', function () {
            try {
                $totalUsers = \App\Models\User::count();
                $totalAgents = \App\Models\User::where('role', 'agent')->count();
                $totalShippers = \App\Models\User::where('role', 'shipper')->count();
                $totalOrders = \App\Models\Order::count();
                $completedOrders = \App\Models\Order::where('status', 'delivered')->count();
                $totalRevenue = \App\Models\Order::where('status', 'delivered')->sum('total_fee') ?? 0;
                
                return response()->json([
                    'success' => true,
                    'message' => 'Admin data summary from MySQL',
                    'data' => [
                        'total_users' => $totalUsers,
                        'total_agents' => $totalAgents,
                        'total_shippers' => $totalShippers,
                        'total_orders' => $totalOrders,
                        'completed_orders' => $completedOrders,
                        'total_revenue' => number_format($totalRevenue, 0, ',', '.') . ' VNĐ',
                        'database_connection' => 'MySQL kết nối thành công!',
                        'test_endpoints' => [
                            'agents' => '/dev/test-agents-api',
                            'shippers' => '/dev/test-shippers-api',
                            'reports' => '/dev/test-reports-api'
                        ]
                    ]
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lỗi kết nối database: ' . $e->getMessage()
                ]);
            }
        })->name('admin-data-summary');
        
        Route::get('/create-sample-orders', function () {
            try {
                // Tạo một số đơn hàng mẫu
                $user = \App\Models\User::where('role', 'user')->first();
                $agent = \App\Models\User::where('role', 'agent')->first();
                $shipper = \App\Models\User::where('role', 'shipper')->first();
                
                if (!$user) {
                    // Tạo user mẫu nếu chưa có
                    $user = \App\Models\User::create([
                        'name' => 'Nguyễn Văn A',
                        'email' => 'user1@example.com',
                        'phone' => '0123456789',
                        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                        'role' => 'user',
                        'status' => 'active'
                    ]);
                }
                
                $orders = [];
                for ($i = 1; $i <= 10; $i++) {
                    $status = ['pending', 'confirmed', 'picked_up', 'in_transit', 'delivered'][rand(0, 4)];
                    $fee = rand(25000, 150000);
                    $trackingNumber = 'CX' . str_pad(time() + $i, 8, '0', STR_PAD_LEFT);
                    
                    $order = \App\Models\Order::create([
                        'tracking_number' => $trackingNumber,
                        'user_id' => $user->id,
                        'agent_id' => $agent ? $agent->id : null,
                        'shipper_id' => $shipper ? $shipper->id : null,
                        'pickup_name' => 'Người gửi ' . $i,
                        'pickup_phone' => '091234567' . $i,
                        'pickup_address' => 'Số ' . ($i * 10) . ' Đường ABC, Hà Nội',
                        'pickup_ward' => 'Phường 1',
                        'pickup_district' => 'Quận Ba Đình',
                        'pickup_city' => 'Hà Nội',
                        'delivery_name' => 'Người nhận ' . $i,
                        'delivery_phone' => '098765432' . $i,
                        'delivery_address' => 'Số ' . ($i * 15) . ' Đường XYZ, TP.HCM',
                        'delivery_ward' => 'Phường 1',
                        'delivery_district' => 'Quận 1',
                        'delivery_city' => 'TP.HCM',
                        'package_type' => 'document',
                        'weight' => rand(1, 5),
                        'length' => 20,
                        'width' => 15,
                        'height' => 5,
                        'value' => rand(100000, 1000000),
                        'cod_amount' => rand(0, 500000),
                        'shipping_fee' => 25000,
                        'insurance_fee' => 0,
                        'total_fee' => $fee,
                        'status' => $status,
                        'payment_method' => 'cash',
                        'payment_status' => $status === 'delivered' ? 'paid' : 'pending',
                        'created_at' => now()->subDays(rand(0, 30))
                    ]);
                    
                    $orders[] = $order->tracking_number;
                }
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sample orders created successfully',
                    'orders' => $orders
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating sample orders: ' . $e->getMessage()
                ]);
            }
        })->name('create-sample-orders');
        
        Route::get('/create-sample-data', function () {
            try {
                // Create sample agents
                $agent1 = \App\Models\User::firstOrCreate([
                    'email' => 'agent1@courierxpress.com'
                ], [
                    'name' => 'Chi nhánh Hà Nội',
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role' => 'agent',
                    'status' => 'active',
                    'phone' => '0123456789',
                    'address' => '123 Đường ABC, Quận Ba Đình, Hà Nội',
                    'city' => 'Hà Nội'
                ]);
                
                $agent2 = \App\Models\User::firstOrCreate([
                    'email' => 'agent2@courierxpress.com'
                ], [
                    'name' => 'Chi nhánh TP.HCM',
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role' => 'agent',
                    'status' => 'active',
                    'phone' => '0987654321',
                    'address' => '456 Đường XYZ, Quận 1, TP.HCM',
                    'city' => 'TP.HCM'
                ]);
                
                // Create sample shippers
                $shipper1 = \App\Models\User::firstOrCreate([
                    'email' => 'shipper1@courierxpress.com'
                ], [
                    'name' => 'Nguyễn Văn Tài',
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role' => 'shipper',
                    'status' => 'active',
                    'phone' => '0912345678',
                    'address' => '789 Đường DEF, Quận Cầu Giấy, Hà Nội',
                    'city' => 'Hà Nội'
                ]);
                
                $shipper2 = \App\Models\User::firstOrCreate([
                    'email' => 'shipper2@courierxpress.com'
                ], [
                    'name' => 'Lê Thị Hoa',
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role' => 'shipper',
                    'status' => 'active',
                    'phone' => '0934567890',
                    'address' => '321 Đường GHI, Quận 3, TP.HCM',
                    'city' => 'TP.HCM'
                ]);
                
                return response()->json([
                    'success' => true,
                    'message' => 'Sample data created successfully',
                    'data' => [
                        'agents' => [$agent1->name, $agent2->name],
                        'shippers' => [$shipper1->name, $shipper2->name]
                    ]
                ]);
                
            } catch (\Exception $e) {
                return response()->json([
                    'success' => false,
                    'message' => 'Error creating sample data: ' . $e->getMessage()
                ]);
            }
        })->name('create-sample-data');
        
        Route::get('/init-admin-data', function () {
            try {
                // Tạo admin user nếu chưa có
                $admin = \App\Models\User::firstOrCreate([
                    'email' => 'admin@courierxpress.com'
                ], [
                    'name' => 'Admin CourierXpress',
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'role' => 'admin',
                    'status' => 'active',
                    'phone' => '0123456789'
                ]);
                
                // Tạo một số agents
                for ($i = 1; $i <= 3; $i++) {
                    \App\Models\User::firstOrCreate([
                        'email' => "agent{$i}@courierxpress.com"
                    ], [
                        'name' => "Agent {$i}",
                        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                        'role' => 'agent',
                        'status' => 'active',
                        'phone' => "09{$i}234567{$i}"
                    ]);
                }
                
                // Tạo một số shippers
                for ($i = 1; $i <= 5; $i++) {
                    \App\Models\User::firstOrCreate([
                        'email' => "shipper{$i}@courierxpress.com"
                    ], [
                        'name' => "Shipper {$i}",
                        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                        'role' => 'shipper',
                        'status' => 'active',
                        'phone' => "08{$i}345678{$i}",
                        'is_online' => $i <= 3
                    ]);
                }
                
                // Tạo một số users
                for ($i = 1; $i <= 10; $i++) {
                    \App\Models\User::firstOrCreate([
                        'email' => "user{$i}@example.com"
                    ], [
                        'name' => "Khách hàng {$i}",
                        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                        'role' => 'user',
                        'status' => 'active',
                        'phone' => "07{$i}567890{$i}"
                    ]);
                }
                
                // Tạo một số orders mẫu
                $users = \App\Models\User::where('role', 'user')->take(5)->get();
                $shippers = \App\Models\User::where('role', 'shipper')->take(3)->get();
                $agents = \App\Models\User::where('role', 'agent')->take(2)->get();
                
                if ($users->count() > 0 && \App\Models\Order::count() < 10) {
                    $statuses = ['pending', 'confirmed', 'assigned', 'in_transit', 'delivered', 'cancelled'];
                    
                    for ($i = 1; $i <= 15; $i++) {
                        $user = $users->random();
                        $shipper = $shippers->isNotEmpty() ? $shippers->random() : null;
                        $agent = $agents->isNotEmpty() ? $agents->random() : null;
                        $status = $statuses[array_rand($statuses)];
                        
                        \App\Models\Order::create([
                            'tracking_number' => 'CX' . str_pad($i, 6, '0', STR_PAD_LEFT),
                            'user_id' => $user->id,
                            'shipper_id' => $shipper ? $shipper->id : null,
                            'agent_id' => $agent ? $agent->id : null,
                            'pickup_name' => 'Người gửi ' . $i,
                            'pickup_phone' => '0987654321',
                            'pickup_address' => "Địa chỉ lấy hàng {$i}",
                            'pickup_ward' => 'Phường ' . $i,
                            'pickup_district' => 'Quận ' . ($i % 5 + 1),
                            'pickup_city' => 'Hồ Chí Minh',
                            'delivery_name' => 'Người nhận ' . $i,
                            'delivery_phone' => '0123456789',
                            'delivery_address' => "Địa chỉ giao hàng {$i}",
                            'delivery_ward' => 'Phường ' . ($i + 1),
                            'delivery_district' => 'Quận ' . ($i % 7 + 1),
                            'delivery_city' => 'Hồ Chí Minh',
                            'package_type' => ['standard', 'express', 'economy'][array_rand(['standard', 'express', 'economy'])],
                            'weight' => rand(1, 50) / 10,
                            'cod_amount' => rand(0, 5000000),
                            'shipping_fee' => rand(20000, 100000),
                            'total_fee' => rand(20000, 150000),
                            'status' => $status,
                            'payment_method' => 'cod',
                            'payment_status' => 'pending',
                            'notes' => "Ghi chú đơn hàng {$i}",
                            'created_at' => now()->subDays(rand(0, 30)),
                            'assigned_at' => in_array($status, ['assigned', 'in_transit', 'delivered']) ? now()->subDays(rand(0, 5)) : null,
                            'completed_at' => $status === 'delivered' ? now()->subDays(rand(0, 2)) : null,
                        ]);
                    }
                }
                
                $totalUsers = \App\Models\User::count();
                $totalOrders = \App\Models\Order::count();
                
                return "<h1>Dữ liệu admin đã được tạo thành công!</h1>
                       <p>Tổng users: {$totalUsers}</p>
                       <p>Tổng orders: {$totalOrders}</p>
                       <p>Admin login: admin@courierxpress.com / 123456</p>
                       <p><a href='/admin/dashboard'>Vào Admin Dashboard</a></p>";
                       
            } catch (\Exception $e) {
                return "Lỗi tạo dữ liệu: " . $e->getMessage();
            }
        })->name('init-admin-data');
        
        // Test kết nối database
        Route::get('/test-db-connection', function () {
            try {
                // Test database connection
                $connection = \DB::connection()->getPDO();
                $dbName = \DB::connection()->getDatabaseName();
                
                // Test tables exist
                $tables = \DB::select("SHOW TABLES");
                $tableNames = array_map(function($table) {
                    return reset($table);
                }, $tables);
                
                // Test User model
                $userCount = \App\Models\User::count();
                $adminCount = \App\Models\User::where('role', 'admin')->count();
                $agentCount = \App\Models\User::where('role', 'agent')->count();
                $shipperCount = \App\Models\User::where('role', 'shipper')->count();
                
                // Test Order model
                $orderCount = \App\Models\Order::count();
                $pendingOrders = \App\Models\Order::where('status', 'pending')->count();
                $deliveredOrders = \App\Models\Order::where('status', 'delivered')->count();
                
                return "<h1>Test Kết nối Database Thành Công!</h1>
                       <h2>Database Info:</h2>
                       <p>Database: {$dbName}</p>
                       <p>Tables: " . count($tableNames) . " bảng</p>
                       <p>Danh sách tables: " . implode(', ', $tableNames) . "</p>
                       
                       <h2>User Statistics:</h2>
                       <p>Tổng users: {$userCount}</p>
                       <p>Admins: {$adminCount}</p>
                       <p>Agents: {$agentCount}</p>
                       <p>Shippers: {$shipperCount}</p>
                       
                       <h2>Order Statistics:</h2>
                       <p>Tổng orders: {$orderCount}</p>
                       <p>Pending orders: {$pendingOrders}</p>
                       <p>Delivered orders: {$deliveredOrders}</p>
                       
                       <h2>Actions:</h2>
                       <p><a href='/dev/init-admin-data'>Tạo dữ liệu mẫu</a></p>
                       <p><a href='/admin/dashboard'>Vào Admin Dashboard</a></p>";
                       
            } catch (\Exception $e) {
                return "<h1>Lỗi kết nối Database!</h1><p>" . $e->getMessage() . "</p><p>Vui lòng kiểm tra XAMPP MySQL đã chạy chưa.</p>";
            }
        })->name('test-db-connection');
        
        // Test registration route
        Route::get('/test-register', function() {
            try {
                $user = \App\Models\User::create([
                    'name' => 'Test User ' . now()->format('H:i:s'),
                    'email' => 'test' . time() . '@example.com',
                    'phone' => '0123' . rand(100000, 999999),
                    'password' => \Illuminate\Support\Facades\Hash::make('password123'),
                    'role' => 'user',
                    'status' => 'active',
                ]);
                
                return "<h1>Registration Test Success!</h1>
                       <p>User created: {$user->name}</p>
                       <p>Email: {$user->email}</p>
                       <p>Phone: {$user->phone}</p>
                       <p>ID: {$user->id}</p>
                       <p><a href='/login'>Try logging in</a></p>";
                       
            } catch (\Exception $e) {
                return "<h1>Registration Test Failed!</h1><p>" . $e->getMessage() . "</p>";
            }
        })->name('test-register');
    });
}