@extends('layouts.admin')
@section('title', 'Dashboard Karyawan')
@section('page_title', 'Terminal Karyawan')

@section('content')
<!-- Focused Header: Profil & Absensi -->
<div class="row" style="margin-bottom: 32px;">
    <div class="col-md-8">
        <div class="card" style="padding: 24px; background: linear-gradient(135deg, var(--sidebar-bg) 0%, #1e293b 100%);">
            <div style="display: flex; align-items: center; gap: 20px;">
                <div style="width: 80px; height: 80px; background: var(--primary); border-radius: 20px; display: flex; align-items: center; justify-content: center; font-size: 32px; color: white; box-shadow: 0 10px 15px -3px rgba(79, 70, 229, 0.4);">
                    <i class="fa-solid fa-user-tie"></i>
                </div>
                <div>
                    <h2 style="font-size: 24px; font-weight: 800; color: var(--text-main); margin-bottom: 4px;">Halo, {{ $karyawan->nama_lengkap }}!</h2>
                    <p style="color: var(--text-muted); font-size: 14px; margin-bottom: 12px;">NIP: <span style="color: var(--primary-light); font-weight: 700;">{{ $karyawan->nip }}</span> &bull; Cabang: {{ $karyawan->branch->name ?? 'Pusat' }}</p>
                    
                    @if(!$absensi)
                        <span class="badge badge-warning" style="padding: 6px 12px;"><i class="fa-solid fa-circle-exclamation"></i> Belum Absen Masuk</span>
                    @elseif(!$absensi->jam_pulang)
                        <span class="badge badge-success" style="padding: 6px 12px;"><i class="fa-solid fa-circle-check"></i> Sudah Absen Masuk ({{ $absensi->jam_masuk }})</span>
                    @else
                        <span class="badge badge-info" style="padding: 6px 12px;"><i class="fa-solid fa-flag-checkered"></i> Selesai Bekerja ({{ $absensi->jam_pulang }})</span>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-4">
        <div class="card" style="padding: 24px; text-align: center; height: 100%; display: flex; flex-direction: column; justify-content: center; background: #1e1b4b; border: 1px dashed var(--primary);">
            <div style="font-size: 13px; color: var(--text-muted); margin-bottom: 10px;">Ringkasan Gaji Periode Ini</div>
            <div style="font-size: 24px; font-weight: 800; color: #4ade80; margin-bottom: 4px;">
                Rp {{ $currentPayroll ? number_format($currentPayroll->total_gaji, 0, ',', '.') : '0' }}
            </div>
            <a href="{{ route('karyawan.payroll') }}" style="font-size: 11px; color: var(--primary-light); text-decoration: none; font-weight: 600;">Lihat Detail Slip <i class="fa-solid fa-chevron-right"></i></a>
        </div>
    </div>
</div>

<!-- Main Action: Absensi -->
<div class="row" style="margin-bottom: 32px;">
    <div class="col-12">
        <div class="card" style="padding: 40px; text-align: center; border-radius: 24px; background: rgba(30, 41, 59, 0.5); border: 2px solid rgba(255,255,255,0.05);">
            <h3 style="margin-bottom: 24px; font-weight: 700;">Konfirmasi Kehadiran</h3>
            
            <div style="max-width: 400px; margin: 0 auto;">
                @if(!$absensi)
                    <button onclick="doAbsen()" id="btnAbsen" class="btn btn-primary w-100 py-4" style="font-size: 18px; border-radius: 16px; font-weight: 800; box-shadow: 0 20px 25px -5px rgba(79, 70, 229, 0.4);">
                        <i class="fa-solid fa-right-to-bracket" style="margin-right: 12px;"></i> KLIK ABSEN MASUK
                    </button>
                @elseif(!$absensi->jam_pulang)
                    <button onclick="doAbsen()" id="btnAbsen" class="btn btn-danger w-100 py-4" style="font-size: 18px; border-radius: 16px; font-weight: 800; box-shadow: 0 20px 25px -5px rgba(244, 63, 94, 0.4);">
                        <i class="fa-solid fa-right-from-bracket" style="margin-right: 12px;"></i> KLIK ABSEN PULANG
                    </button>
                @else
                    <div style="padding: 30px; background: rgba(74, 222, 128, 0.1); border: 1px solid #22c55e; border-radius: 16px; color: #4ade80;">
                        <i class="fa-solid fa-circle-check" style="font-size: 40px; margin-bottom: 15px;"></i>
                        <div style="font-weight: 700; font-size: 18px;">Terima kasih atas kerja kerasnya!</div>
                        <div style="font-size: 13px; opacity: 0.8;">Sampai jumpa di hari kerja berikutnya.</div>
                    </div>
                @endif
            </div>
            
            <p id="locationStatus" style="margin-top: 20px; font-size: 12px; color: var(--text-muted);">
                Sistem akan mencatat lokasi GPS Anda secara otomatis.
            </p>
        </div>
    </div>
</div>

<!-- History Tables -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header">
                <h3 style="font-size: 16px; font-weight: 700;"><i class="fa-solid fa-calendar-days" style="color: var(--primary); margin-right: 8px;"></i> Riwayat Absensi Terakhir</h3>
            </div>
            <div>
                <table>
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Jam Masuk</th>
                            <th>Jam Pulang</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestAbsensi as $item)
                        <tr>
                            <td><strong>{{ $item->tanggal }}</strong></td>
                            <td class="text-success">{{ $item->jam_masuk }}</td>
                            <td class="text-danger">{{ $item->jam_pulang ?? '-' }}</td>
                            <td><span class="badge badge-success">{{ ucfirst($item->status) }}</span></td>
                            <td><i class="fa-solid fa-location-dot" style="opacity: 0.4;"></i></td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">Belum ada riwayat absensi.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
async function doAbsen() {
    const btn = document.getElementById('btnAbsen');
    const oldHtml = btn.innerHTML;

    if (!navigator.geolocation) {
        alert("Geolocation tidak didukung oleh browser Anda.");
        return;
    }

    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Memproses Lokasi...';

    navigator.geolocation.getCurrentPosition(async (position) => {
        try {
            const response = await fetch('/api/hr/absen', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({
                    lat: position.coords.latitude,
                    lon: position.coords.longitude
                })
            });

            const result = await response.json();
            alert(result.message);
            location.reload();
        } catch (e) {
            alert("Gagal melakukan absensi. Cek koneksi internet Anda.");
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        }
    }, (error) => {
        alert("Gagal mendapatkan lokasi GPS. Pastikan izin lokasi diaktifkan.");
        btn.disabled = false;
        btn.innerHTML = oldHtml;
    });
}
</script>
@endsection
