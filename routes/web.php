<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\StorefrontController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\CmsController;
use App\Http\Controllers\Admin\ChatPanelController;
// Bengkel Controllers
use App\Http\Controllers\Admin\Bengkel\VehicleController as AdminVehicleCtrl;
use App\Http\Controllers\Admin\Bengkel\SparePartController as AdminSparePartCtrl;
use App\Http\Controllers\Admin\Bengkel\ServiceController as AdminServiceCtrl;
use App\Http\Controllers\Admin\Bengkel\MechanicController as AdminMechanicCtrl;
use App\Http\Controllers\Admin\Bengkel\BookingController as AdminBookingCtrl;
use App\Http\Controllers\Admin\Bengkel\OilChangeAnalyticsController;
use App\Http\Controllers\Mechanic\WorkOrderController as MechanicWorkOrderCtrl;
use App\Http\Controllers\Mechanic\DashboardController as MechanicDashboardCtrl;
use App\Http\Controllers\Customer\VehicleController as CustomerVehicleCtrl;
use App\Http\Controllers\Customer\BengkelBookingController as CustomerBookingCtrl;
use App\Http\Controllers\Customer\ServiceHistoryController as CustomerServiceHistoryCtrl;
use App\Http\Controllers\Customer\MembershipCardController;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboardCtrl;
// Financial & Ops Controllers
use App\Http\Controllers\AccountController;
use App\Http\Controllers\TransactionController;
use App\Http\Controllers\ReportController;
use App\Http\Controllers\OperationalController;
use App\Http\Controllers\UserController;

/* ============================================================
 *  PUBLIC STOREFRONT (No Auth Required)
 * ============================================================ */
Route::get('/', [StorefrontController::class, 'home'])->name('home');
Route::get('/katalog', [StorefrontController::class, 'catalog'])->name('storefront.catalog');
Route::get('/produk/{slug}', [StorefrontController::class, 'productDetail'])->name('storefront.product');
Route::get('/berita', [StorefrontController::class, 'articles'])->name('storefront.articles');
Route::get('/berita/{slug}', [StorefrontController::class, 'article'])->name('storefront.article');

// Static Pages
Route::get('/syarat-ketentuan', [StorefrontController::class, 'terms'])->name('storefront.terms');
Route::get('/kebijakan-privasi', [StorefrontController::class, 'privacy'])->name('storefront.privacy');

// Cart API (Session-based, no auth needed)
Route::post('/cart/add', [CheckoutController::class, 'addToCart'])->name('cart.add');
Route::post('/cart/remove', [CheckoutController::class, 'removeFromCart'])->name('cart.remove');
Route::get('/cart/count', function() {
    $cart = session('cart', []);
    $count = array_sum(array_column($cart, 'quantity'));
    return response()->json(['count' => $count]);
})->name('cart.count');

// Checkout (Guest / Auth)
Route::get('/checkout', [CheckoutController::class, 'show'])->name('checkout.show');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/checkout/sukses/{orderNumber}', [CheckoutController::class, 'success'])->name('checkout.success');

/* ============================================================
 *  AUTH ROUTES
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
            $user = $karyawan->withoutGlobalScopes()->user;
        }
    }

    // 3. Verifikasi Keberadaan User
    if (!$user) {
        return back()->withErrors(['email' => 'Nomor WhatsApp / ID / NIP mendeteksi data yang tidak terdaftar di database pusat.'])->withInput();
    }

    // 4. Verifikasi Password
    if (!\Illuminate\Support\Facades\Hash::check($password, $user->password)) {
        return back()->withErrors(['email' => 'Kata Sandi (Password) yang Anda masukkan salah. Mohon periksa kembali.'])->withInput();
    }

    // 5. Jika password benar, verifikasi peran
    $adminRoles = ['SUPER_ADMIN', 'admin-pusat', 'owner'];
    $karyawanRoles = ['karyawan', 'kasir', 'kepala-cabang', 'wakil-kepala-cabang', 'mechanic'];

    // Category Validation
    if ($request->role_category === 'admin' && !in_array($user->role, $adminRoles)) {
        return back()->withErrors(['email' => 'Akun ' . $user->name . ' (' . $user->role . ') harus login melalui jalur Karyawan.'])->withInput();
    }
    if ($request->role_category === 'karyawan' && !in_array($user->role, $karyawanRoles)) {
        return back()->withErrors(['email' => 'Akun ' . $user->name . ' (' . $user->role . ') harus login melalui jalur Admin Pusat.'])->withInput();
    }

    \Illuminate\Support\Facades\Auth::login($user);
    $request->session()->put('tenant_id', $user->tenant_id); // Set tenant_id in session
    $request->session()->regenerate();

    // Device Binding Logic
    if ($request->filled('device_uuid')) {
        if (!$user->device_uuid) {
            $user->update(['device_uuid' => $request->device_uuid]);
        } elseif ($user->device_uuid !== $request->device_uuid) {
            \Illuminate\Support\Facades\Auth::logout();
            return back()->withErrors(['email' => 'Sistem mendeteksi akses dari perangkat ilegal. Hubungi IT Support.']);
        }
    }

    // Redirect based on role
    if (in_array($user->role, $adminRoles)) {
        return redirect()->route('admin.dashboard');
    }

    if (in_array($user->role, $karyawanRoles)) {
        return redirect()->route('karyawan.dashboard');
    }

    return $user->role === 'customer' ? redirect()->route('customer.dashboard') : redirect()->route('home');
});

// ---- Modul POS (Terminal Kasir) ----
Route::get('/pos', [\App\Http\Controllers\POSController::class, 'index'])->name('pos.index');

Route::middleware(['auth', 'ensure-cashier-session'])->group(function() {
    Route::post('/pos', [\App\Http\Controllers\POSController::class, 'store'])->name('pos.store');
});

Route::post('/pos/open-session', [\App\Http\Controllers\POSController::class, 'openSession'])->name('pos.open-session');

Route::post('/logout', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Auth::logout();
    $request->session()->invalidate();
    $request->session()->regenerateToken();
    return redirect()->route('login');
})->name('logout');

/* ============================================================
 *  ADMIN DASHBOARD (Auth + Role Required)
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,EDITOR_KONTEN'])
    ->prefix('admin-dashboard')
    ->name('admin.')
    ->group(function () {

    // Dashboard Utama
    Route::get('/', function () {
        $stats = [
            'total_produk'      => \App\Models\EcProduct::count(),
            'pesanan_baru'      => \App\Models\EcOrder::where('status', 'perlu_diproses')->count(),
            'stok_kritis'       => \App\Models\EcProduct::whereRaw('stock <= min_stock_alert')->count(),
            'pendapatan_hari_ini' => \App\Models\EcOrder::where('payment_status', 'paid')
                                        ->whereDate('paid_at', today())
                                        ->sum('total'),
            'chat_belum_dibaca'  => \App\Models\ChatSession::where('status', 'active')->count(),
        ];
        $latestOrders = \App\Models\EcOrder::latest()->limit(5)->get();
        return view('admin.dashboard', compact('stats', 'latestOrders'));
    })->name('dashboard');

    /* ============================================================
     *  KARYAWAN DASHBOARD (Auth + Karyawan Role Required)
     * ============================================================ */
    Route::middleware(['auth', 'role:karyawan,kasir,kepala-cabang,wakil-kepala-cabang,mechanic'])
        ->prefix('karyawan-dashboard')
        ->name('karyawan.')
        ->group(function () {
            
        Route::get('/', [\App\Http\Controllers\Karyawan\DashboardController::class, 'index'])->name('dashboard');
        Route::get('/payroll', [\App\Http\Controllers\Karyawan\DashboardController::class, 'payroll'])->name('payroll');
    });

    // ---- Manajemen Produk ----
    Route::resource('products', AdminProductController::class);
    Route::patch('products/{product}/stock', [AdminProductController::class, 'updateStock'])->name('products.update-stock');
    Route::patch('products/{product}/price', [AdminProductController::class, 'updatePrice'])->name('products.update-price');
    Route::resource('categories', \App\Http\Controllers\Admin\ProductCategoryController::class)->except(['create', 'show', 'edit']);

    // ---- Manajemen Pesanan ----
    Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.update-status');
    Route::patch('orders/{order}/verify-payment', [AdminOrderController::class, 'verifyPayment'])->name('orders.verify-payment');

    // ---- CMS: Artikel ----
    Route::get('cms/articles', [CmsController::class, 'articles'])->name('cms.articles');
    Route::get('cms/articles/create', [CmsController::class, 'articleCreate'])->name('cms.articles.create');
    Route::post('cms/articles', [CmsController::class, 'articleStore'])->name('cms.articles.store');
    Route::get('cms/articles/{article}/edit', [CmsController::class, 'articleEdit'])->name('cms.articles.edit');
    Route::put('cms/articles/{article}', [CmsController::class, 'articleUpdate'])->name('cms.articles.update');
    Route::delete('cms/articles/{article}', [CmsController::class, 'articleDestroy'])->name('cms.articles.destroy');

    // ---- CMS: Testimoni ----
    Route::get('cms/testimonials', [CmsController::class, 'testimonials'])->name('cms.testimonials');
    Route::post('cms/testimonials', [CmsController::class, 'testimonialStore'])->name('cms.testimonials.store');
    Route::delete('cms/testimonials/{testimonial}', [CmsController::class, 'testimonialDestroy'])->name('cms.testimonials.destroy');

    // ---- CMS: Pengaturan Web ----
    Route::get('cms/settings', [CmsController::class, 'settings'])->name('cms.settings');
    Route::post('cms/settings', [CmsController::class, 'updateSettings'])->name('cms.settings.update');

    // ---- Live Chat Panel ----
    Route::get('chat', [ChatPanelController::class, 'index'])->name('chat.index');
    Route::get('chat/{session}', [ChatPanelController::class, 'show'])->name('chat.show');
    Route::get('chat/{session}/messages', [ChatPanelController::class, 'getMessages'])->name('chat.messages');
    Route::post('chat/{session}/reply', [ChatPanelController::class, 'reply'])->name('chat.reply');
    Route::post('chat/status', [ChatPanelController::class, 'updateStatus'])->name('chat.status');

    // ---- Pengaturan & Keamanan ----
    Route::resource('users', UserController::class);
    Route::get('audit-trail', [\App\Http\Controllers\Admin\AuditLogController::class, 'index'])->name('audit-trail.index');
    Route::get('audit-trail/export', [\App\Http\Controllers\Admin\AuditLogController::class, 'export'])->name('audit-trail.export');

    Route::get('global-stats', function() {
        return response()->json([
            'unread_chats' => \App\Models\ChatMessage::where('sender_type', 'visitor')->where('is_read', false)->count(),
            'pending_orders' => \App\Models\EcOrder::where('status', 'perlu_diproses')->count(),
            'critical_stock' => \App\Models\EcProduct::whereRaw('stock <= min_stock_alert')->count(),
        ]);
    })->name('global-stats');
});

// ---- Rute Laporan & Akuntansi (Tanpa Prefix admin. agar sesuai dengan Controller/View) ----
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,EDITOR_KONTEN'])
    ->prefix('admin-dashboard')
    ->group(function () {
    Route::resource('accounts', AccountController::class)->names([
        'index' => 'accounts.index',
        'create' => 'accounts.create',
        'store' => 'accounts.store',
    ]);
    Route::resource('transactions', TransactionController::class)->names([
        'index' => 'transactions.index',
        'create' => 'transactions.create',
        'store' => 'transactions.store',
    ]);
    Route::get('reports', [ReportController::class, 'index'])->name('reports.index');
    Route::get('reports/profit-loss', [ReportController::class, 'profitLoss'])->name('reports.profit_loss');
    Route::get('reports/export-csv', [ReportController::class, 'exportCsv'])->name('reports.export_csv');
    Route::get('reports/compliance', function() { return view('reports.compliance'); })->name('reports.compliance');
    Route::get('reports/complaints-monitor', function() { return view('reports.complaint_monitor'); })->name('reports.complaints_monitor');
    Route::get('reports/stock-monitor', function() { return view('reports.stock_monitor'); })->name('reports.stock_monitor');
    Route::get('reconciliation', function() { return view('reconciliation.index'); })->name('reconciliation.index');

    // ---- Manajemen Operasional ----
    Route::get('operations', [OperationalController::class, 'index'])->name('operations.index');
    Route::post('operations/attendance', [OperationalController::class, 'attendance'])->name('operations.attendance');
    Route::post('operations/sop', [OperationalController::class, 'sopLog'])->name('operations.sop');
    Route::post('operations/complaint', [OperationalController::class, 'complaint'])->name('operations.complaint');
    Route::post('operations/stock', [OperationalController::class, 'stockRequest'])->name('operations.stock');
});

/* ============================================================
 *  LIVE CHAT: Public Visitor API
 * ============================================================ */
Route::prefix('chat')->name('chat.visitor.')->group(function () {
    Route::post('/init', function (\Illuminate\Http\Request $request) {
        $key = $request->session()->getId();
        $session = \App\Models\ChatSession::firstOrCreate(
            ['session_key' => $key],
            [
                'visitor_name' => 'Guest_' . substr($key, 0, 6),
                'status' => 'active', 
                'last_activity_at' => now()
            ]
        );
        $messages = $session->messages()->orderBy('created_at')->get();
        $setting = \App\Models\ChatSetting::first();
        return response()->json([
            'session_id' => $session->id,
            'messages'   => $messages,
            'is_online'  => $setting->is_online ?? true,
        ]);
    })->name('init');

    Route::post('/send', function (\Illuminate\Http\Request $request) {
        $request->validate(['message' => 'required|string|max:2000']);
        $key = $request->session()->getId();
        $session = \App\Models\ChatSession::where('session_key', $key)->firstOrFail();
        $msg = \App\Models\ChatMessage::create([
            'session_id'  => $session->id,
            'sender_type' => 'visitor',
            'message'     => $request->message,
        ]);
        $session->update(['last_activity_at' => now()]);
        return response()->json(['success' => true, 'message_id' => $msg->id]);
    })->name('send');

    Route::get('/messages', function (\Illuminate\Http\Request $request) {
        $key = $request->session()->getId();
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
 *  MODUL BENGKEL — Admin Routes
 * ============================================================ */
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL,admin'])
    ->prefix('admin-dashboard/bengkel')
    ->name('bengkel.admin.')
    ->group(function () {
        // Manajemen Kendaraan
        Route::resource('vehicles', AdminVehicleCtrl::class);
        // Manajemen Suku Cadang
        Route::resource('spare-parts', AdminSparePartCtrl::class);
        Route::post('spare-parts/{sparePart}/adjust-stock', [AdminSparePartCtrl::class, 'adjustStock'])
            ->name('spare-parts.adjust-stock');
        // Katalog Servis
        Route::resource('services', AdminServiceCtrl::class);
        // Manajemen Mekanik
        Route::resource('mechanics', AdminMechanicCtrl::class);
        // Manajemen Booking Bengkel
        Route::resource('bookings', AdminBookingCtrl::class);
        // Analitik Ganti Oli
        Route::get('analytics/oil-change', [OilChangeAnalyticsController::class, 'index'])
            ->name('analytics.oil-change');
    });

/* ============================================================
 *  MODUL BENGKEL — Mechanic Routes
 * ============================================================ */
Route::middleware(['auth', 'role:mechanic'])
    ->prefix('mechanic')
    ->name('mechanic.')
    ->group(function () {
        Route::get('/dashboard', [MechanicDashboardCtrl::class, 'index'])->name('dashboard');
        Route::get('/work-orders', [MechanicWorkOrderCtrl::class, 'index'])->name('work-orders.index');
        Route::get('/work-orders/{workOrder}', [MechanicWorkOrderCtrl::class, 'show'])->name('work-orders.show');
        Route::post('/work-orders/{workOrder}/start', [MechanicWorkOrderCtrl::class, 'start'])->name('work-orders.start');
        Route::post('/work-orders/{workOrder}/progress', [MechanicWorkOrderCtrl::class, 'addProgress'])->name('work-orders.add-progress');
        Route::post('/work-orders/{workOrder}/items', [MechanicWorkOrderCtrl::class, 'addItem'])->name('work-orders.add-item');
        Route::post('/work-orders/{workOrder}/complete', [MechanicWorkOrderCtrl::class, 'complete'])->name('work-orders.complete');
    });

/* ============================================================
 *  MODUL BENGKEL — Customer Routes
 * ============================================================ */
Route::middleware(['auth', 'role:customer'])
    ->prefix('customer')
    ->name('customer.')
    ->group(function () {
        Route::get('/dashboard', [CustomerDashboardCtrl::class, 'index'])->name('dashboard');
        // Kendaraan Pelanggan
        Route::resource('vehicles', CustomerVehicleCtrl::class)->except(['destroy']);
        // Booking Servis
        Route::resource('bookings', CustomerBookingCtrl::class)->only(['index', 'create', 'store', 'show']);
        Route::post('bookings/{booking}/cancel', [CustomerBookingCtrl::class, 'cancel'])->name('bookings.cancel');
        // Riwayat Servis
        Route::get('service-history', [CustomerServiceHistoryCtrl::class, 'index'])->name('service-history.index');
        Route::get('service-history/{serviceHistory}', [CustomerServiceHistoryCtrl::class, 'show'])->name('service-history.show');
        // Kartu Member
        Route::get('membership-card', [MembershipCardController::class, 'show'])->name('membership-card');
    });

// ---- Sistem HR & Analisis Lanjutan ----
Route::middleware(['auth', 'role:SUPER_ADMIN,ADMIN_OPERASIONAL'])
    ->prefix('admin-dashboard')
    ->group(function () {
    Route::get('hr', [\App\Http\Controllers\Admin\HRController::class, 'index'])->name('hr.index');
    Route::post('hr/karyawan', [\App\Http\Controllers\Admin\HRController::class, 'storeKaryawan'])->name('hr.karyawan.store');
    Route::get('hr/izin', [\App\Http\Controllers\Admin\HRController::class, 'daftarIzin'])->name('hr.izin.index');
    Route::get('hr/payroll', [\App\Http\Controllers\Admin\HRController::class, 'payroll'])->name('hr.payroll');
    Route::post('hr/payroll/{payroll}/update', [\App\Http\Controllers\Admin\HRController::class, 'updatePayrollStatus'])->name('hr.payroll.update');
    Route::get('hr/hitung-gaji/{bulan}', [\App\Http\Controllers\Admin\HRController::class, 'hitungGaji'])->name('hr.gaji.hitung');

    Route::get('analysis/overview', [\App\Http\Controllers\Admin\AnalysisController::class, 'overview'])->name('analysis.overview');
    Route::get('analysis/maintenance', [\App\Http\Controllers\Admin\AnalysisController::class, 'maintenanceAnalytics'])->name('analysis.maintenance');
});

/* ============================================================
 *  LANGUAGE SWITCHER
 * ============================================================ */
Route::get('/lang/{locale}', function ($locale) {
    if (in_array($locale, ['en', 'id'])) {
        session(['locale' => $locale]);
    }
    return redirect()->back();
})->name('lang.switch');
