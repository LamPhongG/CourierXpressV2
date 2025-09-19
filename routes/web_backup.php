<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Http\Request;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\LanguageController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\Shipper\DashboardController as ShipperDashboardController;

// Trang chủ
Route::get('/', function () {
    return view('home');
});

// Test route
Route::get('/test', function () {
    return 'Server hoạt động tốt! CourierXpress đã sẵn sàng.';
});

// Test accounts info
Route::get('/test-accounts', function () {
    return view('test-accounts');
});

// Debug users - show all users in database
Route::get('/debug-users', function () {
    $users = \App\Models\User::all(['id', 'name', 'email', 'role', 'status']);
    return response()->json($users);
});

// Test password check
Route::get('/test-password', function () {
    $user = \App\Models\User::where('email', 'admin@courierxpress.com')->first();
    if ($user) {
        $testPassword = '123456';
        $hashCheck = \Illuminate\Support\Facades\Hash::check($testPassword, $user->password);
        return response()->json([
            'user_found' => true,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status,
            'password_hash' => $user->password,
            'test_password' => $testPassword,
            'password_matches' => $hashCheck,
        ]);
    }
    return response()->json(['user_found' => false]);
});

// Create test user with simple password
Route::get('/create-test-user', function () {
    // Delete existing test user if exists
    \App\Models\User::where('email', 'test@test.com')->delete();
    
    // Create new test user
    $user = \App\Models\User::create([
        'name' => 'Test User',
        'email' => 'test@test.com',
        'phone' => '0909999999',
        'password' => \Illuminate\Support\Facades\Hash::make('123456'),
        'role' => 'admin',
        'status' => 'active',
    ]);
    
    return response()->json([
        'message' => 'Test user created successfully',
        'user' => $user,
        'login_info' => [
            'email' => 'test@test.com',
            'password' => '123456'
        ]
    ]);
});

// Fix admin user password
Route::get('/fix-admin-password', function () {
    $user = \App\Models\User::where('email', 'admin@courierxpress.com')->first();
    if ($user) {
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->status = 'active';
        $user->save();
        
        return response()->json([
            'message' => 'Admin password fixed successfully',
            'user' => $user,
            'login_info' => [
                'email' => 'admin@courierxpress.com',
                'password' => '123456'
            ]
        ]);
    }
    
    return response()->json(['message' => 'Admin user not found']);
});

// Fix all user passwords
Route::get('/fix-all-passwords', function () {
    $users = \App\Models\User::all();
    $fixed = [];
    
    foreach ($users as $user) {
        // Check if password is already properly hashed
        if (!\Illuminate\Support\Facades\Hash::needsRehash($user->password)) {
            // Try to verify if it's a valid bcrypt hash
            try {
                \Illuminate\Support\Facades\Hash::check('test', $user->password);
                continue; // Password is already properly hashed
            } catch (Exception $e) {
                // Password is not properly hashed, fix it
            }
        }
        
        // Fix the password
        $user->password = \Illuminate\Support\Facades\Hash::make('123456');
        $user->status = 'active';
        $user->save();
        
        $fixed[] = [
            'email' => $user->email,
            'name' => $user->name,
            'role' => $user->role
        ];
    }
    
    return response()->json([
        'message' => 'All user passwords fixed successfully',
        'fixed_users' => $fixed,
        'total_fixed' => count($fixed),
        'note' => 'All passwords are now: 123456'
    ]);
});

// Route tracking
Route::get('/tracking', function (Request $request) {
    $id = $request->get('tracking_id'); // lấy giá trị từ input name="tracking_id"
    return view('tracking', ['id' => $id]);
})->name('tracking');

// Import Shipper Routes
require __DIR__.'/shipper.php';

// Auth routes
Route::get('/login', function () {
    return view('auth.login');
})->name('login');

Route::get('/register', function () {
    return view('auth.register');
})->name('register');

// Xử lý đăng nhập
Route::post('/login', [AuthenticatedSessionController::class, 'store'])->name('login.store');

// Xử lý đăng ký
Route::post('/register', [RegisteredUserController::class, 'store'])->name('register.store');

// Xử lý đăng xuất
Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');

// Dashboard routes - Temporarily remove role middleware for testing
Route::middleware(['auth'])->group(function () {
    // Admin routes  
    Route::get('/admin/dashboard', [DashboardController::class, 'adminDashboard'])
        // ->middleware('role:admin') // Temporarily disabled
        ->name('admin.dashboard');
    
    // Admin sophisticated dashboard routes
    Route::get('/admin/users', function() {
        return view('admin.users.index');
    });
    Route::get('/admin/orders', function() {
        return view('admin.orders.index');
    });
    
    // Admin API endpoints
    Route::prefix('api/admin')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'getStats']);
        Route::get('/users', [\App\Http\Controllers\Admin\UserController::class, 'index']);
        Route::post('/users', [\App\Http\Controllers\Admin\UserController::class, 'store']);
        Route::get('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'show']);
        Route::put('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'update']);
        Route::delete('/users/{id}', [\App\Http\Controllers\Admin\UserController::class, 'destroy']);
        Route::put('/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus']);
        
        Route::get('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'index']);
        Route::post('/orders', [\App\Http\Controllers\Admin\OrderController::class, 'store']);
        Route::get('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'show']);
        Route::put('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'update']);
        Route::delete('/orders/{id}', [\App\Http\Controllers\Admin\OrderController::class, 'destroy']);
        Route::get('/agents', [\App\Http\Controllers\Admin\OrderController::class, 'getAgents']);
        Route::get('/shippers', [\App\Http\Controllers\Admin\OrderController::class, 'getShippers']);
    });
    
    // User routes
    Route::get('/user/dashboard', [DashboardController::class, 'userDashboard'])
        // ->middleware('role:user') // Temporarily disabled
        ->name('user.dashboard');
    Route::get('/api/user/data', [DashboardController::class, 'getUserData']);
    Route::get('/api/user/recent-orders', [DashboardController::class, 'getRecentOrders']);
    
    // Agent routes  
    Route::get('/agent/dashboard', [DashboardController::class, 'agentDashboard'])
        // ->middleware('role:agent') // Temporarily disabled
        ->name('agent.dashboard');
    
    // Agent API endpoints
    Route::prefix('api/agent')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Agent\DashboardController::class, 'getStats']);
        Route::get('/pending-orders', [\App\Http\Controllers\Agent\DashboardController::class, 'getPendingOrders']);
        Route::get('/shippers', [\App\Http\Controllers\Agent\DashboardController::class, 'getShippers']);
    });
    
    // Shipper routes (trong shipper.php)
    Route::get('/api/shipper/data', [DashboardController::class, 'getShipperData']);
    Route::get('/api/shipper/activities', [DashboardController::class, 'getRecentActivities']);
    
    // Additional shipper API endpoints for sophisticated dashboard
    Route::prefix('api/shipper')->group(function () {
        Route::get('/current-orders', [\App\Http\Controllers\Shipper\OrderController::class, 'getCurrentOrders']);
        Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities']);
        Route::get('/status', [DashboardController::class, 'getShipperData']);
        Route::get('/statistics', [DashboardController::class, 'getShipperData']);
    });
});

// Language routes
Route::get('/language/{locale}', [LanguageController::class, 'changeLanguage'])->name('language.change');

// Language demo page
Route::get('/demo', function () {
    return view('language-demo');
})->name('language.demo');

// Full translation demo page
Route::get('/full-demo', function () {
    return view('full-translation-demo');
})->name('full.demo');

// Complete services demo with full translation
Route::get('/complete-demo', function () {
    return view('services.complete-demo');
})->name('complete.demo');

// Individual customer complete translation demo
Route::get('/individual-complete', function () {
    return view('customers.individual-complete');
})->name('individual.complete');

// All services with complete translation (like your image)
Route::get('/all-services', function () {
    return view('all-services-translated');
})->name('all.services');

// FedEx-style smart translation demo
Route::get('/fedex-style', function () {
    return view('fedex-style-demo');
})->name('fedex.style');

// Lalamove-style translation demo
Route::get('/lalamove-style', function () {
    return view('lalamove-style');
})->name('lalamove.style');

// Header one-line demo
Route::get('/header-demo', function () {
    return view('header-demo');
})->name('header.demo');

// Dropdown test page
Route::get('/dropdown-test', function () {
    return view('dropdown-test');
})->name('dropdown.test');

// Dịch vụ routes
Route::get('/dich-vu/giao-hang', [PageController::class, 'dichVuGiaoHang'])->name('dich-vu.giao-hang');
Route::get('/dich-vu/xe-tai', [PageController::class, 'dichVuXeTai'])->name('dich-vu.xe-tai');
Route::get('/dich-vu/chuyen-nha', [PageController::class, 'dichVuChuyenNha'])->name('dich-vu.chuyen-nha');
Route::get('/dich-vu/doanh-nghiep', [PageController::class, 'dichVuDoanhNghiep'])->name('dich-vu.doanh-nghiep');

// Khách hàng routes
Route::get('/khach-hang/ca-nhan', [PageController::class, 'khachHangCaNhan'])->name('khach-hang.ca-nhan');
Route::get('/khach-hang/doanh-nghiep', [PageController::class, 'khachHangDoanhNghiep'])->name('khach-hang.doanh-nghiep');
Route::get('/cong-dong/ho-tro', [PageController::class, 'congDongHoTro'])->name('cong-dong.ho-tro');

// Tài xế routes
Route::get('/tai-xe/dang-ky', [PageController::class, 'dangKyTaiXe'])->name('tai-xe.dang-ky');
Route::get('/tai-xe/cong-dong', [PageController::class, 'congDongTaiXe'])->name('tai-xe.cong-dong');
Route::get('/tai-xe/cam-nang', [PageController::class, 'camNangTaiXe'])->name('tai-xe.cam-nang');

// Hỗ trợ routes
Route::get('/ho-tro/khach-hang', [PageController::class, 'hoTroKhachHang'])->name('ho-tro.khach-hang');
Route::get('/ho-tro/tai-xe', [PageController::class, 'hoTroTaiXe'])->name('ho-tro.tai-xe');

// Tuyển dụng routes
Route::get('/tuyen-dung/ve-chung-toi', [PageController::class, 'veChungToi'])->name('tuyen-dung.ve-chung-toi');
Route::get('/tuyen-dung/cau-chuyen', [PageController::class, 'cauChuyenCourierXpress'])->name('tuyen-dung.cau-chuyen');
Route::get('/tuyen-dung/gia-nhap', [PageController::class, 'giaNhapCourierXpress'])->name('tuyen-dung.gia-nhap');

// Truncate and recreate all users to ensure clean data
Route::get('/reset-all-users', function () {
    // Clear all users
    \App\Models\User::truncate();
    
    // Recreate with proper hashing
    $users = [
        [
            'name' => 'Admin CourierXpress',
            'email' => 'admin@courierxpress.com',
            'phone' => '0901234567',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => 'admin',
            'status' => 'active',
        ],
        [
            'name' => 'Agent Hồ Chí Minh',
            'email' => 'agent@courierxpress.com',
            'phone' => '0901234568',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => 'agent',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
        ],
        [
            'name' => 'Nguyễn Văn Shipper',
            'email' => 'shipper@courierxpress.com',
            'phone' => '0901234569',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => 'shipper',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
            'is_online' => true,
        ],
        [
            'name' => 'Trần Thị Khách Hàng',
            'email' => 'customer@courierxpress.com',
            'phone' => '0901234570',
            'password' => \Illuminate\Support\Facades\Hash::make('123456'),
            'role' => 'user',
            'status' => 'active',
            'city' => 'Hồ Chí Minh',
        ],
    ];
    
    foreach ($users as $userData) {
        \App\Models\User::create($userData);
    }
    
    return response()->json([
        'message' => 'All users reset and recreated successfully',
        'users_created' => count($users),
        'test_accounts' => [
            'admin@courierxpress.com => 123456',
            'agent@courierxpress.com => 123456',
            'shipper@courierxpress.com => 123456',
            'customer@courierxpress.com => 123456',
        ]
    ]);
});

// Emergency fix for specific user
Route::get('/emergency-fix-agent', function () {
    try {
        // Find the problematic user
        $user = \App\Models\User::where('email', 'agent@courierxpress.com')->first();
        
        if ($user) {
            // Force update with proper bcrypt hash
            \Illuminate\Support\Facades\DB::table('users')
                ->where('email', 'agent@courierxpress.com')
                ->update([
                    'password' => \Illuminate\Support\Facades\Hash::make('123456'),
                    'status' => 'active',
                    'updated_at' => now()
                ]);
            
            return response()->json([
                'success' => true,
                'message' => 'Agent user password fixed with emergency method',
                'login_info' => [
                    'email' => 'agent@courierxpress.com',
                    'password' => '123456'
                ]
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Agent user not found'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test admin dashboard without auth middleware
Route::get('/test-admin-dashboard-direct', function () {
    // Bypass auth middleware and directly show admin dashboard
    return view('admin.dashboard');
});

// Test dashboard access
Route::get('/test-dashboard-access', function () {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated']);
    }
    
    $user = auth()->user();
    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status
        ],
        'dashboard_routes' => [
            'admin' => route('admin.dashboard'),
            'agent' => route('agent.dashboard'),
            'user' => route('user.dashboard'),
            'shipper' => route('shipper.dashboard')
        ]
    ]);
});

// Debug current user role
Route::get('/debug-current-user', function () {
    if (!auth()->check()) {
        return response()->json(['error' => 'Not authenticated', 'redirect' => '/login']);
    }
    
    $user = auth()->user();
    return response()->json([
        'authenticated' => true,
        'user' => [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status
        ],
        'available_dashboards' => [
            'admin' => '/admin/dashboard',
            'agent' => '/agent/dashboard',
            'user' => '/user/dashboard',
            'shipper' => '/shipper/dashboard'
        ],
        'recommended_dashboard' => match($user->role) {
            'admin' => '/admin/dashboard',
            'agent' => '/agent/dashboard',
            'shipper' => '/shipper/dashboard',
            'user' => '/user/dashboard',
            default => '/user/dashboard'
        }
    ]);
});

// Quick role fix - Reset user role in case it's wrong
Route::get('/fix-user-role/{email}/{role}', function ($email, $role) {
    $user = \App\Models\User::where('email', $email)->first();
    if ($user) {
        $user->role = $role;
        $user->save();
        return response()->json([
            'success' => true,
            'message' => "User {$email} role updated to {$role}",
            'user' => $user
        ]);
    }
    return response()->json(['error' => 'User not found']);
});

// Test actual login simulation
Route::get('/test-login-flow', function () {
    // This simulates what happens when someone submits the login form
    try {
        $admin = \App\Models\User::where('email', 'admin@courierxpress.com')->first();
        if ($admin && \Illuminate\Support\Facades\Hash::check('123456', $admin->password)) {
            
            // Explicitly set the guard and login
            Auth::guard('web')->login($admin, false); // false = don't remember
            
            // Regenerate session for security
            request()->session()->regenerate();
            
            // Save the session immediately
            request()->session()->save();
            
            // Store user data in session (like our updated AuthenticatedSessionController does)
            session()->put('user_data', [
                'id' => $admin->id,
                'name' => $admin->name,
                'email' => $admin->email,
                'role' => $admin->role,
                'status' => $admin->status
            ]);
            
            // Redirect to admin dashboard like the actual login flow
            return redirect()->route('admin.dashboard');
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Login failed - password check failed'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Simulate actual form POST login
Route::get('/test-form-login/{role}', function ($role = 'admin') {
    // Simulate what happens when someone submits the login form via POST
    $credentials = [
        'admin' => ['email' => 'admin@courierxpress.com', 'password' => '123456'],
        'agent' => ['email' => 'agent@courierxpress.com', 'password' => '123456'],
        'customer' => ['email' => 'customer@courierxpress.com', 'password' => '123456'],
        'shipper' => ['email' => 'shipper@courierxpress.com', 'password' => '123456'],
    ];
    
    if (!isset($credentials[$role])) {
        return response()->json(['error' => 'Invalid role. Use: admin, agent, customer, shipper']);
    }
    
    $creds = $credentials[$role];
    
    // Create a fake request to simulate form submission
    $request = new \Illuminate\Http\Request();
    $request->merge([
        'email' => $creds['email'],
        'password' => $creds['password'],
        'remember' => false
    ]);
    
    // Use the actual AuthenticatedSessionController
    $controller = new \App\Http\Controllers\Auth\AuthenticatedSessionController();
    
    try {
        return $controller->store($request);
    } catch (Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'line' => $e->getLine(),
            'file' => $e->getFile()
        ]);
    }
});

// Test real login form POST simulation
Route::get('/test-real-login/{role}', function ($role = 'admin') {
    $credentials = [
        'admin' => ['email' => 'admin@courierxpress.com', 'password' => '123456'],
        'agent' => ['email' => 'agent@courierxpress.com', 'password' => '123456'],
        'customer' => ['email' => 'customer@courierxpress.com', 'password' => '123456'],
        'shipper' => ['email' => 'shipper@courierxpress.com', 'password' => '123456'],
    ];
    
    if (!isset($credentials[$role])) {
        return response()->json(['error' => 'Invalid role']);
    }
    
    $creds = $credentials[$role];
    
    // Simulate what the real login form does
    if (Auth::attempt($creds, false)) {
        request()->session()->regenerate();
        
        $user = Auth::user();
        
        // Store user data like the real AuthenticatedSessionController
        request()->session()->put('user_data', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status
        ]);
        
        // Return success with redirect info instead of redirecting
        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'user' => $user,
            'redirect_to' => match($user->role) {
                'admin' => '/admin/dashboard',
                'agent' => '/agent/dashboard',
                'shipper' => '/shipper/dashboard',
                default => '/user/dashboard'
            },
            'session_info' => [
                'session_id' => session()->getId(),
                'authenticated' => auth()->check()
            ]
        ]);
    } else {
        return response()->json(['error' => 'Login failed']);
    }
});

// Test login for admin
Route::get('/test-admin-login', function () {
    try {
        // Manually login as admin for testing
        $admin = \App\Models\User::where('email', 'admin@courierxpress.com')->first();
        if ($admin) {
            Auth::login($admin);
            return response()->json([
                'success' => true,
                'message' => 'Admin logged in successfully',
                'user' => [
                    'id' => $admin->id,
                    'name' => $admin->name,
                    'email' => $admin->email,
                    'role' => $admin->role
                ],
                'redirect' => '/admin/dashboard'
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Admin user not found'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test login without redirect
Route::get('/test-login-no-redirect', function () {
    try {
        $admin = \App\Models\User::where('email', 'admin@courierxpress.com')->first();
        if ($admin && \Illuminate\Support\Facades\Hash::check('123456', $admin->password)) {
            
            // Login the user
            Auth::guard('web')->login($admin, false);
            
            // Regenerate session
            request()->session()->regenerate();
            
            // Check immediately if authentication worked
            return response()->json([
                'success' => true,
                'message' => 'Login attempt completed',
                'authenticated_immediately' => auth()->check(),
                'user_immediately' => auth()->user(),
                'session_id' => session()->getId(),
                'session_data' => session()->all()
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Password check failed'
            ]);
        }
    } catch (Exception $e) {
        return response()->json([
            'success' => false,
            'error' => $e->getMessage()
        ]);
    }
});

// Test login and immediate dashboard access in same request
Route::get('/test-login-and-dashboard/{role}', function ($role = 'admin') {
    $credentials = [
        'admin' => ['email' => 'admin@courierxpress.com', 'password' => '123456'],
        'agent' => ['email' => 'agent@courierxpress.com', 'password' => '123456'],
        'customer' => ['email' => 'customer@courierxpress.com', 'password' => '123456'],
        'shipper' => ['email' => 'shipper@courierxpress.com', 'password' => '123456'],
    ];
    
    if (!isset($credentials[$role])) {
        return response('Invalid role', 400);
    }
    
    $creds = $credentials[$role];
    
    // Attempt login
    if (Auth::attempt($creds, false)) {
        request()->session()->regenerate();
        
        $user = Auth::user();
        
        // Store user data
        request()->session()->put('user_data', [
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
            'role' => $user->role,
            'status' => $user->status
        ]);
        
        // Save session
        request()->session()->save();
        
        // Now try to render the dashboard immediately
        try {
            if ($role === 'admin') {
                return view('admin.dashboard');
            } else {
                return view('user.dashboard');
            }
        } catch (Exception $e) {
            return response()->json([
                'error' => 'Dashboard render failed',
                'message' => $e->getMessage(),
                'authenticated' => auth()->check(),
                'user' => auth()->user()
            ]);
        }
    } else {
        return response()->json(['error' => 'Login failed']);
    }
});

// Debug current authentication status
Route::get('/debug-auth-status', function () {
    return response()->json([
        'authenticated' => auth()->check(),
        'user' => auth()->user(),
        'session_id' => session()->getId(),
        'session_data' => session()->all(),
        'auth_guard' => config('auth.defaults.guard'),
        'session_driver' => config('session.driver')
    ]);
});

// Comprehensive login test page
Route::get('/login-test-page', function () {
    return view('auth.login-test');
});
