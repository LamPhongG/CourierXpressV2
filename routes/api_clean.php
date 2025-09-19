<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Here is where you can register API routes for your application.
*/

Route::middleware(['auth'])->group(function () {
    
    // ===== ADMIN API ROUTES =====
    Route::middleware(['role:admin'])->prefix('admin')->name('api.admin.')->group(function () {
        // Dashboard stats
        Route::get('/stats', [\App\Http\Controllers\Admin\DashboardController::class, 'getStats'])->name('stats');
        
        // User management
        Route::apiResource('/users', \App\Http\Controllers\Admin\UserController::class);
        Route::put('/users/{id}/toggle-status', [\App\Http\Controllers\Admin\UserController::class, 'toggleStatus'])->name('users.toggle-status');
        
        // Order management
        Route::apiResource('/orders', \App\Http\Controllers\Admin\OrderController::class);
        Route::get('/agents', [\App\Http\Controllers\Admin\OrderController::class, 'getAgents'])->name('agents');
        Route::get('/shippers', [\App\Http\Controllers\Admin\OrderController::class, 'getShippers'])->name('shippers');
    });
    
    // ===== AGENT API ROUTES =====
    Route::middleware(['role:agent'])->prefix('agent')->name('api.agent.')->group(function () {
        Route::get('/stats', [\App\Http\Controllers\Agent\DashboardController::class, 'getStats'])->name('stats');
        Route::get('/pending-orders', [\App\Http\Controllers\Agent\DashboardController::class, 'getPendingOrders'])->name('pending-orders');
        Route::get('/shippers', [\App\Http\Controllers\Agent\DashboardController::class, 'getShippers'])->name('shippers');
    });
    
    // ===== USER API ROUTES =====
    Route::middleware(['role:user'])->prefix('user')->name('api.user.')->group(function () {
        Route::get('/data', [\App\Http\Controllers\DashboardController::class, 'getUserData'])->name('data');
        Route::get('/recent-orders', [\App\Http\Controllers\DashboardController::class, 'getRecentOrders'])->name('recent-orders');
    });
    
    // ===== SHIPPER API ROUTES =====
    Route::middleware(['role:shipper'])->prefix('shipper')->name('api.shipper.')->group(function () {
        // Dashboard data
        Route::get('/dashboard', [\App\Http\Controllers\Shipper\DashboardController::class, 'getDashboardData'])->name('dashboard');
        Route::get('/statistics', [\App\Http\Controllers\Shipper\DashboardController::class, 'getStatistics'])->name('statistics');
        Route::get('/recent-activities', [\App\Http\Controllers\Shipper\DashboardController::class, 'getRecentActivities'])->name('recent-activities');
        
        // Order management
        Route::get('/current-orders', [\App\Http\Controllers\Shipper\OrderController::class, 'getCurrentOrders'])->name('current-orders');
        Route::get('/orders/list', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrders'])->name('orders.list');
        Route::get('/orders/{id}', [\App\Http\Controllers\Shipper\OrderController::class, 'getOrderDetails'])->name('orders.show');
        Route::put('/orders/{id}/status', [\App\Http\Controllers\Shipper\OrderController::class, 'updateStatus'])->name('orders.update-status');
        
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
    });
}