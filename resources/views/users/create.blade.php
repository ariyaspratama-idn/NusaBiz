@extends('layouts.admin')
@section('title', 'Tambah Staff Baru')
@section('page_title', 'Konfigurasi Sistem')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('admin.users.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:white;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Registrasi Staff</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Tambahkan personel baru dan tentukan level akses mereka.</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.users.store') }}" method="POST">
    @csrf
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:32px;">
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:white; padding:32px;">
            <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:24px;">Informasi Akun</h3>
            
            <div style="margin-bottom:24px;">
                <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Lengkap</label>
                <input type="text" name="name" placeholder="Contoh: Budi Santoso" required 
                       style="width: 100%; padding: 14px 18px; border: 1px solid var(--border); border-radius: 12px; font-size: 15px; outline: none; transition: var(--transition);"
                       onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.1)'">
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Alamat Email</label>
                    <input type="email" name="email" placeholder="budi@nusabiz.com" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Password Awal</label>
                    <input type="password" name="password" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                </div>
            </div>

            <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px;">
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Peran (Role)</label>
                    <select name="role" required style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; background:#f8fafc;">
                        <option value="SUPER_ADMIN">SUPER ADMIN</option>
                        <option value="BRANCH_MANAGER">BRANCH MANAGER</option>
                        <option value="CASHIER" selected>CASHIER</option>
                        <option value="AUDITOR">AUDITOR</option>
                    </select>
                </div>
                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Penempatan Cabang</label>
                    <select name="branch_id" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; background:#f8fafc;">
                        <option value="">Kantor Pusat / Semua Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <div style="display:flex; flex-direction:column; gap:24px;">
            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:white; padding:28px;">
                <h4 style="font-size:14px; font-weight:800; color:var(--text-main); margin-bottom:16px;">Panduan Akses</h4>
                <ul style="padding-left:18px; margin:0; font-size:12px; color:var(--text-muted); display:flex; flex-direction:column; gap:10px;">
                    <li><strong>Super Admin:</strong> Akses penuh ke seluruh sistem.</li>
                    <li><strong>Branch Manager:</strong> Mengelola operasional cabang tertentu.</li>
                    <li><strong>Cashier:</strong> Akses terbatas ke menu POS.</li>
                    <li><strong>Auditor:</strong> Hak akses read-only untuk laporan keuangan.</li>
                </ul>
            </div>
            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:16px; font-size:16px;">
                <i class="fa-solid fa-user-check"></i> <span>Selesaikan Integrasi</span>
            </button>
            <a href="{{ route('admin.users.index') }}" class="btn btn-outline" style="width:100%; justify-content:center; padding:12px;">Batalkan</a>
        </div>
    </div>
</form>
@endsection
