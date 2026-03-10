@extends('layouts.premium')

@section('content')
<div class="row align-items-center mb-5">
    <div class="col-md-8">
        <h2 class="fw-bold mb-1">
            @if(auth()->user()->isAdmin())
                {{ __('ui.dashboard') }} — OmniBiz Pusat
            @else
                Dashboard — {{ auth()->user()->branch->name ?? 'Cabang' }}
            @endif
        </h2>
        <p class="text-muted">
            Selamat Datang, <strong>{{ auth()->user()->name }}</strong>. Berikut ringkasan performa ekosistem bisnis Anda hari ini.
        </p>
    </div>
    <div class="col-md-4 text-md-end">
        <div class="d-flex gap-2 justify-content-end">
            <a href="/pos" class="btn btn-premium shadow-sm">
                <i data-lucide="shopping-cart"></i> Point of Sale
            </a>
            <a href="/transactions/create" class="btn btn-outline-light border-secondary" style="border-radius: 12px;">
                <i data-lucide="plus-circle"></i> {{ __('ui.new_transaction') }}
            </a>
        </div>
    </div>
</div>

<div class="row">
    <!-- Stats Card 1: Revenue (Unified) -->
    <div class="col-md-3 mb-4">
        <div class="card-premium p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <div class="logo-box" style="background: rgba(34, 211, 238, 0.15); color: #22d3ee;">
                    <i data-lucide="trending-up"></i>
                </div>
                <span class="text-success small fw-bold">+12% <i data-lucide="arrow-up-right" style="width: 14px;"></i></span>
            </div>
            <h6 class="text-muted mb-1">{{ __('ui.revenue') }}</h6>
            <h3 class="fw-bold mb-0">Rp 45.2M</h3>
            <div class="mt-3">
                <a href="/reports" class="text-decoration-none small text-info">Detail Laporan <i data-lucide="chevron-right" style="width: 14px;"></i></a>
            </div>
        </div>
    </div>

    <!-- Stats Card 2: Branches -->
    <div class="col-md-3 mb-4">
        <div class="card-premium p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <div class="logo-box" style="background: rgba(16, 185, 129, 0.15); color: #10b981;">
                    <i data-lucide="building"></i>
                </div>
            </div>
            <h6 class="text-muted mb-1">{{ __('ui.active_branches') }}</h6>
            <h3 class="fw-bold mb-0">{{ \App\Models\Branch::count() }}</h3>
            <div class="mt-3">
                <a href="/branches" class="text-decoration-none small text-success">Kelola Cabang <i data-lucide="chevron-right" style="width: 14px;"></i></a>
            </div>
        </div>
    </div>

    <!-- Stats Card 3: Transactions -->
    <div class="col-md-3 mb-4">
        <div class="card-premium p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <div class="logo-box" style="background: rgba(99, 102, 241, 0.15); color: #818cf8;">
                    <i data-lucide="refresh-cw"></i>
                </div>
            </div>
            <h6 class="text-muted mb-1">Total Transaksi</h6>
            <h3 class="fw-bold mb-0">{{ \App\Models\Transaction::count() }}</h3>
            <div class="mt-3">
                <a href="/transactions" class="text-decoration-none small text-primary">Riwayat Kasir <i data-lucide="chevron-right" style="width: 14px;"></i></a>
            </div>
        </div>
    </div>

    <!-- Stats Card 4: Inventory -->
    <div class="col-md-3 mb-4">
        <div class="card-premium p-4 h-100">
            <div class="d-flex justify-content-between mb-3">
                <div class="logo-box" style="background: rgba(245, 158, 11, 0.15); color: #fbbf24;">
                    <i data-lucide="box"></i>
                </div>
                <span class="text-danger small fw-bold">Stock Low!</span>
            </div>
            <h6 class="text-muted mb-1">Stok Bahan</h6>
            <h3 class="fw-bold mb-0">12 Item</h3>
            <div class="mt-3">
                <a href="/operations" class="text-decoration-none small text-warning">Cek Inventaris <i data-lucide="chevron-right" style="width: 14px;"></i></a>
            </div>
        </div>
    </div>
</div>

<!-- ANALYTICS SECTION -->
<div class="row g-4 mt-2 mb-5">
    <!-- Performance Chart -->
    <div class="col-md-8">
        <div class="card-premium p-4">
            <h5 class="fw-bold mb-4">{{ __('ui.branch_performance') }}</h5>
            <canvas id="performanceChart" height="300"></canvas>
        </div>
    </div>
    <!-- Attendance & SOP Summary -->
    <div class="col-md-4">
        <div class="card-premium p-4 mb-4">
            <h5 class="fw-bold mb-3">{{ __('ui.attendance_rate') }}</h5>
            <div class="text-center py-4">
                <canvas id="attendanceChart" height="200"></canvas>
            </div>
            <div class="d-flex justify-content-center gap-3 mt-2">
                <span class="small d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 10px; height: 10px; background: #10b981;"></span> {{ __('ui.on_time') }}</span>
                <span class="small d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 10px; height: 10px; background: #f59e0b;"></span> {{ __('ui.late') }}</span>
                <span class="small d-flex align-items-center gap-1"><span class="rounded-circle" style="width: 10px; height: 10px; background: #ef4444;"></span> {{ __('ui.absent') }}</span>
            </div>
        </div>
        <div class="card-premium p-4">
            <h5 class="fw-bold mb-3">{{ __('ui.sop_compliance') }}</h5>
            <div class="d-flex align-items-center justify-content-between mb-3">
                <span class="small text-muted">{{ __('ui.cleaning_standard') }}</span>
                <span class="badge-premium bg-success bg-opacity-20 text-success">100%</span>
            </div>
            <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05);">
                <div class="progress-bar bg-success" role="progressbar" style="width: 100%"></div>
            </div>
            <div class="d-flex align-items-center justify-content-between mb-2">
                <span class="small text-muted">{{ __('ui.inventory_check') }}</span>
                <span class="badge-premium bg-warning bg-opacity-20 text-warning">85%</span>
            </div>
            <div class="progress mb-3" style="height: 6px; background: rgba(255,255,255,0.05);">
                <div class="progress-bar bg-warning" role="progressbar" style="width: 85%"></div>
            </div>
        </div>
    </div>
</div>

<div class="row mt-4">
    <div class="col-md-8">
        <div class="card-premium p-4">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0">{{ __('ui.recent_transactions') }}</h5>
                <a href="/transactions" class="btn btn-outline-light btn-sm border-secondary px-3" style="border-radius: 8px;">{{ __('ui.view_all') }}</a>
            </div>
            <div class="table-responsive">
                <table class="table-premium">
                    <thead>
                        <tr class="text-muted small text-uppercase">
                            <th>Tanggal</th>
                            <th>Referensi</th>
                            <th>Nominal</th>
                            <th class="text-end">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(\App\Models\Transaction::latest()->take(5)->get() as $tx)
                        <tr>
                            <td>{{ $tx->transaction_date ? $tx->transaction_date->format('d M Y') : '-' }}</td>
                            <td><span class="fw-medium">{{ $tx->transaction_no }}</span></td>
                            <td class="fw-bold text-success">Rp {{ number_format($tx->amount, 0, ',', '.') }}</td>
                            <td class="text-end">
                                <span class="badge-premium bg-success bg-opacity-20 text-success">Completed</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" class="text-center py-4 text-muted">Belum ada transaksi hari ini.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card-premium p-4 h-100">
            <h5 class="fw-bold mb-4">Aksi Cepat Ekosistem</h5>
            <div class="d-grid gap-3">
                <a href="/pos" class="btn-premium py-3 d-flex align-items-center gap-3 px-3 shadow-sm">
                    <i data-lucide="shopping-cart"></i> Terminal Kasir POS
                </a>
                <a href="/branches" class="btn btn-outline-light border-secondary py-3 d-flex align-items-center gap-3 px-3 shadow-sm" style="border-radius: 12px; text-decoration: none;">
                    <i data-lucide="building"></i> Kelola Daftar Cabang
                </a>
                <a href="/users" class="btn btn-outline-light border-secondary py-3 d-flex align-items-center gap-3 px-3 shadow-sm" style="border-radius: 12px; text-decoration: none;">
                    <i data-lucide="users"></i> Data User / Staff
                </a>
                <a href="/reports" class="btn btn-outline-light border-secondary py-3 d-flex align-items-center gap-3 px-3 shadow-sm" style="border-radius: 12px; text-decoration: none;">
                    <i data-lucide="file-text"></i> Laporan Konsolidasi
                </a>
            </div>
        </div>
    </div>
</div>

<div class="row g-4 mt-4 mb-5">
    <div class="col-md-6">
        <div class="card-premium p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-white">Tiket Komplain Aktif</h5>
                <span class="badge-premium bg-danger bg-opacity-20 text-danger px-3">{{ \App\Models\Complaint::where('status', 'OPEN')->count() }} OPEN</span>
            </div>
            <div class="list-group list-group-flush">
                @forelse(\App\Models\Complaint::with('branch')->latest()->take(3)->get() as $complaint)
                <div class="list-group-item px-0 py-3 border-0 border-bottom border-light border-opacity-10 bg-transparent">
                    <div class="d-flex gap-3">
                        <div class="bg-dark rounded d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border: 1px solid var(--border-color);">
                            <i data-lucide="alert-circle" class="text-danger" style="width: 20px;"></i>
                        </div>
                        <div class="flex-grow-1">
                            <div class="d-flex align-items-center justify-content-between mb-1">
                                <span class="fw-bold small text-white">{{ $complaint->branch->name }}</span>
                                <span class="text-muted small">{{ $complaint->created_at->diffForHumans() }}</span>
                            </div>
                            <p class="text-muted small mb-0">{{ Str::limit($complaint->description, 60) }}</p>
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Tidak ada komplain aktif.</div>
                @endforelse
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card-premium p-4 h-100">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-white">Permintaan Stok Cabang</h5>
                <a href="/operations" class="btn btn-outline-info btn-sm px-3" style="border-radius: 8px;">Order Stok</a>
            </div>
            <div class="list-group list-group-flush">
                @forelse(\App\Models\StockRequest::with('branch')->latest()->take(3)->get() as $req)
                <div class="list-group-item px-0 py-3 border-0 border-bottom border-light border-opacity-10 bg-transparent">
                    <div class="d-flex align-items-center justify-content-between mb-1">
                        <span class="fw-bold small text-info">{{ $req->item_name }} ({{ $req->quantity }})</span>
                        <span class="badge-premium bg-warning bg-opacity-20 text-warning px-3">{{ $req->status }}</span>
                    </div>
                    <div class="text-muted small">{{ $req->branch->name }} — <span class="font-italic text-truncate">{{ Str::limit($req->reason, 40) }}</span></div>
                </div>
                @empty
                <div class="text-center py-4 text-muted small">Belum ada permintaan stok.</div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Labels mapping for localization
    const chartLabels = {
        revenue: "{{ __('ui.revenue') }}",
        attendance: "Attendance %",
        onTime: "{{ __('ui.on_time') }}",
        late: "{{ __('ui.late') }}",
        absent: "{{ __('ui.absent') }}"
    };

    // Performance Chart
    const performanceCtx = document.getElementById('performanceChart').getContext('2d');
    new Chart(performanceCtx, {
        type: 'bar',
        data: {
            labels: ['Jakarta Sel', 'Tangerang', 'Bandung Pst', 'Surabaya', 'Medan'],
            datasets: [{
                label: chartLabels.revenue + ' (M)',
                data: [420, 380, 450, 310, 260],
                backgroundColor: 'rgba(79, 70, 229, 0.7)',
                hoverBackgroundColor: 'rgba(79, 70, 229, 0.9)',
                borderRadius: 8,
            }]
        },
        options: {
            responsive: true,
            plugins: { 
                legend: { position: 'bottom', labels: { color: '#94a3b8', font: { family: 'Outfit', size: 12 } } }
            },
            scales: { 
                y: { grid: { color: 'rgba(255,255,255,0.05)' }, border: { display: false }, ticks: { color: '#94a3b8' } },
                x: { grid: { display: false }, border: { display: false }, ticks: { color: '#94a3b8' } }
            }
        }
    });

    // Attendance Chart
    const attendanceCtx = document.getElementById('attendanceChart').getContext('2d');
    new Chart(attendanceCtx, {
        type: 'doughnut',
        data: {
            labels: [chartLabels.onTime, chartLabels.late, chartLabels.absent],
            datasets: [{
                data: [85, 10, 5],
                backgroundColor: ['#10b981', '#f59e0b', '#ef4444'],
                borderWidth: 0,
                hoverOffset: 10
            }]
        },
        options: {
            cutout: '80%',
            plugins: { 
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection
