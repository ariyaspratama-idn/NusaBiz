<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\Auth\RegisteredUserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        $user = auth()->user();
        return match($user->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'mechanic' => redirect()->route('mechanic.dashboard'),
            'customer' => redirect()->route('customer.dashboard'),
            default => redirect()->route('login'),
        };
    }
    return redirect()->route('login');
});

// Guest routes (Login & Register)
Route::middleware('guest')->group(function () {
    Route::get('login', [AuthenticatedSessionController::class, 'create'])
        ->name('login');
    
    Route::post('login', [AuthenticatedSessionController::class, 'store']);

    Route::get('register', [RegisteredUserController::class, 'create'])
        ->name('register');
    
    Route::post('register', [RegisteredUserController::class, 'store']);
});

// Authenticated routes
Route::middleware('auth')->group(function () {
    Route::post('logout', [AuthenticatedSessionController::class, 'destroy'])
        ->name('logout');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Admin\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Customer Management
        Route::resource('customers', App\Http\Controllers\Admin\CustomerController::class);
        Route::get('customers/{customer}/barcode', [App\Http\Controllers\Admin\CustomerController::class, 'printBarcode'])
            ->name('customers.barcode');
        Route::post('customers/scan', [App\Http\Controllers\Admin\CustomerController::class, 'scanBarcode'])
            ->name('customers.scan');
        
        // Vehicle Management
        Route::resource('vehicles', App\Http\Controllers\Admin\VehicleController::class);
        
        // Mechanic Management
        Route::resource('mechanics', App\Http\Controllers\Admin\MechanicController::class);
        
        // Spare Parts Management
        Route::resource('spare-parts', App\Http\Controllers\Admin\SparePartController::class);
        Route::post('spare-parts/{sparePart}/adjust-stock', [App\Http\Controllers\Admin\SparePartController::class, 'adjustStock'])
            ->name('spare-parts.adjust-stock');
        
        // Service Catalog
        Route::resource('services', App\Http\Controllers\Admin\ServiceController::class);
        
        // Booking Management
        Route::resource('bookings', App\Http\Controllers\Admin\BookingController::class);
        
        // Reports
        Route::get('reports', [App\Http\Controllers\Admin\ReportController::class, 'index'])
            ->name('reports.index');
        Route::get('reports/financial', [App\Http\Controllers\Admin\ReportController::class, 'financial'])
            ->name('reports.financial');
        Route::get('reports/work-orders', [App\Http\Controllers\Admin\ReportController::class, 'workOrders'])
            ->name('reports.work-orders');
        Route::get('reports/inventory', [App\Http\Controllers\Admin\ReportController::class, 'inventory'])
            ->name('reports.inventory');
        
        // Oil Change Analytics
        Route::get('analytics/oil-change', [App\Http\Controllers\Admin\OilChangeAnalyticsController::class, 'index'])
            ->name('analytics.oil-change');
    });

    // Mechanic routes
    Route::middleware(['role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Mechanic\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Work Orders
        Route::get('/work-orders', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'index'])
            ->name('work-orders.index');
        Route::get('/work-orders/{workOrder}', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'show'])
            ->name('work-orders.show');
        Route::post('/work-orders/{workOrder}/start', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'start'])
            ->name('work-orders.start');
        Route::post('/work-orders/{workOrder}/progress', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'addProgress'])
            ->name('work-orders.add-progress');
        Route::post('/work-orders/{workOrder}/items', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'addItem'])
            ->name('work-orders.add-item');
        Route::post('/work-orders/{workOrder}/complete', [App\Http\Controllers\Mechanic\WorkOrderController::class, 'complete'])
            ->name('work-orders.complete');
    });

    // Customer routes
    Route::middleware(['role:customer'])->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [App\Http\Controllers\Customer\DashboardController::class, 'index'])
            ->name('dashboard');
        
        // Vehicles
        Route::resource('vehicles', App\Http\Controllers\Customer\VehicleController::class)
            ->except(['destroy']);
        
        // Bookings
        Route::resource('bookings', App\Http\Controllers\Customer\BookingController::class)
            ->only(['index', 'create', 'store', 'show']);
        Route::post('bookings/{booking}/cancel', [App\Http\Controllers\Customer\BookingController::class, 'cancel'])
            ->name('bookings.cancel');
        
        // Service History
        Route::get('service-history', [App\Http\Controllers\Customer\ServiceHistoryController::class, 'index'])
            ->name('service-history.index');
        Route::get('service-history/{serviceHistory}', [App\Http\Controllers\Customer\ServiceHistoryController::class, 'show'])
            ->name('service-history.show');
        
        // Membership Card
        Route::get('membership-card', [App\Http\Controllers\Customer\MembershipCardController::class, 'show'])
            ->name('membership-card');
    });
});
