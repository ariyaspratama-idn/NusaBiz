<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') — OmniBiz OS</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        :root {
            --primary: #4f46e5;
            --primary-glow: rgba(79, 70, 229, 0.4);
            --secondary: #0891b2;
            --bg-dark: #0f172a;
            --sidebar-bg: #1e293b;
            --card-bg: #1e293b;
            --text-main: #f8fafc;
            --text-muted: #94a3b8;
            --border-color: rgba(255, 255, 255, 0.1);
            --sidebar-width: 280px;
        }

        body {
            font-family: 'Outfit', sans-serif;
            background-color: var(--bg-dark);
            color: var(--text-main);
            margin: 0;
            display: flex;
            min-height: 100vh;
        }

        #sidebar {
            width: var(--sidebar-width);
            background-color: var(--sidebar-bg);
            border-right: 1px solid var(--border-color);
            display: flex;
            flex-direction: column;
            position: fixed;
            height: 100vh;
            z-index: 1000;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        }

        .sidebar-header {
            padding: 30px 24px;
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .logo-box {
            width: 40px;
            height: 40px;
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: 0 4px 12px var(--primary-glow);
        }

        .sidebar-nav {
            flex: 1;
            padding: 0 16px 24px;
            overflow-y: auto;
        }

        .nav-label {
            font-size: 11px;
            font-weight: 700;
            color: var(--text-muted);
            text-transform: uppercase;
            letter-spacing: 1.5px;
            padding: 24px 16px 8px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 16px;
            color: var(--text-muted);
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.2s;
            font-weight: 500;
        }

        .nav-link i { font-size: 18px; width: 24px; text-align: center; }
        .nav-link:hover, .nav-link.active {
            background-color: rgba(255, 255, 255, 0.05);
            color: #fff;
        }
        .nav-link.active {
            background: linear-gradient(90deg, var(--primary-glow), transparent);
            border-left: 4px solid var(--primary);
            color: #fff;
        }

        #main-content {
            margin-left: var(--sidebar-width);
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .top-navbar {
            height: 80px;
            padding: 0 32px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            background: rgba(15, 23, 42, 0.8);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 900;
        }

        .card-premium {
            background: var(--card-bg);
            border: 1px solid var(--border-color);
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.2);
            transition: transform 0.3s;
        }

        .card-premium:hover {
            transform: translateY(-5px);
            border-color: var(--primary);
        }

        .btn-premium {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            border: none;
            border-radius: 12px;
            padding: 12px 24px;
            color: white;
            font-weight: 600;
            transition: all 0.3s;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-premium:hover {
            box-shadow: 0 8px 16px var(--primary-glow);
            transform: scale(1.02);
            color: white;
        }

        .badge-premium {
            padding: 6px 12px;
            border-radius: 50px;
            font-size: 11px;
            font-weight: 700;
            text-transform: uppercase;
        }

        .table-premium {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0 8px;
        }

        .table-premium tr { background: rgba(255,255,255,0.02); }
        .table-premium td, .table-premium th { padding: 16px 24px; }
        .table-premium tr td:first-child { border-radius: 12px 0 0 12px; }
        .table-premium tr td:last-child { border-radius: 0 12px 12px 0; }

        @media (max-width: 1024px) {
            #sidebar { width: 80px; }
            .sidebar-header h2, .nav-link span, .nav-label { display: none; }
            #main-content { margin-left: 80px; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <aside id="sidebar">
        <div class="sidebar-header">
            <div class="logo-box">
                <i data-lucide="zap" class="text-white"></i>
            </div>
            <h2 class="m-0 fw-bold h4">OmniBiz <span class="text-primary">OS</span></h2>
        </div>

        <nav class="sidebar-nav">
            <div class="nav-label">Main Menu</div>
            <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> <span>Dashboard</span>
            </a>

            @if(in_array(auth()->user()->role, ['SUPER_ADMIN', 'owner', 'admin-pusat']))
                <div class="nav-label">Bisnis & Finance</div>
                <a href="{{ route('accounts.index') }}" class="nav-link {{ request()->is('admin-dashboard/accounts*') ? 'active' : '' }}">
                    <i data-lucide="book-key"></i> <span>Chart of Accounts</span>
                </a>
                <a href="{{ route('transactions.index') }}" class="nav-link {{ request()->is('admin-dashboard/transactions*') ? 'active' : '' }}">
                    <i data-lucide="refresh-cw"></i> <span>Transaksi</span>
                </a>
                <a href="{{ route('reports.index') }}" class="nav-link {{ request()->is('admin-dashboard/reports*') ? 'active' : '' }}">
                    <i data-lucide="bar-chart-3"></i> <span>Laporan Keuangan</span>
                </a>
            @endif

            <div class="nav-label">Operasional</div>
            @if(in_array(auth()->user()->role, ['mechanic', 'karyawan', 'admin-cabang', 'SUPER_ADMIN']))
                <a href="/pos" class="nav-link">
                    <i data-lucide="shopping-cart"></i> <span>Kasir / POS</span>
                </a>
                <a href="{{ route('operations.index') }}" class="nav-link">
                    <i data-lucide="clipboard-list"></i> <span>Log Aktivitas</span>
                </a>
            @endif

            <div class="nav-label">E-Commerce</div>
            <a href="{{ route('admin.products.index') }}" class="nav-link">
                <i data-lucide="package"></i> <span>Produk Digital</span>
            </a>
            <a href="{{ route('admin.orders.index') }}" class="nav-link">
                <i data-lucide="truck"></i> <span>Pesanan</span>
            </a>

            <div class="nav-label">Sistem</div>
            <a href="{{ route('admin.users.index') }}" class="nav-link">
                <i data-lucide="users"></i> <span>Karyawan</span>
            </a>
            <a href="{{ route('admin.audit-trail.index') }}" class="nav-link">
                <i data-lucide="shield-check"></i> <span>Audit Log</span>
            </a>
        </nav>

        <div class="sidebar-footer p-4 mt-auto border-top border-secondary">
            <div class="d-flex align-items-center gap-3">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center text-white" style="width: 40px; height: 40px; font-weight: 700;">
                    {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                </div>
                <div class="user-meta overflow-hidden">
                    <div class="small fw-bold text-truncate">{{ auth()->user()->name }}</div>
                    <div class="text-muted" style="font-size: 10px;">{{ strtoupper(auth()->user()->role) }}</div>
                </div>
                <form action="{{ route('logout') }}" method="POST" class="ms-auto">
                    @csrf
                    <button type="submit" class="p-0 bg-transparent border-0 text-muted hover-danger">
                        <i data-lucide="log-out" style="width: 18px;"></i>
                    </button>
                </form>
            </div>
        </div>
    </aside>

    <main id="main-content">
        <header class="top-navbar">
            <div class="navbar-left">
                <h4 class="m-0 fw-bold">@yield('page_title', 'Overview')</h4>
            </div>
            <div class="navbar-right d-flex align-items-center gap-4">
                <div class="language-switch d-none d-md-flex gap-2">
                    <a href="{{ route('lang.switch', 'id') }}" class="text-decoration-none {{ app()->getLocale() == 'id' ? 'text-primary' : 'text-muted' }}">ID</a>
                    <span class="text-muted">|</span>
                    <a href="{{ route('lang.switch', 'en') }}" class="text-decoration-none {{ app()->getLocale() == 'en' ? 'text-primary' : 'text-muted' }}">EN</a>
                </div>
                <button class="bg-transparent border-0 text-muted"><i data-lucide="bell"></i></button>
                <button class="bg-transparent border-0 text-muted"><i data-lucide="settings"></i></button>
            </div>
        </header>

        <div class="container-fluid p-4">
            @if(session('success'))
                <div class="alert alert-success bg-success bg-opacity-10 border-success border-opacity-20 text-success mb-4" style="border-radius: 12px;">
                    {{ session('success') }}
                </div>
            @endif
            @yield('content')
        </div>
    </main>

    <script>
        lucide.createIcons();
    </script>
    @stack('scripts')
</body>
</html>
