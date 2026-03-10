@extends('layouts.admin')
@section('title', 'Edit Produk: ' . $product->name)
@section('page_title', 'CMS - Katalog Produk')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('admin.products.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:var(--bg-card);transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Edit Produk</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Perbarui spesifikasi, harga, atau ketersediaan stok produk</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.update', $product) }}" method="POST" enctype="multipart/form-data">
    @csrf @method('PUT')
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:32px;">
        <!-- Area Utama -->
        <div style="display:flex; flex-direction:column; gap:28px;">
            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:32px;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:24px; text-transform:uppercase; letter-spacing:0.5px;">Informasi Dasar</h3>
                
                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Lengkap Produk</label>
                    <input type="text" name="name" value="{{ old('name', $product->name) }}" required 
                           style="width: 100%; padding: 14px 18px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 15px; outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">SKU</label>
                        <input type="text" name="sku" value="{{ old('sku', $product->sku) }}" readonly 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-card-2); color: var(--text-muted); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; cursor:not-allowed; outline:none;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Kategori Produk</label>
                        <select name="category_id" style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}" {{ $product->category_id == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Deskripsi Lengkap</label>
                    <textarea name="description" rows="10" 
                              style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                              onfocus="this.style.borderColor='var(--primary)'">{{ old('description', $product->description) }}</textarea>
                </div>
            </div>

            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:32px;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:24px; text-transform:uppercase; letter-spacing:0.5px;">Harga & Inventaris</h3>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Harga Jual (Rp)</label>
                        <input type="number" name="price" value="{{ old('price', $product->price) }}" required 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight:700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Harga Sale (Rp)</label>
                        <input type="number" name="sale_price" value="{{ old('sale_price', $product->sale_price) }}" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Stok Saat Ini</label>
                        <input type="number" name="stock" value="{{ old('stock', $product->stock) }}" required 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Minimal Stok Alert</label>
                        <input type="number" name="min_stock_alert" value="{{ old('min_stock_alert', $product->min_stock_alert) }}" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Berat (Gram)</label>
                        <input type="number" name="weight" value="{{ old('weight', $product->weight) }}" required 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Config -->
        <div style="display:flex; flex-direction:column; gap:28px;">
            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow-sm); background:var(--bg-card); padding:28px;">
                <h4 style="font-size:14px; font-weight:800; color:var(--text-main); margin-bottom:20px; text-transform:uppercase; letter-spacing:0.5px;">Media & Status</h4>
                
                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Status Produk</label>
                    <select name="status" style="width:100%; padding:10px 14px; background: var(--bg-main); color: var(--text-main); border:1px solid var(--border); border-radius:10px; font-size:14px; font-weight:700;">
                        <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>🟢 Aktif</option>
                        <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>🔴 Non-aktif</option>
                        <option value="out_of_stock" {{ $product->status == 'out_of_stock' ? 'selected' : '' }}>🟠 Stok Habis</option>
                    </select>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Gambar Produk</label>
                    @if($product->main_image)
                    <div style="position:relative; margin-bottom:12px;">
                        <img src="{{ asset('storage/'.$product->main_image) }}" style="width:100%; border-radius:12px; border:1px solid var(--border); box-shadow: 0 4px 10px rgba(0,0,0,0.25);">
                        <div style="position:absolute; top:8px; right:8px; background:rgba(0,0,0,0.6); color:white; font-size:10px; padding:4px 8px; border-radius:20px; backdrop-filter: blur(4px);">Gambar Saat Ini</div>
                    </div>
                    @endif
                    <div style="border: 2px dashed var(--border); padding:24px; border-radius:16px; text-align:center; background:var(--bg-main);">
                        <input type="file" name="main_image" style="font-size:11px; width:100%; color: var(--text-muted);">
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding:12px; background:var(--bg-card-2); border-radius:12px; border:1px solid var(--border);">
                    <input type="checkbox" name="is_featured" value="1" id="featured" {{ $product->is_featured ? 'checked' : '' }} style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="featured" style="font-size:13px; font-weight:700; color:var(--text-main); cursor:pointer;">Produk Unggulan</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:16px; font-size:16px; box-shadow: 0 8px 24px rgba(99, 102, 241, 0.3);">
                <i class="fa-solid fa-cloud-arrow-up"></i> <span>Simpan Perubahan</span>
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="width:100%; justify-content:center; padding:12px;">Batalkan</a>
        </div>
    </div>
</form>
@endsection
