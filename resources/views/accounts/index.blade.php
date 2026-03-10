@extends('layouts.admin')
@section('title', 'Daftar Akun (COA)')
@section('page_title', 'Manajemen Akuntansi')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Daftar Akun (COA)</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Struktur bagan akun untuk pencatatan keuangan sistematis</p>
    </div>
    <a href="{{ route('accounts.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus-circle"></i> <span>Tambah Akun Baru</span>
    </a>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #fcfcfd;">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Kode Akun</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Nama Akun</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Tipe / Kategori</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Saldo Berjalan</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($accounts as $account)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='#fbfaff'" onmouseout="this.style.background='white'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <span style="background: #f1f5f9; color: var(--primary); font-family: monospace; font-size: 13px; font-weight: 800; padding: 6px 12px; border-radius: 8px; border: 1px solid #e2e8f0;">
                            {{ $account->code }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:700; color: var(--text-main); font-size: 14px;">{{ $account->name }}</div>
                        @if($account->description)
                            <div style="font-size:11px;color:var(--text-muted); margin-top: 2px;">{{ Str::limit($account->description, 50) }}</div>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @php
                            $colors = [
                                'ASSET' => ['bg' => '#f0f9ff', 'text' => '#0284c7'],
                                'LIABILITY' => ['bg' => '#fff1f2', 'text' => '#e11d48'],
                                'EQUITY' => ['bg' => '#f0fdf4', 'text' => '#16a34a'],
                                'REVENUE' => ['bg' => '#f5f3ff', 'text' => '#7c3aed'],
                                'EXPENSE' => ['bg' => '#fff7ed', 'text' => '#ea580c']
                            ];
                            $c = $colors[$account->type] ?? ['bg' => '#f8fafc', 'text' => '#64748b'];
                        @endphp
                        <span style="background: {{ $c['bg'] }}; color: {{ $c['text'] }}; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; letter-spacing: 0.5px;">{{ $account->type }}</span>
                        @if($account->category)
                        <div style="font-size: 10px; color: var(--text-muted); margin-top: 4px; font-weight: 600;">{{ $account->category }}</div>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        @php $balance = $account->balances->sum('current_balance'); @endphp
                        <div style="font-weight:800; color: {{ $balance < 0 ? '#e11d48' : 'var(--text-main)' }}; font-size: 15px;">
                            Rp {{ number_format($balance, 0, ',', '.') }}
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                         <div style="display:flex;gap:8px; justify-content: flex-end;">
                            <a href="{{ route('accounts.edit', $account) }}" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px;">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
