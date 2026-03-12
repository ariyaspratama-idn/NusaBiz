<?php

use App\Http\Controllers\Admin\AccountController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\ReportController;
use App\Http\Controllers\Admin\TransactionController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ChatPanelController;
use App\Http\Controllers\Admin\OperationalController;
use App\Http\Controllers\Admin\Bengkel\VehicleController as AdminVehicleCtrl;
use App\Http\Controllers\Admin\Bengkel\SparePartController as AdminSparePartCtrl;
use App\Http\Controllers\Admin\Bengkel\ServiceController as AdminServiceCtrl;
use App\Http\Controllers\Admin\Bengkel\MechanicController as AdminMechanicCtrl;
use App\Http\Controllers\Admin\Bengkel\BookingController as AdminBookingCtrl;
use App\Http\Controllers\Admin\Bengkel\OilChangeAnalyticsController;
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardCtrl;
use App\Http\Controllers\Mechanic\WorkOrderController as MechanicWorkOrderCtrl;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardCtrl;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleCtrl;
use App\Http\Controllers\Customer\BookingController as CustomerBookingCtrl;
use App\Http\Controllers\Customer\ServiceHistoryController as CustomerServiceHistoryCtrl;
use App\Http\Controllers\Customer\MembershipCardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [\App\Http\Controllers\StorefrontController::class, 'index'])->name('home');
Route::get('/catalog', [\App\Http\Controllers\StorefrontController::class, 'catalog'])->name('catalog');
Route::get('/product/{product:slug}', [\App\Http\Controllers\StorefrontController::class, 'productDetail'])->name('product.detail');
Route::get('/article/{article:slug}', [\App\Http\Controllers\StorefrontController::class, 'articleDetail'])->name('article.detail');

/* ============================================================
 *  AUTENTIKASI & MULTI-TENANCY LOGIN
 * ============================================================ */
Route::get('/login', fn() => view('auth.login'))->name('login');
Route::post('/login', function (\Illuminate\Http\Request $request) {
    $credentials = $request->validate([
        'email'         => 'required|string',
        'password'      => 'required',
        'device_uuid'   => 'nullable|string',
        'role_category' => 'required|in:admin,karyawan',
    ]);

    $input = trim($request->email);
    $password = $request->password;

    // 1. Coba cari user berdasarkan email langsung
    $user = \App\Models\User::withoutGlobalScopes()->where('email', $input)->first();

    // 2. Jika tidak ketemu, coba cari berdasarkan NIP di tabel karyawans
    if (!$user) {
        $karyawan = \App\Models\Karyawan::withoutGlobalScopes()->where('nip', $input)->first();
        if ($karyawan) {
            $user = \App\Models\User::withoutGlobalScopes()->find($karyawan->user_id);
        }
    }

    if (!$user) {
        return back()->withErrors(['email' => 'ID / NIP (' . $input . ') tidak terdaftar di database pusat.'])->withInput();
    }

    if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return back()->withErrors(['email' => 'Kata Sandi salah. Mohon periksa kembali.'])->withInput();
    }

    // Role Groups
    $adminRoles = ['SUPER_ADMIN', 'admin-pusat', 'owner'];
    $karyawanRoles = ['karyawan', 'kasir', 'kepala-cabang', 'wakil-kepala-cabang', 'mechanic'];

    if ($request->role_category === 'admin' && !in_array($user->role, $adminRoles)) {
        return back()->withErrors(['email' => 'Akun ' . $user->name . ' harus login melalui jalur Karyawan.'])->withInput();
    }
    if ($request->role_category === 'karyawan' && !in_array($user->role, $karyawanRoles)) {
        return back()->withErrors(['email' => 'Akun ' . $user->name . ' harus login melalui jalur Admin Pusat.'])->withInput();
    }

    \Illuminate\Support\Facades\Auth::login($user);
    $request->session()->put('tenant_id', $user->tenant_id);
    $request->session()->regenerate();

    if (in_array($user->role, $adminRoles)) return redirect()->route('admin.dashboard');
    if (in_array($user->role, $karyawanRoles)) return redirect()->route('karyawan.dashboard');
    return $user->role === 'customer' ? redirect()->route('customer.dashboard') : redirect()->route('home');
});

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/* ============================================================
 *  KARYAWAN DASHBOARD
 * ============================================================ */
Route::middleware(['auth', 'role:karyawan,kasir,kepala-cabang,wakil-kepala-cabang,mechanic'])
    ->prefix('karyawan-dashboard')
    ->name('karyawan.')
    ->group(function () {
    Route::get('/', [\App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
    Route::get('/payroll', [\App\Http\Controllers\Karyawan\DashboardController::class, 'payroll'])->name('payroll');
});

/* ============================================================
 *  TERMINAL POS
 * ============================================================ */
Route::get('/pos', [\App\Http\Controllers\POSController::class, 'index'])->name('pos.index');
Route::post('/pos/open-session', [\App\Http\Controllers\POSController::class, 'openSession'])->name('pos.open-session');
Route::middleware(['auth', 'ensure-cashier-session'])->group(function() {
    Route::post('/pos', [\App\Http\Controllers\POSController::class, 'store'])->name('pos.store');
});

/* ============================================================
 *  ADMIN DASHBOARD & MASTER DATA
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,EDITOR_KONTEN,admin-pusat,owner'])
    ->prefix('admin-dashboard')
    ->name('admin.')
    ->group(function () {

    Route::get('/', function () {
        $stats = [
            'total_produk'      => \App\Models\EcProduct::count(),
            'pesanan_baru'      => \App\Models\EcOrder::where('status', 'perlu_diproses')->count(),
            'stok_kritis'       => \App\Models\EcProduct::whereRaw('stock <= min_stock_alert')->count(),
            'pendapatan_hari_ini' => \App\Models\EcOrder::where('payment_status', 'paid')->whereDate('paid_at', today())->sum('total'),
            'chat_belum_dibaca'  => \App\Models\ChatSession::where('status', 'active')->count(),
        ];
        $latestOrders = \App\Models\EcOrder::latest()->limit(5)->get();
        return view('admin.dashboard', compact('stats', 'latestOrders'));
    })->name('dashboard');

    Route::resource('products', AdminProductController::class);
    Route::patch('products/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::patch('products/{product}/price', [AdminProductController::class, 'updatePrice'])->name('products.update-price');
    Route::resource('categories', \App\Http\Controllers\Admin\ProductCategoryController::class)->except(['create', 'show', 'edit']);
    
    Route::resource('orders', AdminOrderController::class);
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');

    Route::get('cms/articles', [CmsController::class, 'articles'])->name('cms.articles');
    Route::resource('cms/articles', CmsController::class)->names('cms.articles_resource'); // Avoid conflict
    
    Route::get('chat', [ChatPanelController::class, 'index'])->name('chat.index');
    Route::get('chat/{session}', [ChatPanelController::class, 'show'])->name('chat.show');
    Route::post('chat/{session}/reply', [ChatPanelController::class, 'reply'])->name('chat.reply');

    Route::resource('users', UserController::class);
    Route::get('audit-trail', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-trail.index');
    
    // HR & Analytics
    Route::get('hr', [\App\Http\Controllers\Admin\HRController::class, 'index'])->name('hr.index');
    Route::get('hr/izin', [\App\Http\Controllers\Admin\HRController::class, 'daftarIzin'])->name('hr.izin.index');
    Route::get('hr/payroll', [\App\Http\Controllers\Admin\HRController::class, 'payroll'])->name('hr.payroll');
    
    Route::get('analysis/overview', [\App\Http\Controllers\Admin\AnalysisController::class, 'overview'])->name('analysis.overview');
});

// Non-prefixed Admin routes for Accounting
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL'])
    ->prefix('admin-dashboard')
    ->group(function () {
    Route::resource('accounts', AccountController::class);
    Route::resource('transactions', TransactionController::class);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('reconciliation', function() { return view('reconciliation.index'); })->name('reconciliation.index');
    Route::get('operations', [OperationalController::class, 'index'])->name('operations.index');
});

/* ============================================================
 *  BENGKEL MODULE
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,admin'])
    ->prefix('admin-dashboard/bengkel')->name('bengkel.admin.')->group(function () {
        Route::resource('vehicles', AdminVehicleCtrl::class);
        Route::resource('spare-parts', AdminSparePartCtrl::class);
        Route::resource('services', AdminServiceCtrl::class);
        Route::resource('mechanics', AdminMechanicCtrl::class);
        Route::resource('bookings', AdminBookingCtrl::class);
});

Route::middleware(['auth', 'role:mechanic'])->prefix('mechanic')->name('mechanic.')->group(function () {
    Route::get('/dashboard', [MechanicDashboardCtrl::class, 'index'])->name('dashboard');
    Route::resource('work-orders', MechanicWorkOrderCtrl::class);
});

Route::middleware(['auth', 'role:customer'])->prefix('customer')->name('customer.')->group(function () {
    Route::get('/dashboard', [CustomerDashboardCtrl::class, 'index'])->name('dashboard');
    Route::resource('vehicles', CustomerVehicleCtrl::class);
    Route::resource('bookings', CustomerBookingCtrl::class);
});

/* ============================================================
 *  PUBLIC API & UTILS
 * ============================================================ */
Route::prefix('chat')->name('chat.visitor.')->group(function () {
    Route::post('/init', function() { /* ... */ })->name('init');
});

Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');
