@extends('layouts.admin')
@section('title', 'Manajemen Produk')
@section('page_title', 'Manajemen Produk')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Katalog Produk</h2>
        <div style="display:flex;align-items:center;gap:12px;margin-top:4px;">
            <p style="font-size:13px;color:var(--text-muted);">Total {{ $products->total() }} produk terdaftar</p>
            @if($lowStockCount > 0)
            <span style="background: rgba(244,63,94,0.1); color: var(--danger); font-size: 11px; font-weight: 700; padding: 2px 10px; border-radius: 50px; border: 1px solid rgba(244,63,94,0.2);">
                <i class="fa-solid fa-triangle-exclamation"></i> {{ $lowStockCount }} Stok Kritis
            </span>
            @endif
        </div>
    </div>
    <div style="display:flex;gap:12px;">
        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-plus"></i> <span>Tambah Produk Baru</span>
        </a>
    </div>
</div>

<!-- Filter Section -->
<div class="card" style="margin-bottom:24px; border:1px solid var(--border); box-shadow: var(--shadow-sm); background: var(--bg-card-2);">
    <div class="card-body" style="padding:16px 24px;">
        <form method="GET" style="display:flex;gap:16px;flex-wrap:wrap;align-items:center;">
            <div style="flex:1; min-width:280px; position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:16px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:14px;"></i>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama produk atau SKU..." 
                       style="width:100%; padding: 12px 16px 12px 44px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none; transition:var(--transition);"
                       onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                       onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
            </div>
            
            <select name="category_id" style="padding:11px 16px; border:1px solid var(--border); border-radius:12px; font-size:14px; background:var(--bg-main); color:var(--text-main); cursor:pointer; outline:none;">
                <option value="">Semua Kategori</option>
                @foreach($categories as $cat)
                <option value="{{ $cat->id }}" {{ request('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>

            <select name="status" style="padding:11px 16px; border:1px solid var(--border); border-radius:12px; font-size:14px; background:var(--bg-main); color:var(--text-main); cursor:pointer; outline:none;">
                <option value="">Status: Semua</option>
                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Aktif</option>
                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Non-Aktif</option>
                <option value="out_of_stock" {{ request('status') == 'out_of_stock' ? 'selected' : '' }}>Stok Habis</option>
            </select>

            <button type="submit" class="btn btn-outline" style="padding: 11px 24px;">
                <i class="fa-solid fa-filter"></i> <span>Filter</span>
            </button>
        </form>
    </div>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: rgba(255,255,255,0.02);">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Produk</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Kategori</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Harga</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Stok</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $product)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display:flex;align-items:center;gap:14px;">
                            @if($product->main_image)
                            <img src="{{ asset('storage/'.$product->main_image) }}" alt="" style="width:48px;height:48px;object-fit:cover;border-radius:12px; box-shadow: 0 4px 10px rgba(0,0,0,0.25);">
                            @else
                            <div style="width:48px;height:48px;background:var(--bg-main);border-radius:12px;display:flex;align-items:center;justify-content:center;color:var(--text-muted); border:1px solid var(--border);"><i class="fa-solid fa-image" style="font-size:20px;"></i></div>
                            @endif
                            <div>
                                <div style="font-weight:700; color: var(--text-main); font-size: 15px;">{{ $product->name }}</div>
                                <div style="font-size:12px;color:var(--text-muted); margin-top: 2px;">SKU: <span style="font-family:monospace; font-weight: 600;">{{ $product->sku ?? 'N/A' }}</span></div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <span style="background: var(--bg-main); color: var(--text-muted); font-size: 11px; font-weight: 700; padding: 4px 10px; border-radius: 6px; border: 1px solid var(--border);">{{ $product->category?->name ?? 'Uncategorized' }}</span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-weight:800; color: var(--text-main); font-size: 15px;">Rp {{ number_format($product->price, 0, ',', '.') }}</div>
                        @if($product->sale_price)
                        <div style="font-size:11px;color:var(--danger); margin-top: 2px;"><del>Rp {{ number_format($product->sale_price, 0, ',', '.') }}</del></div>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display:flex; flex-direction: column;">
                            <span style="font-weight:800; color: {{ $product->isLowStock() ? 'var(--danger)' : 'var(--text-main)' }}; font-size: 15px;">{{ $product->stock }} <span style="font-size:11px; font-weight: 500; color: var(--text-muted);">Pcs</span></span>
                            @if($product->isLowStock())
                            <span style="font-size:10px; color:var(--danger); font-weight: 700; margin-top: 2px;">● Stok Kritis</span>
                            @endif
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @if($product->status === 'active')
                        <span class="badge badge-success">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor"></span>
                            Aktif
                        </span>
                        @elseif($product->status === 'out_of_stock')
                        <span class="badge badge-danger">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor"></span>
                            Habis
                        </span>
                        @else
                        <span class="badge badge-gray">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor"></span>
                            Non-Aktif
                        </span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        <div style="display:flex;gap:8px; justify-content: flex-end;">
                            <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px;" title="Edit">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.products.destroy', $product) }}" onsubmit="return confirm('Hapus produk ini secara permanen?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px; color: var(--danger); border-color: rgba(244,63,94,0.2);" onmouseover="this.style.background='rgba(244,63,94,0.1)'" onmouseout="this.style.background='transparent'" title="Hapus">
                                    <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center;padding:64px 24px;color:var(--text-muted);">
                        <div style="display: flex; flex-direction: column; align-items: center; gap: 12px;">
                            <i class="fa-solid fa-box-open" style="font-size: 48px; opacity: 0.1;"></i>
                            <div style="font-weight: 600;">Belum ada produk yang ditemukan</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($products->hasPages())
    <div style="padding:24px; background: rgba(255,255,255,0.01); border-top:1px solid var(--border);">
        {{ $products->links() }}
    </div>
    @endif
</div>
@endsection
