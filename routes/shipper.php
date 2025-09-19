<?php

use App\Http\Controllers\Shipper\DashboardController;
use App\Http\Controllers\Shipper\OrderController;
use App\Http\Controllers\Shipper\HistoryController;
use Illuminate\Support\Facades\Route;

// Shipper Routes - Temporarily disable role middleware for testing
Route::middleware(['auth'])->prefix('shipper')->group(function () {
    // Dashboard
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('shipper.dashboard');
    
    // API routes
    Route::prefix('api')->group(function () {
        Route::get('/dashboard-data', [DashboardController::class, 'getDashboardData']);
        Route::get('/current-orders', [DashboardController::class, 'getCurrentOrders']);
        Route::get('/statistics', [DashboardController::class, 'getStatistics']);
        Route::get('/recent-activities', [DashboardController::class, 'getRecentActivities']);
        Route::get('/daily-performance', [DashboardController::class, 'getDailyPerformance']);
        Route::post('/location/update', [DashboardController::class, 'updateLocation']);
        Route::post('/status/update', [DashboardController::class, 'updateStatus']);
        
        // Order API routes
        Route::get('/orders/list', [OrderController::class, 'getOrders']);
        Route::get('/orders/{id}', [OrderController::class, 'getOrderDetails']);
        Route::post('/orders/{id}/update-status', [OrderController::class, 'updateStatus']);
        
        // History API routes
        Route::get('/delivery-history', [HistoryController::class, 'getDeliveryHistory']);
        Route::get('/delivery-statistics', [HistoryController::class, 'getDeliveryStatistics']);
        Route::get('/delivery-details/{id}', [HistoryController::class, 'getDeliveryDetails']);
    });

    // Orders
    Route::get('/orders', [OrderController::class, 'index'])->name('shipper.orders');

    // History
    Route::get('/history', [HistoryController::class, 'index'])->name('shipper.history');
    
    // Tracking
    Route::get('/tracking', function () {
        return view('shipper.tracking');
    })->name('shipper.tracking');
});
