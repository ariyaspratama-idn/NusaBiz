<?php

use App\Http\Controllers\AccountController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ChatPanelController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\Admin\Bengkel\VehicleController as AdminVehicleCtrl;
use App\Http\Controllers\Admin\Bengkel\SparePartController as AdminSparePartCtrl;
use App\Http\Controllers\Admin\Bengkel\ServiceController as AdminServiceCtrl;
use App\Http\Controllers\Admin\Bengkel\MechanicController as AdminMechanicCtrl;
use App\Http\Controllers\Admin\Bengkel\BookingController as AdminBookingCtrl;
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardCtrl;
use App\Http\Controllers\Mechanic\WorkOrderController as MechanicWorkOrderCtrl;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardCtrl;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleCtrl;
use App\Http\Controllers\Customer\BengkelBookingController as CustomerBookingCtrl;
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
 *  AUTENTIKASI & LOGIN
 * ============================================================ */
Route::get('/login', fn() => view('auth.login'))->name('login');

Route::post('/login', function (\Illuminate\Http\Request $request) {
    $request->validate([
        'email'         => 'required|string',
        'password'      => 'required',
        'device_uuid'   => 'nullable|string',
        'role_category' => 'required|in:admin,karyawan',
    ]);

    $input = trim($request->email);
    $password = $request->password;

    // 1. Cari user berdasarkan email
    $user = \App\Models\User::withoutGlobalScopes()->where('email', $input)->first();

    // 2. Jika gagal, cari berdasarkan NIP
    if (!$user) {
        $karyawan = \App\Models\Karyawan::withoutGlobalScopes()->where('nip', $input)->first();
        if ($karyawan) {
            $user = \App\Models\User::withoutGlobalScopes()->find($karyawan->user_id);
        }
    }

    if (!$user) {
        return back()->withErrors(['email' => 'ID / NIP (' . $input . ') tidak terdaftar di database.'])->withInput();
    }

    if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return back()->withErrors(['email' => 'Kata sandi salah. Mohon periksa kembali.'])->withInput();
    }

    $adminRoles    = ['SUPER_ADMIN', 'admin-pusat', 'owner'];
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

    if (in_array($user->role, $adminRoles))    return redirect()->route('admin.dashboard');
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
Route::middleware(['auth', 'ensure-cashier-session'])->group(function () {
    Route::post('/pos', [\App\Http\Controllers\POSController::class, 'store'])->name('pos.store');
});

/* ============================================================
 *  ADMIN DASHBOARD & MASTER DATA
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,EDITOR_KONTEN,admin-pusat,owner'])
    ->prefix('admin-dashboard')
    ->name('admin.')
    ->group(function () {

        // ---- Dashboard ----
        Route::get('/', function () {
            $stats = [
                'total_produk'       => \App\Models\EcProduct::count(),
                'pesanan_baru'       => \App\Models\EcOrder::where('status', 'perlu_diproses')->count(),
                'stok_kritis'        => \App\Models\EcProduct::whereRaw('stock <= min_stock_alert')->count(),
                'pendapatan_hari_ini' => \App\Models\EcOrder::where('payment_status', 'paid')->whereDate('paid_at', today())->sum('total'),
                'chat_belum_dibaca'  => \App\Models\ChatSession::where('status', 'active')->count(),
            ];
            $latestOrders = \App\Models\EcOrder::latest()->limit(5)->get();
            return view('admin.dashboard', compact('stats', 'latestOrders'));
        })->name('dashboard');

        // ---- Produk ----
        Route::resource('products', AdminProductController::class);
        Route::patch('products/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
        Route::patch('products/{product}/price', [AdminProductController::class, 'updatePrice'])->name('products.update-price');
        Route::resource('categories', \App\Http\Controllers\Admin\ProductCategoryController::class)->except(['create', 'show', 'edit']);

        // ---- Pesanan ----
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
        Route::patch('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');

        // ---- CMS ----
        Route::get('cms/articles', [CmsController::class, 'articles'])->name('cms.articles');
        Route::get('cms/articles/create', [CmsController::class, 'articleCreate'])->name('cms.articles.create');
        Route::post('cms/articles', [CmsController::class, 'articleStore'])->name('cms.articles.store');
        Route::get('cms/articles/{article}/edit', [CmsController::class, 'articleEdit'])->name('cms.articles.edit');
        Route::put('cms/articles/{article}', [CmsController::class, 'articleUpdate'])->name('cms.articles.update');
        Route::delete('cms/articles/{article}', [CmsController::class, 'articleDestroy'])->name('cms.articles.destroy');

        Route::get('cms/testimonials', [CmsController::class, 'testimonials'])->name('cms.testimonials');
        Route::post('cms/testimonials', [CmsController::class, 'testimonialStore'])->name('cms.testimonials.store');
        Route::delete('cms/testimonials/{testimonial}', [CmsController::class, 'testimonialDestroy'])->name('cms.testimonials.destroy');

        Route::get('cms/settings', [CmsController::class, 'settings'])->name('cms.settings');
        Route::post('cms/settings', [CmsController::class, 'updateSettings'])->name('cms.settings.update');

        // ---- Live Chat Panel ----
        Route::get('chat', [ChatPanelController::class, 'index'])->name('chat.index');
        Route::get('chat/{session}', [ChatPanelController::class, 'show'])->name('chat.show');
        Route::get('chat/{session}/messages', [ChatPanelController::class, 'getMessages'])->name('chat.messages');
        Route::post('chat/{session}/reply', [ChatPanelController::class, 'reply'])->name('chat.reply');
        Route::post('chat/status', [ChatPanelController::class, 'updateStatus'])->name('chat.status');

        // ---- Pengguna & Audit ----
        Route::resource('users', UserController::class);
        Route::get('audit-trail', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-trail.index');
        Route::get('audit-trail/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-trail.export');

        // ---- HR & Analytics ----
        Route::get('hr', [\App\Http\Controllers\Admin\HRController::class, 'index'])->name('hr.index');
        Route::post('hr/karyawan', [\App\Http\Controllers\Admin\HRController::class, 'storeKaryawan'])->name('hr.karyawan.store');
        Route::get('hr/izin', [\App\Http\Controllers\Admin\HRController::class, 'daftarIzin'])->name('hr.izin.index');
        Route::get('hr/payroll', [\App\Http\Controllers\Admin\HRController::class, 'payroll'])->name('hr.payroll');
        Route::post('hr/payroll/{payroll}/update', [\App\Http\Controllers\Admin\HRController::class, 'updatePayrollStatus'])->name('hr.payroll.update');
        Route::get('hr/hitung-gaji/{bulan}', [\App\Http\Controllers\Admin\HRController::class, 'hitungGaji'])->name('hr.gaji.hitung');

        Route::get('analysis/overview', [\App\Http\Controllers\Admin\AnalysisController::class, 'overview'])->name('analysis.overview');
        Route::get('analysis/maintenance', [\App\Http\Controllers\Admin\AnalysisController::class, 'maintenanceAnalytics'])->name('analysis.maintenance');

        // ---- Operasional ----
        Route::get('operations', [OperationalController::class, 'index'])->name('operations.index');
        Route::post('operations/attendance', [OperationalController::class, 'attendance'])->name('operations.attendance');
        Route::post('operations/sop', [OperationalController::class, 'sopLog'])->name('operations.sop');
        Route::post('operations/complaint', [OperationalController::class, 'complaint'])->name('operations.complaint');
        Route::post('operations/stock', [OperationalController::class, 'stockRequest'])->name('operations.stock');

        // ---- Global Stats ----
        Route::get('global-stats', function () {
            return response()->json([
                'unread_chats'   => \App\Models\ChatMessage::where('sender_type', 'visitor')->where('is_read', false)->count(),
                'pending_orders' => \App\Models\EcOrder::where('status', 'perlu_diproses')->count(),
                'critical_stock' => \App\Models\EcProduct::whereRaw('stock <= min_stock_alert')->count(),
            ]);
        })->name('global-stats');
    });

/* ============================================================
 *  AKUNTANSI & LAPORAN
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,EDITOR_KONTEN,admin-pusat,owner'])
    ->prefix('admin-dashboard')
    ->group(function () {
        Route::resource('accounts', AccountController::class)->names([
            'index'  => 'accounts.index',
            'create' => 'accounts.create',
            'store'  => 'accounts.store',
        ]);
        Route::resource('transactions', TransactionController::class)->names([
            'index'  => 'transactions.index',
            'create' => 'transactions.create',
            'store'  => 'transactions.store',
        ]);
        Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
        Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
        Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export_csv');
        Route::get('reports/compliance', fn() => view('reports.compliance'))->name('reports.compliance');
        Route::get('reports/complaints-monitor', fn() => view('reports.complaint_monitor'))->name('reports.complaints_monitor');
        Route::get('reports/stock-monitor', fn() => view('reports.stock_monitor'))->name('reports.stock_monitor');
        Route::get('reconciliation', fn() => view('reconciliation.index'))->name('reconciliation.index');
    });

/* ============================================================
 *  BENGKEL MODULE — Admin
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,admin'])
    ->prefix('admin-dashboard/bengkel')
    ->name('bengkel.admin.')
    ->group(function () {
        Route::resource('vehicles', AdminVehicleCtrl::class);
        Route::resource('spare-parts', AdminSparePartCtrl::class);
        Route::post('spare-parts/{sparePart}/adjust-stock', [AdminSparePartCtrl::class, 'adjustStock'])->name('spare-parts.adjust-stock');
        Route::resource('services', AdminServiceCtrl::class);
        Route::resource('mechanics', AdminMechanicCtrl::class);
        Route::resource('bookings', AdminBookingCtrl::class);
    });

/* ============================================================
 *  BENGKEL MODULE — Mechanic
 * ============================================================ */
Route::middleware(['auth', 'role:mechanic'])
    ->prefix('mechanic')
    ->name('mechanic.')
    ->group(function () {
        Route::get('/dashboard', [MechanicDashboardCtrl::class, 'index'])->name('dashboard');
        Route::get('/work-orders', [MechanicWorkOrderCtrl::class, 'index'])->name('work-orders.index');
        Route::get('/work-orders/{workOrder}', [MechanicWorkOrderCtrl::class, 'show'])->name('work-orders.show');
        Route::post('/work-orders/{workOrder}/start', [MechanicWorkOrderCtrl::class, 'start'])->name('work-orders.start');
        Route::post('/work-orders/{workOrder}/complete', [MechanicWorkOrderCtrl::class, 'complete'])->name('work-orders.complete');
    });

/* ============================================================
 *  CUSTOMER MODULE
 * ============================================================ */
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardCtrl::class, 'index'])->name('dashboard');
        Route::resource('vehicles', CustomerVehicleCtrl::class)->except(['destroy']);
        Route::resource('bookings', CustomerBookingCtrl::class)->only(['index', 'create', 'store', 'show']);
        Route::post('bookings/{booking}/cancel', [CustomerBookingCtrl::class, 'cancel'])->name('bookings.cancel');
        Route::get('service-history', [CustomerServiceHistoryCtrl::class, 'index'])->name('service-history.index');
        Route::get('service-history/{serviceHistory}', [CustomerServiceHistoryCtrl::class, 'show'])->name('service-history.show');
        Route::get('membership-card', [MembershipCardController::class, 'show'])->name('membership-card');
    });

/* ============================================================
 *  LIVE CHAT: Public Visitor API
 * ============================================================ */
Route::prefix('chat')->name('chat.visitor.')->group(function () {
    Route::post('/init', function (\Illuminate\Http\Request $request) {
        $key     = $request->session()->getId();
        $session = \App\Models\ChatSession::firstOrCreate(
            ['session_key' => $key],
            ['visitor_name' => 'Guest_' . substr($key, 0, 6), 'status' => 'active', 'last_activity_at' => now()]
        );
        $messages = $session->messages()->orderBy('created_at')->get();
        $setting  = \App\Models\ChatSetting::first();
        return response()->json([
            'session_id' => $session->id,
            'messages'   => $messages,
            'is_online'  => $setting->is_online ?? true,
        ]);
    })->name('init');

    Route::post('/send', function (\Illuminate\Http\Request $request) {
        $request->validate(['message' => 'required|string|max:2000']);
        $key     = $request->session()->getId();
        $session = \App\Models\ChatSession::where('session_key', $key)->firstOrFail();
        $msg     = \App\Models\ChatMessage::create([
            'session_id'  => $session->id,
            'sender_type' => 'visitor',
            'message'     => $request->message,
        ]);
        $session->update(['last_activity_at' => now()]);
        return response()->json(['success' => true, 'message_id' => $msg->id]);
    })->name('send');

    Route::get('/messages', function (\Illuminate\Http\Request $request) {
        $key     = $request->session()->getId();
        $session = \App\Models\ChatSession::where('session_key', $key)->first();
        if (!$session) return response()->json(['messages' => []]);
        $messages = $session->messages()
            ->orderBy('created_at')
            ->when($request->filled('after_id'), fn($q) => $q->where('id', '>', $request->after_id))
            ->get();
        return response()->json(['messages' => $messages]);
    })->name('messages');
});

/* ============================================================
 *  LANGUAGE SWITCHER
 * ============================================================ */
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) session(['locale' => $locale]);
    return redirect()->back();
})->name('lang.switch');
