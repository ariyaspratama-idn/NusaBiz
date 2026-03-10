@extends('layouts.admin')
@section('title', 'Laporan & Intelijen Bisnis')
@section('page_title', 'Analisis Performa')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Financial Intelligence</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Analisis performa bisnis dan profitabilitas secara komprehensif</p>
    </div>
</div>

<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap:24px;">
    <!-- Profit & Loss -->
    <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:white; padding:32px; transition:var(--transition);" onmouseover="this.style.transform='translateY(-6px)'; this.style.boxShadow='var(--shadow-lg)'" onmouseout="this.style.transform='none'; this.style.boxShadow='var(--shadow-sm)'">
        <div style="width:56px;height:56px;background:#f0f9ff;color:#0284c7;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <i class="fa-solid fa-chart-line" style="font-size:24px;"></i>
        </div>
        <h3 style="font-size:18px; font-weight:800; color:var(--text-main); margin-bottom:8px;">Laporan Laba Rugi</h3>
        <p style="font-size:13px; color:var(--text-muted); line-height:1.6; margin-bottom:24px;">Pantau pendapatan, biaya operasional, dan margin laba bersih perusahaan dalam periode tertentu.</p>
        <a href="{{ route('reports.profit_loss') }}" class="btn btn-outline" style="width:100%; justify-content:center; padding:12px;">Lihat Analitik</a>
    </div>

    <!-- SOP Compliance -->
    <div class="card" style="border-radius:24px; border:none; box-shadow:var(--shadow); background: linear-gradient(135deg, #06b6d4 0%, #0891b2 100%); padding:32px; color:white; transition:var(--transition);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">
        <div style="width:56px;height:56px;background:rgba(255,255,255,0.2);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <i class="fa-solid fa-clipboard-check" style="font-size:24px;"></i>
        </div>
        <h3 style="font-size:18px; font-weight:800; margin-bottom:8px;">Kepatuhan SOP</h3>
        <p style="font-size:13px; opacity:0.9; line-height:1.6; margin-bottom:24px;">Monitoring bukti aktivitas operasional cabang sesuai standar prosedur yang ditetapkan.</p>
        <a href="{{ route('reports.compliance') }}" class="btn" style="width:100%; justify-content:center; padding:12px; background:white; color:#0891b2; font-weight:800;">Buka Monitor</a>
    </div>

    <!-- Stock Orders -->
    <div class="card" style="border-radius:24px; border:none; box-shadow:var(--shadow); background: linear-gradient(135deg, #f59e0b 0%, #d97706 100%); padding:32px; color:white; transition:var(--transition);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">
        <div style="width:56px;height:56px;background:rgba(255,255,255,0.2);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <i class="fa-solid fa-truck-ramp-box" style="font-size:24px;"></i>
        </div>
        <h3 style="font-size:18px; font-weight:800; margin-bottom:8px;">Pesanan Stok Cabang</h3>
        <p style="font-size:13px; opacity:0.9; line-height:1.6; margin-bottom:24px;">Kelola permintaan stok inventaris dari seluruh cabang operasional secara terpusat.</p>
        <a href="{{ route('reports.stock_monitor') }}" class="btn" style="width:100%; justify-content:center; padding:12px; background:white; color:#d97706; font-weight:800;">Lihat Pesanan</a>
    </div>

    <!-- Complaint Feed -->
    <div class="card" style="border-radius:24px; border:none; box-shadow:var(--shadow); background: linear-gradient(135deg, #ef4444 0%, #b91c1c 100%); padding:32px; color:white; transition:var(--transition);" onmouseover="this.style.transform='translateY(-6px)'" onmouseout="this.style.transform='none'">
        <div style="width:56px;height:56px;background:rgba(255,255,255,0.2);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <i class="fa-solid fa-triangle-exclamation" style="font-size:24px;"></i>
        </div>
        <h3 style="font-size:18px; font-weight:800; margin-bottom:8px;">Keluhan & Masalah</h3>
        <p style="font-size:13px; opacity:0.9; line-height:1.6; margin-bottom:24px;">Monitoring real-time komplain pelanggan dan kendala teknis dari lapangan.</p>
        <a href="{{ route('reports.complaints_monitor') }}" class="btn" style="width:100%; justify-content:center; padding:12px; background:white; color:#b91c1c; font-weight:800;">Pantau Keluhan</a>
    </div>

    <!-- Balance Sheet (Soon) -->
    <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:white; padding:32px; opacity: 0.6;">
        <div style="width:56px;height:56px;background:#f8fafc;color:#94a3b8;border-radius:16px;display:flex;align-items:center;justify-content:center;margin-bottom:24px;">
            <i class="fa-solid fa-scale-balanced" style="font-size:24px;"></i>
        </div>
        <h3 style="font-size:18px; font-weight:800; color:var(--text-main); margin-bottom:8px;">Neraca Keuangan</h3>
        <p style="font-size:13px; color:var(--text-muted); line-height:1.6; margin-bottom:24px;">Ringkasan posisi aset, kewajiban, dan ekuitas perusahaan secara real-time.</p>
        <button class="btn btn-outline" style="width:100%; justify-content:center; padding:12px;" disabled>Segera Hadir</button>
    </div>
</div>
@endsection
