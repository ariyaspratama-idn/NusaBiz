@extends('layouts.admin')
@section('title', 'Chat: ' . $session->visitor_name)
@section('page_title', 'Live Support')

@section('content')
<div style="display:grid; grid-template-columns: 350px 1fr; gap:0; height: calc(100vh - 160px); background: var(--bg-card); border-radius: 24px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
    <!-- Sidebar Chat (Daftar Sesi) -->
    <div style="border-right: 1px solid var(--border); background: rgba(255,255,255,0.01); display: flex; flex-direction: column;">
        <div style="padding: 24px; border-bottom: 1px solid var(--border);">
            <div style="display:flex; align-items:center; justify-content:space-between; margin-bottom:20px;">
                <h3 style="font-size:15px; font-weight:800; color:var(--text-main); letter-spacing:0.5px; text-transform:uppercase;">Percakapan</h3>
                <a href="{{ route('admin.chat.index') }}" style="width:32px;height:32px;border-radius:10px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:var(--bg-main);transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
                    <i class="fa-solid fa-arrow-left" style="font-size:12px;"></i>
                </a>
            </div>
            <div style="position:relative;">
                <i class="fa-solid fa-magnifying-glass" style="position:absolute; left:14px; top:50%; transform:translateY(-50%); color:var(--text-muted); font-size:12px;"></i>
                <input type="text" placeholder="Cari..." style="width:100%; padding:10px 10px 10px 38px; border: 1px solid var(--border); border-radius:12px; font-size:12px; outline:none; background:var(--bg-main); color:var(--text-main);">
            </div>
        </div>
        
        <div style="flex:1; overflow-y: auto; padding: 12px; scrollbar-width: none;">
            @php $sessions = \App\Models\ChatSession::latest()->limit(20)->get(); @endphp
            @foreach($sessions as $s)
            <a href="{{ route('admin.chat.show', $s) }}" style="display:flex; align-items:center; gap:12px; padding:12px; border-radius:16px; text-decoration:none; transition:var(--transition); margin-bottom:6px; {{ $session->id == $s->id ? 'background:rgba(99,102,241,0.08); border:1px solid rgba(99,102,241,0.2);' : 'opacity: 0.7;' }}" onmouseover="this.style.background='rgba(255,255,255,0.03)'; this.style.opacity='1'" onmouseout="this.style.background='{{ $session->id == $s->id ? 'rgba(99,102,241,0.08)' : 'transparent' }}'; this.style.opacity='{{ $session->id == $s->id ? '1' : '0.7' }}'">
                <div style="width:40px;height:40px;background:linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);color:white;border-radius:12px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:15px;flex-shrink:0;">{{ strtoupper(substr($s->visitor_name,0,1)) }}</div>
                <div style="flex:1; overflow:hidden;">
                    <div style="font-weight:700; color:var(--text-main); font-size:13px; margin-bottom:2px;">{{ $s->visitor_name }}</div>
                    <div style="font-size:10px; color:var(--text-muted); white-space:nowrap; overflow:hidden; text-overflow:ellipsis; font-weight:600;">
                        {{ $s->last_activity_at->diffForHumans() }}
                    </div>
                </div>
            </a>
            @endforeach
        </div>
    </div>

    <!-- Chat Area -->
    <div style="display:flex; flex-direction:column; background:rgba(255,255,255,0.01);">
        <!-- Chat Header -->
        <div style="padding: 20px 32px; border-bottom: 1px solid var(--border); display:flex; align-items:center; justify-content:space-between; background:rgba(255,255,255,0.02);">
            <div style="display:flex; align-items:center; gap:16px;">
                <div style="width:48px;height:48px;background:linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);color:white;border-radius:14px;display:flex;align-items:center;justify-content:center;font-weight:800;font-size:18px; box-shadow:0 4px 12px rgba(99,102,241,0.2);">{{ strtoupper(substr($session->visitor_name,0,1)) }}</div>
                <div>
                    <h3 style="font-size:16px; font-weight:800; color:var(--text-main); line-height:1.2;">{{ $session->visitor_name }}</h3>
                    <div id="typing-status" style="display:flex; align-items:center; gap:6px; margin-top:4px;">
                        <span style="width:8px;height:8px;background:#10b981;border-radius:50%; box-shadow:0 0 8px #10b981;"></span>
                        <span style="font-size:12px; color:#10b981; font-weight:700; text-transform:uppercase; letter-spacing:0.5px;">Online</span>
                    </div>
                </div>
            </div>
            <div style="display:flex; gap:10px;">
                <button class="btn-chat-action"><i class="fa-solid fa-phone"></i></button>
                <button class="btn-chat-action"><i class="fa-solid fa-video"></i></button>
                <button class="btn-chat-action"><i class="fa-solid fa-circle-info"></i></button>
            </div>
        </div>

        <style>
            .btn-chat-action {
                width:40px;height:40px;border-radius:12px;border:1px solid var(--border);background:var(--bg-main);color:var(--text-muted);cursor:pointer;transition:var(--transition);display:flex;align-items:center;justify-content:center;
            }
            .btn-chat-action:hover {
                border-color:var(--primary); color:var(--primary); background:rgba(99,102,241,0.1);
            }
            #chat-messages::-webkit-scrollbar { width: 4px; }
            #chat-messages::-webkit-scrollbar-thumb { background: rgba(255,255,255,0.05); border-radius:10px; }
        </style>

        <!-- Messages Box -->
        <div id="chat-messages" style="flex:1; overflow-y:auto; padding:32px; display:flex; flex-direction:column; gap:24px; background:var(--bg-main); position:relative; scroll-behavior: smooth;">
            <!-- Subtle patterns -->
            <div style="position:absolute; inset:0; background:radial-gradient(circle at 10% 10%, rgba(99,102,241,0.03) 0%, transparent 40%); pointer-events:none;"></div>
            
            @foreach($messages as $msg)
                @if($msg->sender_type == 'visitor')
                    <div style="display:flex; align-items:flex-end; gap:12px; max-width:80%;">
                        <div style="width:32px;height:32px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:var(--text-muted);">{{ strtoupper(substr($session->visitor_name,0,1)) }}</div>
                        <div style="background:var(--bg-card); padding:14px 18px; border-radius:20px 20px 20px 6px; box-shadow:var(--shadow-sm); border:1px solid var(--border); position:relative;">
                            <div style="font-size:14px; color:var(--text-main); line-height:1.6; font-weight:500;">{{ $msg->message }}</div>
                            <div style="font-size:10px; color:var(--text-muted); margin-top:6px; text-align:right; font-weight:700;">{{ $msg->created_at->format('H:i') }}</div>
                        </div>
                    </div>
                @else
                    <div style="display:flex; align-items:flex-end; gap:12px; max-width:80%; align-self:flex-end; flex-direction:row-reverse;">
                        <div style="width:32px;height:32px;background:var(--primary);color:white;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;box-shadow:0 4px 10px rgba(99,102,241,0.2);">{{ strtoupper(substr(auth()->user()->name,0,1)) }}</div>
                        <div style="background:linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color:white; padding:14px 18px; border-radius:20px 20px 6px 20px; box-shadow: 0 8px 16px rgba(99,102,241,0.15);">
                            <div style="font-size:14px; line-height:1.6; font-weight:500;">{{ $msg->message }}</div>
                            <div style="font-size:10px; opacity:0.8; margin-top:6px; text-align:right; font-weight:700; display:flex; align-items:center; justify-content:flex-end; gap:4px;">
                                {{ $msg->created_at->format('H:i') }} 
                                <i class="fa-solid fa-check-double" style="font-size:9px;"></i>
                            </div>
                        </div>
                    </div>
                @endif
            @endforeach
        </div>

        <!-- Input Area -->
        <div style="padding:24px 32px; border-top:1px solid var(--border); background:rgba(255,255,255,0.02);">
            <form id="chat-form" style="display:flex; gap:16px; align-items:center; position:relative;">
                <div style="display:flex; gap:10px;">
                    <button type="button" class="btn-tool"><i class="fa-solid fa-plus"></i></button>
                    <button type="button" class="btn-tool"><i class="fa-regular fa-face-smile"></i></button>
                </div>
                <div style="flex:1; position:relative;">
                    <textarea id="chat-textarea" placeholder="Tulis balasan untuk {{ $session->visitor_name }}..." 
                              style="width:100%; padding:14px 24px; border: 1px solid var(--border); border-radius:24px; font-size:14px; outline:none; transition:var(--transition); resize:none; max-height:120px; background:var(--bg-card); color:var(--text-main);"
                              onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 15px rgba(99,102,241,0.15)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"></textarea>
                </div>
                <button type="submit" style="width:54px;height:54px;border-radius:50%;border:none;background:var(--primary);color:white;cursor:pointer;display:flex;align-items:center;justify-content:center;box-shadow: 0 8px 20px rgba(99,102,241,0.3); transition:var(--transition);" onmouseover="this.style.transform='scale(1.08)'; this.style.boxShadow='0 10px 25px rgba(99,102,241,0.4)'" onmouseout="this.style.transform='none';">
                    <i class="fa-solid fa-paper-plane" style="font-size:18px;"></i>
                </button>
            </form>
        </div>
    </div>
</div>

<style>
    .btn-tool {
        width:38px;height:38px;border-radius:50%;border:1px solid var(--border);background:var(--bg-main);color:var(--text-muted);cursor:pointer;transition:var(--transition);
    }
    .btn-tool:hover {
        background:rgba(255,255,255,0.05); color:var(--text-main); border-color:var(--primary);
    }
</style>

<script>
    const msgBox = document.getElementById('chat-messages');
    let lastMsgId = {{ $messages->last()?->id ?? 0 }};
    const visitorName = "{{ $session->visitor_name }}";
    const adminInitial = "{{ strtoupper(substr(auth()->user()->name, 0, 1)) }}";

    function scrollToBottom() {
        msgBox.scrollTop = msgBox.scrollHeight;
    }
    scrollToBottom();

    function appendMessageUI(m) {
        const isVisitor = m.sender_type === 'visitor';
        const div = document.createElement('div');
        
        if(isVisitor) {
            div.style.cssText = "display:flex; align-items:flex-end; gap:12px; max-width:80%; animation: fadeIn 0.3s ease;";
            div.innerHTML = `
                <div style="width:32px;height:32px;background:var(--bg-card);border:1px solid var(--border);border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;color:var(--text-muted);">${visitorName.charAt(0).toUpperCase()}</div>
                <div style="background:var(--bg-card); padding:14px 18px; border-radius:20px 20px 20px 6px; box-shadow:var(--shadow-sm); border:1px solid var(--border); position:relative;">
                    <div style="font-size:14px; color:var(--text-main); line-height:1.6; font-weight:500;">${m.message}</div>
                    <div style="font-size:10px; color:var(--text-muted); margin-top:6px; text-align:right; font-weight:700;">${m.created_at}</div>
                </div>
            `;
        } else {
            div.style.cssText = "display:flex; align-items:flex-end; gap:12px; max-width:80%; align-self:flex-end; flex-direction:row-reverse; animation: fadeIn 0.3s ease;";
            div.innerHTML = `
                <div style="width:32px;height:32px;background:var(--primary);color:white;border-radius:12px;flex-shrink:0;display:flex;align-items:center;justify-content:center;font-size:12px;font-weight:800;box-shadow:0 4px 10px rgba(99,102,241,0.2);">${adminInitial}</div>
                <div style="background:linear-gradient(135deg, var(--primary) 0%, var(--primary-dark) 100%); color:white; padding:14px 18px; border-radius:20px 20px 6px 20px; box-shadow: 0 8px 16px rgba(99,102,241,0.15);">
                    <div style="font-size:14px; line-height:1.6; font-weight:500;">${m.message}</div>
                    <div style="font-size:10px; opacity:0.8; margin-top:6px; text-align:right; font-weight:700; display:flex; align-items:center; justify-content:flex-end; gap:4px;">
                        ${m.created_at} <i class="fa-solid fa-check-double" style="font-size:9px;"></i>
                    </div>
                </div>
            `;
        }
        msgBox.appendChild(div);
        scrollToBottom();
        lastMsgId = Math.max(lastMsgId, m.id);
    }

    @keyframes fadeIn { from { opacity:0; transform: translateY(5px); } to { opacity:1; transform: translateY(0); } }

    // Polling Logic
    setInterval(() => {
        fetch('{{ route("admin.chat.messages", $session) }}?after_id=' + lastMsgId)
            .then(res => res.json())
            .then(data => {
                if(data.messages && data.messages.length) {
                    data.messages.forEach(m => appendMessageUI(m));
                }
            });
    }, 4000);

    // Handle AJAX reply
    document.getElementById('chat-form').addEventListener('submit', function(e) {
        e.preventDefault();
        const textarea = document.getElementById('chat-textarea');
        const text = textarea.value.trim();
        if(!text) return;
        textarea.value = '';

        fetch('{{ route("admin.chat.reply", $session) }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({ message: text })
        })
        .then(res => res.json())
        .then(data => {
            if(data.success) {
                // Sorting UI append or waiting for poll
            }
        });
    });
</script>
@endsection

