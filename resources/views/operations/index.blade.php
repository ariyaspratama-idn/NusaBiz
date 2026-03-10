@extends('layouts.admin')
@section('title', 'Pusat Aktivitas Operasional')
@section('page_title', 'Manajemen Operasional')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Operations Center</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola kepatuhan SOP, keluhan, dan permintaan stok cabang</p>
    </div>
</div>

{{-- Global Branch Switcher --}}
<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow-sm); margin-bottom: 28px; background: white;">
    <div class="card-body" style="padding: 16px 24px; display: flex; align-items: center; justify-content: space-between;">
        <label style="font-size:13px; font-weight:700; color:#64748b; margin-bottom:0; display:flex; align-items:center; gap:10px;">
            <i class="fa-solid fa-map-location-dot" style="color:var(--primary);"></i> <span>SWITCH MONITORING CABANG:</span>
        </label>
        <form action="{{ route('operations.index') }}" method="GET" id="branchSwitcherForm">
            <select name="branch_id" onchange="this.form.submit()" 
                    style="padding: 10px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; color: var(--text-main); outline:none; cursor:pointer; background: #f8fafc;">
                @foreach($branches as $branch)
                    <option value="{{ $branch->id }}" {{ $selectedBranchId == $branch->id ? 'selected' : '' }}>{{ $branch->name }}</option>
                @endforeach
            </select>
        </form>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 1fr; gap:28px;">
    <!-- SOP Checklist Section (Full Width Top) -->
    <div style="grid-column: span 2;">
        <div class="card" style="border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); background: white; overflow: hidden;">
            <div style="background: #fcfcfd; border-bottom: 1px solid var(--border); padding: 20px 28px; display:flex; align-items:center; gap:12px;">
                <div style="width:40px;height:40px;background:#f0f9ff;color:#0284c7;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                    <i class="fa-solid fa-clipboard-check" style="font-size:20px;"></i>
                </div>
                <h3 style="font-size:18px; font-weight:800; color:var(--text-main);">Verifikasi Kepatuhan SOP</h3>
            </div>
            <div class="card-body" style="padding: 32px;">
                <form action="{{ route('operations.sop') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="branch_id" value="{{ $selectedBranchId }}">
                    <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Item SOP yang Dijalankan</label>
                            <select name="sop_id" style="width: 100%; padding: 14px 20px; border: 1px solid var(--border); border-radius: 14px; font-size: 15px; background: #f8fafc; outline:none; cursor:pointer;">
                                @forelse($sops as $sop)
                                    <option value="{{ $sop->id }}">[{{ strtoupper($sop->category) }}] {{ $sop->name }}</option>
                                @empty
                                    <option disabled>Tidak ada SOP untuk cabang ini</option>
                                @endforelse
                            </select>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Status Pelaksanaan</label>
                            <div style="display:flex; gap:20px; background:#f8fafc; padding:14px 20px; border-radius:14px; border:1px solid var(--border);">
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:700; color:#16a34a; font-size:14px;">
                                    <input type="radio" name="status" value="DONE" checked style="accent-color:#16a34a;"> Berhasil Dijalankan
                                </label>
                                <label style="display:flex; align-items:center; gap:8px; cursor:pointer; font-weight:700; color:#dc2626; font-size:14px;">
                                    <input type="radio" name="status" value="FAILED" style="accent-color:#dc2626;"> Gagal/Kendala
                                </label>
                            </div>
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Bukti Foto (Evidence)</label>
                            <input type="file" name="photo" style="width: 100%; padding: 10px; border: 1px solid var(--border); border-radius: 14px; background: #f8fafc; font-size:13px;">
                        </div>
                        <div style="grid-column: span 2;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 10px;">Catatan Tambahan</label>
                            <input type="text" name="notes" placeholder="Tulis catatan atau detail kendala di sini..." 
                                   style="width: 100%; padding: 14px 20px; border: 1px solid var(--border); border-radius: 14px; font-size: 14px; outline:none; transition:var(--transition);"
                                   onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.1)'">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary" style="width: 100%; padding: 16px; justify-content: center; margin-top: 32px; font-size: 16px;" @disabled($sops->isEmpty())>
                        <i class="fa-solid fa-paper-plane"></i> <span>Kirim Laporan Operasional</span>
                    </button>
                </form>
            </div>
        </div>
    </div>

    <!-- Complaint Section -->
    <div class="card" style="border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); background: white; overflow: hidden;">
        <div style="background: #fef2f2; border-bottom: 1px solid var(--border); padding: 20px 28px; display:flex; align-items:center; gap:12px;">
            <div style="width:40px;height:40px;background:#fee2e2;color:#dc2626;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-triangle-exclamation" style="font-size:20px;"></i>
            </div>
            <h3 style="font-size:18px; font-weight:800; color:#991b1b;">Input Keluhan Pelanggan</h3>
        </div>
        <div class="card-body" style="padding: 28px;">
            <form action="{{ route('operations.complaint') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Pilih Cabang Terkait</label>
                    <select name="branch_id" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none; background: #f8fafc;">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Deskripsi Keluhan</label>
                    <textarea name="description" rows="3" placeholder="Jelaskan detail keluhan pelanggan..." 
                              style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none; transition:var(--transition);"
                              onfocus="this.style.borderColor='#dc2626'"></textarea>
                </div>
                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Foto Bukti (Opsi)</label>
                    <input type="file" name="photo" style="width:100%; font-size:12px;">
                </div>
                <button type="submit" class="btn" style="width:100%; padding:14px; background:#dc2626; color:white; border:none; border-radius:12px; font-weight:800; justify-content:center;">
                    <i class="fa-solid fa-bullhorn"></i> <span>Laporkan Keluhan</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Stock Request Section -->
    <div class="card" style="border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); background: white; overflow: hidden;">
        <div style="background: #f8fafc; border-bottom: 1px solid var(--border); padding: 20px 28px; display:flex; align-items:center; gap:12px;">
            <div style="width:40px;height:40px;background:#f1f5f9;color:#1e293b;border-radius:12px;display:flex;align-items:center;justify-content:center;">
                <i class="fa-solid fa-box-open" style="font-size:20px;"></i>
            </div>
            <h3 style="font-size:18px; font-weight:800; color:var(--text-main);">Permintaan Stok Barang</h3>
        </div>
        <div class="card-body" style="padding: 28px;">
            <form action="{{ route('operations.stock') }}" method="POST">
                @csrf
                <div style="margin-bottom: 16px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Pilih Cabang</label>
                    <select name="branch_id" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; background: #f8fafc; outline:none;">
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:grid; grid-template-columns: 2fr 1fr; gap:16px; margin-bottom:16px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Barang</label>
                        <input type="text" name="item_name" placeholder="Nama item..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none;" required>
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Jumlah</label>
                        <input type="number" name="quantity" value="1" min="1" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none;" required>
                    </div>
                </div>
                <div style="margin-bottom:16px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Tujuan Permintaan</label>
                    <select name="purpose" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; background: #f8fafc; outline:none;">
                        <option value="STOCK_REPLENISHMENT">Pengisian Stok Ulang</option>
                        <option value="URGENT_SURGERY">Kebutuhan Mendesak/Servis</option>
                        <option value="NEW_INVENTORY">Inventaris Baru</option>
                    </select>
                </div>
                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Detail Alasan</label>
                    <input type="text" name="reason" placeholder="Contoh: Stok menipis..." style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none;" required>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; padding:14px; border-radius:12px; font-weight:800; justify-content:center;">
                    <i class="fa-solid fa-truck-fast"></i> <span>Ajukan Pengadaan Stok</span>
                </button>
            </form>
        </div>
    </div>
</div>
@endsection
