@extends('layouts.app')

@section('content')
<div class="no-print mb-4 d-flex justify-content-between align-items-center">
    <a href="/reports" class="btn btn-light btn-sm px-3 d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
        <i data-lucide="arrow-left" style="width: 16px;"></i> {{ __('ui.back_to_intelligence') }}
    </a>
    <div class="col-md-4 text-md-end d-flex gap-2 justify-content-end">
        <a href="{{ route('reports.profit_loss', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="btn btn-outline-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px; border: 1px solid rgba(79, 70, 229, 0.2);">
            <i data-lucide="printer"></i> Cetak Profesional
        </a>
        <a href="{{ route('reports.profit_loss.export', request()->all()) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%); border: none;">
            <i data-lucide="download"></i> Export Data
        </a>
    </div>
</div>

{{-- Global Filter Bar --}}
<div class="no-print card border-0 shadow-sm mb-4" style="border-radius: 16px; background: rgba(255, 255, 255, 0.8); backdrop-filter: blur(10px);">
    <div class="card-body p-4">
        <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.branch') }}</label>
                <select name="branch_id" class="form-select border-light bg-light" style="border-radius: 8px;">
                    <option value="">{{ __('ui.all_branches') }}</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.division') }}</label>
                <select name="division" class="form-select border-light bg-light" style="border-radius: 8px;">
                    <option value="">{{ __('ui.all_divisions') }}</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div }}" {{ $division == $div ? 'selected' : '' }}>{{ $div }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.start_date') }}</label>
                <input type="date" name="start_date" class="form-control border-light bg-light" style="border-radius: 8px;" value="{{ $startDate }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.end_date') }}</label>
                <input type="date" name="end_date" class="form-control border-light bg-light" style="border-radius: 8px;" value="{{ $endDate }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100 fw-bold" style="border-radius: 8px; background: #1e1b4b;">{{ __('ui.apply_filter') }}</button>
                <a href="{{ route('reports.profit_loss') }}" class="btn btn-light border" style="border-radius: 8px;">{{ __('ui.reset') }}</a>
            </div>
        </form>
    </div>
</div>

<div class="report-container bg-white p-5 shadow-lg mb-5" style="border-radius: 20px; border: 1px solid rgba(0,0,0,0.05);">
    {{-- Report Header --}}
    <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-5">
        <div>
            <div class="d-flex align-items-center gap-3 mb-2">
                <div class="bg-indigo p-2 rounded-lg d-flex align-items-center justify-content-center" style="width: 45px; height: 45px; background: #1e1b4b; border-radius: 12px;">
                    <i data-lucide="bar-chart-3" class="text-white" style="width: 24px;"></i>
                </div>
                <div>
                    <h3 class="fw-bold m-0 text-dark" style="letter-spacing: -1px;">NusaBiz <span class="text-primary" style="color: #4f46e5 !important;">Intelligence</span></h3>
                    <p class="text-secondary small mb-0 fw-medium">Financial Integrity & Performance Report</p>
                </div>
            </div>
        </div>
        <div class="text-end">
            <h1 class="fw-bold text-uppercase m-0" style="font-size: 1.5rem; color: #1e1b4b;">{{ __('ui.net_profit_loss') }}</h1>
            <p class="text-secondary mb-0 fw-bold small">ID: NB/FR/{{ date('Ymd') }}-PL</p>
        </div>
    </div>

    {{-- Meta Information --}}
    <div class="row mb-5 bg-light p-4 mx-0" style="border-radius: 12px; border-left: 5px solid #1e1b4b;">
        <div class="col-6">
            <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Scope Analysis</div>
            <div class="fw-bold text-dark">
                @if($branchId) {{ $branches->find($branchId)->name }} (Branch) @else Global Enterprise @endif
                @if($division) — {{ $division }} (Division) @endif
            </div>
            <div class="text-secondary mt-1 small">Generated on: {{ date('D, d M Y - H:i') }}</div>
        </div>
        <div class="col-6 text-end">
            <div class="small text-muted text-uppercase fw-bold mb-1" style="font-size: 0.65rem; letter-spacing: 1px;">Reporting Period</div>
            <div class="fw-bold text-primary" style="color: #4f46e5 !important;">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} — {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Financial Summary Grid --}}
    <div class="row g-4 mb-5">
        <div class="col-md-3">
            <div class="p-4 border-0 shadow-sm text-white" style="border-radius: 20px; background: #1e1b4b;">
                <div class="small text-white text-opacity-75 text-uppercase fw-bold mb-2" style="font-size: 0.6rem;">Revenue</div>
                <h4 class="fw-bold m-0">Rp {{ number_format($revenue, 0, ',', '.') }}</h4>
                <div class="mt-2 small {{ $revGrowth >= 0 ? 'text-success' : 'text-danger' }}">
                    <i data-lucide="{{ $revGrowth >= 0 ? 'trending-up' : 'trending-down' }}" style="width: 14px;"></i>
                    {{ number_format(abs($revGrowth), 1) }}% vs Prev
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border text-dark" style="border-radius: 20px; background: rgba(0,0,0,0.02);">
                <div class="small text-secondary text-uppercase fw-bold mb-2" style="font-size: 0.6rem;">COGS (HPP)</div>
                <h4 class="fw-bold m-0">Rp {{ number_format($hpp, 0, ',', '.') }}</h4>
                <div class="mt-2 small text-muted">Cost of Sales</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border-0 shadow-sm text-white" style="border-radius: 20px; background: linear-gradient(135deg, #4f46e5 0%, #3730a3 100%);">
                <div class="small text-white text-opacity-75 text-uppercase fw-bold mb-2" style="font-size: 0.6rem;">Gross Profit</div>
                <h4 class="fw-bold m-0">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h4>
                <div class="mt-2 small text-white text-opacity-75">Margin: {{ $revenue > 0 ? number_format(($grossProfit / $revenue) * 100, 1) : 0 }}%</div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="p-4 border-0 shadow-sm {{ $netProfit >= 0 ? 'bg-success text-white' : 'bg-danger text-white' }}" style="border-radius: 20px;">
                <div class="small text-white text-opacity-75 text-uppercase fw-bold mb-2" style="font-size: 0.6rem;">Net Profit</div>
                <h4 class="fw-bold m-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h4>
                <div class="mt-2 small text-white text-opacity-75">
                    Growth: {{ number_format($netGrowth, 1) }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Account Details --}}
    <div class="row mb-5 g-5">
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i data-lucide="plus-circle" class="text-success" style="width: 20px;"></i>
                <h5 class="fw-bold m-0 text-uppercase small">Rincian Pendapatan</h5>
            </div>
            <div class="p-4 bg-light bg-opacity-50" style="border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                <table class="table table-sm table-borderless align-middle m-0">
                    <tbody>
                        @forelse($revenueDetails as $detail)
                        <tr class="border-bottom border-light">
                            <td class="py-2 text-secondary fw-medium">{{ $detail->account->name }}</td>
                            <td class="py-2 text-end fw-bold">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-muted small italic p-3">{{ __('ui.no_transactions') }}</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="pt-3 fw-bold text-dark">Total Pendapatan</td>
                            <td class="pt-3 text-end fw-bold text-dark">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
        <div class="col-md-6">
            <div class="d-flex align-items-center gap-2 mb-3">
                <i data-lucide="minus-circle" class="text-danger" style="width: 20px;"></i>
                <h5 class="fw-bold m-0 text-uppercase small">Rincian HPP & Beban</h5>
            </div>
            <div class="p-4 bg-light bg-opacity-50" style="border-radius: 12px; border: 1px solid rgba(0,0,0,0.05);">
                <table class="table table-sm table-borderless align-middle m-0">
                    <tbody>
                        {{-- HPP Group --}}
                        @if($hpp > 0)
                        <tr class="table-danger text-white">
                            <td class="py-2 fw-bold bg-danger text-white rounded-start" style="padding-left: 10px;">HPP (Harga Pokok Penjualan)</td>
                            <td class="py-2 text-end fw-bold bg-danger text-white rounded-end" style="padding-right: 10px;">(Rp {{ number_format($hpp, 0, ',', '.') }})</td>
                        </tr>
                        @endif
                        
                        <tr class="text-muted"><td colspan="2" class="py-2 small fw-bold">Biaya Operasional & Umum:</td></tr>
                        @forelse($expenseDetails as $detail)
                        <tr class="border-bottom border-light">
                            <td class="py-2 text-secondary fw-medium ps-3">{{ $detail->account->name }}</td>
                            <td class="py-2 text-end fw-bold text-danger">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="2" class="text-muted small italic p-3">{{ __('ui.no_transactions') }}</td></tr>
                        @endforelse
                    </tbody>
                    <tfoot>
                        <tr>
                            <td class="pt-3 fw-bold text-dark">Total Pengeluaran</td>
                            <td class="pt-3 text-end fw-bold text-dark">Rp {{ number_format($hpp + $expense, 0, ',', '.') }}</td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>

    {{-- Bottom Summary Bar --}}
    <div class="p-4 bg-dark text-white d-flex justify-content-between align-items-center mb-5" style="border-radius: 12px; background: #0f172a !important;">
        <span class="text-uppercase small fw-bold tracking-widest text-white text-opacity-50">Laba Bersih Akhir (Final Net Result)</span>
        <h2 class="fw-bold m-0 text-white">Rp {{ number_format($netProfit, 0, ',', '.') }}</h2>
    </div>

    {{-- Transactions - Compact Table --}}
    <div>
        <h5 class="fw-bold mb-3 text-uppercase small" style="letter-spacing: 1px;">Audit Riwayat Transaksi Terakhir</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle border" style="font-size: 0.85rem; border-radius: 8px; overflow: hidden;">
                <thead class="bg-light">
                    <tr>
                        <th class="py-3 px-4 border-0">Tanggal</th>
                        <th class="py-3 border-0">Referensi</th>
                        <th class="py-3 border-0">Cabang</th>
                        <th class="py-3 border-0">Akun / Kategori</th>
                        <th class="py-3 px-4 border-0 text-end">Jumlah</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                    <tr class="border-bottom border-light">
                        <td class="px-4 text-muted">{{ $tx->transaction_date->format('d/m/Y') }}</td>
                        <td class="fw-bold text-dark">{{ $tx->transaction_no }}</td>
                        <td class="text-secondary">{{ $tx->branch->name }}</td>
                        <td>
                            <div class="d-flex align-items-center gap-2">
                                <span class="badge {{ $tx->account->type == 'REVENUE' ? 'bg-success' : 'bg-danger' }} rounded-circle" style="width: 8px; height: 8px; padding: 0;"></span>
                                <span class="fw-medium">{{ $tx->account->name }}</span>
                            </div>
                        </td>
                        <td class="px-4 text-end fw-bold {{ $tx->account->type == 'REVENUE' ? 'text-success' : 'text-dark' }}">
                            {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-5 text-muted">Belum ada transaksi dalam periode ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Signatures --}}
    <div class="row mt-5 pt-5 border-top">
        <div class="col-8">
            <p class="small text-muted mb-0">Dokumen ini diterbitkan secara digital oleh sistem ERP NusaBiz Intelligence.</p>
            <p class="small text-muted">Keakuratan data diverifikasi berdasarkan catatan buku besar yang sinkron dengan TiDB Cloud.</p>
        </div>
        <div class="col-4 text-end">
            <div class="p-3 border d-inline-block text-start mb-2" style="border-radius: 12px; background: #f8fafc;">
                <span class="small text-uppercase fw-bold text-secondary d-block mb-1" style="font-size: 0.5rem;">Verified By</span>
                <span class="fw-bold text-dark">NusaBiz Finance Bot</span>
            </div>
            <p class="small text-muted mb-0">{{ now()->format('d M Y, H:i') }}</p>
        </div>
    </div>
</div>

<style>
.bg-indigo { background-color: #4f46e5; }
.text-indigo { color: #4f46e5; }
.tracking-widest { letter-spacing: 0.1em; }

@media print {
    body { background: white !important; margin: 0 !important; padding: 0 !important; }
    .no-print { display: none !important; }
    .sidebar, #mobile-toggle { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .report-container { 
        box-shadow: none !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        border: none !important;
        width: 100% !important;
    }
    @page { 
        size: A4; 
        margin: 1.5cm; 
    }
}
</style>
@endsection
