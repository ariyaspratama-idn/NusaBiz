@extends('layouts.admin')
@section('title', 'Edit Artikel: ' . $article->title)
@section('page_title', 'CMS - Edit Konten')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Edit Artikel</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Perbarui konten artikul yang sudah ada</p>
    </div>
    <a href="{{ route('admin.cms.articles') }}" class="btn btn-outline">
        <i class="fa-solid fa-arrow-left"></i> <span>Kembali ke Daftar</span>
    </a>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="card-body" style="padding: 32px;">
        <form action="{{ route('admin.cms.articles.update', $article) }}" method="POST" enctype="multipart/form-data">
            @csrf @method('PUT')
            <div style="display:grid; grid-template-columns: 2fr 1fr; gap:32px;">
                <!-- Main Editor Area -->
                <div style="display:flex; flex-direction:column; gap:24px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Judul Artikel</label>
                        <input type="text" name="title" value="{{ old('title', $article->title) }}" required 
                               style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 18px; font-weight: 700; outline: none; transition: var(--transition);"
                               onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Ringkasan (Excerpt)</label>
                        <textarea name="excerpt" rows="3" style="width: 100%; padding: 14px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none;">{{ old('excerpt', $article->excerpt) }}</textarea>
                    </div>

                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Isi Konten</label>
                        <textarea name="body" rows="15" required style="width: 100%; padding: 20px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 15px; line-height: 1.8; outline: none;">{{ old('body', $article->body) }}</textarea>
                    </div>
                </div>

                <!-- Sidebar Settings -->
                <div style="display:flex; flex-direction:column; gap:24px;">
                    <div style="background: var(--bg-card-2); padding:24px; border-radius:20px; border:1px solid var(--border);">
                        <h4 style="font-size:14px; font-weight:800; color:var(--text-main); margin-bottom:20px; text-transform:uppercase;">Pengaturan Publikasi</h4>
                        
                        <div style="margin-bottom:20px;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Kategori</label>
                            <select name="category" style="width:100%; padding:10px 14px; background: var(--bg-main); color: var(--text-main); border:1px solid var(--border); border-radius:10px; font-size:14px;">
                                <option value="berita" {{ $article->category == 'berita' ? 'selected' : '' }}>Berita</option>
                                <option value="artikel" {{ $article->category == 'artikel' ? 'selected' : '' }}>Artikel</option>
                                <option value="promo" {{ $article->category == 'promo' ? 'selected' : '' }}>Promo</option>
                            </select>
                        </div>

                        <div style="margin-bottom:20px;">
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Status</label>
                            <select name="status" style="width:100%; padding:10px 14px; background: var(--bg-main); color: var(--text-main); border:1px solid var(--border); border-radius:10px; font-size:14px;">
                                <option value="draft" {{ $article->status == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ $article->status == 'published' ? 'selected' : '' }}>Published</option>
                            </select>
                        </div>

                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">Gambar Unggulan</label>
                            @if($article->featured_image)
                            <img src="{{ asset('storage/'.$article->featured_image) }}" style="width:100%; border-radius:10px; margin-bottom:12px; border:1px solid var(--border); box-shadow: 0 4px 12px rgba(0,0,0,0.3);">
                            @endif
                            <input type="file" name="featured_image" style="font-size:12px; width:100%; color: var(--text-muted);">
                        </div>
                    </div>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; padding-top: 32px; border-top: 1px solid var(--border); margin-top: 32px;">
                <button type="reset" class="btn btn-outline" style="padding: 12px 24px;">Reset</button>
                <button type="submit" class="btn btn-primary" style="padding: 12px 40px;">
                    <i class="fa-solid fa-save"></i> <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>
@endsection
