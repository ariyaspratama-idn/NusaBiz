<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Financial MS') }}</title>
    
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <!-- Bootstrap & Icons -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://unpkg.com/lucide@latest"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <style>
        :root {
            --primary: #6366f1;
            --primary-hover: #4f46e5;
            --sidebar-bg: #0b0f1a;
            --sidebar-text: #8892a4;
            --sidebar-active: #e2e8f0;
            --sidebar-active-bg: rgba(99, 102, 241, 0.14);
            --bg-glass: rgba(22, 27, 39, 0.95);
            --bg-main: #0d1117;
            --text-main: #e2e8f0;
            --text-muted: #7c8899;
            --border: rgba(255,255,255,0.07);
        }

        body { 
            font-family: 'Outfit', sans-serif;
            background: linear-gradient(135deg, #0d1117 0%, #0f1624 100%);
            min-height: 100vh;
            color: var(--text-main);
            overflow-x: hidden;
        }

        /* Sidebar Glassmorphism */
        .sidebar { 
            min-height: 100vh; 
            background: var(--sidebar-bg);
            color: white; 
            padding: 24px 16px;
            position: fixed;
            width: 260px;
            transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
            z-index: 1000;
            box-shadow: 10px 0 30px rgba(0,0,0,0.4);
            display: flex;
            flex-direction: column;
            border-right: 1px solid rgba(255,255,255,0.05);
        }

        .sidebar-brand {
            font-size: 1.25rem;
            font-weight: 700;
            margin-bottom: 32px;
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 0 12px;
            background: linear-gradient(to right, #818cf8, #c084fc);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .nav-group { margin-bottom: 24px; }
        .nav-label { 
            font-size: 0.75rem; 
            text-transform: uppercase; 
            letter-spacing: 0.08em; 
            color: #475569; 
            margin-bottom: 8px;
            padding-left: 12px;
            font-weight: 700;
        }

        .sidebar a { 
            color: var(--sidebar-text); 
            text-decoration: none; 
            padding: 11px 16px; 
            display: flex;
            align-items: center;
            gap: 12px;
            border-radius: 12px;
            margin-bottom: 2px;
            transition: all 0.2s ease;
            font-weight: 500;
            font-size: 0.875rem;
        }

        .sidebar a i { width: 20px; }
        .sidebar a:hover { 
            background: rgba(255,255,255,0.05); 
            color: #f1f5f9;
            transform: translateX(3px);
        }
        
        .sidebar .active { 
            background: var(--sidebar-active-bg); 
            color: var(--sidebar-active) !important;
            border-left: 3px solid var(--primary);
        }

        /* Mobile Menu */
        #mobile-toggle {
            display: none;
            position: fixed;
            top: 15px;
            right: 15px;
            z-index: 1100;
            background: #161b27;
            border: 1px solid rgba(255,255,255,0.1);
            padding: 8px;
            border-radius: 8px;
            color: white;
            box-shadow: 0 4px 12px rgba(0,0,0,0.4);
        }

        .main-content { 
            margin-left: 260px;
            padding: 32px; 
            transition: all 0.3s;
        }

        /* Card Styles — Dark Glass */
        .card {
            border: 1px solid var(--border) !important;
            border-radius: 20px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.4);
            background: var(--bg-glass);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            color: var(--text-main) !important;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 12px 30px rgba(0,0,0,0.5);
            border-color: rgba(99,102,241,0.2) !important;
        }

        /* Override Bootstrap text colors for dark mode */
        .text-dark { color: var(--text-main) !important; }
        .text-secondary { color: var(--text-muted) !important; }
        .bg-light { background: rgba(255,255,255,0.04) !important; }
        .border-top { border-color: var(--border) !important; }
        .border-bottom { border-color: var(--border) !important; }
        .table-hover tbody tr:hover { background: rgba(255,255,255,0.02) !important; }
        .table { color: var(--text-main) !important; --bs-table-hover-bg: rgba(255,255,255,0.02); }
        .bg-light.text-center { background: rgba(255,255,255,0.03) !important; }
        .list-group-item { background: transparent !important; border-color: var(--border) !important; color: var(--text-main) !important; }
        .list-group-item:hover { background: rgba(255,255,255,0.03) !important; }
        .progress { background-color: rgba(255,255,255,0.07) !important; }
        .btn-light { background: rgba(255,255,255,0.07) !important; border: 1px solid rgba(255,255,255,0.1) !important; color: var(--text-main) !important; }
        .btn-light:hover { background: rgba(255,255,255,0.12) !important; }
        .alert-success { background: rgba(16,185,129,0.12) !important; color: #34d399 !important; border-color: rgba(16,185,129,0.2) !important; color: #065f46; }
        .dropdown-menu { background: #1c2333 !important; border: 1px solid var(--border) !important; }
        .dropdown-item { color: var(--text-main) !important; }
        .dropdown-item:hover { background: rgba(255,255,255,0.05) !important; }
        .badge.bg-danger { background: rgba(244,63,94,0.15) !important; color: #fb7185 !important; }
        .badge.bg-success-subtle { background: rgba(16,185,129,0.15) !important; color: #34d399 !important; }
        .badge.bg-warning-subtle { background: rgba(251,146,60,0.15) !important; color: #fbbf24 !important; }
        .badge.bg-primary-subtle { background: rgba(99,102,241,0.15) !important; color: #a5b4fc !important; }
        .badge.bg-danger-subtle { background: rgba(244,63,94,0.15) !important; color: #fb7185 !important; }

        /* Topbar */
        .main-content > div:first-child {
            background: rgba(13,17,23,0.7);
            backdrop-filter: blur(12px);
            border-radius: 16px;
            padding: 12px 20px;
            border: 1px solid var(--border);
            margin-bottom: 24px;
        }

        .stats-icon {
            width: 48px;
            height: 48px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 16px;
        }

        /* Scrollbar dark */
        ::-webkit-scrollbar { width: 6px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.1); border-radius: 10px; }

        @media (max-width: 768px) {
            .sidebar { 
                transform: translateX(-100%); 
                width: 100%;
            }
            .sidebar.show { transform: translateX(0); }
            .main-content { margin-left: 0; padding: 24px 16px; }
            #mobile-toggle { display: block; }
        }

        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }

        .fade-in { animation: fadeIn 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    </style>
</head>
<body>
    <button id="mobile-toggle" class="no-print">
        <i data-lucide="menu"></i>
    </button>

    <div class="sidebar" id="sidebar">
        <div class="sidebar-brand">
            <i data-lucide="wallet"></i>
            <span>NusaBiz</span>
        </div>

        <div class="nav-group">
            <div class="nav-label">{{ __('ui.main_menu') ?? 'Main Menu' }}</div>
            <a href="/" class="{{ request()->is('/') ? 'active' : '' }}">
                <i data-lucide="layout-dashboard"></i> {{ __('ui.dashboard') }}
            </a>
            
            {{-- Alat Cabang (Hanya muncul jika punya Branch) --}}
            @if(auth()->user()?->branch_id || auth()->user()?->role === \App\Models\User::ROLE_SUPER_ADMIN)
            <a href="/pos" class="{{ request()->is('pos*') ? 'active' : '' }}" style="background: linear-gradient(to right, rgba(99, 102, 241, 0.1), rgba(168, 85, 247, 0.1)); border-left: 2px solid #6366f1;">
                <i data-lucide="shopping-cart"></i> Terminal POS
            </a>
            <a href="/operations" class="{{ request()->is('operations*') ? 'active' : '' }}">
                <i data-lucide="activity"></i> Pusat Operasional
            </a>
            @endif

            {{-- Master Data (Hanya Admin) --}}
            @if(auth()->user()?->role === \App\Models\User::ROLE_SUPER_ADMIN)
            <a href="/branches" class="{{ request()->is('branches*') ? 'active' : '' }}">
                <i data-lucide="building-2"></i> {{ __('ui.branches') }}
            </a>
            <a href="/users" class="{{ request()->is('users*') ? 'active' : '' }}">
                <i data-lucide="users"></i> Data User / Pegawai
            </a>
            @endif

            {{-- Financial & Transactions (Hanya Admin / Manager Pusat) --}}
            @if(auth()->user()?->role === \App\Models\User::ROLE_SUPER_ADMIN || auth()->user()?->role === \App\Models\User::ROLE_AUDITOR)
            <a href="/accounts" class="{{ request()->is('accounts*') ? 'active' : '' }}">
                <i data-lucide="list-tree"></i> {{ __('ui.charts_of_accounts') }}
            </a>
            <a href="/transactions" class="{{ request()->is('transactions*') ? 'active' : '' }}">
                <i data-lucide="arrow-right-left"></i> {{ __('ui.transactions') }}
            </a>
            @endif
        </div>

        <div class="nav-group">
            <div class="nav-label">Enterprise Monitoring (Pusat)</div>
            @if(auth()->user()?->role === \App\Models\User::ROLE_SUPER_ADMIN || auth()->user()?->role === \App\Models\User::ROLE_AUDITOR)
            <a href="{{ route('reports.compliance') }}" class="{{ request()->is('reports/compliance*') ? 'active' : '' }}">
                <i data-lucide="clipboard-check"></i> Monitoring SOP
            </a>
            <a href="{{ route('reports.complaints_monitor') }}" class="{{ request()->is('reports/complaints*') ? 'active' : '' }}">
                <i data-lucide="message-square"></i> Monitoring Komplain
            </a>
            <a href="{{ route('reports.stock_monitor') }}" class="{{ request()->is('reports/stocks*') ? 'active' : '' }}">
                <i data-lucide="package-search"></i> Monitoring Stock
            </a>
            
            <a href="{{ route('reconciliation.index') }}" class="{{ request()->is('reconciliation*') ? 'active' : '' }}">
                <i data-lucide="landmark"></i> Rekonsiliasi Bank
            </a>
            @endif
            @if(auth()->user()?->role === \App\Models\User::ROLE_SUPER_ADMIN)
            <a href="{{ route('admin.audit-trail.index') }}" class="{{ request()->is('admin-dashboard/audit-trail*') ? 'active' : '' }}">
                <i data-lucide="shield-check"></i> Audit Trail
            </a>
            @endif
        </div>

        <div class="mt-auto pt-4 border-top border-secondary border-opacity-25">
            <div class="nav-label mb-3">Language / Bahasa</div>
            <div class="px-2">
                <div class="btn-group w-100" role="group" style="padding: 2px; background: rgba(255,255,255,0.05); border-radius: 12px;">
                    <a href="/lang/en" class="btn btn-sm py-2 {{ App::getLocale() == 'en' ? 'btn-primary shadow-sm' : 'text-muted border-0' }}" 
                       style="border-radius: 10px; font-weight: 600; font-size: 0.75rem;">
                       ENG
                    </a>
                    <a href="/lang/id" class="btn btn-sm py-2 {{ App::getLocale() == 'id' ? 'btn-primary shadow-sm' : 'text-muted border-0' }}" 
                       style="border-radius: 10px; font-weight: 600; font-size: 0.75rem;">
                       IDN
                    </a>
                </div>
            </div>
            
            <form method="POST" action="{{ route('logout') }}" class="mt-3 px-2">
                @csrf
                <button type="submit" class="btn btn-outline-danger w-100 d-flex align-items-center justify-content-center gap-2 py-2" style="border-radius: 10px; font-size: 0.875rem;">
                    <i data-lucide="log-out" style="width: 16px;"></i>
                    Logout
                </button>
            </form>
        </div>
    </div>

    <div class="main-content">
        <div class="d-flex align-items-center justify-content-end mb-4 px-2 no-print">
            <div class="d-flex align-items-center gap-3">
                <div class="dropdown">
                    <button class="btn btn-white shadow-sm border-0 p-2 position-relative" type="button" id="notificationDropdown" data-bs-toggle="dropdown" aria-expanded="false" style="border-radius: 10px;">
                        <i data-lucide="bell" style="width: 20px;"></i>
                        @php
                            $unreadCount = \App\Models\Complaint::where('status', 'OPEN')->count() + \App\Models\StockRequest::where('status', 'PENDING')->count();
                        @endphp
                        @if($unreadCount > 0)
                            <span class="position-absolute top-10 start-90 translate-middle badge rounded-pill bg-danger" style="padding: 4px; font-size: 0.5rem; border: 2px solid white;">{{ $unreadCount }}</span>
                        @endif
                    </button>
                    <div class="dropdown-menu dropdown-menu-end border-0 shadow-lg p-0 mt-3" aria-labelledby="notificationDropdown" style="width: 320px; border-radius: 15px; overflow: hidden;">
                        <div class="p-3 bg-primary text-white">
                            <h6 class="mb-0 fw-bold">{{ __('ui.recent_activity') ?? 'Notifikasi' }}</h6>
                        </div>
                        <div class="list-group list-group-flush" style="max-height: 400px; overflow-y: auto;">
                            {{-- Complaints --}}
                            @foreach(\App\Models\Complaint::with('branch')->where('status', 'OPEN')->latest()->take(3)->get() as $complaint)
                            <a href="/" class="list-group-item list-group-item-action border-0 p-3">
                                <div class="d-flex gap-3">
                                    <div class="bg-danger-subtle text-danger rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                        <i data-lucide="alert-circle" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ $complaint->branch->name }}</div>
                                        <div class="text-secondary smaller" style="font-size: 0.75rem;">{{ $complaint->description }}</div>
                                        <div class="text-muted mt-1" style="font-size: 0.65rem;">{{ $complaint->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                            @endforeach

                            {{-- Stock Requests --}}
                            @foreach(\App\Models\StockRequest::with('branch')->where('status', 'PENDING')->latest()->take(3)->get() as $sreq)
                            <a href="/" class="list-group-item list-group-item-action border-0 p-3">
                                <div class="d-flex gap-3">
                                    <div class="bg-primary-subtle text-primary rounded-circle d-flex align-items-center justify-content-center flex-shrink-0" style="width: 32px; height: 32px;">
                                        <i data-lucide="package" style="width: 16px;"></i>
                                    </div>
                                    <div>
                                        <div class="fw-bold small">{{ $sreq->item_name }} ({{ $sreq->quantity }})</div>
                                        <div class="text-secondary smaller" style="font-size: 0.75rem;">{{ $sreq->branch->name }} - {{ $sreq->reason }}</div>
                                        <div class="text-muted mt-1" style="font-size: 0.65rem;">{{ $sreq->created_at->diffForHumans() }}</div>
                                    </div>
                                </div>
                            </a>
                            @endforeach

                            @if($unreadCount == 0)
                            <div class="p-4 text-center text-muted small">
                                <i data-lucide="check-circle" class="mb-2 d-block mx-auto" style="width: 24px; opacity: 0.2;"></i>
                                {{ __('ui.no_notifications_found') }}
                            </div>
                            @endif
                        </div>
                        <div class="p-2 border-top bg-light text-center">
                            <a href="/" class="text-decoration-none small fw-bold text-primary">{{ __('ui.view_all_notifications') }}</a>
                        </div>
                    </div>
                </div>
                <div class="d-flex align-items-center gap-2">
                    <div class="bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold" style="width: 36px; height: 36px; font-size: 0.8rem;">
                        {{ substr(auth()->user()->name ?? 'U', 0, 2) }}
                    </div>
                    <div class="d-flex flex-column">
                        <span class="fw-semibold small line-height-1">{{ auth()->user()->name }}</span>
                        <span class="text-secondary smaller" style="font-size: 0.7rem;">{{ auth()->user()->role }}</span>
                    </div>
                </div>

                <div class="ms-2 border-start ps-3">
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn btn-sm btn-outline-danger d-flex align-items-center gap-1 px-3" style="border-radius: 8px;">
                            <i data-lucide="log-out" style="width: 14px;"></i>
                            Logout
                        </button>
                    </form>
                </div>
            </div>
        </div>
        <div class="container-fluid fade-in">
            @if(session('success'))
                <div class="alert alert-success border-0 shadow-sm mb-4" style="border-radius: 12px; background: #ecfdf5; color: #065f46;">
                    <div class="d-flex align-items-center gap-2">
                        <i data-lucide="check-circle" style="width: 20px;"></i>
                        {{ session('success') }}
                    </div>
                </div>
            @endif
            @yield('content')
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Init Lucide Icons
        lucide.createIcons();

        // Mobile Toggle
        const toggle = document.getElementById('mobile-toggle');
        const sidebar = document.getElementById('sidebar');
        
        toggle.addEventListener('click', () => {
            sidebar.classList.toggle('show');
            const icon = toggle.querySelector('i');
            if(sidebar.classList.contains('show')) {
                icon.setAttribute('data-lucide', 'x');
            } else {
                icon.setAttribute('data-lucide', 'menu');
            }
            lucide.createIcons();
        });
    </script>
</body>
</html>
