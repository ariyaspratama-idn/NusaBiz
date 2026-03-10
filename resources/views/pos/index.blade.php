<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Super POS Terminal - BuBeKu</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <style>
        :root {
            --primary: #6366f1;
            --primary-light: #818cf8;
            --accent: #f43f5e;
            --bg: #0f172a;
            --card: rgba(30, 41, 59, 0.7);
            --text: #f8fafc;
            --text-muted: #94a3b8;
            --border: rgba(255, 255, 255, 0.1);
        }

        body {
            font-family: 'Inter', sans-serif;
            background: radial-gradient(circle at top right, #1e1b4b, #0f172a);
            color: var(--text);
            margin: 0;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        .layout {
            display: grid;
            grid-template-columns: 1fr 400px;
            gap: 24px;
            max-width: 1200px;
            width: 100%;
        }

        .card {
            background: var(--card);
            backdrop-filter: blur(12px);
            border: 1px solid var(--border);
            border-radius: 24px;
            padding: 32px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.5);
        }

        /* Day Message Widget */
        .day-widget {
            background: linear-gradient(135deg, var(--primary), #4f46e5);
            padding: 20px;
            border-radius: 20px;
            margin-bottom: 24px;
            display: flex;
            align-items: center;
            gap: 16px;
            animation: fadeIn 0.8s ease-out;
        }

        .day-icon { font-size: 2.5rem; }
        .day-text h2 { margin: 0; font-size: 1.25rem; font-weight: 700; }
        .day-text p { margin: 4px 0 0; font-size: 0.9rem; opacity: 0.8; }

        h1 { font-size: 1.5rem; margin-top: 0; display: flex; align-items: center; gap: 10px; }

        .input-group { margin-bottom: 20px; }
        label { display: block; font-size: 0.85rem; font-weight: 500; color: var(--text-muted); margin-bottom: 8px; }
        input, select {
            width: 100%;
            padding: 14px;
            background: rgba(15, 23, 42, 0.5);
            border: 1px solid var(--border);
            border-radius: 12px;
            color: white;
            font-size: 1rem;
            transition: all 0.2s;
        }
        input:focus { outline: none; border-color: var(--primary); box-shadow: 0 0 0 4px rgba(99, 102, 241, 0.2); }

        .btn {
            cursor: pointer;
            border: none;
            border-radius: 14px;
            font-weight: 600;
            transition: all 0.2s;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
        }

        .btn-add { background: var(--primary); color: white; padding: 14px; width: 100%; margin-top: 10px; }
        .btn-add:hover { background: var(--primary-light); transform: translateY(-2px); }

        /* Cart Styles */
        .cart-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid var(--border); padding-bottom: 10px; }
        .cart-items { max-height: 400px; overflow-y: auto; margin-bottom: 20px; }
        .cart-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 12px 0;
            border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            animation: slideIn 0.3s ease-out;
        }
        .item-info h4 { margin: 0; font-size: 0.95rem; }
        .item-info p { margin: 4px 0 0; font-size: 0.8rem; color: var(--text-muted); }
        .item-price { font-weight: 600; font-size: 0.95rem; }
        .btn-del { background: none; color: var(--accent); font-size: 1.2rem; padding: 4px 8px; border-radius: 8px; }
        .btn-del:hover { background: rgba(244, 63, 94, 0.1); }

        .summary { background: rgba(15, 23, 42, 0.4); border-radius: 16px; padding: 20px; }
        .summary-row { display: flex; justify-content: space-between; margin-bottom: 10px; font-size: 0.9rem; }
        .total-row { border-top: 1px dashed var(--border); margin-top: 15px; padding-top: 15px; font-size: 1.25rem; font-weight: 700; color: var(--primary-light); }

        .btn-checkout { background: var(--primary); color: white; padding: 18px; width: 100%; margin-top: 24px; font-size: 1.1rem; }
        .btn-checkout:hover { background: var(--primary-light); box-shadow: 0 10px 15px -3px rgba(99, 102, 241, 0.4); }
        .btn-checkout:disabled { background: #334155; color: #64748b; cursor: not-allowed; }

        .overlay {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(15, 23, 42, 0.9);
            backdrop-filter: blur(8px);
            z-index: 100;
            justify-content: center;
            align-items: center;
            flex-direction: column;
        }

        /* Top Nav */
        .top-nav {
            display: flex;
            justify-content: space-between;
            align-items: center;
            width: 100%;
            max-width: 1200px;
            margin-bottom: 20px;
            animation: fadeIn 0.5s ease-out;
        }
        .nav-brand { font-weight: 700; font-size: 1.25rem; color: var(--primary-light); }
        .nav-actions { display: flex; gap: 15px; }
        .btn-nav { 
            background: rgba(255,255,255,0.05); 
            color: white; 
            padding: 8px 16px; 
            font-size: 0.9rem; 
            text-decoration: none;
            border: 1px solid var(--border);
        }
        .btn-nav:hover { background: rgba(255,255,255,0.1); }
        .btn-logout { background: rgba(244, 63, 94, 0.1); color: var(--accent); border-color: rgba(244, 63, 94, 0.2); }
        .btn-logout:hover { background: var(--accent); color: white; }

        @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
        @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }

        /* Custom scrollbar */
        .cart-items::-webkit-scrollbar { width: 6px; }
        .cart-items::-webkit-scrollbar-thumb { background: var(--border); border-radius: 10px; }
    </style>
</head>
<body>

<div style="display: flex; flex-direction: column; align-items: center; width: 100%;">
<div class="top-nav">
    <div class="nav-brand">NusaBiz | POS</div>
    <div class="nav-actions">
        <a href="/" class="btn btn-nav">
            <span>🏠</span> Dashboard
        </a>
        <form action="{{ route('logout') }}" method="POST" style="display:inline;">
            @csrf
            <button type="submit" class="btn btn-nav btn-logout">
                <span>🚪</span> Logout
            </button>
        </form>
    </div>
</div>

@if($activeRegister)
<div style="width: 100%; max-width: 1200px; margin-bottom: 20px; background: rgba(74, 222, 128, 0.2); border: 1px solid #4ade80; padding: 10px 20px; border-radius: 12px; color: #4ade80; display: flex; justify-content: space-between; align-items: center;">
    <div><strong>Shift Terbuka</strong> &mdash; Kasir: {{ auth()->user()->name }}</div>
    <div>Waktu Buka: {{ $activeRegister->opened_at->format('H:i') }} | Modal Awal: Rp {{ number_format($activeRegister->opening_balance, 0, ',', '.') }}</div>
</div>
<!-- Hidden input for cash register ID -->
<input type="hidden" id="cash_register_id" value="{{ $activeRegister->id }}">
@else
<div style="width: 100%; max-width: 1200px; margin-bottom: 20px; background: rgba(244, 63, 94, 0.2); border: 1px solid #f43f5e; padding: 10px 20px; border-radius: 12px; color: #f43f5e; display: flex; justify-content: space-between; align-items: center;">
    <div><strong>Shift Belum Dibuka!</strong> &mdash; Silakan buka shift kasir ('Tutup Cash Register') terlebih dahulu jika ingin melacak saldo. Transaksi saat ini tidak akan masuk ke shift manapun.</div>
</div>
<input type="hidden" id="cash_register_id" value="">
@endif

<div class="layout">
    <!-- Left: Form Input -->
    <div class="card">
        <div class="day-widget" id="dayWidget">
            <div class="day-icon" id="dayEmoji">🌤️</div>
            <div class="day-text">
                <h2 id="dayGreeting">Happy Day!</h2>
                <p id="dayMessage">Semangat melayani pelanggan hari ini.</p>
            </div>
        </div>

        <h1>🛒 Tambah Produk</h1>
        
        <form id="addItemForm" onsubmit="event.preventDefault(); addToCart();">
            <div class="input-group">
                <label>Nama Produk</label>
                <input type="text" id="itemName" placeholder="Ketik nama produk..." required>
            </div>
            
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label>Harga Satuan (Rp)</label>
                    <input type="number" id="itemPrice" placeholder="0" required>
                </div>
                <div class="input-group">
                    <label>Jumlah</label>
                    <input type="number" id="itemQty" value="1" min="1" required>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px;">
                <div class="input-group">
                    <label>Cabang</label>
                    <select id="branch_id" required>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}" {{ optional($defaultBranch)->id == $branch->id ? 'selected' : '' }}>
                                {{ $branch->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group">
                    <label>Metode Pembayaran (Jika Lunas)</label>
                    <select id="account_id" required>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}" {{ optional($defaultAccount)->id == $account->id ? 'selected' : '' }}>
                                {{ $account->name }}
                            </option>
                        @endforeach
                    </select>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-top: 10px; padding: 15px; background: rgba(0,0,0,0.2); border-radius: 12px; border: 1px dashed var(--border);">
                <div class="input-group" style="margin-bottom:0;">
                    <label style="color: var(--primary-light);">Pelanggan (Wajib Jika Kasbon)</label>
                    <select id="contact_id">
                        <option value="">-- Pelanggan Umum --</option>
                        @foreach($contacts as $contact)
                            <option value="{{ $contact->id }}">{{ $contact->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="input-group" style="margin-bottom:0;">
                    <label style="color: var(--primary-light);">Status Pembayaran</label>
                    <select id="payment_status" onchange="togglePaymentStatus(this.value)">
                        <option value="PAID">✅ Lunas (Bayar Sekarang)</option>
                        <option value="UNPAID">⏱️ Kasbon (Catat Buku Hutang)</option>
                    </select>
                </div>
            </div>

            <button type="submit" class="btn btn-add" style="margin-top: 20px;">
                <span>➕</span> Tambah ke Keranjang Belanja
            </button>
        </form>
    </div>

    <!-- Right: Cart & Summary -->
    <div class="card">
        <div class="cart-header">
            <h3>🛍️ Keranjang</h3>
            <span id="cartCount" style="background: var(--primary); padding: 4px 10px; border-radius: 20px; font-size: 0.8rem;">0 Item</span>
        </div>

        <div class="cart-items" id="cartDisplay">
            <!-- Items injected here -->
            <div style="text-align: center; color: var(--text-muted); padding: 40px 0;">
                <p>Belum ada barang dipilih</p>
            </div>
        </div>

        <div class="summary">
            <div class="summary-row">
                <span>Subtotal</span>
                <span id="labelSubtotal">Rp 0</span>
            </div>
            <div class="summary-row">
                <span>PPN (11%)</span>
                <span id="labelTax">Rp 0</span>
            </div>
            <div class="summary-row" style="color: #4ade80;">
                <span>Diskon <span id="labelDiscPct">(0%)</span></span>
                <span id="labelDiscount">- Rp 0</span>
            </div>
            <div class="summary-row total-row">
                <span>Total Bayar</span>
                <span id="labelTotal">Rp 0</span>
            </div>
        </div>

        <button class="btn btn-checkout" id="checkoutBtn" onclick="checkout()" disabled>
            <span>🚀</span> Selesaikan Transaksi Pusat
        </button>
    </div>
</div>

<div class="overlay" id="successOverlay">
    <div style="font-size: 5rem; color: #4ade80; margin-bottom: 20px;">✅</div>
    <h2 style="font-size: 2rem; margin-bottom: 10px;">Transaksi Berhasil!</h2>
    <p id="successDetail" style="color: var(--text-muted); margin-bottom: 30px;">Data telah disinkronkan ke server pusat.</p>
    <button class="btn" onclick="location.reload()" style="background: var(--primary); color: white; padding: 14px 40px;">Buka Terminal Baru</button>
</div>

<script>
    let cart = [];
    const formatRp = (num) => "Rp " + num.toLocaleString('id-ID');

    // Day Checker Logic (Day Greeting)
    function setDayGreeting() {
        const d = new Date();
        const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        const emoji = ['😴', '☕', '🔥', '🛡️', '⚡', '🎉', '🌟'];
        const messages = {
            0: "Sleepy Sunday - Rest well!",
            1: "Monday - Back to business.",
            2: "Tuesday - Keep the momentum.",
            3: "Wednesday - Middle of the hunt.",
            4: "Thursday - Almost there.",
            5: "Finally Friday! Let's wrap up.",
            6: "Super Saturday - Top sales day!"
        };
        
        const theDay = d.getDay();
        document.getElementById('dayEmoji').innerText = emoji[theDay];
        document.getElementById('dayGreeting').innerText = `Happy ${days[theDay]}!`;
        document.getElementById('dayMessage').innerText = messages[theDay];
    }

    function addToCart() {
        const name = document.getElementById('itemName').value;
        const price = parseFloat(document.getElementById('itemPrice').value);
        const qty = parseFloat(document.getElementById('itemQty').value);

        cart.push({ id: Date.now(), name, price, qty, total: price * qty });
        
        document.getElementById('itemName').value = '';
        document.getElementById('itemPrice').value = '';
        document.getElementById('itemQty').value = '1';
        document.getElementById('itemName').focus();
        
        renderCart();
    }

    function removeFromCart(id) {
        cart = cart.filter(i => i.id !== id);
        renderCart();
    }

    function renderCart() {
        const display = document.getElementById('cartDisplay');
        const count = document.getElementById('cartCount');
        const checkoutBtn = document.getElementById('checkoutBtn');
        
        count.innerText = `${cart.length} Item`;
        
        if (cart.length === 0) {
            display.innerHTML = `<div style="text-align: center; color: var(--text-muted); padding: 40px 0;"><p>Belum ada barang dipilih</p></div>`;
            checkoutBtn.disabled = true;
            updateSummary(0);
            return;
        }

        checkoutBtn.disabled = false;
        display.innerHTML = cart.map(item => `
            <div class="cart-item">
                <div class="item-info">
                    <h4>${item.name}</h4>
                    <p>${item.qty} x ${formatRp(item.price)}</p>
                </div>
                <div style="display: flex; align-items: center; gap: 12px;">
                    <div class="item-price">${formatRp(item.total)}</div>
                    <button class="btn-del" onclick="removeFromCart(${item.id})">×</button>
                </div>
            </div>
        `).join('');

        const subtotal = cart.reduce((sum, item) => sum + item.total, 0);
        updateSummary(subtotal);
    }

    function updateSummary(subtotal) {
        const tax = subtotal * 0.11;
        
        // Smart Discount Logic from analis sebelumnya
        let discPct = 0;
        if (subtotal > 500000) discPct = 5;
        else if (subtotal > 300000) discPct = 2;
        else if (subtotal > 100000) discPct = 0.5;

        const disc = (discPct / 100) * subtotal;
        const total = subtotal + tax - disc;

        document.getElementById('labelSubtotal').innerText = formatRp(subtotal);
        document.getElementById('labelTax').innerText = formatRp(tax);
        document.getElementById('labelDiscPct').innerText = `(${discPct}%)`;
        document.getElementById('labelDiscount').innerText = `- ${formatRp(disc)}`;
        document.getElementById('labelTotal').innerText = formatRp(total);

        // Store values for submission
        window.currentSummary = { subtotal, tax, disc, total };
    }

    function togglePaymentStatus(status) {
        const accountSelect = document.getElementById('account_id');
        const contactSelect = document.getElementById('contact_id');
        
        if (status === 'UNPAID') {
            accountSelect.disabled = true;
            accountSelect.style.opacity = '0.5';
            contactSelect.style.borderColor = 'var(--primary)';
        } else {
            accountSelect.disabled = false;
            accountSelect.style.opacity = '1';
            contactSelect.style.borderColor = 'var(--border)';
        }
    }

    async function checkout() {
        const btn = document.getElementById('checkoutBtn');
        btn.disabled = true;
        btn.innerText = "⏳ Sinkronisasi...";

        const payload = {
            items: cart,
            branch_id: document.getElementById('branch_id').value,
            account_id: document.getElementById('account_id').value,
            contact_id: document.getElementById('contact_id').value || null,
            payment_status: document.getElementById('payment_status').value,
            cash_register_id: document.getElementById('cash_register_id').value || null,
            ...window.currentSummary,
            discount: window.currentSummary.disc // mapping name
        };

        if (payload.payment_status === 'UNPAID' && !payload.contact_id) {
            alert("⚠️ Visi BuBeKu: Jika pembayaran KASBON / NGUTANG, maka nama Pelanggan WAJIB dipilih untuk dimasukkan ke Buku Hutang!");
            btn.disabled = false;
            btn.innerText = "🚀 Selesaikan Transaksi Pusat";
            return;
        }

        try {
            const res = await fetch('/pos', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(payload)
            });

            const result = await res.json();
            if (result.success) {
                document.getElementById('successDetail').innerText = `Ref: ${result.data.transaction_no} | Berhasil dicatat ke database pusat.`;
                document.getElementById('successOverlay').style.display = 'flex';
            } else {
                alert("Gagal: " + result.message);
                btn.disabled = false;
                btn.innerText = "🚀 Selesaikan Transaksi Pusat";
            }
        } catch (e) {
            alert("Koneksi Error");
            btn.disabled = false;
            btn.innerText = "🚀 Selesaikan Transaksi Pusat";
        }
    }

    document.addEventListener('DOMContentLoaded', () => {
        setDayGreeting();
        renderCart();
    });
</script>

</div>
</body>
</html>
