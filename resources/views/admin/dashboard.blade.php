@extends('layouts.admin')
@section('title', 'Dashboard')
@section('page_title', 'Dashboard NusaBiz')

@section('content')
<!-- Stat Cards -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 20px; margin-bottom:32px;">

    <div class="stat-card" style="background: linear-gradient(135deg, #1c2333 0%, #1e2a42 100%);">
        <div class="stat-icon" style="background: rgba(99,102,241,0.15); color: #a5b4fc;"><i class="fa-solid fa-box-archive"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Katalog Produk</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ number_format($stats['total_produk']) }}</div>
            <div style="font-size: 11px; color: #a5b4fc; margin-top: 6px;"><i class="fa-solid fa-check-circle"></i> Terkelola sistem</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #1c2333 0%, #2a1e20 100%);">
        <div class="stat-icon" style="background: rgba(251,146,60,0.15); color: #fb923c;"><i class="fa-solid fa-cart-shopping"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Pesanan Baru</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ $stats['pesanan_baru'] }}</div>
            <div style="font-size: 11px; color: #fb923c; font-weight: 600; margin-top: 6px;"><i class="fa-solid fa-circle-exclamation"></i> Perlu segera diproses</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #1c2333 0%, #1a2b24 100%);">
        <div class="stat-icon" style="background: rgba(16,185,129,0.15); color: #34d399;"><i class="fa-solid fa-money-bill-trend-up"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Pendapatan Hari Ini</div>
            <div style="font-size: 20px; font-weight: 800; color: var(--text-main);">Rp {{ number_format($stats['pendapatan_hari_ini'], 0, ',', '.') }}</div>
            <div style="font-size: 11px; color: #34d399; font-weight: 600; margin-top: 6px;"><i class="fa-solid fa-arrow-trend-up"></i> Peningkatan 12% vs kemarin</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #1c2333 0%, #261a1c 100%);">
        <div class="stat-icon" style="background: rgba(244,63,94,0.15); color: #fb7185;"><i class="fa-solid fa-triangle-exclamation"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Stok Kritis</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ $stats['stok_kritis'] }}</div>
            <div style="font-size: 11px; color: #fb7185; font-weight: 600; margin-top: 6px;"><i class="fa-solid fa-bell"></i> Segera hubungi supplier</div>
        </div>
    </div>

    <div class="stat-card" style="background: linear-gradient(135deg, #1c2333 0%, #162434 100%);">
        <div class="stat-icon" style="background: rgba(34,211,238,0.15); color: #67e8f9;"><i class="fa-solid fa-comments"></i></div>
        <div>
            <div style="font-size: 13px; font-weight: 600; color: var(--text-muted); margin-bottom: 4px;">Chat Aktif</div>
            <div style="font-size: 28px; font-weight: 800; color: var(--text-main);">{{ $stats['chat_belum_dibaca'] }}</div>
            <div style="font-size: 11px; color: #67e8f9; font-weight: 600; margin-top: 6px;"><i class="fa-solid fa-circle-dot"></i> Pelanggan menunggu balasan</div>
        </div>
    </div>

</div>

<!-- Latest Orders -->
<div class="card">
    <div class="card-header">
        <h3 style="font-size: 17px; font-weight: 800; color: var(--text-main);">
            <i class="fa-solid fa-clock-rotate-left" style="color:var(--primary);margin-right:8px;"></i> Pesanan Terbaru
        </h3>
        <a href="{{ route('admin.orders.index') }}" class="btn btn-primary" style="padding: 8px 20px; font-size: 13px;">
            <span>Lihat Semua</span> <i class="fa-solid fa-arrow-right" style="font-size:12px;"></i>
        </a>
    </div>
    <div>
        <table>
            <thead>
                <tr>
                    <th>No. Pesanan</th>
                    <th>Pelanggan</th>
                    <th>Total</th>
                    <th>Status Bayar</th>
                    <th>Status Order</th>
                    <th>Tanggal</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($latestOrders as $order)
                <tr>
                    <td><strong style="color:var(--primary-light);">{{ $order->order_number }}</strong></td>
                    <td>
                        <div style="font-weight:600;color:var(--text-main);">{{ $order->customer_name }}</div>
                        <div style="font-size:11px;color:var(--text-muted);">{{ $order->customer_phone }}</div>
                    </td>
                    <td style="font-weight:700;">Rp {{ number_format($order->total, 0, ',', '.') }}</td>
                    <td>
                        @php
                            $payBadge = match($order->payment_status) {
                                'paid'      => 'badge-success',
                                'failed'    => 'badge-danger',
                                'refunded'  => 'badge-info',
                                default     => 'badge-warning',
                            };
                        @endphp
                        <span class="badge {{ $payBadge }}">{{ ucfirst($order->payment_status) }}</span>
                    </td>
                    <td>
                        @php
                            $statusBadge = match($order->status) {
                                'perlu_diproses' => 'badge-warning',
                                'diproses'       => 'badge-info',
                                'dikirim'        => 'badge-purple',
                                'selesai'        => 'badge-success',
                                'dibatalkan'     => 'badge-danger',
                                default          => 'badge-gray',
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ str_replace('_', ' ', ucfirst($order->status)) }}</span>
                    </td>
                    <td style="font-size:12px;color:var(--text-muted);">{{ $order->created_at->format('d M Y, H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 6px 14px; font-size: 12px; border-radius: 8px;">
                            <span>Detail</span> <i class="fa-solid fa-chevron-right" style="font-size:10px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">
                        <i class="fa-solid fa-inbox" style="font-size:28px;display:block;margin-bottom:10px;opacity:0.4;"></i>
                        Belum ada pesanan masuk
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
