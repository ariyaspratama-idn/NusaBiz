@extends('layouts.admin')
@section('title', 'Manajemen Artikel')
@section('page_title', 'Artikel & Berita')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Manajemen Konten</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Publikasikan berita, artikel, dan promo terbaru</p>
    </div>
    <div style="display:flex;gap:12px;">
        <a href="{{ route('admin.cms.articles.create') }}" class="btn btn-primary">
            <i class="fa-solid fa-feather-pointed"></i> <span>Tulis Artikel Baru</span>
        </a>
    </div>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="table-wrap">
        <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
            <thead>
                <tr style="background: rgba(255,255,255,0.02);">
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Artikel</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Kategori</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Penulis</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Status</th>
                    <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Tanggal</th>
                    <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($articles as $article)
                <tr style="transition: background 0.2s;" onmouseover="this.style.background='rgba(255,255,255,0.02)'" onmouseout="this.style.background='transparent'">
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display:flex;align-items:center;gap:14px;">
                            @if($article->featured_image)
                            <img src="{{ asset('storage/'.$article->featured_image) }}" alt="" style="width:56px;height:40px;object-fit:cover;border-radius:8px; box-shadow: 0 4px 10px rgba(0,0,0,0.25);">
                            @else
                            <div style="width:56px;height:40px;background:var(--bg-main);border-radius:8px;display:flex;align-items:center;justify-content:center;color:var(--text-muted); border:1px solid var(--border);"><i class="fa-solid fa-image" style="font-size:16px;"></i></div>
                            @endif
                            <div style="max-width:300px;">
                                <div style="font-weight:700; color: var(--text-main); font-size: 14px; white-space: nowrap; overflow: hidden; text-overflow: ellipsis;">{{ $article->title }}</div>
                                <div style="font-size:11px;color:var(--text-muted); margin-top: 2px;">{{ Str::limit($article->excerpt, 60) }}</div>
                            </div>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @php
                            $catColor = match($article->category) {
                                'berita' => '#0284c7',
                                'artikel' => '#7c3aed',
                                'promo' => '#ea580c',
                                default => '#64748b'
                            };
                        @endphp
                        <span style="background: {{ $catColor }}20; color: {{ $catColor }}; font-size: 10px; font-weight: 800; padding: 4px 10px; border-radius: 6px; text-transform: uppercase; border: 1px solid {{ $catColor }}30;">{{ $article->category }}</span>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="display:flex; align-items:center; gap:8px;">
                            <div style="width:24px;height:24px;background:var(--primary);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:700;">{{ strtoupper(substr($article->author->name, 0, 1)) }}</div>
                            <span style="font-size:13px; font-weight:600; color:var(--text-main);">{{ $article->author->name }}</span>
                        </div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        @if($article->status === 'published')
                        <span class="badge badge-success">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor"></span>
                            Publik
                        </span>
                        @else
                        <span class="badge badge-gray">
                            <span style="width: 6px; height: 6px; border-radius: 50%; background: currentColor"></span>
                            Draft
                        </span>
                        @endif
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                        <div style="font-size:12px;color:var(--text-main);font-weight:600;">{{ $article->published_at ? $article->published_at->format('d M Y') : 'N/A' }}</div>
                        <div style="font-size:10px;color:var(--text-muted);">{{ $article->created_at->format('H:i') }}</div>
                    </td>
                    <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                        <div style="display:flex;gap:8px; justify-content: flex-end;">
                            <a href="{{ route('admin.cms.articles.edit', $article) }}" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px;" title="Edit">
                                <i class="fa-solid fa-pen-to-square" style="font-size: 14px;"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.cms.articles.destroy', $article) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px; color: var(--danger); border-color: rgba(244,63,94,0.2);" onmouseover="this.style.background='rgba(244,63,94,0.1)'" onmouseout="this.style.background='transparent'">
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
                            <i class="fa-solid fa-feather" style="font-size: 48px; opacity: 0.1;"></i>
                            <div style="font-weight: 600;">Belum ada artikel yang ditulis</div>
                        </div>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
    @if($articles->hasPages())
    <div style="padding:24px; background: rgba(255,255,255,0.01); border-top:1px solid var(--border);">
        {{ $articles->links() }}
    </div>
    @endif
</div>
@endsection
