@extends('layouts.admin')
@section('title', __('ui.create_account'))
@section('page_title', 'Manajemen Akuntansi')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div style="display:flex; align-items:center; gap:16px;">
        <a href="{{ route('accounts.index') }}" style="width:40px;height:40px;border-radius:12px;border:1px solid var(--border);display:flex;align-items:center;justify-content:center;color:var(--text-muted);background:white;transition:var(--transition);" onmouseover="this.style.borderColor='var(--primary)'; this.style.color='var(--primary)'" onmouseout="this.style.borderColor='var(--border)'; this.style.color='var(--text-muted)'">
            <i class="fa-solid fa-arrow-left"></i>
        </a>
        <div>
            <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">{{ __('ui.create_account') }}</h2>
            <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">{{ __('ui.create_account_description') }}</p>
        </div>
    </div>
</div>

<form action="{{ route('accounts.store') }}" method="POST" id="smartAccountForm">
    @csrf
    
    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 32px; align-items: start;">
        {{-- Left Column: Smart Logic --}}
        <div style="display: flex; flex-direction: column; gap: 32px;">
            {{-- 1. Visual Type Selection --}}
            <div class="card" style="border-radius: 24px; border: 1px solid var(--border); background: white; padding: 32px; box-shadow: var(--shadow-sm);">
                <label style="display: flex; align-items: center; gap: 12px; font-size: 16px; font-weight: 800; color: var(--text-main); margin-bottom: 24px;">
                    <i class="fa-solid fa-layer-group" style="color: var(--primary);"></i>
                    Pilih Tipe Akun (Pusat Logika)
                </label>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(100px, 1fr)); gap: 16px;">
                    @php
                        $types = [
                            ['id' => 'ASSET', 'label' => 'Asset', 'sub' => '1xxx', 'icon' => 'wallet', 'color' => '#3b82f6'],
                            ['id' => 'LIABILITY', 'label' => 'Liability', 'sub' => '2xxx', 'icon' => 'landmark', 'color' => '#ef4444'],
                            ['id' => 'EQUITY', 'label' => 'Equity', 'sub' => '3xxx', 'icon' => 'users', 'color' => '#10b981'],
                            ['id' => 'REVENUE', 'label' => 'Revenue', 'sub' => '4xxx', 'icon' => 'trending-up', 'color' => '#06b6d2'],
                            ['id' => 'EXPENSE', 'label' => 'Expense', 'sub' => '5xxx', 'icon' => 'trending-down', 'color' => '#f59e0b'],
                        ];
                    @endphp

                    @foreach($types as $type)
                    <div style="position: relative;">
                        <input type="radio" name="type" id="type_{{ $type['id'] }}" value="{{ $type['id'] }}" {{ $loop->first ? 'checked' : '' }} required style="position: absolute; opacity: 0;">
                        <label for="type_{{ $type['id'] }}" class="type-card" style="display: block; padding: 20px 12px; text-align: center; border-radius: 16px; border: 2px solid #f1f5f9; background: #f8fafc; cursor: pointer; transition: var(--transition);">
                            <div class="icon-box" style="width: 44px; height: 44px; margin: 0 auto 12px; border-radius: 12px; background: {{ $type['color'] }}15; color: {{ $type['color'] }}; display: flex; align-items: center; justify-content: center; transition: var(--transition);">
                                <i class="fa-solid fa-{{ $type['icon'] }}" style="font-size: 18px;"></i>
                            </div>
                            <div style="font-weight: 800; font-size: 13px; color: var(--text-main);">{{ $type['label'] }}</div>
                            <div style="font-size: 10px; color: var(--text-muted); font-weight: 600;">{{ $type['sub'] }}</div>
                        </label>
                    </div>
                    @endforeach
                </div>
            </div>

            {{-- 2. Detail Data --}}
            <div class="card" style="border-radius: 24px; border: 1px solid var(--border); background: white; padding: 40px; box-shadow: var(--shadow-sm);">
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">{{ __('ui.account_code') }}</label>
                        <div style="display: flex; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; transition: var(--transition); background: #f8fafc;">
                            <span id="codePrefix" style="background: #e2e8f0; padding: 12px 16px; font-weight: 800; color: var(--text-main); border-right: 1px solid var(--border);">1</span>
                            <input type="text" name="code" id="accountCode" 
                                   style="width: 100%; border: none; background: transparent; padding: 12px 16px; outline: none; font-weight: 700; color: var(--text-main);" 
                                   placeholder="e.g. 101" required>
                        </div>
                        <small style="font-size: 11px; color: var(--text-muted); margin-top: 6px; display: block;">Saran: Nomor yang diawali angka di atas.</small>
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">{{ __('ui.category') }}</label>
                        <input type="text" name="category" id="accountCategory" 
                               style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc;" 
                               placeholder="e.g. Kas dan Bank"
                               onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.05)'"
                               onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                        <div id="categorySuggestions" style="display: flex; flex-wrap: wrap; gap: 8px; margin-top: 12px;"></div>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">{{ __('ui.account_name') }}</label>
                        <input type="text" name="name" 
                               style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; font-weight: 700;" 
                               placeholder="e.g. Kas Utama Surabaya" required
                               onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.05)'"
                               onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'; this.style.boxShadow='none'">
                    </div>

                    <div>
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Saldo Normal</label>
                        <select name="normal_balance" id="normalBalance" 
                                style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; appearance: none; cursor: pointer; font-weight: 600;" required>
                            <option value="DEBIT">Debit</option>
                            <option value="KREDIT">Kredit</option>
                        </select>
                    </div>

                    <div style="display: flex; align-items: center; gap: 12px; padding-top: 24px;">
                         <label class="switch">
                            <input type="checkbox" name="is_header" id="is_header" value="1">
                            <span class="slider round"></span>
                        </label>
                        <span style="font-size: 14px; font-weight: 700; color: var(--text-main);">Jadikan Akun Induk (Header)?</span>
                    </div>

                    <div style="grid-column: span 2;">
                        <label style="display: block; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 0.5px; margin-bottom: 8px;">Deskripsi / Catatan Akun</label>
                        <textarea name="description" rows="3" 
                                  style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; transition: var(--transition); background: #f8fafc; resize: none;" 
                                  placeholder="Jelaskan fungsi akun ini..."
                                  onfocus="this.style.borderColor='var(--primary)'; this.style.background='white'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.05)'"
                                  onblur="this.style.borderColor='var(--border)'; this.style.background='#f8fafc'; this.style.boxShadow='none'"></textarea>
                    </div>
                </div>

                {{-- 3. Advanced Enterprise Controls --}}
                <div style="margin-top: 40px; border-top: 1px solid #f1f5f9; padding-top: 32px;">
                    <div id="advancedToggle" style="display: flex; align-items: center; gap: 10px; cursor: pointer; color: var(--primary); margin-bottom: 24px;">
                        <i class="fa-solid fa-sliders"></i>
                        <span style="font-weight: 800; font-size: 15px;">Pengaturan Lanjutan (Enterprise Controls)</span>
                        <i class="fa-solid fa-chevron-down" id="toggleIcon" style="font-size: 12px; transition: var(--transition);"></i>
                    </div>

                    <div id="advancedContent" style="display: none; flex-direction: column; gap: 24px;">
                        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 24px;">
                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                    <i class="fa-solid fa-location-dot"></i> Pembatasan Cabang
                                </label>
                                <select name="restricted_branch_id" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; background: #f8fafc;">
                                    <option value="">Global (Tersedia untuk Semua Cabang)</option>
                                    @foreach($branches as $branch)
                                        <option value="{{ $branch->id }}">{{ $branch->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                    <i class="fa-solid fa-user-tie"></i> Penanggung Jawab (PIC)
                                </label>
                                <select name="pic_id" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; background: #f8fafc;">
                                    <option value="">Pilih Staff Bertanggung Jawab</option>
                                    @foreach($users as $user)
                                        <option value="{{ $user->id }}">{{ $user->name }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                    <i class="fa-solid fa-piggy-bank"></i> Anggaran Bulanan (Plafon)
                                </label>
                                <div style="display: flex; border: 1px solid var(--border); border-radius: 12px; overflow: hidden; background: #f8fafc;">
                                    <span style="background: #e2e8f0; padding: 12px; font-weight: 800; color: var(--text-main);">Rp</span>
                                    <input type="number" name="monthly_budget" placeholder="0" style="width: 100%; border: none; background: transparent; padding: 12px; outline: none;">
                                </div>
                            </div>

                            <div>
                                <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                    <i class="fa-solid fa-percent"></i> Default Pajak
                                </label>
                                <select name="default_tax_rate" style="width: 100%; border: 1px solid var(--border); border-radius: 12px; padding: 12px 16px; outline: none; background: #f8fafc;">
                                    <option value="0">Tanpa Pajak (0%)</option>
                                    <option value="11">PPN (11%)</option>
                                    <option value="10">PB1 (10%)</option>
                                    <option value="2">PPh 23 (2%)</option>
                                </select>
                            </div>
                        </div>

                        <div>
                            <label style="display: flex; align-items: center; gap: 8px; font-size: 12px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; margin-bottom: 8px;">
                                <i class="fa-solid fa-file-pdf"></i> Dokumen Digital (E-Filling)
                            </label>
                            <input type="file" name="attachment" style="width: 100%; border: 1px dashed var(--border); border-radius: 12px; padding: 16px; background: #fff;">
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Guidance --}}
        <div style="display: flex; flex-direction: column; gap: 24px; position: sticky; top: 32px;">
            <div style="background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%); border-radius: 24px; padding: 32px; color: white; box-shadow: var(--shadow-lg);">
                <h5 style="display: flex; align-items: center; gap: 10px; font-size: 16px; font-weight: 800; margin-bottom: 24px;">
                    <i class="fa-solid fa-lightbulb" style="color: #fbbf24;"></i>
                    Cerdas Akunting
                </h5>
                <div id="contextHelp" style="font-size: 14px; line-height: 1.6; color: #94a3b8; margin-bottom: 32px;">
                    <p>Silakan pilih tipe akun di sebelah kiri untuk melihat panduan khusus.</p>
                </div>

                <div id="advancedHelp" style="display: none; padding-top: 24px; border-top: 1px solid rgba(255,255,255,0.1);">
                    <h6 style="font-size: 12px; font-weight: 800; color: var(--primary-light); text-transform: uppercase; margin-bottom: 12px;">Enterprise Intelligence</h6>
                    <p id="advancedHelpText" style="font-size: 13px; color: #cbd5e1; line-height: 1.6;"></p>
                </div>

                <div style="margin-top: 40px; border-top: 1px solid rgba(255,255,255,0.1); padding-top: 32px;">
                    <button type="submit" style="width: 100%; background: var(--primary); color: white; border: none; padding: 18px; border-radius: 16px; font-weight: 800; font-size: 15px; cursor: pointer; transition: var(--transition); display: flex; align-items: center; justify-content: center; gap: 10px; box-shadow: 0 4px 12px rgba(79, 70, 229, 0.4);" onmouseover="this.style.transform='translateY(-2px)'; this.style.boxShadow='0 8px 16px rgba(79, 70, 229, 0.3)'" onmouseout="this.style.transform='none'; this.style.boxShadow='0 4px 12px rgba(79, 70, 229, 0.4)'">
                        <i class="fa-solid fa-check-circle"></i>
                        {{ __('ui.create_account') }}
                    </button>
                    <div style="text-align: center; margin-top: 16px; font-size: 11px; color: #64748b; font-weight: 600;">Internal Control v10.0 Aktif</div>
                </div>
            </div>

            <div style="background: #fff; border-radius: 20px; border: 1px solid var(--border); padding: 24px;">
                <h6 style="font-size: 13px; font-weight: 800; color: var(--text-main); margin-bottom: 16px;">Audit Persiapan</h6>
                <div style="display: flex; flex-direction: column; gap: 12px;">
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--text-muted);">
                        <i class="fa-solid fa-circle-check" style="color: var(--success);"></i>
                        <span>Validasi Format Kode GL</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 10px; font-size: 12px; color: var(--text-muted);">
                        <i class="fa-solid fa-circle-check" style="color: var(--success);"></i>
                        <span>Pemeriksaan Duplikasi</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
    /* Premium Switch Button */
    .switch { position: relative; display: inline-block; width: 44px; height: 24px; }
    .switch input { opacity: 0; width: 0; height: 0; }
    .slider { position: absolute; cursor: pointer; top: 0; left: 0; right: 0; bottom: 0; background-color: #e2e8f0; transition: .4s; }
    .slider:before { position: absolute; content: ""; height: 18px; width: 18px; left: 3px; bottom: 3px; background-color: white; transition: .4s; }
    input:checked + .slider { background-color: var(--primary); }
    input:checked + .slider:before { transform: translateX(20px); }
    .slider.round { border-radius: 34px; }
    .slider.round:before { border-radius: 50%; }

    /* Type Card Logic */
    input[name="type"]:checked + .type-card {
        border-color: var(--primary) !important;
        background: white !important;
        box-shadow: 0 8px 20px rgba(79, 70, 229, 0.08);
    }
    input[name="type"]:checked + .type-card .icon-box {
        background: var(--primary) !important;
        color: white !important;
    }
    .type-card:hover:not(input[name="type"]:checked + .type-card) {
        border-color: #cbd5e1 !important;
        background: #f1f5f9 !important;
    }
    .suggestion-badge {
        background: #f1f5f9; color: var(--text-muted); padding: 6px 12px; border-radius: 8px; font-size: 11px; font-weight: 700; cursor: pointer; border: 1px solid transparent; transition: var(--transition);
    }
    .suggestion-badge:hover { background: white; border-color: var(--primary); color: var(--primary); }
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Toggle Advanced Content
    const advancedToggle = document.getElementById('advancedToggle');
    const advancedContent = document.getElementById('advancedContent');
    const toggleIcon = document.getElementById('toggleIcon');
    
    advancedToggle.onclick = () => {
        const isHidden = advancedContent.style.display === 'none';
        advancedContent.style.display = isHidden ? 'flex' : 'none';
        toggleIcon.style.transform = isHidden ? 'rotate(180deg)' : 'rotate(0deg)';
    };

    const typeRadios = document.querySelectorAll('input[name="type"]');
    const codePrefix = document.getElementById('codePrefix');
    const normalBalance = document.getElementById('normalBalance');
    const contextHelp = document.getElementById('contextHelp');
    const categorySuggestions = document.getElementById('categorySuggestions');
    const accountCategory = document.getElementById('accountCategory');
    const advancedHelp = document.getElementById('advancedHelp');
    const advancedHelpText = document.getElementById('advancedHelpText');

    const config = {
        'ASSET': { prefix: '1', balance: 'DEBIT', help: '<strong>Asset</strong>: Apa yang dimiliki perusahaan (Kas, Piutang, Peralatan). Menambah di Debit.', cats: ['Kas dan Bank', 'Persediaan', 'Piutang Usaha', 'Aset Tetap'], adv: 'Gunakan fitur **E-Filling** untuk melampirkan scan sertifikat aset tetap Anda.' },
        'LIABILITY': { prefix: '2', balance: 'KREDIT', help: '<strong>Liability</strong>: Hutang atau kewajiban perusahaan kepada pihak luar. Menambah di Kredit.', cats: ['Hutang Usaha', 'Hutang Gaji', 'Pinjaman Bank'], adv: 'Anda bisa melampirkan akta hutang atau kontrak pinjaman di bagian Dokumen Digital.' },
        'EQUITY': { prefix: '3', balance: 'KREDIT', help: '<strong>Equity</strong>: Modal pemilik atau laba ditahan dalam bisnis. Menambah di Kredit.', cats: ['Modal Pemilik', 'Laba Ditahan', 'Prive'], adv: 'PIC Akutansi biasanya adalah Direktur atau Owner untuk akun Modal.' },
        'REVENUE': { prefix: '4', balance: 'KREDIT', help: '<strong>Revenue</strong>: Pendapatan dari hasil penjualan barang atau jasa. Menambah di Kredit.', cats: ['Penjualan Produk', 'Pendapatan Layanan', 'Diskon Penjualan'], adv: 'Default Pajak membantu menghitung PPN secara otomatis saat ada penjualan.' },
        'EXPENSE': { prefix: '5', balance: 'DEBIT', help: '<strong>Expense</strong>: Biaya yang dikeluarkan untuk menjalankan operasional. Menambah di Debit.', cats: ['Beban Gaji', 'Beban Sewa', 'Beban Listrik & Air', 'Beban Pemasaran'], adv: 'Gunakan **Anggaran Bulanan** untuk mencegah biaya operasional yang membengkak.' }
    };

    function updateSmartLogic(type) {
        const c = config[type];
        codePrefix.textContent = c.prefix;
        normalBalance.value = c.balance;
        contextHelp.innerHTML = `<p>${c.help}</p>`;
        
        // Update Advanced Help
        advancedHelp.style.display = 'block';
        advancedHelpText.innerHTML = c.adv;

        // Update Categories
        categorySuggestions.innerHTML = '';
        c.cats.forEach(cat => {
            const span = document.createElement('span');
            span.className = 'suggestion-badge';
            span.textContent = cat;
            span.onclick = () => accountCategory.value = cat;
            categorySuggestions.appendChild(span);
        });
    }

    typeRadios.forEach(radio => {
        radio.addEventListener('change', (e) => updateSmartLogic(e.target.value));
    });

    // Init with first selected radio
    const activeRadio = document.querySelector('input[name="type"]:checked');
    if(activeRadio) updateSmartLogic(activeRadio.value);
});
</script>
@endsection
