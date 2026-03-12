@extends('layouts.admin')

@section('title', 'Analisis Keuangan Lanjutan')
@section('page_title', 'Analisis Keuangan Lanjutan')

@section('content')
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap:24px; margin-bottom:32px;">
    <!-- Stat Cards -->
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(99,102,241,0.1); color:var(--primary);"><i class="fa-solid fa-chart-line"></i></div>
        <div style="color:var(--text-muted); font-size:14px; font-weight:600;">Tren Pendapatan</div>
        <div style="font-size:28px; font-weight:800; margin-top:8px;">+12.5%</div>
        <div style="font-size:12px; color:var(--success); margin-top:4px;"><i class="fa-solid fa-arrow-up"></i> Naik dari bulan lalu</div>
    </div>
    
    <div class="stat-card">
        <div class="stat-icon" style="background:rgba(16,185,129,0.1); color:var(--success);"><i class="fa-solid fa-wallet"></i></div>
        <div style="color:var(--text-muted); font-size:14px; font-weight:600;">Laba Bersih Estimasi</div>
        <div style="font-size:28px; font-weight:800; margin-top:8px;">Rp 45.2M</div>
        <div style="font-size:12px; color:var(--text-muted); margin-top:4px;">Berdasarkan transaksi terverifikasi</div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3 class="text-main">Top Kategori Penjualan</h3>
    </div>
    <div class="card-body" style="padding:24px;">
        @forelse($topKategori as $kat)
        <div style="margin-bottom:20px;">
            <div style="display:flex; justify-content:space-between; margin-bottom:8px;">
                <span style="font-weight:600;">{{ $kat->name }}</span>
                <span style="color:var(--primary-light);">{{ $kat->total_terjual }} terjual</span>
            </div>
            <div style="height:8px; background:rgba(255,255,255,0.05); border-radius:10px; overflow:hidden;">
                <div style="width:{{ min(100, $kat->total_terjual) }}%; height:100%; background:var(--primary);"></div>
            </div>
        </div>
        @empty
        <p style="text-align:center; color:var(--text-muted);">Belum ada data analisis yang tersedia.</p>
        @endforelse
    </div>
</div>
@endsection
