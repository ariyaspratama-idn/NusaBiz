@extends('layouts.admin')

@section('title', 'Pengaturan Website')
@section('page_title', 'Pengaturan Website (CMS)')

@section('content')
<div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:28px;">
    <div>
        <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); letter-spacing: -0.5px;">Pengaturan Website</h2>
        <p style="font-size:13px;color:var(--text-muted);margin-top:4px;">Kelola narasi profil perusahaan dan kebijakan legal</p>
    </div>
</div>

<div class="card" style="border-radius: 20px; border: 1px solid var(--border); box-shadow: var(--shadow); overflow: hidden; background: var(--bg-card);">
    <div class="card-header" style="background: rgba(255,255,255,0.02); border-bottom: 1px solid var(--border); padding: 24px;">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--text-main);"><i class="fa-solid fa-sliders" style="color:var(--primary);margin-right:8px;"></i> Konten Profil & Legal</h3>
    </div>
    <div class="card-body" style="padding: 32px;">
        <form action="{{ route('admin.cms.settings.update') }}" method="POST">
            @csrf
            
            <div style="background: var(--bg-card-2); border-radius: 20px; border: 1px solid var(--border); padding: 32px; margin-bottom: 32px;">
                <h4 style="font-size: 15px; font-weight: 800; color: var(--text-main); margin-bottom: 24px; display: flex; align-items: center; gap: 10px;">
                    <i class="fa-solid fa-building" style="color: var(--primary);"></i> PROFIL PERUSAHAAN
                </h4>
                
                <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 32px;">
                    <div>
                        <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Sejarah Singkat</label>
                        <textarea name="company_history" rows="8" 
                                  style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                                  onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                                  onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('company_history', $settings['company_history']) }}</textarea>
                    </div>
                    
                    <div style="display: flex; flex-direction: column; gap: 24px;">
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Moto Bisnis</label>
                            <input type="text" name="company_motto" value="{{ old('company_motto', $settings['company_motto']) }}" 
                                   style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; font-weight: 700; font-style: italic; outline: none; transition: var(--transition);"
                                   onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                                   onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">
                        </div>
                        <div>
                            <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Visi Perusahaan</label>
                            <textarea name="company_vision" rows="4" 
                                      style="width: 100%; padding: 12px 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                                      onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                                      onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('company_vision', $settings['company_vision']) }}</textarea>
                        </div>
                    </div>
                </div>
                
                <div style="margin-top: 24px;">
                    <label style="display: block; font-size: 11px; font-weight: 800; color: var(--text-muted); text-transform: uppercase; letter-spacing: 1px; margin-bottom: 8px;">Misi (Poin-poin)</label>
                    <textarea name="company_mission" rows="4" 
                              style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 14px; line-height: 1.6; outline: none; transition: var(--transition);"
                              placeholder="Masukkan poin misi, satu per baris..."
                              onfocus="this.style.borderColor='var(--primary)'; this.style.boxShadow='0 0 0 4px rgba(99, 102, 241, 0.1)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('company_mission', $settings['company_mission']) }}</textarea>
                </div>
            </div>

            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(400px, 1fr)); gap: 32px; margin-bottom: 32px;">
                <div style="background: var(--bg-card-2); border-radius: 20px; border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-size: 14px; font-weight: 800; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-file-contract" style="color: #f59e0b;"></i> SYARAT & KETENTUAN
                    </h4>
                    <textarea name="terms_conditions" rows="12" 
                              style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 13px; line-height: 1.6; outline: none; transition: var(--transition);"
                              onfocus="this.style.borderColor='#f59e0b'; this.style.boxShadow='0 0 0 4px rgba(245, 158, 11, 0.1)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('terms_conditions', $settings['terms_conditions']) }}</textarea>
                </div>

                <div style="background: var(--bg-card-2); border-radius: 20px; border: 1px solid var(--border); padding: 24px; box-shadow: var(--shadow-sm);">
                    <h4 style="font-size: 14px; font-weight: 800; color: var(--text-main); margin-bottom: 20px; display: flex; align-items: center; gap: 10px;">
                        <i class="fa-solid fa-shield-halved" style="color: #10b981;"></i> KEBIJAKAN PRIVASI
                    </h4>
                    <textarea name="privacy_policy" rows="12" 
                              style="width: 100%; padding: 16px; background: var(--bg-main); color: var(--text-main); border: 1px solid var(--border); border-radius: 12px; font-size: 13px; line-height: 1.6; outline: none; transition: var(--transition);"
                              onfocus="this.style.borderColor='#10b981'; this.style.boxShadow='0 0 0 4px rgba(16, 185, 129, 0.1)'"
                              onblur="this.style.borderColor='var(--border)'; this.style.boxShadow='none'">{{ old('privacy_policy', $settings['privacy_policy']) }}</textarea>
                </div>
            </div>

            <div style="display: flex; justify-content: flex-end; gap: 16px; padding-top: 24px; border-top: 1px solid var(--border);">
                <button type="reset" class="btn btn-outline" style="padding: 12px 24px;">Batalkan</button>
                <button type="submit" class="btn btn-primary" style="padding: 12px 32px;">
                    <i class="fa-solid fa-cloud-arrow-up"></i> <span>Simpan Perubahan</span>
                </button>
            </div>
        </form>
    </div>
</div>

@endsection
