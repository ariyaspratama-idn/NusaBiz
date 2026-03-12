@extends('layouts.app')

@section('content')
<div class="row no-print mb-4">
    <div class="col-md-12 text-end">
        <button onclick="window.print()" class="btn btn-dark d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
            <i data-lucide="printer"></i> Cetak PDF Profesional
        </button>
    </div>
</div>

<div class="print-container p-5 bg-white shadow-sm mx-auto" style="max-width: 1000px; min-height: 1000px; border-top: 8px solid #1e1b4b;">
    <!-- Company Header -->
    <div class="d-flex justify-content-between align-items-start mb-5 pb-4 border-bottom">
        <div>
            <h1 class="fw-bold text-uppercase m-0" style="letter-spacing: 2px; color: #1e1b4b;">NUSA BIZ</h1>
            <p class="text-secondary m-0 fw-bold">INTELLIGENCE FINANCIAL REPORT</p>
            <p class="small text-muted mt-2">Official Multi-Branch ERP System Document</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold mb-1">LAPORAN LABA RUGI</h4>
            <p class="text-secondary mb-1">Periode: {{ \Carbon\Carbon::parse($startDate)->format('d/m/Y') }} - {{ \Carbon\Carbon::parse($endDate)->format('d/m/Y') }}</p>
            <span class="badge bg-dark px-3 py-2" style="font-size: 0.7rem;">CONFIDENTIAL / ORIGINAL</span>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="row mb-5 text-center g-0 border rounded-4 overflow-hidden">
        <div class="col-3 p-4 border-end bg-light">
            <p class="small text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.6rem;">Revenue</p>
            <h5 class="fw-bold mb-0">Rp {{ number_format($revenue, 0, ',', '.') }}</h5>
        </div>
        <div class="col-3 p-4 border-end bg-light">
            <p class="small text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.6rem;">COGS (HPP)</p>
            <h5 class="fw-bold mb-0">Rp {{ number_format($hpp, 0, ',', '.') }}</h5>
        </div>
        <div class="col-3 p-4 border-end bg-light">
            <p class="small text-uppercase text-secondary fw-bold mb-1" style="font-size: 0.6rem;">Gross Profit</p>
            <h5 class="fw-bold mb-0 text-primary">Rp {{ number_format($grossProfit, 0, ',', '.') }}</h5>
        </div>
        <div class="col-3 p-4 bg-dark text-white">
            <p class="small text-uppercase fw-bold mb-1" style="opacity: 0.8; font-size: 0.6rem;">Net Profit</p>
            <h5 class="fw-bold mb-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h5>
        </div>
    </div>

    <!-- Detailed breakdown -->
    <div class="row g-5">
        <div class="col-6">
            <h6 class="fw-bold text-uppercase mb-3 border-bottom pb-2" style="font-size: 0.75rem;">I. Pendapatan (Revenue)</h6>
            <table class="table table-sm table-borderless">
                @foreach($revenueDetails as $detail)
                <tr>
                    <td class="text-secondary small">{{ $detail->account->name }}</td>
                    <td class="text-end fw-medium small">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="fw-bold small">TOTAL REVENUE</td>
                    <td class="text-end fw-bold small">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                </tr>
            </table>

            <h6 class="fw-bold text-uppercase mt-5 mb-3 border-bottom pb-2" style="font-size: 0.75rem;">II. Harga Pokok Penjualan (COGS)</h6>
            <table class="table table-sm table-borderless">
                @foreach($hppDetails as $detail)
                <tr>
                    <td class="text-secondary small">{{ $detail->account->name }}</td>
                    <td class="text-end fw-medium small">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="fw-bold small">TOTAL COGS</td>
                    <td class="text-end fw-bold small">Rp {{ number_format($hpp, 0, ',', '.') }}</td>
                </tr>
            </table>
            
            <div class="mt-3 p-3 bg-light rounded text-end">
                <span class="small fw-bold text-uppercase text-secondary">Gross Profit: </span>
                <span class="fw-bold">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
            </div>
        </div>
        
        <div class="col-6">
            <h6 class="fw-bold text-uppercase mb-3 border-bottom pb-2" style="font-size: 0.75rem;">III. Biaya Operasional (Expenses)</h6>
            <table class="table table-sm table-borderless">
                @foreach($expenseDetails as $detail)
                <tr>
                    <td class="text-secondary small">{{ $detail->account->name }}</td>
                    <td class="text-end fw-medium small text-danger">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="fw-bold small">TOTAL EXPENSE</td>
                    <td class="text-end fw-bold small text-danger">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                </tr>
            </table>

            <div class="mt-5 p-4 bg-dark text-white rounded shadow-sm">
                <div class="d-flex justify-content-between align-items-center mb-1">
                    <span class="small text-uppercase fw-bold text-white text-opacity-50" style="font-size: 0.6rem;">Gross Profit</span>
                    <span class="small">Rp {{ number_format($grossProfit, 0, ',', '.') }}</span>
                </div>
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <span class="small text-uppercase fw-bold text-white text-opacity-50" style="font-size: 0.6rem;">Other Expenses</span>
                    <span class="small">({{ number_format($expense, 0, ',', '.') }})</span>
                </div>
                <div class="border-top border-secondary pt-3 d-flex justify-content-between align-items-center">
                    <span class="fw-bold text-uppercase" style="font-size: 0.8rem;">Net Profit Loss</span>
                    <h4 class="fw-bold m-0">Rp {{ number_format($netProfit, 0, ',', '.') }}</h4>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer Signature -->
    <div class="mt-5 pt-5">
        <div class="row text-center mt-5">
            <div class="col-4">
                <p class="small text-muted mb-5">Generated By System:</p>
                <p class="small fw-bold mt-2">NusaBiz Intelligence Bot</p>
                <div class="mx-auto border-bottom" style="width: 150px;"></div>
                <p class="small text-muted mt-1">{{ now()->format('d/m/Y H:i') }}</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <p class="small text-muted mb-5">Authorized Signature:</p>
                <div class="mx-auto border-bottom" style="width: 200px; height: 50px;"></div>
                <p class="small fw-bold mt-2">Finance Director / Manager</p>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 text-center text-muted smaller" style="font-size: 10px;">
        Dokumen ini sah dan diakui secara internal sebagai laporan performa keuangan periode berjalan.
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .sidebar, .navbar, .no-print, .btn { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .print-container { box-shadow: none !important; border: none !important; padding: 0 !important; max-width: 100% !important; }
}
</style>
@endsection
