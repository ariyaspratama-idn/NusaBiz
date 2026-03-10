@extends('layouts.admin')
@section('title', 'Daftar Pesanan')
@section('page_title', 'EC - Manajemen Pesanan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Daftar Pesanan</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola pesanan pelanggan dan status pengiriman</p>
    </div>
</div>

<!-- Stat summary -->
<div style="display:grid; grid-template-columns: repeat(auto-fit, minmax(180px, 1fr)); gap:16px; margin-bottom:28px;">
    @foreach([
        ['label'=>'Menunggu Bayar','key'=>'menunggu','color'=>'#94a3b8', 'bg' => 'var(--bg-card)'],
        ['label'=>'Perlu Diproses','key'=>'perlu_diproses','color'=>'#fb923c', 'bg' => 'var(--bg-card)'],
        ['label'=>'Dalam Proses','key'=>'diproses','color'=>'#38bdf8', 'bg' => 'var(--bg-card)'],
        ['label'=>'Telah Dikirim','key'=>'dikirim','color'=>'#a78bfa', 'bg' => 'var(--bg-card)'],
    ] as $s)
    <div class="card" style="padding:24px; text-align:center; border: 1px solid var(--border); border-radius:20px; background: {{ $s['bg'] }}; transition: var(--transition); box-shadow:var(--shadow-sm);" onmouseover="this.style.borderColor='var(--primary)'; this.style.transform='translateY(-4px)'" onmouseout="this.style.borderColor='var(--border)'; this.style.transform='none'">
        <div style="font-size:32px; font-weight:800; color: var(--text-main); letter-spacing:-1px;">{{ $stats[$s['key']] }}</div>
        <div style="margin-top:8px;">
            <span style="font-size: 11px; font-weight: 800; text-transform: uppercase; letter-spacing: 1px; color: {{ $s['color'] }}">{{ $s['label'] }}</span>
        </div>
    </div>
    @endforeach
</div>

<!-- Filter Section -->
<div class="card" style="margin-bottom:24px; border:1px solid var(--border); box-shadow: var(--shadow-sm); background: var(--bg-card); border-radius:20px;">
    <div class="card-body" style="padding:20px 24px;">
        <form method="GET" style="display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
            <div style="flex:1; min-width:280px; position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:14px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari No. Order atau nama pelanggan..." 
                       style="width:100%; padding: 12px 16px 12px 44px; background:var(--bg-main); color:var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none; transition:var(--transition);"
                       onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                       onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
            </div>
            
            <select name="status" style="padding:11px 16px; border:1px solid var(--border); border-radius:12px; font-size:14px; background:var(--bg-main); color:var(--text-main); cursor:pointer; outline:none;">
                <option value="">Semua Status Pesanan</option>
                @foreach(['menunggu_pembayaran','perlu_diproses','diproses','dikirim','selesai','dibatalkan'] as $st)
                <option value="{{ $st }}" {{ request('status') == $st ? 'selected' : '' }}>{{ str_replace('_',' ',ucfirst($st)) }}</option>
                @endforeach
            </select>

            <select name="payment_status" style="padding:11px 16px; border:1px solid var(--border); border-radius:12px; font-size:14px; background:var(--bg-main); color:var(--text-main); cursor:pointer; outline:none;">
                <option value="">Semua Status Bayar</option>
                <option value="pending" {{ request('payment_status') == 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') == 'paid' ? 'selected' : '' }}>Lunas</option>
            </select>

            <button type="submit" class="btn btn-outline" style="padding: 11px 24px; border-radius:12px;">
                <i class="fa-solid fa-magnifying-glass"></i> <span>Cari</span>
            </button>
        </form>
    </div>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: rgba(255,255,255,0.02);">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">No. Pesanan</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Pelanggan</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Total</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Pengiriman</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Bayar</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <strong style="color:var(--primary); font-size: 14px; font-family: 'JetBrains Mono', monospace;">{{ $order->order_number }}</strong>
                        <div style="font-size: 11px; color: var(--text-muted); margin-top: 4px;">{{ $order->created_at->format('d M Y, H:i') }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:700; color: var(--text-main);">{{ $order->customer_name }}</div>
                        <div style="font-size:12px;color:var(--text-muted); margin-top: 4px;"><i class="fa-solid fa-phone" style="font-size:10px;"></i> {{ $order->customer_phone }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:800; color: var(--text-main); font-size: 15px;">Rp {{ number_format($order->total, 0, ',', '.') }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-size:13px; font-weight: 600; color: var(--text-main);">{{ ucfirst(str_replace('_',' ',$order->shipping_type)) }}</div>
                        <div style="font-size:11px;color:var(--text-muted); margin-top: 4px;">{{ $order->shipping_courier ?? 'Kurir Internal' }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @php
                            $pb = match($order->payment_status) {
                                'paid' => ['bg' => 'rgba(16,185,129,0.1)', 'text' => '#10b981', 'label' => 'Lunas'],
                                'failed' => ['bg' => 'rgba(239,68,68,0.1)', 'text' => '#ef4444', 'label' => 'Gagal'],
                                'refunded' => ['bg' => 'rgba(59,130,246,0.1)', 'text' => '#3b82f6', 'label' => 'Refund'],
                                default => ['bg' => 'rgba(245,158,11,0.1)', 'text' => '#f59e0b', 'label' => 'Pending']
                            };
                        @endphp
                        <span style="background: {{ $pb['bg'] }}; color: {{ $pb['text'] }}; font-size: 11px; font-weight: 800; padding: 4px 10px; border-radius: 6px; border: 1px solid {{ $pb['text'] }}20;">{{ $pb['label'] }}</span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @php
                            $sb = match($order->status) {
                                'perlu_diproses' => ['bg' => 'rgba(245,158,11,0.1)', 'text' => '#f59e0b', 'label' => 'Perlu Proses'],
                                'diproses' => ['bg' => 'rgba(59,130,246,0.1)', 'text' => '#3b82f6', 'label' => 'Diproses'],
                                'dikirim' => ['bg' => 'rgba(139,92,246,0.1)', 'text' => '#8b5cf6', 'label' => 'Dikirim'],
                                'selesai' => ['bg' => 'rgba(16,185,129,0.1)', 'text' => '#10b981', 'label' => 'Selesai'],
                                'dibatalkan' => ['bg' => 'rgba(239,68,68,0.1)', 'text' => '#ef4444', 'label' => 'Batal'],
                                default => ['bg' => 'rgba(148,163,184,0.1)', 'text' => '#94a3b8', 'label' => ucfirst($order->status)]
                            };
                        @endphp
                        <span style="background: {{ $sb['bg'] }}; color: {{ $sb['text'] }}; font-size: 11px; font-weight: 800; padding: 4px 12px; border-radius: 50px; display: inline-flex; align-items: center; gap: 6px; border: 1px solid {{ $sb['text'] }}20;">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: {{ $sb['text'] }}"></span>
                            {{ $sb['label'] }}
                        </span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-outline" style="padding: 8px 16px; font-size: 12px; border-radius: 10px; border-color:var(--border);">
                            <span>Detail Order</span> <i class="fa-solid fa-chevron-right" style="font-size:10px; margin-left:6px;"></i>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" style="text-align:center;padding:80px 24px;color:var(--text-muted);">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 16px;">
                            <i class="fa-solid fa-cart-flatbed" style="font-size: 48px; opacity: 0.1;"></i>
                            <div style="font-weight: 600; font-size:15px;">Belum ada pesanan yang masuk</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($orders->hasPages())
    <div style="padding:24px; border-top:1px solid var(--border);">
        {{ $orders->links() }}
    </div>
    @endif
</div>
@endsection

