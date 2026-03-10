@extends('layouts.admin')
@section('title', 'Testimoni Pelanggan')
@section('page_title', 'Suara Pelanggan')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Testimoni Pelanggan</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola feedback pelanggan untuk ditampilkan di landing page</p>
    </div>
</div>

<div style="display:grid; grid-template-columns: 1fr 2fr; gap:32px; align-items: start;">
    <!-- Form Tambah -->
    <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
        <div class="card-header" style="background: white; border-bottom: 1px solid var(--border); padding: 20px 24px;">
            <h3 style="font-size: 16px; font-weight: 800; color: var(--text-main);"><i class="fa-solid fa-plus-circle" style="color:var(--primary);margin-right:8px;"></i> Tambah Testimoni</h3>
        </div>
        <div class="card-body" style="padding: 24px;">
            <form action="{{ route('admin.cms.testimonials.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Nama Pelanggan</label>
                    <input type="text" name="customer_name" required 
                           style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.1)'"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>
                
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Posisi / Perusahaan</label>
                    <input type="text" name="customer_position" placeholder="Contoh: CEO of Tech" 
                           style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; outline: none; transition: var(--transition);"
                           onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.1)'"
                           onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Isi Testimoni</label>
                    <textarea name="content" rows="4" required 
                              style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                              onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(79, 70, 229, 0.1)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'"></textarea>
                </div>

                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Rating (1-5)</label>
                    <select name="rating" style="width: 100%; padding: 12px 16px; border: 1px solid var(--border); border-radius: 12px; font-size: 14px; background: white; outline: none; cursor:pointer;">
                        <option value="5">⭐⭐⭐⭐⭐ (Sangat Puas)</option>
                        <option value="4">⭐⭐⭐⭐ (Puas)</option>
                        <option value="3">⭐⭐⭐ (Cukup)</option>
                        <option value="2">⭐⭐ (Kurang)</option>
                        <option value="1">⭐ (Sangat Kurang)</option>
                    </select>
                </div>

                <div style="margin-bottom: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: #64748b; text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Avatar Pelanggan (Opsi)</label>
                    <input type="file" name="customer_avatar" style="font-size: 13px; color: var(--text-muted);">
                </div>

                <button type="submit" class="btn btn-primary" style="width: 100%; padding: 14px; justify-content: center;">
                    <i class="fa-solid fa-paper-plane"></i> <span>Simpan Testimoni</span>
                </button>
            </form>
        </div>
    </div>

    <!-- List Testimoni -->
    <div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden;">
        <div class="table-wrap">
            <table style="width: 100%; border-collapse: separate; border-spacing: 0;">
                <thead>
                    <tr style="background: #fcfcfd;">
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Pelanggan</th>
                        <th style="padding: 16px 24px; text-align: left; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Ulasan</th>
                        <th style="padding: 16px 24px; text-align: right; font-size: 12px; font-weight: 700; color: #64748b; text-transform: uppercase; letter-spacing: 1px; border-bottom: 1px solid var(--border);">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($testimonials as $testi)
                    <tr style="transition: background 0.2s;" onmouseover="this.style.background='#fbfaff'" onmouseout="this.style.background='white'">
                        <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                            <div style="display:flex;align-items:center;gap:12px;">
                                @if($testi->customer_avatar)
                                <img src="{{ asset('storage/'.$testi->customer_avatar) }}" alt="" style="width:40px;height:40px;object-fit:cover;border-radius:50%; border: 2px solid white; box-shadow: 0 4px 8px rgba(0,0,0,0.1);">
                                @else
                                <div style="width:40px;height:40px;background:linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);color:white;border-radius:50%;display:flex;align-items:center;justify-content:center;font-weight:700;font-size:14px;">{{ strtoupper(substr($testi->customer_name,0,1)) }}</div>
                                @endif
                                <div>
                                    <div style="font-weight:700; color: var(--text-main); font-size: 14px;">{{ $testi->customer_name }}</div>
                                    <div style="font-size:11px;color:var(--text-muted); margin-top: 2px;">{{ $testi->customer_position ?? 'Customer' }}</div>
                                </div>
                            </div>
                        </td>
                        <td style="padding: 20px 24px; border-bottom: 1px solid var(--border);">
                            <div style="color: #f59e0b; font-size: 10px; margin-bottom: 6px;">
                                @for($i=1; $i<=5; $i++)
                                <i class="fa-{{ $i <= $testi->rating ? 'solid' : 'regular' }} fa-star"></i>
                                @endfor
                            </div>
                            <div style="font-size:13px; color: #475569; line-height: 1.5; font-style: italic;">"{{ Str::limit($testi->content, 120) }}"</div>
                        </td>
                        <td style="padding: 20px 24px; border-bottom: 1px solid var(--border); text-align: right;">
                            <form method="POST" action="{{ route('admin.cms.testimonials.destroy', $testi) }}" onsubmit="return confirm('Hapus testimoni ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-outline" style="padding: 8px; width: 36px; height: 36px; border-radius: 10px; color: var(--danger); border-color: #fee2e2;" onmouseover="this.style.background='#fef2f2'" onmouseout="this.style.background='white'">
                                    <i class="fa-solid fa-trash-can" style="font-size: 14px;"></i>
                                </button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="3" style="text-align:center;padding:48px 24px;color:var(--text-muted);">
                            <div style="font-weight: 600;">Belum ada testimoni.</div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($testimonials->hasPages())
        <div style="padding:20px; border-top: 1px solid var(--border);">{{ $testimonials->links() }}</div>
        @endif
    </div>
</div>
@endsection
