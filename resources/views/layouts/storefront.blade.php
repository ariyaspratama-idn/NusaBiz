<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'NusaBiz') — by Wave Project</title>
    <meta name="description" content="@yield('meta_description', 'NusaBiz by Wave Project - Platform Bisnis Terpadu Indonesia')">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        window.addEventListener('load', function() {
            const loader = document.getElementById('global-loader');
            setTimeout(() => {
                loader.classList.add('hidden');
            }, 600);
        });

        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        primary: '#6366f1',
                        'primary-dark': '#4f46e5',
                        accent: '#8b5cf6'
                    }
                }
            }
        }
    </script>
    <style>
        :root {
            --primary: #6366f1;
            --primary-dark: #4f46e5;
            --accent: #8b5cf6;
            --dark: #0f172a;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: rgba(0,0,0,0.06);
            --bg-soft: #f8fafc;
            --white: #ffffff;
            --glass: rgba(255, 255, 255, 0.7);
            --shadow-premium: 0 20px 50px rgba(0,0,0,0.05);
            --radius: 24px;
        }

        /* Global Loading Screen */
        #global-loader {
            position: fixed;
            inset: 0;
            background: #ffffff;
            z-index: 9999;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            transition: opacity 0.5s ease, visibility 0.5s;
        }

        #global-loader.hidden {
            opacity: 0;
            visibility: hidden;
        }

        .loader-content {
            text-align: center;
            animation: loader-pulse 2s infinite ease-in-out;
        }

        .loader-logo {
            width: 120px;
            height: auto;
            margin-bottom: 24px;
            filter: drop-shadow(0 10px 20px rgba(0,0,0,0.05));
        }

        .loader-bar {
            width: 200px;
            height: 3px;
            background: rgba(0,0,0,0.03);
            border-radius: 10px;
            overflow: hidden;
            position: relative;
        }

        .loader-progress {
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, var(--primary), transparent);
            animation: loader-slide 1.5s infinite linear;
        }

        @keyframes loader-pulse {
            0%, 100% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.05); opacity: 0.8; }
        }

        @keyframes loader-slide {
            0% { left: -100%; }
            100% { left: 100%; }
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { 
            font-family: 'Inter', sans-serif; 
            color: var(--text-main); 
            background: #ffffff; 
            overflow-x: hidden;
            -webkit-font-smoothing: antialiased;
        }
        a { color: inherit; text-decoration: none; transition: all 0.3s ease; }
        
        /* Premium Scrollbar */
        ::-webkit-scrollbar { width: 8px; }
        ::-webkit-scrollbar-track { background: var(--bg-soft); }
        ::-webkit-scrollbar-thumb { background: #e2e8f0; border-radius: 10px; border: 2px solid var(--bg-soft); }
        ::-webkit-scrollbar-thumb:hover { background: #cbd5e1; }

        /* Navbar Glassmorphism */
        nav.main-nav {
            position: sticky; top: 0; z-index: 1000; 
            background: var(--glass);
            backdrop-filter: blur(20px); 
            -webkit-backdrop-filter: blur(20px);
            border-bottom: 1px solid rgba(0,0,0,0.04);
            padding: 0 40px; height: 72px; 
            display: flex; align-items: center; justify-content: space-between;
            transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        }
        nav.main-nav.scrolled {
            height: 60px; background: rgba(255,255,255,0.9);
            box-shadow: 0 4px 20px rgba(0,0,0,0.03);
        }

        .nav-brand { font-size: 22px; font-weight: 900; display: flex; align-items: center; gap: 12px; color: var(--dark); letter-spacing: -0.5px; }
        .nav-brand .brand-logo {
            width: 40px; height: 40px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); 
            border-radius: 12px; display: flex; align-items: center; justify-content: center; 
            color: white; font-size: 18px; box-shadow: 0 8px 16px rgba(99,102,241,0.25);
        }
        .nav-brand span { color: var(--primary); }
        
        .nav-links { display: flex; align-items: center; gap: 32px; font-size: 14px; font-weight: 600; }
        .nav-links a { color: var(--text-muted); position: relative; }
        .nav-links a::after {
            content: ''; position: absolute; bottom: -4px; left: 0; width: 0; height: 2px;
            background: var(--primary); transition: width 0.3s ease; border-radius: 10px;
        }
        .nav-links a:hover { color: var(--primary); }
        .nav-links a:hover::after { width: 100%; }
        
        .nav-actions { display: flex; align-items: center; gap: 16px; }
        .cart-btn {
            position: relative; padding: 10px 24px; background: var(--dark);
            color: white; border-radius: 16px; font-size: 13px; font-weight: 700;
            display: flex; align-items: center; gap: 10px; transition: all 0.3s ease;
            box-shadow: 0 10px 20px rgba(15,23,42,0.1);
        }
        .cart-btn:hover { background: var(--primary); transform: translateY(-2px); box-shadow: 0 12px 24px rgba(99,102,241,0.3); }
        .cart-count {
            position: absolute; top: -8px; right: -8px; background: #fb7185;
            color: white; min-width: 20px; height: 20px; border-radius: 50%; padding: 0 5px;
            font-size: 10px; font-weight: 800; display: flex; align-items: center; justify-content: center;
            border: 2px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.1);
        }

        /* Hero Refined */
        .hero {
            background: #ffffff; padding: 120px 24px 80px; text-align: center; position: relative; overflow: hidden;
        }
        .hero-blob {
            position: absolute; width: 600px; height: 600px; background: rgba(99,102,241,0.05);
            filter: blur(100px); border-radius: 50%; z-index: 0;
        }
        .blob-1 { top: -200px; right: -100px; background: rgba(99,102,241,0.1); }
        .blob-2 { bottom: -200px; left: -100px; background: rgba(139,92,246,0.1); }

        .hero-content { position: relative; max-width: 850px; margin: 0 auto; z-index: 1; }
        .hero-badge {
            display: inline-flex; align-items: center; gap: 8px;
            background: rgba(99,102,241,0.06); border: 1px solid rgba(99,102,241,0.1);
            color: var(--primary); padding: 8px 20px; border-radius: 50px; 
            font-size: 12px; font-weight: 700; margin-bottom: 32px; letter-spacing: 0.5px;
            text-transform: uppercase;
        }
        .hero h1 { font-size: 64px; font-weight: 900; line-height: 1; margin-bottom: 24px; color: var(--dark); letter-spacing: -2px; }
        .hero h1 span { background: linear-gradient(to right, var(--primary), var(--accent)); -webkit-background-clip: text; -webkit-text-fill-color: transparent; }
        .hero p { font-size: 18px; color: var(--text-muted); line-height: 1.8; margin-bottom: 40px; font-weight: 500; }
        
        .btn-premium-primary {
            padding: 16px 36px; background: var(--primary); color: white;
            border-radius: 16px; font-weight: 800; font-size: 15px; transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
            display: flex; align-items: center; gap: 10px; box-shadow: 0 10px 25px rgba(99,102,241,0.3);
        }
        .btn-premium-primary:hover { transform: translateY(-4px) scale(1.02); box-shadow: 0 15px 35px rgba(99,102,241,0.4); }
        .btn-premium-outline {
            padding: 16px 36px; border: 2px solid #e2e8f0; color: var(--dark);
            border-radius: 16px; font-weight: 700; font-size: 15px; transition: all 0.3s ease;
        }
        .btn-premium-outline:hover { background: #f8fafc; border-color: var(--primary); color: var(--primary); }

        /* General Section */
        .section-label {
            display: inline-block; background: rgba(99,102,241,0.1); color: var(--primary);
            padding: 6px 16px; border-radius: 50px; font-size: 11px; font-weight: 800;
            margin-bottom: 16px; letter-spacing: 1.5px; text-transform: uppercase;
        }

        /* Footer Modern */
        footer { background: #0f172a; color: #94a3b8; padding: 100px 40px 40px; border-radius: 48px 48px 0 0; margin-top: 80px; }
        .footer-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 48px; max-width: 1400px; margin: 0 auto 60px; }
        .footer-brand .brand { font-size: 24px; font-weight: 900; color: white; margin-bottom: 16px; }
        .footer-brand p { font-size: 14px; line-height: 1.8; color: #64748b; }
        .footer-col h4 { font-size: 12px; font-weight: 800; color: white; margin-bottom: 24px; text-transform: uppercase; letter-spacing: 2px; }
        .footer-col ul li { margin-bottom: 12px; }
        .footer-col ul li a { font-size: 14px; color: #64748b; transition: all 0.3s ease; }
        .footer-col ul li a:hover { color: var(--primary); padding-left: 8px; }
        .footer-bottom { border-top: 1px solid rgba(255,255,255,0.05); padding-top: 40px; display: flex; justify-content: space-between; align-items: center; flex-wrap: wrap; gap: 20px; font-size: 13px; max-width: 1400px; margin: 0 auto; color: #475569; }

        /* Live Chat Widget Refined */
        #chat-widget { position: fixed; bottom: 32px; right: 32px; z-index: 9999; }
        #chat-toggle {
            width: 64px; height: 64px; border-radius: 20px; background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%);
            color: white; border: none; cursor: pointer; box-shadow: 0 12px 30px rgba(99,102,241,0.4);
            font-size: 24px; display: flex; align-items: center; justify-content: center; transition: all 0.4s cubic-bezier(0.34, 1.56, 0.64, 1);
        }
        #chat-toggle:hover { transform: scale(1.1) rotate(5deg); }
        #chat-window {
            position: absolute; bottom: 85px; right: 0;
            width: 380px; height: 550px; background: white; border-radius: 32px;
            box-shadow: 0 30px 100px rgba(0,0,0,0.2); display: none; flex-direction: column; overflow: hidden; border: 4px solid white;
        }
        #chat-window.open { display: flex; animation: chatIn 0.5s cubic-bezier(0.34, 1.56, 0.64, 1); }
        @keyframes chatIn { from { opacity: 0; transform: translateY(40px) scale(0.9); } to { opacity: 1; transform: translateY(0) scale(1); } }
        
        .chat-header {
            background: linear-gradient(135deg, var(--primary) 0%, var(--accent) 100%); color: white; padding: 24px;
            display: flex; align-items: center; gap: 14px;
        }
        .chat-header .avatar { width: 44px; height: 44px; border-radius: 12px; background: rgba(255,255,255,0.2); display: flex; align-items: center; justify-content: center; font-size: 18px; }
        .chat-header .info .name { font-weight: 800; font-size: 15px; }
        .chat-header .info .status { font-size: 11px; opacity: 0.9; font-weight: 600; display: flex; align-items: center; gap: 4px; }
        .chat-header .info .status::before { content: ''; width: 6px; height: 6px; background: #4ade80; border-radius: 50%; display: inline-block; }
        
        .chat-messages { flex: 1; overflow-y: auto; padding: 24px; display: flex; flex-direction: column; gap: 16px; background: #f8fafc; }
        .chat-msg { max-width: 85%; }
        .chat-bubble { padding: 12px 16px; border-radius: 18px; font-size: 14px; line-height: 1.5; font-weight: 500; box-shadow: 0 4px 15px rgba(0,0,0,0.03); }
        .chat-msg.visitor { align-self: flex-end; }
        .chat-msg.visitor .chat-bubble { background: var(--primary); color: white; border-bottom-right-radius: 4px; }
        .chat-msg.admin { align-self: flex-start; }
        .chat-msg.admin .chat-bubble { background: white; color: var(--text-main); border-bottom-left-radius: 4px; }
        
        .chat-input-area { padding: 20px; background: white; border-top: 1px solid rgba(0,0,0,0.05); display: flex; gap: 12px; align-items: center; }
        .chat-input-area input { flex: 1; padding: 12px 20px; background: #f1f5f9; border: 2px solid transparent; border-radius: 16px; font-size: 14px; outline: none; transition: all 0.3s ease; font-weight: 500; }
        .chat-input-area input:focus { background: white; border-color: var(--primary); box-shadow: 0 10px 20px rgba(99,102,241,0.05); }
        .chat-send { width: 48px; height: 48px; background: var(--primary); color: white; border: none; border-radius: 14px; cursor: pointer; display: flex; align-items: center; justify-content: center; font-size: 18px; transition: all 0.3s ease; }
        .chat-send:hover { background: var(--primary-dark); transform: scale(1.05); }

        @media (max-width: 768px) {
            nav.main-nav { padding: 0 20px; }
            .hero h1 { font-size: 36px; }
            .nav-links { display: none; }
            #chat-window { width: calc(100vw - 40px); right: 0; }
        }
    </style>
    @stack('styles')
</head>
<body>
    <!-- Global Loader -->
    <div id="global-loader">
        <div class="loader-content">
            <img src="{{ asset('img/loading-icon.png') }}" alt="Loading..." class="loader-logo">
            <div class="loader-bar">
                <div class="loader-progress"></div>
            </div>
        </div>
    </div>

<!-- Navbar -->
<nav class="main-nav">
    <a href="{{ route('home') }}" class="nav-brand">
        <img src="{{ asset('img/logo-nusabiz.png') }}" alt="NusaBiz Logo" style="height: 48px; width: auto; object-fit: contain;">
    </a>
    <div class="nav-links">
        <a href="{{ route('home') }}">Home</a>
        <a href="{{ route('storefront.catalog') }}">Katalog</a>
        <a href="{{ route('storefront.articles') }}">Berita</a>
        <a href="{{ route('home') }}#profil-berita">About</a>
        <a href="{{ route('home') }}#kontak">Contact</a>
    </div>
    <div class="nav-actions">
        <a href="{{ route('checkout.show') }}" class="cart-btn" id="cart-btn-nav">
            <i class="fa-solid fa-bag-shopping"></i>
            <span class="hidden sm:inline">Keranjang</span>
            <span class="cart-count" id="cart-count-nav" style="display:none;">0</span>
        </a>
    </div>
</nav>

<!-- Page Content -->
<div class="min-h-screen">
    @if(session('success'))
    <div class="max-w-7xl mx-auto px-6 mt-8">
        <div class="bg-green-50 border border-green-100 text-green-700 p-5 rounded-3xl flex items-center gap-4 shadow-sm" role="alert">
            <div class="w-10 h-10 bg-green-100 rounded-full flex items-center justify-center text-green-600 flex-shrink-0">
                <i class="fa-solid fa-circle-check"></i>
            </div>
            <div>
                <strong class="block font-black text-sm uppercase tracking-wider">SUCCESS</strong>
                <span class="text-sm font-medium">{{ session('success') }}</span>
            </div>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="max-w-7xl mx-auto px-6 mt-8">
        <div class="bg-red-50 border border-red-100 text-red-700 p-5 rounded-3xl flex items-center gap-4 shadow-sm" role="alert">
            <div class="w-10 h-10 bg-red-100 rounded-full flex items-center justify-center text-red-600 flex-shrink-0">
                <i class="fa-solid fa-circle-exclamation"></i>
            </div>
            <div>
                <strong class="block font-black text-sm uppercase tracking-wider">ERROR</strong>
                <span class="text-sm font-medium">{{ session('error') }}</span>
            </div>
        </div>
    </div>
    @endif

    @yield('content')
</div>

<!-- Footer -->
<footer id="kontak">
    <div class="footer-grid">
        <div class="footer-brand">
            <div class="brand">
                <img src="{{ asset('img/logo-nusabiz.png') }}" alt="NusaBiz Logo" style="height: 40px; width: auto; object-fit: contain; margin-bottom: 16px;">
            </div>
            <p>Platform bisnis terpadu untuk UMKM Indonesia. Menyatukan teknologi dan strategi untuk pertumbuhan eksponensial.</p>
            <div style="display:flex;gap:16px;margin-top:32px;">
                <a href="#" style="width:40px;height:40px;background:rgba(255,255,255,0.05);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;"><i class="fab fa-instagram"></i></a>
                <a href="#" onclick="event.preventDefault(); toggleChat()" style="width:40px;height:40px;background:rgba(255,255,255,0.05);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;" title="Live Chat"><i class="fa-solid fa-comments"></i></a>
                <a href="#" style="width:40px;height:40px;background:rgba(255,255,255,0.05);border-radius:12px;display:flex;align-items:center;justify-content:center;color:white;font-size:18px;"><i class="fab fa-tiktok"></i></a>
            </div>
        </div>
        <div class="footer-col">
            <h4>Toko</h4>
            <ul>
                <li><a href="{{ route('storefront.catalog') }}">Semua Produk</a></li>
                <li><a href="{{ route('storefront.catalog') }}?sort=newest">Produk Terbaru</a></li>
                <li><a href="{{ route('checkout.show') }}">Keranjang</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Informasi</h4>
            <ul>
                <li><a href="{{ route('storefront.articles') }}">Berita & Artikel</a></li>
                <li><a href="{{ route('storefront.terms') }}">Syarat & Ketentuan</a></li>
                <li><a href="{{ route('storefront.privacy') }}">Kebijakan Privasi</a></li>
            </ul>
        </div>
        <div class="footer-col">
            <h4>Hubungi Kami</h4>
            <ul>
                <li><a href="#"><i class="fa-solid fa-envelope mr-2"></i> info@nusabiz.id</a></li>
                <li><a href="#" onclick="event.preventDefault(); toggleChat()"><i class="fa-solid fa-headset mr-2"></i> Layanan Konsumen</a></li>
                <li><a href="#"><i class="fa-solid fa-location-dot mr-2"></i> Jakarta, Indonesia</a></li>
            </ul>
        </div>
    </div>
    <div class="footer-bottom">
        <p>© {{ date('Y') }} NusaBiz Archive. Made with passion by <strong style="color:white;">Wave Project</strong></p>
        <p class="opacity-50">Powered by High-Performance Infrastructure</p>
    </div>
</footer>

<!-- Live Chat Widget -->
<div id="chat-widget">
    <div id="chat-window">
        <div class="chat-header">
            <div class="avatar"><i class="fa-solid fa-headset"></i></div>
            <div class="info">
                <div class="name">Customer Support</div>
                <div class="status">Live — Ready to assist</div>
            </div>
        </div>
        <div class="chat-messages" id="chat-messages">
            <div class="chat-msg admin">
                <div class="chat-bubble">Halo! Selamat datang di NusaBiz. Ada yang bisa kami bantu hari ini? 😊</div>
            </div>
        </div>
        <div class="chat-input-area">
            <input type="text" id="chat-input" placeholder="Tulis pesan..." onkeydown="if(event.key==='Enter') sendChatMessage()" autocomplete="off">
            <button class="chat-send" onclick="sendChatMessage()"><i class="fa-solid fa-paper-plane"></i></button>
        </div>
    </div>
    <button id="chat-toggle" onclick="toggleChat()">
        <i class="fa-solid fa-comment-dots" id="chat-icon"></i>
    </button>
</div>

<script>
// Scroll effect for Navbar
window.addEventListener('scroll', () => {
    const nav = document.querySelector('nav.main-nav');
    if (window.scrollY > 50) {
        nav.classList.add('scrolled');
    } else {
        nav.classList.remove('scrolled');
    }
});

// Cart count
function updateCartUI() {
    fetch('/cart/count').then(r=>r.json()).then(d=>{
        const el = document.getElementById('cart-count-nav');
        if(el && d.count > 0) { 
            el.textContent = d.count; 
            el.style.display = 'flex'; 
        } else if (el) {
            el.style.display = 'none';
        }
    }).catch(()=>{});
}
updateCartUI();

function addToCart(id, name, price, qty = 1) {
    let variantId = null;
    const variantRadio = document.querySelector('input[name="variant"]:checked');
    if (variantRadio) { variantId = variantRadio.value; }

    const btn = event.currentTarget || document.activeElement;
    const originalHtml = btn.innerHTML;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i>';
    btn.disabled = true;

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: id, quantity: qty, variant_id: variantId })
    }).then(res => res.json())
      .then(data => {
          if(data.success) {
              updateCartUI();
              const cartIcon = document.getElementById('cart-btn-nav');
              if(cartIcon) {
                  cartIcon.animate([{ transform: 'scale(1)' }, { transform: 'scale(1.1)' }, { transform: 'scale(1)' }], { duration: 300 });
              }
              btn.innerHTML = '<i class="fa-solid fa-check text-green-500"></i> Added';
              setTimeout(() => { btn.innerHTML = originalHtml; btn.disabled = false; }, 1500);
          }
      }).catch(err => {
          console.error(err);
          btn.innerHTML = originalHtml;
          btn.disabled = false;
      });
}

function addToCartAndCheckout(id, name, price, qty = 1) {
    let variantId = null;
    const variantRadio = document.querySelector('input[name="variant"]:checked');
    if (variantRadio) { variantId = variantRadio.value; }

    fetch('/cart/add', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        },
        body: JSON.stringify({ product_id: id, quantity: qty, variant_id: variantId })
    }).then(res => res.json())
      .then(data => { window.location.href = '/checkout'; })
      .catch(() => { alert('Terjadi kesalahan koneksi.'); });
}

// Chat Widget Logic
let chatOpen = false;
let lastMsgId = 0;
let sessionInited = false;

function toggleChat() {
    chatOpen = !chatOpen;
    const win = document.getElementById('chat-window');
    const icon = document.getElementById('chat-icon');
    if(chatOpen) {
        win.classList.add('open');
        icon.className = 'fa-solid fa-xmark';
        if(!sessionInited) initChat();
    } else {
        win.classList.remove('open');
        icon.className = 'fa-solid fa-comment-dots';
    }
}

function initChat() {
    sessionInited = true;
    fetch('/chat/init', { method:'POST', headers: {'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content} })
        .then(r=>r.json()).then(d=>{
            if(d.messages && d.messages.length) {
                d.messages.forEach(m => appendMessage(m.sender_type, m.message, m.id));
            }
            startPolling();
        }).catch(()=>{});
}

function appendMessage(type, text, id) {
    const box = document.getElementById('chat-messages');
    const div = document.createElement('div');
    div.className = 'chat-msg ' + type;
    div.innerHTML = `<div class="chat-bubble">${text}</div>`;
    box.appendChild(div);
    box.scrollTop = box.scrollHeight;
    if(id) lastMsgId = Math.max(lastMsgId, id);
}

function sendChatMessage() {
    const input = document.getElementById('chat-input');
    const msg = input.value.trim();
    if(!msg) return;
    input.value = '';
    appendMessage('visitor', msg, null);
    
    fetch('/chat/send', {
        method:'POST',
        headers:{'Content-Type':'application/json','X-CSRF-TOKEN':document.querySelector('meta[name="csrf-token"]').content},
        body: JSON.stringify({message: msg})
    }).then(r=>r.json()).then(d=>{
        if(d.message_id) lastMsgId = Math.max(lastMsgId, d.message_id);
    });
}

function startPolling() {
    setInterval(() => {
        if(!chatOpen) return;
        fetch('/chat/messages?after_id=' + lastMsgId)
            .then(r=>r.json()).then(d=>{
                if(d.messages && d.messages.length) {
                    d.messages.forEach(m=>{
                        if(m.sender_type === 'admin') {
                            appendMessage('admin', m.message, m.id);
                        } else {
                            lastMsgId = Math.max(lastMsgId, m.id);
                        }
                    });
                }
            }).catch(()=>{});
    }, 4000);
}
</script>
@stack('scripts')
</body>
</html>
