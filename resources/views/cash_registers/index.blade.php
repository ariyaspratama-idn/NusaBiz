@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        color: white;
    }
    .btn-action {
        background: #6366f1;
        color: white;
        border: none;
        padding: 12px 24px;
        border-radius: 14px;
        font-weight: 600;
        transition: all 0.2s;
    }
    .btn-action:hover { background: #818cf8; transform: translateY(-2px); }
    
    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 20px;
        margin-bottom: 30px;
    }
    .stat-box {
        background: rgba(15, 23, 42, 0.3);
        padding: 20px;
        border-radius: 16px;
        border: 1px solid rgba(255,255,255,0.05);
    }
    input, select {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        border-radius: 12px;
        padding: 12px;
        width: 100%;
        margin-top: 8px;
    }
</style>

<div class="container py-5">
    <div class="mb-4">
        <h1 class="h2 text-white mb-1">🏧 Manajemen Shift Kasir</h1>
        <p class="text-muted">Pastikan uang di laci sama dengan catatan aplikasi.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            @if($activeRegister)
                <div class="glass-card mb-4 border-success">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h3 class="h4 mb-0 text-success">🟢 Shift Sedang Berjalan</h3>
                        <span class="badge bg-success">OPEN</span>
                    </div>

                    <div class="stats-grid">
                        <div class="stat-box">
                            <small class="text-muted d-block mb-1">Buka Sejak</small>
                            <span class="h5">{{ $activeRegister->opened_at->format('H:i, d M Y') }}</span>
                        </div>
                        <div class="stat-box">
                            <small class="text-muted d-block mb-1">Modal Awal</small>
                            <span class="h5">Rp {{ number_format($activeRegister->opening_balance, 0, ',', '.') }}</span>
                        </div>
                        @php
                            $salesSum = \App\Models\Transaction::where('cash_register_id', $activeRegister->id)
                                ->where('type', 'INCOME')
                                ->where('payment_status', 'PAID')
                                ->sum('amount');
                        @endphp
                        <div class="stat-box">
                            <small class="text-muted d-block mb-1">Total Penjualan Tunai</small>
                            <span class="h5 text-info">Rp {{ number_format($salesSum, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <div class="bg-dark p-4 rounded-4 mb-4" style="border: 1px dashed #4ade80;">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="h5 mb-0">Estimasi Uang di Laci (System):</span>
                            <span class="h4 mb-0 text-white">Rp {{ number_format($activeRegister->opening_balance + $salesSum, 0, ',', '.') }}</span>
                        </div>
                    </div>

                    <form action="{{ route('shifts.close', $activeRegister->id) }}" method="POST">
                        @csrf
                        <div class="mb-4">
                            <label class="text-white small fw-bold">HITUNG FISIK: Masukkan Total Uang Tunai di Laci Saat Ini</label>
                            <input type="number" name="closing_physical_balance" placeholder="0" required class="form-control-lg">
                            <small class="text-muted mt-2 d-block">Sistem akan menghitung selisih (discrepancy) secara otomatis.</small>
                        </div>
                        <button type="submit" class="btn-action w-100 bg-danger">🔴 Tutup Shift & Selesaikan Rekonsiliasi</button>
                    </form>
                </div>
            @else
                <div class="glass-card mb-4">
                    <h3 class="h4 mb-4">🚪 Buka Shift Baru</h3>
                    <form action="{{ route('shifts.open') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Cabang</label>
                                @php
                                    $branches = \App\Models\Branch::where('is_active', true)->get();
                                @endphp
                                <select name="branch_id" required>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="small text-muted">Modal Awal (Uang di Laci)</label>
                                <input type="number" name="opening_balance" value="0" required>
                            </div>
                        </div>
                        <button type="submit" class="btn-action w-100 mt-3">🚀 Mulai Sesi Kasir Baru</button>
                    </form>
                </div>
            @endif

            <div class="glass-card">
                <h3 class="h4 mb-4">📜 Riwayat Shift Terakhir</h3>
                <div class="table-responsive">
                    <table class="table table-dark table-hover mb-0">
                        <thead>
                            <tr>
                                <th>BUKA</th>
                                <th>TUTUP</th>
                                <th>SYSTEM</th>
                                <th>FISIK</th>
                                <th>SELISIH</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($history as $h)
                            <tr>
                                <td>{{ $h->opened_at->format('d/m H:i') }}</td>
                                <td>{{ $h->closed_at->format('d/m H:i') }}</td>
                                <td>Rp {{ number_format($h->closing_system_balance, 0, ',', '.') }}</td>
                                <td>Rp {{ number_format($h->closing_physical_balance, 0, ',', '.') }}</td>
                                <td class="{{ $h->discrepancy < 0 ? 'text-danger' : ($h->discrepancy > 0 ? 'text-success' : '') }}">
                                    Rp {{ number_format($h->discrepancy, 0, ',', '.') }}
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="glass-card mb-4">
                <h4 class="h5 mb-3 text-primary-light">💡 Tips Visi BuBeKu</h4>
                <p class="small text-muted">Selalu hitung uang di laci fisik di awal dan akhir hari. Jika ada selisih, sistem akan mencatatnya sebagai 'discrepancy' yang bisa dipantau oleh auditor.</p>
                <hr class="opacity-10">
                <p class="small text-muted mb-0">Gunakan fitur <strong>POS Lite</strong> untuk transaksi harian agar stok dan jurnal keuangan terupdate secara otomatis ke server pusat.</p>
            </div>
        </div>
    </div>
</div>
@endsection
