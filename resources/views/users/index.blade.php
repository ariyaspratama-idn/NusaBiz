@extends('layouts.admin')
@section('title', 'Manajemen Staff')
@section('page_title', 'Konfigurasi Sistem')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Manajemen Staff</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola hak akses administrator, manajer, dan auditor operasional.</p>
    </div>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-user-plus"></i> <span>Tambah Staff Baru</span>
    </a>
</div>

<div class="card" style="border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: white;">
    <div class="table-wrap">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Staff / Email</th>
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Peran (Role)</th>
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Cabang / Kantor</th>
                    <th style="padding:16px 24px; text-align:center; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Status</th>
                    <th style="padding:16px 24px; text-align:right; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($users as $user)
                <tr style="border-bottom:1px solid #f1f5f9; transition:var(--transition);" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding:16px 24px;">
                        <div style="display:flex; align-items:center; gap:12px;">
                            <div style="width:32px;height:32px;background:var(--primary);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:12px;">
                                {{ strtoupper(substr($user->name, 0, 1)) }}
                            </div>
                            <div>
                                <div style="font-size:13px; font-weight:700; color:var(--text-main);">{{ $user->name }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ $user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:16px 24px;">
                        @php
                            $roleBadge = match($user->role) {
                                'SUPER_ADMIN' => 'badge-danger',
                                'BRANCH_MANAGER' => 'badge-primary',
                                'CASHIER' => 'badge-success',
                                'AUDITOR' => 'badge-info',
                                default   => 'badge-secondary'
                            };
                        @endphp
                        <span class="badge {{ $roleBadge }}" style="font-size:10px; font-weight:700;">{{ str_replace('_', ' ', $user->role) }}</span>
                    </td>
                    <td style="padding:16px 24px; font-size:13px; color:var(--text-muted);">
                        <i class="fa-solid fa-building" style="font-size:11px; margin-right:6px; color:var(--primary);"></i>
                        {{ $user->branch->name ?? 'Kantor Pusat' }}
                    </td>
                    <td style="padding:16px 24px; text-align:center;">
                        <div style="display:flex; align-items:center; justify-content:center; gap:6px; color:var(--success); font-size:11px; font-weight:700;">
                            <div style="width:6px;height:6px;background:var(--success);border-radius:50%;"></div>
                            Aktif
                        </div>
                    </td>
                    <td style="padding:16px 24px; text-align:right;">
                        <div style="display:flex; justify-content:flex-end; gap:8px;">
                            <button class="icon-btn" style="width:32px;height:32px;border-radius:8px;border:1px solid var(--border);color:var(--text-muted);background:white;">
                                <i class="fa-solid fa-pen-to-square"></i>
                            </button>
                            <button class="icon-btn" style="width:32px;height:32px;border-radius:8px;border:1px solid var(--border);color:#ef4444;background:white;">
                                <i class="fa-solid fa-trash"></i>
                            </button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
