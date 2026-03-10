@extends('layouts.admin')
@section('title', 'Catat Transaksi Baru')
@section('page_title', 'Aktivitas Keuangan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('transactions.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:white;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Catat Transaksi Baru</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Catat entri keuangan baru ke dalam buku besar secara sistematis.</p>
        </div>
    </div>
</div>

<form action="{{ route('transactions.store') }}" method="POST">
    @csrf
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
        <div class="card" style="border-radius: 24px; border: 1px solid var(--border); background: white; padding: 40px; box-shadow: var(--shadow-sm);">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">{{ __('ui.date') }}</label>
                    <input type="date" name="transaction_date" 
                           style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; font-weight: 700;" 
                           value="{{ date('Y-m-d') }}" required
                           onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.05)'"
                           onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Cabang</label>
                    <select name="branch_id" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; font-weight: 600; cursor: pointer;" required>
                        <option value="">Pilih Cabang</option>
                        @foreach($branches as $branch)
                            <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Tipe Transaksi</label>
                    <select name="type" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; font-weight: 600; cursor: pointer;" required>
                        <option value="INCOME">Income / Pendapatan</option>
                        <option value="EXPENSE">Expense / Pengeluaran</option>
                    </select>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Akun (GL)</label>
                    <select name="account_id" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; font-weight: 700; cursor: pointer;" required>
                        <option value="">Pilih Akun</option>
                        @foreach($accounts as $account)
                            <option value="{{ $account->id }}">[{{ $account->code }}] {{ $account->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Jumlah / Amount</label>
                    <div style="display: flex; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: var(--transition); background: #f8fafc;">
                        <span style="background: #e2e8f0; padding: 12px 20px; font-weight: 800; color: var(--text-main); border-right: 1px solid var(--border);">Rp</span>
                        <input type="number" name="amount" placeholder="0" 
                               style="width: 100%; border: none; background: transparent; padding: 12px 16px; outline: none; font-weight: 800; font-size: 18px; color: var(--text-main);" required>
                    </div>
                </div>

                <div style="grid-column: span 2;">
                    <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Deskripsi</label>
                    <textarea name="description" rows="3" 
                              style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; resize: none;" 
                              placeholder="Ketik detail transaksi di sini..."
                              onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.05)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'; this.style.boxShadow='none'"></textarea>
                </div>
            </div>
        </div>

        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 32px;">
            <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 24px; padding: 32px; color: white; box-shadow: var(--shadow-lg);">
                <h5 style="display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 800; margin-bottom: 24px;">
                    <i class="fa-solid fa-circle-info" style="color: var(--primary-light);"></i>
                    Panduan Entri
                </h5>
                <ul style="list-style: none; display: flex; flex-direction: column; gap: 16px; padding: 0;">
                    <li style="display: flex; gap: 12px; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                        <i class="fa-solid fa-hashtag" style="margin-top: 3px; color: var(--primary-light);"></i>
                        <span>Nomor Transaksi akan dihasilkan secara otomatis oleh sistem.</span>
                    </li>
                    <li style="display: flex; gap: 12px; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                        <i class="fa-solid fa-magnifying-glass-chart" style="margin-top: 3px; color: var(--primary-light);"></i>
                        <span>Pastikan akun GL sudah sesuai untuk akurasi laporan laba rugi.</span>
                    </li>
                    <li style="display: flex; gap: 12px; font-size: 13px; color: #94a3b8; line-height: 1.5;">
                        <i class="fa-solid fa-shield-halved" style="margin-top: 3px; color: var(--primary-light);"></i>
                        <span>Transaksi akan langsung diposting ke dalam jurnal besar.</span>
                    </li>
                </ul>

                <div style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 32px;">
                    <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 800; font-size: 15px; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(79, 70, 229, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.4)'">
                        <i class="fa-solid fa-cloud-arrow-up"></i>
                        Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection
