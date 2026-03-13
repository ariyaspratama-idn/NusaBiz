<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin') — NusaBiz</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #a5b4fc;
            --primary-dark: #4338ca;
            --secondary: #22d3ee;
            --accent: #f59e0b;
            --success: #10b981;
            --danger: #f43f5e;
            --warning: #fb923c;

            --sidebar-bg: #0b0f1a;
            --sidebar-hover: rgba(255,255,255,0.05);
            --sidebar-active: rgba(99, 102, 241, 0.18);
            --sidebar-text: #8892a4;

            --bg-main: #0d1117;
            --bg-card: #161b27;
            --bg-card-2: #1c2333;
            --text-main: #e2e8f0;
            --text-muted: #7c8899;
            --border: rgba(255,255,255,0.07);

            --radius-sm: 8px;
            --radius-md: 12px;
            --radius-lg: 20px;
            --shadow-sm: 0 1px 3px rgba(0,0,0,0.4);
            --shadow: 0 4px 16px rgba(0,0,0,0.35);
            --shadow-lg: 0 10px 30px rgba(0,0,0,0.5);
            --transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        /* Global Loading Screen */
        #global-loader {
            position: fixed;
            inset: 0;
            background: #0b0f1a;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s;
        }

        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            text-align: center;
            animation: loader-pulse 2s infinite ease-in-out;
        }

        .loader-logo {
            width: 120px;
            height: auto;
            margin-bottom: 24px;
            filter: drop-shadow(0 0 20px rgba(99, 102, 241, 0.4));
        }

        .loader-bar {
            width: 200px;
            height: 3px;
            background: rgba(255,255,255,0.05);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .loader-progress {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            animation: loader-slide 1.5s infinite linear;
        }

        @keyframes loader-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        @keyframes loader-slide {
            0% { left: -100%; }
            100% { left: 100%; }
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }
        
        body { 
            font-family: 'Inter', sans-serif; 
            background: var(--bg-main); 
            color: var(--text-main); 
            display: flex; 
            min-height: 100vh;
            overflow-x: hidden;
        }

        /* Sidebar Upgrade */
        #sidebar {
            width: 280px; 
            background: var(--sidebar-bg);
            display: flex; 
            flex-direction: column; 
            position: fixed; 
            height: 100vh;
            z-index: 1000;
            transition: var(--transition);
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-logo {
            padding: 24px;
            display: flex;
            flex-direction: column;
            align-items: center;
            border-bottom: 1px solid rgba(255,255,255,0.03);
            margin-bottom: 16px;
        }

        .logo-wrapper {
            width: 100%;
            padding: 0 10px;
        }

        .logo-img {
            width: 100%;
            height: auto;
            max-height: 50px;
            object-fit: contain;
            filter: drop-shadow(0 4px 10px rgba(0,0,0,0.3));
            transition: var(--transition);
        }

        .logo-img:hover {
            transform: scale(1.02);
        }

        .sidebar-nav { 
            flex: 1; 
            padding: 10px 16px; 
            overflow-y: auto;
            scrollbar-width: none;
        }

        .nav-section-title {
            font-size: 11px;
            font-weight: 700;
            color: #475569;
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 24px 16px 8px;
        }

        .nav-item {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--sidebar-text);
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
            border-radius: 12px;
            margin-bottom: 4px;
            transition: var(--transition);
        }

        .nav-item i { font-size: 18px; width: 24px; text-align: center; opacity: 0.7; }

        .nav-item:hover {
            background: var(--sidebar-hover);
            color: #fff;
            transform: translateX(4px);
        }

        .nav-item.active {
            background: var(--sidebar-active);
            color: var(--primary-light);
            font-weight: 600;
            box-shadow: inset 4px 0 0 var(--primary);
        }

        .nav-item.active i { opacity: 1; color: var(--primary); }

        /* Main Content Glassy Header */
        #main { 
            margin-left: 280px; 
            flex: 1; 
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        header.topbar {
            background: rgba(13, 17, 23, 0.85);
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border-bottom: 1px solid var(--border);
            height: 72px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .topbar-title { 
            font-size: 22px; 
            font-weight: 800; 
            color: var(--text-main);
            letter-spacing: -0.5px;
        }

        /* Stats Cards Upgrade */
        .stat-card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            padding: 24px;
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border);
            transition: var(--transition);
            position: relative;
            overflow: hidden;
        }

        .stat-card:hover {
            transform: translateY(-5px);
            box-shadow: var(--shadow-lg);
            border-color: var(--primary-light);
        }

        .stat-icon {
            width: 56px;
            height: 56px;
            border-radius: 16px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 24px;
            margin-bottom: 16px;
        }

        /* Buttons Premium */
        .btn {
            padding: 10px 20px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 14px;
            transition: var(--transition);
            display: inline-flex;
            align-items: center;
            gap: 8px;
            border: none;
            cursor: pointer;
        }

        .btn-primary {
            background: linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%);
            color: white;
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.25);
        }

        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(79, 70, 229, 0.35);
        }

        .btn-outline {
            background: transparent;
            border: 1.5px solid rgba(255,255,255,0.12);
            color: var(--text-main);
        }

        .btn-outline:hover {
            border-color: var(--primary);
            color: var(--primary-light);
            background: rgba(99, 102, 241, 0.08);
        }

        /* Tables Modern */
        .card {
            background: var(--bg-card);
            border-radius: var(--radius-lg);
            border: 1px solid var(--border);
            box-shadow: var(--shadow);
            margin-bottom: 24px;
        }

        .card-header {
            padding: 24px;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        table { width: 100%; border-collapse: separate; border-spacing: 0; }
        thead th { 
            background: rgba(255,255,255,0.03);
            padding: 14px 24px;
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            border-bottom: 1px solid var(--border);
        }

        tbody td { 
            padding: 18px 24px; 
            border-bottom: 1px solid var(--border);
            color: var(--text-main);
            vertical-align: middle;
        }

        tbody tr:hover { background: rgba(255,255,255,0.025); }

        /* Badge System */
        .badge {
            display: inline-flex; align-items: center; gap: 5px;
            padding: 4px 12px; border-radius: 50px;
            font-size: 11px; font-weight: 700; letter-spacing: 0.3px;
        }
        .badge-success  { background: rgba(16,185,129,0.15); color: #34d399; }
        .badge-danger   { background: rgba(244,63,94,0.15);  color: #fb7185; }
        .badge-warning  { background: rgba(251,146,60,0.15); color: #fbbf24; }
        .badge-info     { background: rgba(34,211,238,0.15); color: #67e8f9; }
        .badge-purple   { background: rgba(168,85,247,0.15); color: #c084fc; }
        .badge-gray     { background: rgba(148,163,184,0.1); color: #94a3b8; }

        /* Alert system */
        .alert {
            padding: 14px 20px; border-radius: var(--radius-md);
            margin-bottom: 20px; display: flex; align-items: center; gap: 10px;
            font-size: 14px; font-weight: 500;
        }
        .alert-success { background: rgba(16,185,129,0.12); color: #34d399; border: 1px solid rgba(16,185,129,0.2); }
        .alert-error   { background: rgba(244,63,94,0.12);  color: #fb7185; border: 1px solid rgba(244,63,94,0.2); }

        /* Page content padding */
        .page-content { padding: 32px; }

        /* Scrollbar dark */
        ::-webkit-scrollbar { width: 6px; height: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }
        ::-webkit-scrollbar-thumb:hover { background: rgba(255,255,255,0.2); }

        @media (max-width: 1024px) {
            #sidebar { width: 80px; }
            .sidebar-logo .logo-text, .nav-item span, .nav-section-title { display: none; }
            #main { margin-left: 80px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Global Loading Screen -->
    <div id="global-loader">
        <div class="loader-content">
            <img src="{{ asset('img/logo-nusabiz.png') }}?v=1.1" alt="Memuat..." class="loader-logo" onerror="this.src='{{ asset('img/loading-icon.png') }}'">
            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>
        </div>
    </div>

    <!-- Sidebar -->
    <aside id="sidebar">
        <a href="{{ route('admin.dashboard') }}" class="sidebar-logo">
            <div class="logo-wrapper">
                <img src="{{ asset('img/logo-nusabiz.png') }}?v=1.1" alt="NusaBiz Logo" class="logo-img" onerror="this.src='{{ asset('img/loading-icon.png') }}'">
            </div>
        </a>
        <nav class="sidebar-nav">
            @php
                $role = auth()->user()->role ?? '';
                $isAdmin = in_array($role, ['admin-pusat', 'owner', 'SUPER_ADMIN', 'ADMIN_OPERASIONAL', 'EDITOR_KONTEN']);
            @endphp

            @if($isAdmin)
                <div class="nav-section-title">Ringkasan Bisnis</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-item {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house-chimney"></i> <span>Dashboard Utama</span>
            </a>

            <div class="nav-section-title">E-Commerce</div>
            <a href="{{ route('admin.products.index') }}" class="nav-item {{ request()->routeIs('admin.products*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-archive"></i> <span>Katalog Produk</span>
            </a>
            <a href="{{ route('admin.categories.index') }}" class="nav-item {{ request()->routeIs('admin.categories*') ? 'active' : '' }}">
                <i class="fa-solid fa-tags"></i> <span>Kategori Produk</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-item {{ request()->routeIs('admin.orders*') ? 'active' : '' }}">
                <i class="fa-solid fa-cart-shopping"></i> <span>Daftar Pesanan</span>
                @php $pendingOrders = \App\Models\EcOrder::where('status', 'perlu_diproses')->count(); @endphp
                @if($pendingOrders > 0) <span class="badge" style="background:var(--danger); color:white; font-size:10px; padding:2px 8px; border-radius:50px; margin-left:auto;">{{ $pendingOrders }}</span> @endif
            </a>

            <div class="nav-section-title">Konten & CMS</div>
            <a href="{{ route('admin.cms.articles') }}" class="nav-item {{ request()->routeIs('admin.cms.articles*') ? 'active' : '' }}">
                <i class="fa-solid fa-feather-pointed"></i> <span>Artikel & Berita</span>
            </a>
            <a href="{{ route('admin.cms.testimonials') }}" class="nav-item {{ request()->routeIs('admin.cms.testimonials*') ? 'active' : '' }}">
                <i class="fa-solid fa-quote-left"></i> <span>Testimoni Pelanggan</span>
            </a>
            <a href="{{ route('admin.cms.settings') }}" class="nav-item {{ request()->routeIs('admin.cms.settings*') ? 'active' : '' }}">
                <i class="fa-solid fa-gear"></i> <span>Pengaturan Website</span>
            </a>

            <div class="nav-section-title">Laporan & Akuntansi</div>
            <a href="{{ route('accounts.index') }}" class="nav-item {{ request()->routeIs('accounts*') ? 'active' : '' }}">
                <i class="fa-solid fa-receipt"></i> <span>Daftar Akun (COA)</span>
            </a>
            <a href="{{ route('transactions.index') }}" class="nav-item {{ request()->routeIs('transactions*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-bill-transfer"></i> <span>Jurnal Transaksi</span>
            </a>
            <a href="{{ route('reports.index') }}" class="nav-item {{ request()->routeIs('reports*') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-pie"></i> <span>Analisis Laba Rugi</span>
            </a>

            <div class="nav-section-title">Manajemen Internal</div>
            <a href="{{ route('admin.operations.index') }}" class="nav-item {{ request()->routeIs('admin.operations*') ? 'active' : '' }}">
                <i class="fa-solid fa-users-gear"></i> <span>Aktivitas Operasional</span>
            </a>
            <a href="{{ route('admin.hr.index') }}" class="nav-item {{ request()->routeIs('admin.hr.index') ? 'active' : '' }}">
                <i class="fa-solid fa-user-group"></i> <span>Daftar Karyawan</span>
            </a>
            <a href="{{ route('admin.hr.izin.index') }}" class="nav-item {{ request()->routeIs('admin.hr.izin*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-check"></i> <span>Pengajuan Izin</span>
            </a>
            <a href="{{ route('admin.hr.payroll') }}" class="nav-item {{ request()->routeIs('admin.hr.payroll*') ? 'active' : '' }}">
                <i class="fa-solid fa-money-check-dollar"></i> <span>Manajemen Gaji</span>
            </a>

            <div class="nav-section-title">Modul Bengkel</div>
            <a href="{{ route('bengkel.admin.bookings.index') }}" class="nav-item {{ request()->routeIs('bengkel.admin*') ? 'active' : '' }}">
                <i class="fa-solid fa-gauge-high"></i> <span>Dashboard Bengkel</span>
            </a>
            <a href="{{ route('bengkel.admin.bookings.index') }}" class="nav-item {{ request()->routeIs('bengkel.admin.bookings*') ? 'active' : '' }}">
                <i class="fa-solid fa-calendar-plus"></i> <span style="font-size: 13px;">Booking Servis</span>
            </a>
            <a href="{{ route('bengkel.admin.mechanics.index') }}" class="nav-item {{ request()->routeIs('bengkel.admin.mechanics*') ? 'active' : '' }}">
                <i class="fa-solid fa-wrench"></i> <span style="font-size: 13px;">Manajemen Mekanik</span>
            </a>
            <a href="{{ route('bengkel.admin.spare-parts.index') }}" class="nav-item {{ request()->routeIs('bengkel.admin.spare-parts*') ? 'active' : '' }}">
                <i class="fa-solid fa-box-open"></i> <span style="font-size: 13px;">Stok Onderdil</span>
            </a>

            <div class="nav-section-title">Analisis Bisnis</div>
            <a href="{{ route('admin.analysis.overview') }}" class="nav-item {{ request()->routeIs('admin.analysis.overview') ? 'active' : '' }}">
                <i class="fa-solid fa-chart-line"></i> <span>Analisis Keuangan</span>
            </a>
            <a href="{{ route('admin.analysis.maintenance') }}" class="nav-item {{ request()->routeIs('admin.analysis.maintenance') ? 'active' : '' }}">
                <i class="fa-solid fa-screwdriver-wrench"></i> <span>Analisis Pemeliharaan</span>
            </a>

            <div class="nav-section-title">Konfigurasi Sistem</div>
            <a href="{{ route('admin.users.index') }}" class="nav-item {{ request()->routeIs('admin.users*') ? 'active' : '' }}">
                <i class="fa-solid fa-user-shield"></i> <span>Manajemen Staff</span>
            </a>
            <a href="{{ route('admin.audit-trail.index') }}" class="nav-item {{ request()->routeIs('admin.audit-trail*') ? 'active' : '' }}">
                <i class="fa-solid fa-clock-rotate-left"></i> <span>Audit Trail</span>
            </a>

            <div class="nav-section-title">Customer Service</div>
            <a href="{{ route('admin.chat.index') }}" class="nav-item {{ request()->routeIs('admin.chat*') ? 'active' : '' }}">
                <i class="fa-solid fa-comment-dots"></i> <span>Live Chat</span>
                @php $unreadChats = \App\Models\ChatMessage::where('sender_type', 'visitor')->where('is_read', false)->count(); @endphp
                @if($unreadChats > 0) <span id="sidebar-chat-badge" class="badge" style="background:var(--primary); color:white; font-size:10px; padding:2px 8px; border-radius:50px; margin-left:auto;">{{ $unreadChats }}</span> @endif
            </a>
            @else
            <div class="nav-section-title">Menu Utama</div>
            <a href="{{ route('karyawan.dashboard') }}" class="nav-item {{ request()->routeIs('karyawan.dashboard') ? 'active' : '' }}">
                <i class="fa-solid fa-house-user"></i> <span>Terminal Absensi</span>
            </a>
            <a href="{{ route('karyawan.payroll') }}" class="nav-item {{ request()->routeIs('karyawan.payroll') ? 'active' : '' }}">
                <i class="fa-solid fa-file-invoice-dollar"></i> <span>Riwayat Gaji</span>
            </a>
            @endif
        </nav>
        <div class="sidebar-footer" style="padding: 10px; display:flex; justify-content:center; padding-bottom: 20px;">
            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" style="background:rgba(255,255,255,0.05); border:1px solid rgba(255,255,255,0.1); width:48px; height:48px; border-radius:14px; color:var(--sidebar-text); cursor:pointer; font-size:18px; transition:all 0.2s; display:flex; justify-content:center; align-items:center;" onmouseover="this.style.color='#ef4444'; this.style.background='rgba(239, 68, 68, 0.1)'; this.style.borderColor='rgba(239, 68, 68, 0.2)'" onmouseout="this.style.color='var(--sidebar-text)'; this.style.background='rgba(255,255,255,0.05)'; this.style.borderColor='rgba(255,255,255,0.1)'" title="Logout">
                    <i class="fa-solid fa-power-off"></i>
                </button>
            </form>
        </div>
    </aside>

    <!-- Main Content -->
    <main id="main">
        <header class="topbar">
            <div class="topbar-title">@yield('page_title', 'Dashboard')</div>
            <div class="topbar-actions">
                <div class="notification-wrapper" style="position:relative;">
                    <button id="notiBtn" class="icon-btn" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);background:white;color:var(--text-muted);cursor:pointer;position:relative;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
                        <i class="fa-regular fa-bell"></i>
                        <span class="dot" style="position:absolute;top:10px;right:10px;width:8px;height:8px;background:var(--danger);border-radius:50%;border:2px solid white;"></span>
                    </button>
                    
                    <div id="notiDropdown" style="display:none; position:absolute; top:50px; right:0; width:320px; background:white; border-radius:20px; border:1px solid var(--border); box-shadow:var(--shadow-lg); z-index:1000; overflow:hidden; animation: fadeInSlide 0.3s ease;">
                        <div style="padding:16px 20px; border-bottom:1px solid var(--border); display:flex; justify-content:space-between; align-items:center; background:#fcfcfd;">
                            <span style="font-weight:800; font-size:14px; color:var(--text-main);">Notifikasi</span>
                            <span style="font-size:11px; color:var(--primary); font-weight:700; cursor:pointer;">Tandai Terbaca</span>
                        </div>
                        <div style="max-height:350px; overflow-y:auto; padding:10px;">
                            <div onclick="window.location.href='{{ route('admin.orders.index') }}'" style="padding:12px; border-radius:12px; display:flex; gap:12px; transition:var(--transition); cursor:pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <div style="width:36px;height:36px;background:#f0f9ff;color:#0284c7;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-cart-shopping"></i></div>
                                <div>
                                    <div style="font-size:13px; font-weight:700; color:var(--text-main);">Pesanan Baru #1029</div>
                                    <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">Seorang pelanggan baru saja memesan item.</div>
                                    <div style="font-size:10px; color:var(--primary); font-weight:600; margin-top:4px;">2 menit yang lalu</div>
                                </div>
                            </div>
                            <div onclick="window.location.href='{{ route('admin.chat.index') }}'" style="padding:12px; border-radius:12px; display:flex; gap:12px; transition:var(--transition); cursor:pointer;" onmouseover="this.style.background='#f8fafc'" onmouseout="this.style.background='transparent'">
                                <div style="width:36px;height:36px;background:#f5f3ff;color:#7c3aed;border-radius:10px;display:flex;align-items:center;justify-content:center;flex-shrink:0;"><i class="fa-solid fa-comment-dots"></i></div>
                                <div>
                                    <div style="font-size:13px; font-weight:700; color:var(--text-main);">Chat Masuk Baru</div>
                                    <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">Pengunjung bernama "Adam" sedang menunggu balasan.</div>
                                    <div style="font-size:10px; color:var(--primary); font-weight:600; margin-top:4px;">15 menit yang lalu</div>
                                </div>
                            </div>
                        </div>
                        <a href="{{ route('admin.orders.index') }}" style="display:block; text-align:center; padding:12px; font-size:12px; font-weight:700; color:var(--text-muted); text-decoration:none; background:#f8fafc; border-top:1px solid var(--border);">Lihat Semua Notifikasi</a>
                    </div>
                </div>
            </div>
        </header>

        <style>
            @keyframes fadeInSlide {
                from { opacity: 0; transform: translateY(10px); }
                to { opacity: 1; transform: translateY(0); }
            }
        </style>

        <script>
            window.addEventListener('load', function() {
                const loader = document.getElementById('global-loader');
                setTimeout(() => {
                    loader.classList.add('hidden');
                }, 500);
            });

            document.addEventListener('DOMContentLoaded', function() {
                const btn = document.getElementById('notiBtn');
                const dropdown = document.getElementById('notiDropdown');
                
                btn.addEventListener('click', function(e) {
                    e.stopPropagation();
                    dropdown.style.display = dropdown.style.display === 'none' ? 'block' : 'none';
                });
                
                document.addEventListener('click', function() {
                    dropdown.style.display = 'none';
                });
                
                dropdown.addEventListener('click', function(e) {
                    e.stopPropagation();
                });

                // Global Stats Polling
                function updateGlobalStats() {
                    fetch('{{ route("admin.global-stats") }}')
                        .then(res => res.json())
                        .then(data => {
                            // Update Chat Badge in Sidebar
                            const chatBadge = document.getElementById('sidebar-chat-badge');
                            if(data.unread_chats > 0) {
                                if(chatBadge) {
                                    chatBadge.textContent = data.unread_chats;
                                    chatBadge.style.display = 'block';
                                }
                            } else if(chatBadge) {
                                chatBadge.style.display = 'none';
                            }

                            // Update Bell Icon Dot
                            const bellDot = document.querySelector('#notiBtn .dot');
                            if(data.unread_chats > 0 || data.pending_orders > 0 || data.critical_stock > 0) {
                                if(bellDot) bellDot.style.display = 'block';
                            } else if(bellDot) {
                                bellDot.style.display = 'none';
                            }
                        }).catch(()=>{});
                }
                setInterval(updateGlobalStats, 5000); // Every 5 seconds
                updateGlobalStats(); // Initial
            });
        </script>

        <div class="page-content">
            @if(session('success'))
                <div class="alert alert-success"><i class="fa-solid fa-circle-check"></i> {{ session('success') }}</div>
            @endif
            @if(session('error'))
                <div class="alert alert-error"><i class="fa-solid fa-circle-xmark"></i> {{ session('error') }}</div>
            @endif
            @yield('content')
        </div>
    </main>
    @stack('scripts')
</body>
</html>
