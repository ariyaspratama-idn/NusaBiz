@extends('layouts.admin')
@section('title', 'Jurnal Transaksi')
@section('page_title', 'Aktivitas Keuangan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Jurnal Transaksi</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Pantau seluruh aktivitas keuangan masuk dan keluar secara real-time</p>
    </div>
    <a href="{{ route('transactions.create') }}" class="btn btn-primary">
        <i class="fa-solid fa-plus-circle"></i> <span>Catat Transaksi Baru</span>
    </a>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: #fcfcfd;">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Referensi</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Cabang & Tanggal</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Akun Terkait</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Jumlah</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Status Jurnal</th>
                </tr>
            </thead>
            <tbody>
                @foreach($transactions as $trx)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='#fbfaff'" onmouseout="this.style.background='white'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:800; color: var(--primary); font-family: monospace; font-size: 14px;">{{ $trx->transaction_no }}</div>
                        <div style="font-size:11px;color:var(--text-muted); margin-top: 2px;">Ref: {{ $trx->reference_no }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:700; color: var(--text-main); font-size: 14px;">{{ $trx->branch->name }}</div>
                        <div style="font-size:11px;color:var(--text-muted); margin-top: 2px;"><i class="fa-regular fa-calendar" style="margin-right:4px;"></i> {{ $trx->transaction_date->format('d M Y') }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:700; color: var(--text-main); font-size: 14px;">{{ $trx->account->name }}</div>
                        @php $badgeColor = $trx->type == 'INCOME' ? '#16a34a' : '#dc2626'; @endphp
                        <span style="font-size: 10px; font-weight: 800; color: {{ $badgeColor }}; text-transform: uppercase; letter-spacing: 0.5px;">
                            {{ $trx->type == 'INCOME' ? 'Pendapatan (+)' : 'Pengeluaran (-)' }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        <div style="font-weight:800; color: {{ $trx->type == 'INCOME' ? '#16a34a' : '#dc2626' }}; font-size: 15px;">
                            {{ $trx->type == 'INCOME' ? '+' : '-' }} Rp {{ number_format($trx->amount, 0, ',', '.') }}
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        @if($trx->journal_header_id)
                            <span style="background: #f0fdf4; color: #16a34a; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-check-circle"></i> Terpembukuan
                            </span>
                        @else
                            <span style="background: #fff7ed; color: #ea580c; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px;">
                                <i class="fa-solid fa-clock"></i> Tertunda
                            </span>
                        @endif
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>
@endsection
