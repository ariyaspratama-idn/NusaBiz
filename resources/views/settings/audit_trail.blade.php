@extends('layouts.admin')
@section('title', 'Audit Trail - Log Aktivitas')
@section('page_title', 'Keamanan & Transparansi')

@section('content')
<style>
    @media print {
        aside, .topbar, .btn, .nav-section-title, .sidebar-footer, .breadcrumb, .notification-wrapper, .alert {
            display: none !important;
        }
        #main {
            margin-left: 0 !important;
            padding: 0 !important;
        }
        .card {
            box-shadow: none !important;
            border: 1px solid #eee !important;
            border-radius: 0 !important;
        }
        .table-wrap {
            overflow: visible !important;
        }
        table {
            width: 100% !important;
            table-layout: fixed;
        }
        th, td {
            font-size: 10px !important;
            padding: 8px !important;
            word-wrap: break-word;
        }
        .print-header {
            display: block !important;
            text-align: center;
            margin-bottom: 30px;
        }
        body {
            background: white !important;
        }
    }
    .print-header { display: none; }
</style>

<div class="print-header">
    <h1 style="font-size: 28px; font-weight: 800; color: #1e293b; margin-bottom: 5px;">NusaBiz Enterprise Suite</h1>
    <h2 style="font-size: 18px; font-weight: 700; color: #64748b;">Laporan Resmi Jejak Audit (Audit Trail)</h2>
    <p style="font-size: 12px; color: #94a3b8; margin-top: 10px;">Dicetak pada: {{ now()->format('d M Y, H:i:s') }} oleh {{ auth()->user()->name }}</p>
    <hr style="border: 0; border-top: 2px solid #f1f5f9; margin: 20px 0;">
</div>

<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;" class="no-print">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Audit Trail🕵️‍♂️</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Pantau setiap perubahan data untuk keamanan dan transparansi total.</p>
    </div>
    <div style="display:flex; gap:12px;">
        <a href="{{ route('admin.audit-trail.export') }}" class="btn btn-outline">
            <i class="fa-solid fa-file-csv" style="color: #10b981;"></i> <span>Ekspor CSV</span>
        </a>
        <button class="btn btn-primary" onclick="window.print()">
            <i class="fa-solid fa-print"></i> <span>Cetak Laporan</span>
        </button>
    </div>
</div>

<div class="card" style="border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: white;">
    <div class="table-wrap">
        <table style="width:100%; border-collapse:collapse;">
            <thead>
                <tr style="background:#f8fafc;">
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border); width: 120px;">Waktu</th>
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border); width: 180px;">User</th>
                    <th style="padding:16px 24px; text-align:center; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border); width: 100px;">Event</th>
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border); width: 150px;">Model & ID</th>
                    <th style="padding:16px 24px; text-align:left; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Detail Perubahan</th>
                    <th style="padding:16px 24px; text-align:right; font-size:11px; font-weight:800; color:#64748b; text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border); width: 120px;" class="no-print">IP Address</th>
                </tr>
            </thead>
            <tbody>
                @forelse($logs as $log)
                <tr style="border-bottom:1px solid #f1f5f9; transition:var(--transition);" onmouseover="this.style.background='#fcfcfd'" onmouseout="this.style.background='transparent'">
                    <td style="padding:16px 24px;">
                        <span style="font-size:12px; font-weight:700; color:var(--text-main);">{{ $log->created_at->format('d M Y') }}</span>
                        <div style="font-size:11px; color:var(--text-muted); margin-top:2px;">{{ $log->created_at->format('H:i:s') }}</div>
                    </td>
                    <td style="padding:16px 24px;">
                        <div style="display:flex; align-items:center; gap:10px;">
                            <div style="width:28px;height:28px;background:var(--primary);color:white;border-radius:8px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:11px;flex-shrink:0;">
                                {{ strtoupper(substr($log->user->name ?? '?', 0, 1)) }}
                            </div>
                            <div style="overflow: hidden;">
                                <div style="font-size:13px; font-weight:700; color:var(--text-main); white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $log->user->name ?? 'System' }}</div>
                                <div style="font-size:11px; color:var(--text-muted);">{{ str_replace('_', ' ', $log->user->role ?? 'N/A') }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding:16px 24px; text-align:center;">
                        @php
                            $colors = match($log->event) {
                                'created' => ['bg' => '#f0fdf4', 'text' => '#16a34a'],
                                'updated' => ['bg' => '#fffbeb', 'text' => '#d97706'],
                                'deleted' => ['bg' => '#fef2f2', 'text' => '#dc2626'],
                                default   => ['bg' => '#f8fafc', 'text' => '#64748b']
                            };
                        @endphp
                        <span style="background:{{ $colors['bg'] }}; color:{{ $colors['text'] }}; font-size:10px; font-weight:800; padding:4px 10px; border-radius:6px; text-transform:uppercase;">{{ $log->event }}</span>
                    </td>
                    <td style="padding:16px 24px;">
                        <div style="font-size:13px; font-weight:700; color:var(--text-main);">{{ class_basename($log->auditable_type) }}</div>
                        <div style="font-size:11px; color:var(--primary); font-weight:700;">#{{ $log->auditable_id }}</div>
                    </td>
                    <td style="padding:16px 24px;">
                        @if($log->event === 'updated')
                            <div style="display:flex; flex-direction:column; gap:6px;">
                                @foreach($log->new_values ?? [] as $key => $new)
                                    <div style="font-size:11px; line-height:1.4; display: flex; align-items: center; gap: 8px;">
                                        <span style="font-weight:700; color:#64748b; width: 80px; flex-shrink: 0;">{{ str_replace('_', ' ', $key) }}:</span>
                                        <div style="display: flex; align-items: center; gap: 6px;">
                                            <span style="color:#94a3b8; text-decoration:line-through;">{{ is_array($log->old_values[$key] ?? '') ? '...' : ($log->old_values[$key] ?? '-') }}</span>
                                            <i class="fa-solid fa-arrow-right" style="font-size:9px; color:var(--primary);"></i>
                                            <span style="color:var(--success); font-weight:700;">{{ is_array($new) ? '...' : $new }}</span>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @elseif($log->event === 'created')
                            <div style="display:flex; align-items:center; gap:8px; color:var(--success); font-size:12px;">
                                <i class="fa-solid fa-circle-plus"></i> <span>Mendaftarkan data baru ke sistem.</span>
                            </div>
                        @else
                            <div style="display:flex; align-items:center; gap:8px; color:var(--danger); font-size:12px;">
                                <i class="fa-solid fa-trash-can"></i> <span>Menghapus data secara permanen.</span>
                            </div>
                        @endif
                    </td>
                    <td style="padding:16px 24px; text-align:right;" class="no-print">
                        <code style="font-size:10px; background:#f8fafc; padding:4px 8px; border-radius:6px; border:1px solid #e2e8f0; color:var(--text-muted); font-family: monospace;">{{ $log->ip_address }}</code>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="padding:64px; text-align:center;">
                        <i class="fa-solid fa-shield-halved" style="font-size:48px; color:#e2e8f0; margin-bottom:16px; display:block;"></i>
                        <h4 style="color:var(--text-main); font-weight:700;">Log Kosong</h4>
                        <p style="color:var(--text-muted); font-size:14px;">Belum ada aktivitas yang terekam dalam database.</p>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($logs->hasPages())
    <div style="padding: 24px; background: #fcfcfd; border-top: 1px solid var(--border);" class="no-print">
        {{ $logs->links() }}
    </div>
    @endif
</div>
@endsection
