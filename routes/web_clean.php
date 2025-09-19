<?php

use Illuminate\Support\Facades\Route;
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

// ===== PUBLIC ROUTES =====
Route::get('/', function () {
    return view('home');
});

Route::get('/tracking', function (Illuminate\Http\Request $request) {
    $id = $request->get('tracking_id');
    return view('tracking', ['id' => $id]);
})->name('tracking');

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
    
    // Admin Dashboard & Routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'adminDashboard'])->name('dashboard');
        Route::get('/users', function() { return view('admin.users.index'); })->name('users');
        Route::get('/orders', function() { return view('admin.orders.index'); })->name('orders');
        Route::get('/agents', function() { return view('admin.agents.index'); })->name('agents');
    });
    
    // Agent Dashboard & Routes
    Route::middleware(['role:agent'])->prefix('agent')->name('agent.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'agentDashboard'])->name('dashboard');
        Route::get('/orders', function() { return view('agent.orders.index'); })->name('orders');
        Route::get('/shippers', function() { return view('agent.shippers.index'); })->name('shippers');
        Route::get('/reports', function() { return view('agent.reports.index'); })->name('reports');
    });
    
    // User Dashboard & Routes
    Route::middleware(['role:user'])->prefix('user')->name('user.')->group(function () {
        Route::get('/dashboard', [DashboardController::class, 'userDashboard'])->name('dashboard');
        Route::get('/create-order', function() { return view('user.create-order'); })->name('create-order');
        Route::get('/profile', function() { return view('user.profile'); })->name('profile');
        Route::get('/orders', function() { return view('user.orders.index'); })->name('orders');
    });
    
    // Shipper Dashboard & Routes (imported from shipper.php)
    Route::middleware(['role:shipper'])->prefix('shipper')->name('shipper.')->group(function () {
        Route::get('/dashboard', [\App\Http\Controllers\Shipper\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/orders', [\App\Http\Controllers\Shipper\OrderController::class, 'index'])->name('orders');
        Route::get('/history', [\App\Http\Controllers\Shipper\HistoryController::class, 'index'])->name('history');
    });
});

// ===== DEMO & DEVELOPMENT ROUTES =====
// Only include these routes in non-production environments
if (app()->environment(['local', 'testing', 'development'])) {
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
    });
}