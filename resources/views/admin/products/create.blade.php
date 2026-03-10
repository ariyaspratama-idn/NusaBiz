@extends('layouts.admin')
@section('title', 'Tambah Produk Baru')
@section('page_title', 'CMS - Katalog Produk')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('admin.products.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:var(--bg-card);transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Tambah Produk Baru</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Masukkan detail dan spesifikasi produk untuk katalog E-commerce</p>
        </div>
    </div>
</div>

<form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
    @csrf
    <div style="display:grid; grid-template-columns: 2fr 1fr; gap:32px;">
        <!-- Area Utama -->
        <div style="display:flex; flex-direction:column; gap:28px;">
            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:32px;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:24px; text-transform:uppercase; letter-spacing:0.5px;">Informasi Dasar</h3>
                
                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Lengkap Produk</label>
                    <input type="text" name="name" required placeholder="Contoh: Suku Cadang Mesin A..." 
                           style="width: 100%; padding: 14px 18px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 15px; outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">SKU (Stock Keeping Unit)</label>
                        <input type="text" name="sku" placeholder="Contoh: SP-001..." 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline:none;"
                               onfocus="this.style.borderColor='var(--primary)'">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Kategori Produk</label>
                        <select name="category_id" style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                            <option value="">Pilih Kategori...</option>
                            @foreach($categories as $cat)
                                <option value="{{ $cat->id }}">{{ $cat->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div>
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Deskripsi Lengkap</label>
                    <textarea name="description" rows="10" placeholder="Jelaskan detail spesifikasi produk di sini..." 
                              style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                              onfocus="this.style.borderColor='var(--primary)'"></textarea>
                </div>
            </div>

            <div class="card" style="border-radius:24px; border:1px solid var(--border); box-shadow:var(--shadow); background:var(--bg-card); padding:32px;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); margin-bottom:24px; text-transform:uppercase; letter-spacing:0.5px;">Harga & Inventaris</h3>
                
                <div style="display:grid; grid-template-columns: 1fr 1fr; gap:24px; margin-bottom:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Harga Jual (Rp)</label>
                        <input type="number" name="price" required value="0" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight:700;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Harga Sale (Opsional)</label>
                        <input type="number" name="sale_price" value="0" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                </div>

                <div style="display:grid; grid-template-columns: 1fr 1fr 1fr; gap:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Stok Saat Ini</label>
                        <input type="number" name="stock" required value="0" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Minimal Stok Alert</label>
                        <input type="number" name="min_stock_alert" value="5" 
                               style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px;">
                    </div>
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Berat (Gram)</label>
                        <input type="number" name="weight" required value="1000" 
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
                        <option value="active">🟢 Aktif (Tampil di Web)</option>
                        <option value="inactive">🔴 Non-aktif</option>
                        <option value="out_of_stock">🟠 Stok Habis</option>
                    </select>
                </div>

                <div style="margin-bottom:24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Gambar Utama</label>
                    <div style="border: 2px dashed var(--border); padding:32px; border-radius:16px; text-align:center; background:var(--bg-main); transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'">
                        <i class="fa-solid fa-cloud-arrow-up" style="font-size:32px; color:var(--text-muted); margin-bottom:12px;"></i>
                        <input type="file" name="main_image" style="font-size:11px; width:100%; color: var(--text-muted);">
                        <p style="font-size:10px; color:var(--text-muted); margin-top:8px;">Format JPG, PNG (Max 2MB)</p>
                    </div>
                </div>

                <div style="display:flex; align-items:center; gap:10px; padding:12px; background:var(--bg-card-2); border-radius:12px; border:1px solid var(--border);">
                    <input type="checkbox" name="is_featured" value="1" id="featured" style="width:18px;height:18px;accent-color:var(--primary);">
                    <label for="featured" style="font-size:13px; font-weight:700; color:var(--text-main); cursor:pointer;">Produk Unggulan</label>
                </div>
            </div>

            <button type="submit" class="btn btn-primary" style="width:100%; justify-content:center; padding:16px; font-size:16px; box-shadow:0 8px 24px rgba(99, 102, 241, 0.3);">
                <i class="fa-solid fa-save"></i> <span>Daftarkan Produk</span>
            </button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline" style="width:100%; justify-content:center; padding:12px;">Batalkan</a>
        </div>
    </div>
</form>
@endsection
