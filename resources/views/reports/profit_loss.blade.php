@extends('layouts.app')

@section('content')
<div class="no-print mb-4 d-flex justify-content-between align-items-center">
    <a href="/reports" class="btn btn-light btn-sm px-3 d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
        <i data-lucide="arrow-left" style="width: 16px;"></i> {{ __('ui.back_to_intelligence') }}
    </a>
    <div class="col-md-4 text-md-end d-flex gap-2 justify-content-end">
        <a href="{{ route('reports.profit_loss', array_merge(request()->all(), ['print' => 1])) }}" target="_blank" class="btn btn-outline-dark d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
            <i data-lucide="printer"></i> Cetak Profesional
        </a>
        <a href="{{ route('reports.profit_loss.export', request()->all()) }}" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
            <i data-lucide="download"></i> Export Data
        </a>
        <button class="btn btn-dark btn-sm px-3 d-inline-flex align-items-center gap-2" style="border-radius: 8px;" onclick="window.print()">
            <i data-lucide="printer" style="width: 16px;"></i> {{ __('ui.export_pdf') }}
        </button>
    </div>
</div>

{{-- Global Filter Bar --}}
<div class="no-print card border-0 shadow-sm mb-4" style="border-radius: 12px;">
    <div class="card-body p-4">
        <form action="{{ route('reports.profit_loss') }}" method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.branch') }}</label>
                <select name="branch_id" class="form-select border-light">
                    <option value="">{{ __('ui.all_branches') }}</option>
                    @foreach($branches as $b)
                        <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.division') }}</label>
                <select name="division" class="form-select border-light">
                    <option value="">{{ __('ui.all_divisions') }}</option>
                    @foreach($divisions as $div)
                        <option value="{{ $div }}" {{ $division == $div ? 'selected' : '' }}>{{ $div }}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.start_date') }}</label>
                <input type="date" name="start_date" class="form-select border-light" value="{{ $startDate }}">
            </div>
            <div class="col-md-2">
                <label class="form-label small fw-bold text-secondary">{{ __('ui.end_date') }}</label>
                <input type="date" name="end_date" class="form-select border-light" value="{{ $endDate }}">
            </div>
            <div class="col-md-3 d-flex gap-2">
                <button type="submit" class="btn btn-primary w-100">{{ __('ui.apply_filter') }}</button>
                <a href="{{ route('reports.profit_loss') }}" class="btn btn-light">{{ __('ui.reset') }}</a>
            </div>
        </form>
    </div>
</div>

<div class="report-container bg-white p-5 shadow-sm mb-5" style="border-radius: 12px; min-height: 29.7cm;">
    {{-- Report Header --}}
    <div class="d-flex justify-content-between align-items-start border-bottom pb-4 mb-5">
        <div>
            <div class="d-flex align-items-center gap-2 mb-2">
                <div class="bg-primary rounded-circle d-flex align-items-center justify-content-center" style="width: 32px; height: 32px;">
                    <i data-lucide="shield" class="text-white" style="width: 18px;"></i>
                </div>
                <h4 class="fw-bold m-0 text-dark">NusaBiz</h4>
            </div>
            <p class="text-secondary small mb-0">Official Multi-Branch Financial Management System</p>
        </div>
        <div class="text-end">
            <h2 class="fw-bold text-uppercase m-0">{{ __('ui.net_profit_loss') }}</h2>
            <p class="text-secondary mb-0">{{ __('ui.official_report') }} #{{ date('Ymd') }}-PL</p>
        </div>
    </div>

    {{-- Meta Information --}}
    <div class="row mb-5">
        <div class="col-6">
            <div class="small text-muted text-uppercase fw-bold mb-1">{{ __('ui.generated_date') }}</div>
            <div class="fw-bold">{{ date('D, d M Y - H:i') }}</div>
            <div class="text-primary mt-1 small fw-bold">
                @if($branchId) [Branch: {{ $branches->find($branchId)->name }}] @else [All Branches] @endif
                @if($division) [Division: {{ $division }}] @endif
            </div>
        </div>
        <div class="col-6 text-end">
            <div class="small text-muted text-uppercase fw-bold mb-1">Period</div>
            <div class="fw-bold">{{ \Carbon\Carbon::parse($startDate)->format('d M Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d M Y') }}</div>
        </div>
    </div>

    {{-- Summary Cards --}}
    <div class="row g-4 mb-5">
        <div class="col-md-4">
            <div class="p-4 border rounded-4">
                <div class="small text-secondary text-uppercase mb-1">{{ __('ui.total_revenue') }}</div>
                <h3 class="fw-bold text-dark m-0">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
                <div class="mt-2 small {{ $revGrowth >= 0 ? 'text-success' : 'text-danger' }} fw-bold">
                    <i data-lucide="{{ $revGrowth >= 0 ? 'trending-up' : 'trending-down' }}" style="width: 14px;"></i>
                    {{ number_format(abs($revGrowth), 1) }}% <span class="text-secondary fw-normal">{{ __('ui.vs_prev_period') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border rounded-4">
                <div class="small text-secondary text-uppercase mb-1">{{ __('ui.total_expense') }}</div>
                <h3 class="fw-bold text-danger m-0">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
                <div class="mt-2 small {{ $expGrowth <= 0 ? 'text-success' : 'text-danger' }} fw-bold text-opacity-75">
                    <i data-lucide="{{ $expGrowth <= 0 ? 'trending-down' : 'trending-up' }}" style="width: 14px;"></i>
                    {{ number_format(abs($expGrowth), 1) }}% <span class="text-secondary fw-normal">{{ __('ui.vs_prev_period') }}</span>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-4 border rounded-4 {{ $revenue - $expense >= 0 ? 'bg-success-subtle border-success' : 'bg-danger-subtle border-danger' }}">
                <div class="small text-secondary text-uppercase mb-1">{{ __('ui.net_profit_loss') }}</div>
                <h3 class="fw-bold {{ $revenue - $expense >= 0 ? 'text-success' : 'text-danger' }} m-0">Rp {{ number_format($revenue - $expense, 0, ',', '.') }}</h3>
                <div class="mt-2 small opacity-75">
                    Margin: {{ $revenue > 0 ? number_format((($revenue - $expense) / $revenue) * 100, 1) : 0 }}%
                </div>
            </div>
        </div>
    </div>

    {{-- Detailed Breakdown --}}
    <div class="row mb-5">
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 border-start border-primary border-4 ps-3 text-uppercase small">{{ __('ui.revenue_details') }}</h5>
            <table class="table table-sm table-borderless align-middle">
                <tbody>
                    @forelse($revenueDetails as $detail)
                    <tr class="border-bottom">
                        <td class="py-2 text-secondary">{{ $detail->account->name }}</td>
                        <td class="py-2 text-end fw-bold">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted small italic p-3">{{ __('ui.no_transactions') }}</td></tr>
                    @endforelse
                    <tr class="table-light">
                        <td class="py-2 fw-bold">{{ __('ui.total_revenue') }}</td>
                        <td class="py-2 text-end fw-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="col-md-6">
            <h5 class="fw-bold mb-3 border-start border-danger border-4 ps-3 text-uppercase small">{{ __('ui.expense_details') }}</h5>
            <table class="table table-sm table-borderless align-middle">
                <tbody>
                    @forelse($expenseDetails as $detail)
                    <tr class="border-bottom">
                        <td class="py-2 text-secondary">{{ $detail->account->name }}</td>
                        <td class="py-2 text-end fw-bold">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                    </tr>
                    @empty
                    <tr><td colspan="2" class="text-muted small italic p-3">{{ __('ui.no_transactions') }}</td></tr>
                    @endforelse
                    <tr class="table-light">
                        <td class="py-2 fw-bold">{{ __('ui.total_expense') }}</td>
                        <td class="py-2 text-end fw-bold">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- Recent Transactions History --}}
    <div class="mt-5">
        <h5 class="fw-bold mb-3 border-start border-dark border-4 ps-3 text-uppercase small">{{ __('ui.transaction_details') }}</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle" style="font-size: 0.85rem;">
                <thead class="bg-light">
                    <tr>
                        <th class="border-0">{{ __('ui.date') }}</th>
                        <th class="border-0">{{ __('ui.reference') }}</th>
                        <th class="border-0">{{ __('ui.branch') }}</th>
                        <th class="border-0">{{ __('ui.category') }}</th>
                        <th class="border-0 text-end">{{ __('ui.amount') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentTransactions as $tx)
                    <tr>
                        <td class="text-secondary">{{ $tx->transaction_date->format('d M Y') }}</td>
                        <td class="fw-bold">{{ $tx->transaction_no }}</td>
                        <td class="text-secondary">{{ $tx->branch->name }}</td>
                        <td>
                            <div class="d-flex flex-column">
                                <span class="badge {{ $tx->account->type == 'REVENUE' ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-2 mb-1" style="width: fit-content;">
                                    {{ $tx->account->name }}
                                </span>
                                @if($tx->division)
                                <small class="text-muted" style="font-size: 0.7rem;">Div: {{ $tx->division }}</small>
                                @endif
                            </div>
                        </td>
                        <td class="text-end fw-bold {{ $tx->account->type == 'REVENUE' ? 'text-success' : 'text-dark' }}">
                            {{ $tx->account->type == 'REVENUE' ? '+' : '-' }} Rp {{ number_format($tx->amount, 0, ',', '.') }}
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="5" class="text-center py-4 text-muted">{{ __('ui.no_transactions') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    {{-- Signatures --}}
    <div class="row mt-5 pt-5 printable-only border-top">
        <div class="col-6">
            <p class="mb-5 small text-secondary">Verified by Finance Management System</p>
            <div class="pt-2">
                <p class="fw-bold mb-0">System Generated Report</p>
                <p class="small text-muted">{{ date('d M Y, H:i:s') }}</p>
            </div>
        </div>
        <div class="col-6 text-end">
            <p class="mb-5 small text-secondary">Authorized Signature</p>
            <div class="mx-auto me-0 border-bottom d-inline-block" style="width: 200px; height: 30px;"></div>
            <p class="fw-bold mt-2">Director / Finance Manager</p>
        </div>
    </div>
</div>

<style>
@media print {
    body { background: white !important; margin: 0 !important; padding: 0 !important; }
    .no-print { display: none !important; }
    .sidebar { display: none !important; }
    #mobile-toggle { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .report-container { 
        box-shadow: none !important; 
        padding: 0 !important; 
        margin: 0 !important; 
        border: none !important;
        width: 100% !important;
    }
    .printable-only { display: flex !important; }
    @page { 
        size: A4; 
        margin: 1.5cm; 
    }
}
.printable-only { display: none; }
</style>

<script>
    // Professional Document Title for Print/Save
    window.onbeforeprint = function() {
        document.title = "Profit_Loss_Report_{{ date('Ymd') }}";
    };
    window.onafterprint = function() {
        document.title = "{{ config('app.name', 'Financial MS') }}";
    };
</script>
@endsection
