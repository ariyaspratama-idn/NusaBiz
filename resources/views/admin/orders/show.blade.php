@extends('layouts.admin')
@section('title', 'Detail Pesanan #' . $order->order_number)
@section('page_title', 'EC - Manajemen Invoice')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('admin.orders.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:var(--bg-card);transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Detail Pesanan #{{ $order->order_number }}</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Dibuat pada {{ $order->created_at->format('d M Y, H:i') }}</p>
        </div>
    </div>
    <div style="display:flex; gap:12px;">
        <button onclick="window.print()" class="btn btn-outline" style="border-radius:12px;">
            <i class="fa-solid fa-print"></i> <span>Cetak Invoice</span>
        </button>
        <button class="btn btn-primary" style="border-radius:12px; box-shadow:0 4px 12px rgba(99,102,241,0.2);">
            <i class="fa-solid fa-download"></i> <span>Unduh PDF</span>
        </button>
    </div>
</div>

<div style="display:grid; grid-template-columns: 2fr 1fr; gap:28px;">
    <!-- Bagian Utama -->
    <div style="display:flex; flex-direction:column; gap:28px;">
        <!-- Informasi Produk -->
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); overflow:hidden;">
            <div style="padding:20px 28px; border-bottom:1px solid var(--border); background:rgba(255,255,255,0.02); display:flex; align-items:center; justify-content:space-between;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); letter-spacing:0.5px; text-transform:uppercase;">Item Pesanan</h3>
                <span style="font-size:12px; color:var(--text-muted); font-weight:700;">TOTAL {{ $order->items->count() }} ITEM</span>
            </div>
            <div style="padding:0;">
                <table style="width:100%; border-collapse:collapse;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.01);">
                            <th style="padding:14px 28px; text-align:left; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Produk</th>
                            <th style="padding:14px 28px; text-align:center; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Harga</th>
                            <th style="padding:14px 28px; text-align:center; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Qty</th>
                            <th style="padding:14px 28px; text-align:right; font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; letter-spacing:1px; border-bottom:1px solid var(--border);">Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($order->items as $item)
                        <tr style="border-bottom:1px solid var(--border);">
                            <td style="padding:16px 28px; display:flex; align-items:center; gap:16px;">
                                <div style="width:56px;height:56px;background:var(--bg-main);border-radius:12px;display:flex;align-items:center;justify-content:center;overflow:hidden;border:1px solid var(--border);">
                                    @if($item->product->main_image)
                                        <img src="{{ asset('storage/'.$item->product->main_image) }}" style="width:100%;height:100%;object-fit:cover;">
                                    @else
                                        <i class="fa-solid fa-image" style="color:var(--border); font-size:20px;"></i>
                                    @endif
                                </div>
                                <div>
                                    <div style="font-size:14px; font-weight:700; color:var(--text-main);">{{ $item->product_name }}</div>
                                    <div style="font-size:11px; color:var(--text-muted); margin-top:4px;">SKU: {{ $item->product->sku ?? '-' }}</div>
                                </div>
                            </td>
                            <td style="padding:16px 28px; text-align:center; font-size:14px; color:var(--text-main); font-weight:600;">Rp {{ number_format($item->price, 0, ',', '.') }}</td>
                            <td style="padding:16px 28px; text-align:center; font-size:14px; color:var(--text-main); font-weight:600;">x{{ $item->quantity }}</td>
                            <td style="padding:16px 28px; text-align:right; font-size:14px; font-weight:800; color:var(--text-main);">Rp {{ number_format($item->subtotal, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <!-- Summaries -->
            <div style="padding:32px 28px; display:flex; justify-content:flex-end; background:rgba(255,255,255,0.01);">
                <div style="width:320px; display:flex; flex-direction:column; gap:14px;">
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--text-muted);">
                        <span>Subtotal Produk</span>
                        <span style="font-weight:700; color:var(--text-main);">Rp {{ number_format($order->subtotal, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; font-size:14px; color:var(--text-muted);">
                        <span>Biaya Pengiriman</span>
                        <span style="font-weight:700; color:var(--text-main);">Rp {{ number_format($order->shipping_cost, 0, ',', '.') }}</span>
                    </div>
                    <div style="display:flex; justify-content:space-between; padding-top:16px; border-top:1px dashed var(--border); margin-top:6px;">
                        <span style="font-weight:800; color:var(--text-main); font-size:16px;">Total Tagihan</span>
                        <span style="font-weight:800; color:var(--primary); font-size:22px; letter-spacing:-0.5px;">Rp {{ number_format($order->total, 0, ',', '.') }}</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alur Riwayat Status -->
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:32px;">
            <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:28px; text-transform:uppercase; letter-spacing:0.5px;">Riwayat Aktivitas</h3>
            <div style="display:flex; flex-direction:column; gap:24px; position:relative;">
                <div style="position:absolute; left:5px; top:10px; bottom:10px; width:2px; background:var(--border); z-index:0;"></div>
                @foreach($order->statusHistories as $history)
                <div style="display:flex; gap:20px; position:relative; z-index:1;">
                    <div style="width:12px; height:12px; border-radius:50%; background:var(--primary); border:2px solid var(--bg-card); box-shadow:0 0 0 4px rgba(99,102,241,0.1); margin-top:4px;"></div>
                    <div style="flex:1;">
                        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
                            <div>
                                <span style="font-weight:800; font-size:14px; color:var(--text-main); line-height:1;">{{ str_replace('_', ' ', ucfirst($history->status)) }}</span>
                                <p style="font-size:13px; color:var(--text-muted); margin-top:6px; line-height:1.5;">{{ $history->notes ?: 'Status diperbarui oleh sistem/admin.' }}</p>
                            </div>
                            <span style="font-size:11px; color:var(--text-muted); font-weight:600; text-transform:uppercase; background:var(--bg-main); padding:4px 8px; border-radius:6px; border:1px solid var(--border);">{{ $history->created_at->format('d M, H:i') }}</span>
                        </div>
                        <div style="font-size:11px; color:var(--primary); font-weight:700; margin-top:8px; display:flex; align-items:center; gap:6px;">
                            <i class="fa-solid fa-user-shield" style="font-size:10px;"></i>
                            <span>PETUGAS: {{ $history->admin->name ?? 'SYSTEM' }}</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>

    <!-- Sidebar Info -->
    <div style="display:flex; flex-direction:column; gap:28px;">
        <!-- Status Panel -->
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:28px;">
            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 16px;">Update Kontrol Pesanan</label>
            <form action="{{ route('admin.orders.update-status', $order) }}" method="POST">
                @csrf @method('PATCH')
                <select name="status" style="width:100%; padding:14px; border:1px solid var(--border); border-radius:12px; font-size:14px; font-weight:700; margin-bottom:16px; outline:none; background:var(--bg-main); color:var(--text-main);">
                    <option value="perlu_diproses" {{ $order->status == 'perlu_diproses' ? 'selected' : '' }}>🔸 Perlu Diproses</option>
                    <option value="diproses" {{ $order->status == 'diproses' ? 'selected' : '' }}>🔹 Sedang Diproses</option>
                    <option value="dikirim" {{ $order->status == 'dikirim' ? 'selected' : '' }}>🚚 Telah Dikirim</option>
                    <option value="selesai" {{ $order->status == 'selesai' ? 'selected' : '' }}>✅ Selesai / Sampai</option>
                    <option value="dibatalkan" {{ $order->status == 'dibatalkan' ? 'selected' : '' }}>❌ Batalkan Pesanan</option>
                </select>
                <div style="margin-bottom:16px;">
                    <label style="display: block; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; margin-left:4px;">No Resi / Tracking</label>
                    <input type="text" name="tracking_number" value="{{ $order->tracking_number }}" placeholder="Contoh: JNE123456789" style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px; font-size:14px;">
                </div>
                <div style="margin-bottom:20px;">
                    <label style="display: block; font-size: 10px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 6px; margin-left:4px;">Catatan Status</label>
                    <textarea name="notes" placeholder="Berikan info progres ke pelanggan..." style="width:100%; padding:12px; background:var(--bg-main); color:var(--text-main); border:1px solid var(--border); border-radius:12px; font-size:13px; height:80px;"></textarea>
                </div>
                <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:14px; box-shadow:0 8px 20px rgba(99,102,241,0.25);">Simpan Perubahan</button>
            </form>
        </div>

        <!-- Info Pelanggan -->
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:var(--bg-card); padding:28px;">
            <h4 style="font-size:11px; font-weight:800; color:var(--text-muted); text-transform:uppercase; margin-bottom:20px; letter-spacing:1px;">Profil Pelanggan</h4>
            <div style="display:flex; align-items:center; gap:16px; margin-bottom:24px;">
                <div style="width:52px;height:52px;background:var(--primary);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:20px; box-shadow:0 4px 12px rgba(99,102,241,0.3);">{{ strtoupper(substr($order->customer_name, 0, 1)) }}</div>
                <div>
                    <h4 style="font-size:16px; font-weight:800; color:var(--text-main);">{{ $order->customer_name }}</h4>
                    <span style="font-size:13px; color:var(--primary); font-weight:600;">{{ $order->customer_phone }}</span>
                </div>
            </div>
            <div style="border-top:1px solid var(--border); padding-top:20px; display:flex; flex-direction:column; gap:16px;">
                <div style="display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:var(--bg-main); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid var(--border);">
                        <i class="fa-solid fa-location-dot" style="color:var(--text-muted); font-size:12px;"></i>
                    </div>
                    <div style="font-size:13px; line-height:1.6; color:var(--text-main);">{{ $order->shipping_address }}</div>
                </div>
                <div style="display:flex; gap:12px;">
                    <div style="width:32px; height:32px; border-radius:10px; background:var(--bg-main); display:flex; align-items:center; justify-content:center; flex-shrink:0; border:1px solid var(--border);">
                        <i class="fa-solid fa-truck" style="color:var(--text-muted); font-size:12px;"></i>
                    </div>
                    <div style="font-size:13px; font-weight:700; color:var(--text-main); margin-top:6px;">{{ strtoupper($order->shipping_courier) }} ({{ $order->shipping_service }})</div>
                </div>
            </div>
        </div>

        <!-- Bukti Pembayaran -->
        <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:var(--bg-card); overflow:hidden;">
            <div style="padding:16px 24px; border-bottom:1px solid var(--border); background:rgba(255,255,255,0.02);">
                <h4 style="font-size:12px; font-weight:800; color:var(--text-main); text-transform:uppercase; letter-spacing:0.5px;">Validasi Pembayaran</h4>
            </div>
            <div style="padding:24px;">
                @if($order->payment_proof)
                <div style="position:relative; margin-bottom:16px;">
                    <a href="{{ asset('storage/'.$order->payment_proof) }}" target="_blank">
                        <img src="{{ asset('storage/'.$order->payment_proof) }}" style="width:100%; border-radius:16px; cursor:zoom-in; border:1px solid var(--border); box-shadow:0 8px 16px rgba(0,0,0,0.2);">
                    </a>
                    <div style="position:absolute; bottom:8px; right:8px; background:rgba(0,0,0,0.6); color:white; font-size:10px; padding:4px 10px; border-radius:20px; backdrop-filter:blur(4px);">Klik Perbesar</div>
                </div>
                
                @if($order->payment_status != 'paid')
                <form action="{{ route('admin.orders.verify-payment', $order) }}" method="POST">
                    @csrf @method('PATCH')
                    <button type="submit" class="btn btn-success" style="width:100%; justify-content:center; padding:12px; font-weight:800; background:#10b981; border:none; color:white; border-radius:12px; box-shadow:0 6px 12px rgba(16,185,129,0.2);">KONFIRMASI LUNAS</button>
                </form>
                @else
                <div style="padding:12px; background:rgba(16,185,129,0.1); border:1px solid rgba(16,185,129,0.2); border-radius:12px; text-align:center; color:#10b981; font-weight:800; font-size:13px;">
                    ✅ PEMBAYARAN TELAH DIVERIFIKASI
                </div>
                @endif
                
                @else
                <div style="text-align:center; padding:20px; background:var(--bg-main); border:2px dashed var(--border); border-radius:20px;">
                    <i class="fa-solid fa-receipt" style="font-size:32px; color:var(--border); margin-bottom:12px;"></i>
                    <p style="font-size:13px; color:var(--text-muted); font-weight:600;">Bukti transfer belum diunggah oleh pelanggan.</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection

