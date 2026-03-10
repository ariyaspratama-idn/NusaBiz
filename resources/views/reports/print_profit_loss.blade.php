@extends('layouts.app')

@section('content')
<div class="row no-print mb-4">
    <div class="col-md-12 text-end">
        <button onclick="window.print()" class="btn btn-dark d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
            <i data-lucide="printer"></i> Cetak PDF Profesional
        </button>
    </div>
</div>

<div class="print-container p-5 bg-white shadow-sm mx-auto" style="max-width: 1000px; min-height: 1000px; border-top: 5px solid #4f46e5;">
    <!-- Company Header -->
    <div class="d-flex justify-content-between align-items-start mb-5 pb-4 border-bottom">
        <div>
            <h1 class="fw-bold text-uppercase m-0" style="letter-spacing: 2px; color: #1e293b;">NUSA BIZ</h1>
            <p class="text-secondary m-0">Ekosistem Bisnis Terpadu Indonesia</p>
            <p class="small text-muted mt-2">Gedung Sudirman Lantai 45, Jakarta Selatan</p>
        </div>
        <div class="text-end">
            <h4 class="fw-bold mb-1">LAPORAN LABA RUGI</h4>
            <p class="text-secondary mb-1">Periode: {{ $startDate }} - {{ $endDate }}</p>
            <span class="badge bg-primary px-3 py-2">ORIGINAL DOCUMENT</span>
        </div>
    </div>

    <!-- Summary Section -->
    <div class="row mb-5 text-center g-0 border rounded-4 overflow-hidden">
        <div class="col-4 p-4 border-end bg-light-subtle">
            <p class="small text-uppercase text-secondary fw-bold mb-1">Total Pendapatan</p>
            <h3 class="fw-bold mb-0 text-success">Rp {{ number_format($revenue, 0, ',', '.') }}</h3>
        </div>
        <div class="col-4 p-4 border-end bg-light-subtle">
            <p class="small text-uppercase text-secondary fw-bold mb-1">Total Pengeluaran</p>
            <h3 class="fw-bold mb-0 text-danger">Rp {{ number_format($expense, 0, ',', '.') }}</h3>
        </div>
        <div class="col-4 p-4 bg-primary text-white">
            <p class="small text-uppercase fw-bold mb-1" style="opacity: 0.8;">Laba/Rugi Bersih</p>
            <h3 class="fw-bold mb-0">Rp {{ number_format($revenue - $expense, 0, ',', '.') }}</h3>
        </div>
    </div>

    <!-- Detailed breakdown -->
    <div class="row">
        <div class="col-6 pe-4">
            <h6 class="fw-bold text-uppercase mb-3 border-bottom pb-2">Pendapatan (Revenue)</h6>
            <table class="table table-sm table-borderless">
                @foreach($revenueDetails as $detail)
                <tr>
                    <td class="text-secondary">{{ $detail->account->name }}</td>
                    <td class="text-end fw-medium">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="fw-bold">TOTAL REVENUE</td>
                    <td class="text-end fw-bold">Rp {{ number_format($revenue, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
        <div class="col-6 ps-4">
            <h6 class="fw-bold text-uppercase mb-3 border-bottom pb-2">Beban-Beban (Expense)</h6>
            <table class="table table-sm table-borderless">
                @foreach($expenseDetails as $detail)
                <tr>
                    <td class="text-secondary">{{ $detail->account->name }}</td>
                    <td class="text-end fw-medium">Rp {{ number_format($detail->total, 0, ',', '.') }}</td>
                </tr>
                @endforeach
                <tr class="border-top">
                    <td class="fw-bold">TOTAL EXPENSE</td>
                    <td class="text-end fw-bold">Rp {{ number_format($expense, 0, ',', '.') }}</td>
                </tr>
            </table>
        </div>
    </div>

    <!-- Footer Signature -->
    <div class="mt-5 pt-5">
        <div class="row text-center mt-5">
            <div class="col-4">
                <p class="small text-muted mb-5">Dibuat Oleh:</p>
                <div class="mx-auto border-bottom" style="width: 150px;"></div>
                <p class="small fw-bold mt-2">Accounting Staff</p>
            </div>
            <div class="col-4"></div>
            <div class="col-4">
                <p class="small text-muted mb-5">Disetujui Oleh:</p>
                <div class="mx-auto border-bottom" style="width: 150px;"></div>
                <p class="small fw-bold mt-2">Finance Manager</p>
            </div>
        </div>
    </div>

    <div class="mt-5 pt-5 text-center text-muted smaller" style="font-size: 10px;">
        Dokumen ini dihasilkan secara otomatis oleh NUSA BIZ ERP pada {{ now()->format('d/m/Y H:i') }}.
    </div>
</div>

<style>
@media print {
    body { background: white !important; }
    .sidebar, .navbar, .no-print, .btn { display: none !important; }
    .main-content { margin: 0 !important; padding: 0 !important; }
    .print-container { box-shadow: none !important; border: none !important; padding: 0 !important; max-width: 100% !important; }
    .card { box-shadow: none !important; border: 1px solid #eee !important; }
}
</style>
@endsection
