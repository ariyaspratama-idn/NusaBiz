@extends('layouts.admin')
@section('title', 'Laporan Gaji')
@section('page_title', 'Riwayat Penggajian')

@section('content')
<!-- ======================================================
     PROFIL KARYAWAN
     ====================================================== -->
<div class="row" style="margin-bottom: 24px;">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e293b 100%); border: 1px solid rgba(99,102,241,0.3); border-radius: 24px; padding: 28px; position:relative; overflow:hidden;">
            <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(99,102,241,0.08);border-radius:50%;"></div>
            <div style="display:flex;align-items:flex-start;gap:20px;flex-wrap:wrap;">
                
                <!-- Avatar / Foto -->
                <div style="position:relative;flex-shrink:0;">
                    <div style="width:90px;height:90px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;color:white;box-shadow:0 10px 20px rgba(99,102,241,0.4);">
                        <i class="fa-solid fa-user-tie"></i>
                    </div>
                </div>

                <!-- Area Konten & Tombol (Kolom) -->
                <div style="flex:1; display:flex; flex-direction:column; gap:20px; z-index:1;">
                    
                    <!-- Baris Info & Jam Real-time -->
                    <div style="display:flex; justify-content:space-between; align-items:center; flex-wrap:wrap; gap:20px;">
                        <!-- Info -->
                        <div style="flex:1;min-width:200px;">
                            <h2 style="font-size:22px;font-weight:800;color:white;margin-bottom:4px;">Halo, {{ $karyawan->nama_lengkap }}! 👋</h2>
                            <p style="color:rgba(255,255,255,0.6);font-size:13px;margin-bottom:12px;">
                                <i class="fa-solid fa-id-badge" style="margin-right:6px;color:#a5b4fc;"></i>NIP: <strong style="color:#a5b4fc;">{{ $karyawan->nip }}</strong>
                                &nbsp;•&nbsp;
                                <i class="fa-solid fa-building" style="margin-right:4px;color:#a5b4fc;"></i>{{ $karyawan->branch->name ?? 'Pusat' }}
                                &nbsp;•&nbsp;
                                <i class="fa-solid fa-briefcase" style="margin-right:4px;color:#a5b4fc;"></i>{{ ucfirst(auth()->user()->role ?? '-') }}
                            </p>
                        </div>

                        <!-- Jam Real-time -->
                        <div style="text-align:center;flex-shrink:0;">
                            <div id="realtimeClock" style="font-size:28px;font-weight:900;color:white;font-variant-numeric:tabular-nums;letter-spacing:2px;"></div>
                            <div id="realtimeDate" style="font-size:11px;color:rgba(255,255,255,0.5);margin-top:4px;"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tombol Aksi Tambahan (Izin & Profil) -->
            <div style="margin-top:24px;padding-top:24px;border-top:1px solid rgba(255,255,255,0.1);display:flex;gap:16px;flex-wrap:wrap;">
                <button onclick="document.getElementById('modalIzin').style.display='flex'" class="btn" style="flex:1;background:rgba(99,102,241,0.1);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3);border-radius:12px;padding:14px;font-size:14px;font-weight:700;min-width:200px;transition:all 0.2s;text-align:center;cursor:pointer;" onmouseover="this.style.background='rgba(99,102,241,0.25)';this.style.color='#fff'" onmouseout="this.style.background='rgba(99,102,241,0.1)';this.style.color='#a5b4fc'">
                    <i class="fa-solid fa-calendar-plus" style="margin-right:8px;"></i>Pengajuan Izin / Cuti
                </button>
                <button onclick="document.getElementById('modalProfile').style.display='flex'" class="btn" style="flex:1;background:rgba(255,255,255,0.05);color:white;border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:14px;font-size:14px;font-weight:700;min-width:200px;transition:all 0.2s;text-align:center;cursor:pointer;" onmouseover="this.style.background='rgba(255,255,255,0.1)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    <i class="fa-solid fa-user-gear" style="margin-right:8px;"></i>Pengaturan Profil / Telegram
                </button>
            </div>
        </div>
    </div>
</div>

<div class="card" style="margin-bottom: 24px;">
    <div class="card-header">
        <h3 style="font-size: 18px; font-weight: 800; color: var(--text-main);">
            <i class="fa-solid fa-file-invoice-dollar" style="color:var(--primary);margin-right:8px;"></i> Slip Gaji Digital
        </h3>
        <span class="badge badge-info" style="font-size: 11px;">Total {{ $payrolls->total() }} Slip</span>
    </div>
    
    <div>
        <table>
            <thead>
                <tr>
                    <th>Periode (Bulan)</th>
                    <th>Gaji Pokok</th>
                    <th>Lembur</th>
                    <th>Potongan/Lainnya</th>
                    <th>Total Gaji</th>
                    <th>Status Pembayaran</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($payrolls as $pay)
                <tr>
                    <td><strong style="color:var(--text-main);">{{ \Carbon\Carbon::parse($pay->periode_bulan)->translatedFormat('F Y') }}</strong></td>
                    <td>Rp {{ number_format($pay->gaji_pokok, 0, ',', '.') }}</td>
                    <td class="text-success">+ Rp {{ number_format($pay->lembur, 0, ',', '.') }}</td>
                    <td class="text-danger">- Rp 0</td>
                    <td style="font-weight: 800; color: #10b981;">Rp {{ number_format($pay->total_gaji, 0, ',', '.') }}</td>
                    <td>
                        @if($pay->status_pembayaran === 'paid')
                            <span class="badge badge-success"><i class="fa-solid fa-check-double"></i> Sudah Dibayar</span>
                        @else
                            <span class="badge badge-warning"><i class="fa-solid fa-clock"></i> Masih Diproses</span>
                        @endif
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding: 5px 12px; font-size: 11px;" onclick="alert('Fitur Cetak PDF segera hadir!')">
                            <i class="fa-solid fa-print"></i> Cetak
                        </button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="7" class="text-center py-5">
                        <i class="fa-solid fa-receipt" style="font-size: 40px; color: var(--text-muted); opacity: 0.3; display: block; margin-bottom: 10px;"></i>
                        Belum ada data penggajian yang tersedia untuk profil Anda.
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<div class="d-flex justify-content-center">
    {{ $payrolls->links() }}
</div>

<div class="alert alert-info" style="background: rgba(34, 211, 238, 0.1); border-left: 5px solid var(--info); color: var(--text-main); font-size: 13px;">
    <i class="fa-solid fa-circle-info" style="margin-right: 8px;"></i> 
    <strong>Catatan:</strong> Jika terdapat ketidaksesuaian data pada slip gaji Anda, silakan hubungi Penanggung Jawab Cabang atau Admin Pusat.
</div>

<!-- ======================================================
     MODAL PENGAJUAN IZIN / LEMBUR
     ====================================================== -->
<div id="modalIzin" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--card-bg);width:100%;max-width:500px;border-radius:24px;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.1);">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(255,255,255,0.05);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="margin:0;font-size:18px;font-weight:700;"><i class="fa-solid fa-file-signature text-primary me-2"></i>Pengajuan Kehadiran</h3>
            <button onclick="document.getElementById('modalIzin').style.display='none'" style="background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:24px;">
            <form action="{{ route('karyawan.izin.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="lat" id="izinLat">
                <input type="hidden" name="lon" id="izinLon">

                <div class="form-group mb-3">
                    <label style="font-size:13px;color:var(--text-muted);margin-bottom:6px;display:block;">Tipe Pengajuan <span class="text-danger">*</span></label>
                    <select name="tipe" class="form-control" required style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1);color:var(--text-main);border-radius:10px;">
                        <option value="">-- Pilih Tipe --</option>
                        <option value="izin">Izin Tidak Masuk</option>
                        <option value="sakit">Sakit</option>
                        <option value="cuti">Cuti Tahunan</option>
                    </select>
                </div>

                <div class="form-group mb-3">
                    <label style="font-size:13px;color:var(--text-muted);margin-bottom:6px;display:block;">Alasan / Keterangan <span class="text-danger">*</span></label>
                    <textarea name="alasan" class="form-control" rows="3" required placeholder="Tulis keterangan detail..." style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1);color:var(--text-main);border-radius:10px;resize:none;"></textarea>
                </div>

                <div class="form-group mb-4">
                    <label style="font-size:13px;color:var(--text-muted);margin-bottom:6px;display:block;">Lampiran Bukti (Foto/PDF) <span class="text-danger">*</span></label>
                    <input type="file" name="bukti" class="form-control" accept="image/*,.pdf" required style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1);color:var(--text-main);border-radius:10px;">
                    <small style="color:var(--text-muted);display:block;margin-top:6px;">Wajib melampirkan surat dokter/bukti kegiatan kerja tambahan.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius:12px;font-weight:700;">
                    Kirim Pengajuan
                </button>
            </form>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL PENGATURAN PROFIL
     ====================================================== -->
<div id="modalProfile" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.7);z-index:9999;backdrop-filter:blur(4px);align-items:center;justify-content:center;padding:20px;">
    <div style="background:var(--card-bg);width:100%;max-width:400px;border-radius:24px;overflow:hidden;box-shadow:0 25px 50px -12px rgba(0,0,0,0.5);border:1px solid rgba(255,255,255,0.1);">
        <div style="padding:20px 24px;border-bottom:1px solid rgba(255,255,255,0.05);display:flex;justify-content:space-between;align-items:center;">
            <h3 style="margin:0;font-size:18px;font-weight:700;"><i class="fa-solid fa-user-gear text-primary me-2"></i>Pengaturan Profil</h3>
            <button onclick="document.getElementById('modalProfile').style.display='none'" style="background:none;border:none;color:var(--text-muted);font-size:20px;cursor:pointer;">&times;</button>
        </div>
        <div style="padding:24px;">
            <form action="{{ route('karyawan.profile.update') }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="form-group mb-4">
                    <label style="font-size:13px;color:var(--text-muted);margin-bottom:6px;display:block;">Nomor Telepon / WhatsApp / Telegram</label>
                    <div style="position:relative;">
                        <span style="position:absolute;left:14px;top:50%;transform:translateY(-50%);color:#a5b4fc;"><i class="fa-brands fa-telegram"></i></span>
                        <input type="text" name="no_hp" value="{{ $karyawan->no_hp ?? '' }}" class="form-control" placeholder="Contoh: 08123456789" style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1);color:var(--text-main);border-radius:10px;padding-left:40px;">
                    </div>
                    <small style="color:var(--text-muted);display:block;margin-top:6px;">Nomor ini digunakan untuk mengirimkan notifikasi dari Bot Telegram sistem.</small>
                </div>

                <button type="submit" class="btn btn-primary w-100 py-3" style="border-radius:12px;font-weight:700;">
                    Simpan Perubahan
                </button>
            </form>
        </div>
    </div>
</div>

<script>
// ========= REAL-TIME CLOCK =========
function updateClock() {
    const now = new Date();
    document.getElementById('realtimeClock').textContent = 
        String(now.getHours()).padStart(2, '0') + ':' + 
        String(now.getMinutes()).padStart(2, '0') + ':' + 
        String(now.getSeconds()).padStart(2, '0');
    
    document.getElementById('realtimeDate').textContent = 
        now.toLocaleDateString('id-ID', { weekday: 'long', day: 'numeric', month: 'long', year: 'numeric' });
}
setInterval(updateClock, 1000);
updateClock();
</script>
@endsection
