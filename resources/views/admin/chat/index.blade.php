@extends('layouts.admin')
@section('title', 'CS - Live Chat Panel')
@section('page_title', 'Customer Support')

@section('content')
<div style="display:grid; grid-template-columns: 350px 1fr; gap:0; height: calc(100vh - 160px); background: var(--bg-card); border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
    <!-- Sidebar Chat -->
    <div style="border-right: 1px solid var(--border); background: rgba(255,255,255,0.01); display: flex; flex-direction: column;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <h3 style="font-size:16px; font-weight:800; color:var(--text-main); letter-spacing:0.5px; text-transform:uppercase;">Percakapan</h3>
                <div class="status-badge" style="display:flex; align-items:center; gap:6px; background:rgba(16,185,129,0.1); color:#10b981; font-size:10px; font-weight:800; padding:4px 10px; border-radius:50px; border:1px solid rgba(16,185,129,0.2);">
                    <span style="width:6px;height:6px;background:#10b981;border-radius:50%; box-shadow:0 0 8px #10b981;"></span> ONLINE
                </div>
            </div>
            <div style="position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:12px;"></i>
                <input type="text" placeholder="Cari percakapan..." style="width:100%; padding:12px 12px 12px 40px; border: 1px solid var(--border); border-radius:14px; font-size:13px; outline:none; transition:var(--transition); background:var(--bg-main); color:var(--text-main);" onfocus="this.style.borderColor='var(--primary)'; this.style.background='var(--bg-card)'" onblur="this.style.borderColor='var(--border)'; this.style.background='var(--bg-main)'">
            </div>
        </div>
        
        <div style="flex:1; overflow-y: auto; padding: 12px; scrollbar-width: none;">
            @forelse($sessions as $s)
            <a href="{{ route('admin.chat.show', $s) }}" style="display:flex; align-items:center; gap:14px; padding:12px 16px; border-radius:18px; text-decoration:none; transition:var(--transition); margin-bottom:6px; border:1px solid transparent;" onmouseover="this.style.background='rgba(255,255,255,0.03)'; this.style.borderColor='var(--border)';" onmouseout="this.style.background='transparent'; this.style.borderColor='transparent';">
                <div style="width:52px;height:52px;background:linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);color:white;border-radius:16px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px;flex-shrink:0; box-shadow: 0 4px 12px rgba(99,102,241,0.2);">{{ strtoupper(substr($s->visitor_name,0,1)) }}</div>
                <div style="flex:1; overflow:hidden;">
                    <div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:4px;">
                        <span style="font-weight:800; color:var(--text-main); font-size:14px;">{{ $s->visitor_name }}</span>
                        <span style="font-size:10px; color:var(--text-muted); font-weight:600;">{{ $s->last_activity_at->diffForHumans(null, true) }}</span>
                    </div>
                    <div style="font-size:12px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis;">
                        {{ $s->messages->first()?->message ?? 'Memulai percakapan...' }}
                    </div>
                </div>
                @if($s->unreadCount() > 0)
                <div style="min-width:20px;height:20px;background:var(--primary);color:white;border-radius:10px;display:flex;align-items:center;justify-content:center;font-size:10px;font-weight:800;flex-shrink:0; padding:0 6px; box-shadow: 0 0 10px rgba(99,102,241,0.4);">{{ $s->unreadCount() }}</div>
                @endif
            </a>
            @empty
            <div style="text-align:center; padding:60px 20px;">
                <div style="font-size:32px; color:var(--border); margin-bottom:16px;"><i class="fa-solid fa-ghost"></i></div>
                <div style="color:var(--text-muted); font-size:13px; font-weight:600;">Belum ada pesan masuk.</div>
            </div>
            @endforelse
        </div>
    </div>

    <!-- Area Chat Kosong -->
    <div style="display:flex; flex-direction:column; align-items:center; justify-content:center; background:var(--bg-main); position:relative;">
        <!-- Abstract Decoration -->
        <div style="position:absolute; top:20%; left:20%; width:200px; height:200px; background:var(--primary); filter:blur(150px); border-radius:50%; opacity:0.1;"></div>
        <div style="position:absolute; bottom:20%; right:20%; width:200px; height:200px; background:var(--secondary); filter:blur(150px); border-radius:50%; opacity:0.1;"></div>

        <div style="text-align:center; max-width:420px; padding:40px; position:relative; z-index:1;">
            <div style="width:110px;height:110px;background:var(--bg-card);border-radius:36px;display:flex;align-items:center;justify-content:center;margin:0 auto 32px;box-shadow:var(--shadow-lg); border:1px solid var(--border);">
                <i class="fa-solid fa-comment-dots" style="font-size:48px; color:var(--primary); text-shadow: 0 0 20px rgba(99,102,241,0.3);"></i>
            </div>
            <h3 style="font-size:24px; font-weight:800; color:var(--text-main); margin-bottom:16px; letter-spacing:-0.5px;">Live Support Hub</h3>
            <p style="font-size:14px; color:var(--text-muted); line-height:1.7; font-weight:500;">Pantau dan balas pesan pelanggan secara instan. Pilih salah satu percakapan di panel kiri untuk mulai berinteraksi.</p>
            
            <div style="margin-top:40px; display:flex; gap:12px; justify-content:center;">
                <div style="padding:10px 16px; background:rgba(255,255,255,0.03); border-radius:12px; border:1px solid var(--border); font-size:11px; color:var(--text-muted);">
                    <i class="fa-solid fa-shield-halved" style="color:var(--primary); margin-right:6px;"></i> Aman & Terenkripsi
                </div>
                <div style="padding:10px 16px; background:rgba(255,255,255,0.03); border-radius:12px; border:1px solid var(--border); font-size:11px; color:var(--text-muted);">
                    <i class="fa-solid fa-bolt" style="color:var(--secondary); margin-right:6px;"></i> Respon Real-time
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    setInterval(() => {
        // Polling simplified refresh
        fetch(window.location.href)
            .then(res => res.text())
            .then(html => {
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const newList = doc.querySelector('.sidebar-nav')?.parentElement?.querySelector('div[style*="overflow-y: auto"]');
                if(newList) {
                    // Update content only if needed (could compare text)
                }
            }).catch(()=>{});
        // location.reload(); // Original approach, but let's be careful with full reload
    }, 30000); 
</script>
@endsection

