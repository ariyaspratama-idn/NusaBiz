@extends('layouts.admin')
@section('title', 'Dashboard Karyawan')
@section('page_title', 'Terminal Karyawan')

@section('content')
<!-- ======================================================
     PROFIL KARYAWAN
     ====================================================== -->
<div class="row" style="margin-bottom: 24px;">
    <div class="col-12">
        <div class="card" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 50%, #1e293b 100%); border: 1px solid rgba(99,102,241,0.3); border-radius: 24px; padding: 28px; position:relative; overflow:hidden;">
            <div style="position:absolute;top:-40px;right:-40px;width:200px;height:200px;background:rgba(99,102,241,0.08);border-radius:50%;"></div>
            <div style="display:flex;align-items:center;gap:20px;flex-wrap:wrap;">
                <!-- Avatar / Foto -->
                <div style="position:relative;flex-shrink:0;">
                    @if($absensi && $absensi->foto_masuk)
                        <img src="{{ asset('storage/'.$absensi->foto_masuk) }}" style="width:90px;height:90px;border-radius:50%;object-fit:cover;border:3px solid #6366f1;box-shadow:0 0 0 4px rgba(99,102,241,0.25);" alt="Foto Absen">
                    @else
                        <div style="width:90px;height:90px;background:linear-gradient(135deg,#6366f1,#8b5cf6);border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:36px;color:white;box-shadow:0 10px 20px rgba(99,102,241,0.4);">
                            <i class="fa-solid fa-user-tie"></i>
                        </div>
                    @endif
                    <!-- Status dot -->
                    @if($absensi && !$absensi->jam_pulang)
                        <div style="position:absolute;bottom:2px;right:2px;width:18px;height:18px;background:#22c55e;border-radius:50%;border:2px solid #1e1b4b;"></div>
                    @endif
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

                            @if(!$absensi)
                                <span style="background:rgba(251,191,36,0.15);color:#fbbf24;border:1px solid rgba(251,191,36,0.3);padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;">
                                    <i class="fa-solid fa-clock"></i> Belum Absen Masuk
                                </span>
                            @elseif(!$absensi->jam_pulang)
                                <span style="background:rgba(34,197,94,0.15);color:#4ade80;border:1px solid rgba(34,197,94,0.3);padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;">
                                    <i class="fa-solid fa-circle-check"></i> Sudah Absen Masuk — {{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }}
                                </span>
                            @else
                                <span style="background:rgba(99,102,241,0.15);color:#a5b4fc;border:1px solid rgba(99,102,241,0.3);padding:6px 14px;border-radius:8px;font-size:12px;font-weight:700;">
                                    <i class="fa-solid fa-flag-checkered"></i> Shift Selesai — Pulang {{ \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') }}
                                </span>
                            @endif
                        </div>

                        <!-- Jam Real-time -->
                        <div style="text-align:center;flex-shrink:0;">
                            <div id="realtimeClock" style="font-size:28px;font-weight:900;color:white;font-variant-numeric:tabular-nums;letter-spacing:2px;"></div>
                            <div id="realtimeDate" style="font-size:11px;color:rgba(255,255,255,0.5);margin-top:4px;"></div>
                        </div>
                    </div>

            <!-- Tombol Aksi Tambahan (Izin & Profil) -->
            <div style="margin-top:20px;padding-top:20px;border-top:1px dashed rgba(255,255,255,0.1);display:flex;gap:12px;flex-wrap:wrap;">
                <button onclick="document.getElementById('modalIzin').style.display='flex'" class="btn" style="flex:1;background:rgba(255,255,255,0.05);color:white;border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:12px;font-weight:600;min-width:200px;transition:all 0.2s;" onmouseover="this.style.background='rgba(99,102,241,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    <i class="fa-solid fa-calendar-plus" style="color:#a5b4fc;margin-right:8px;"></i>Pengajuan Izin / Cuti / Lembur
                </button>
                <button onclick="document.getElementById('modalProfile').style.display='flex'" class="btn" style="flex:1;background:rgba(255,255,255,0.05);color:white;border:1px solid rgba(255,255,255,0.1);border-radius:12px;padding:12px;font-weight:600;min-width:200px;transition:all 0.2s;" onmouseover="this.style.background='rgba(99,102,241,0.2)'" onmouseout="this.style.background='rgba(255,255,255,0.05)'">
                    <i class="fa-solid fa-user-gear" style="color:#a5b4fc;margin-right:8px;"></i>Pengaturan Profil / Telegram
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
     PANEL ABSENSI UTAMA
     ====================================================== -->
<div class="row" style="margin-bottom: 24px;">

    <!-- Kiri: Kamera & Aksi Absen -->
    <div class="col-md-7" style="margin-bottom:16px;">
        <div class="card" style="border-radius:20px;overflow:hidden;border:1px solid rgba(255,255,255,0.07);">
            <div style="padding:20px 24px 16px;border-bottom:1px solid rgba(255,255,255,0.06);">
                <h3 style="font-size:16px;font-weight:700;color:var(--text-main);margin:0;">
                    <i class="fa-solid fa-camera" style="color:#6366f1;margin-right:8px;"></i>
                    @if(!$absensi) Absen Masuk — Foto Selfie
                    @elseif(!$absensi->jam_pulang) Absen Pulang — Foto Selfie
                    @else Absensi Hari Ini Selesai
                    @endif
                </h3>
            </div>
            <div style="padding:24px;">

                @if(!$absensi || !$absensi->jam_pulang)
                <!-- === KAMERA SELFIE === -->
                <div style="position:relative;background:#0f172a;border-radius:16px;overflow:hidden;margin-bottom:20px;aspect-ratio:4/3;display:flex;align-items:center;justify-content:center;" id="cameraWrapper">
                    <video id="cameraFeed" autoplay playsinline muted style="width:100%;height:100%;object-fit:cover;display:none;border-radius:16px;transform:scaleX(-1);"></video>
                    <canvas id="captureCanvas" style="display:none;width:100%;height:100%;border-radius:16px;"></canvas>

                    <!-- Overlay timestamp di kamera -->
                    <div id="cameraTimestampOverlay" style="display:none;position:absolute;bottom:12px;left:12px;right:12px;background:rgba(0,0,0,0.55);backdrop-filter:blur(4px);border-radius:8px;padding:8px 12px;pointer-events:none;">
                        <div style="font-size:11px;color:rgba(255,255,255,0.7);">{{ $karyawan->nama_lengkap }} — {{ $karyawan->nip }}</div>
                        <div id="overlayTime" style="font-size:14px;color:white;font-weight:700;font-variant-numeric:tabular-nums;"></div>
                        <div id="overlayLoc" style="font-size:10px;color:rgba(255,255,255,0.6);margin-top:2px;">📍 Mendeteksi lokasi...</div>
                    </div>

                    <!-- Preview foto yang sudah diambil -->
                    <img id="photoPreview" style="display:none;width:100%;height:100%;object-fit:cover;border-radius:16px;" alt="Foto Selfie">

                    <!-- Placeholder awal -->
                    <div id="cameraPlaceholder" style="text-align:center;color:rgba(255,255,255,0.3);">
                        <i class="fa-solid fa-camera" style="font-size:48px;margin-bottom:12px;display:block;"></i>
                        <div style="font-size:14px;">Klik "Buka Kamera" untuk mengaktifkan</div>
                    </div>
                </div>

                <!-- Kontrol Kamera -->
                <div style="display:flex;gap:12px;margin-bottom:20px;flex-wrap:wrap;">
                    <button onclick="startCamera()" id="btnStartCamera" class="btn btn-outline" style="flex:1;border-radius:12px;padding:10px;">
                        <i class="fa-solid fa-camera-rotate"></i> Buka Kamera
                    </button>
                    <button onclick="capturePhoto()" id="btnCapture" class="btn btn-primary" style="flex:1;border-radius:12px;padding:10px;display:none;">
                        <i class="fa-solid fa-circle"></i> Ambil Foto
                    </button>
                    <button onclick="retakePhoto()" id="btnRetake" class="btn btn-outline" style="flex:1;border-radius:12px;padding:10px;display:none;">
                        <i class="fa-solid fa-arrow-rotate-left"></i> Ulangi
                    </button>
                </div>

                <!-- Status GPS -->
                <div id="gpsStatus" style="background:rgba(99,102,241,0.08);border:1px solid rgba(99,102,241,0.2);border-radius:12px;padding:14px;margin-bottom:20px;font-size:13px;color:rgba(255,255,255,0.6);">
                    <i class="fa-solid fa-location-dot" style="color:#6366f1;margin-right:8px;"></i>
                    <span id="gpsStatusText">GPS belum terdeteksi. Izinkan akses lokasi di browser.</span>
                </div>

                <!-- Tombol Submit -->
                <button onclick="submitAbsen()" id="btnSubmitAbsen" class="btn btn-primary w-100 py-3" style="font-size:16px;font-weight:800;border-radius:14px;box-shadow:0 10px 25px rgba(99,102,241,0.4);opacity:0.4;cursor:not-allowed;" disabled>
                    @if(!$absensi)
                        <i class="fa-solid fa-right-to-bracket" style="margin-right:10px;"></i>KONFIRMASI ABSEN MASUK
                    @else
                        <i class="fa-solid fa-right-from-bracket" style="margin-right:10px;"></i>KONFIRMASI ABSEN PULANG
                    @endif
                </button>
                <div style="text-align:center;margin-top:10px;font-size:11px;color:rgba(255,255,255,0.35);">
                    Foto selfie + lokasi GPS wajib untuk konfirmasi kehadiran
                </div>

                @else
                <!-- SHIFT SELESAI -->
                <div style="text-align:center;padding:40px 20px;">
                    <div style="font-size:64px;margin-bottom:16px;">🏠</div>
                    <div style="font-size:20px;font-weight:800;color:#4ade80;margin-bottom:8px;">Terima kasih atas kerja kerasnya!</div>
                    <div style="color:rgba(255,255,255,0.5);font-size:14px;">Masuk: {{ \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') }} &nbsp;•&nbsp; Pulang: {{ \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') }}</div>
                    @php
                        $menit = \Carbon\Carbon::parse($absensi->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($absensi->jam_pulang));
                        $jam = floor($menit / 60); $sisa = $menit % 60;
                    @endphp
                    <div style="font-size:13px;color:#a5b4fc;margin-top:8px;">⏱ Total kerja: {{ $jam }}j {{ $sisa }}m</div>
                </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Kanan: Info Absensi Hari Ini + Gaji -->
    <div class="col-md-5">
        <!-- Rekap Hari Ini -->
        <div class="card" style="border-radius:20px;border:1px solid rgba(255,255,255,0.07);margin-bottom:16px;padding:20px 24px;">
            <h4 style="font-size:14px;font-weight:700;color:var(--text-muted);margin-bottom:16px;text-transform:uppercase;letter-spacing:1px;">
                <i class="fa-solid fa-calendar-day" style="color:#6366f1;margin-right:8px;"></i>Rekap Hari Ini
            </h4>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                <div style="background:rgba(255,255,255,0.04);border-radius:12px;padding:14px;text-align:center;">
                    <div style="font-size:22px;font-weight:800;color:#4ade80;">{{ $absensi ? \Carbon\Carbon::parse($absensi->jam_masuk)->format('H:i') : '--:--' }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:4px;">Jam Masuk</div>
                </div>
                <div style="background:rgba(255,255,255,0.04);border-radius:12px;padding:14px;text-align:center;">
                    <div style="font-size:22px;font-weight:800;color:#f87171;">{{ ($absensi && $absensi->jam_pulang) ? \Carbon\Carbon::parse($absensi->jam_pulang)->format('H:i') : '--:--' }}</div>
                    <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-top:4px;">Jam Pulang</div>
                </div>
            </div>

            @if($absensi && ($absensi->lat_masuk || $absensi->lon_masuk))
            <div style="margin-top:14px;padding:10px 14px;background:rgba(34,197,94,0.08);border:1px solid rgba(34,197,94,0.2);border-radius:10px;font-size:12px;color:rgba(255,255,255,0.6);">
                <i class="fa-solid fa-location-dot" style="color:#4ade80;margin-right:6px;"></i>
                Lokasi masuk: {{ number_format($absensi->lat_masuk, 5) }}, {{ number_format($absensi->lon_masuk, 5) }}
                <a href="https://maps.google.com/?q={{ $absensi->lat_masuk }},{{ $absensi->lon_masuk }}" target="_blank" style="color:#6366f1;margin-left:8px;font-size:11px;">
                    <i class="fa-solid fa-arrow-up-right-from-square"></i> Buka Peta
                </a>
            </div>
            @endif

            @if($absensi && $absensi->foto_masuk)
            <div style="margin-top:14px;">
                <div style="font-size:11px;color:rgba(255,255,255,0.4);margin-bottom:6px;">Foto Absen Masuk:</div>
                <img src="{{ asset('storage/'.$absensi->foto_masuk) }}" style="width:100%;border-radius:10px;object-fit:cover;max-height:120px;" alt="Foto Absen Masuk">
            </div>
            @endif
        </div>

        <!-- Ringkasan Gaji -->
        <div class="card" style="border-radius:20px;border:1px dashed rgba(99,102,241,0.35);padding:20px 24px;background:rgba(30,27,75,0.6);">
            <h4 style="font-size:14px;font-weight:700;color:var(--text-muted);margin-bottom:16px;text-transform:uppercase;letter-spacing:1px;">
                <i class="fa-solid fa-money-check-dollar" style="color:#a78bfa;margin-right:8px;"></i>Gaji Bulan Ini
            </h4>
            <div style="font-size:28px;font-weight:900;color:#4ade80;margin-bottom:4px;">
                Rp {{ $currentPayroll ? number_format($currentPayroll->total_gaji, 0, ',', '.') : '0' }}
            </div>
            <div style="font-size:12px;color:rgba(255,255,255,0.4);margin-bottom:14px;">Gaji pokok: Rp {{ number_format($karyawan->gaji_pokok ?? 0, 0, ',', '.') }}</div>
            <a href="{{ route('karyawan.payroll') }}" class="btn btn-outline" style="width:100%;border-radius:10px;font-size:13px;padding:10px;">
                <i class="fa-solid fa-file-lines" style="margin-right:6px;"></i>Lihat Slip Gaji
            </a>
        </div>
    </div>
</div>

<!-- ======================================================
     RIWAYAT ABSENSI
     ====================================================== -->
<div class="row">
    <div class="col-12">
        <div class="card" style="border-radius:20px;border:1px solid rgba(255,255,255,0.07);">
            <div class="card-header" style="padding:18px 24px;border-bottom:1px solid rgba(255,255,255,0.06);">
                <h3 style="font-size:15px;font-weight:700;"><i class="fa-solid fa-calendar-days" style="color:#6366f1;margin-right:8px;"></i>Riwayat Absensi Terakhir</h3>
            </div>
            <div style="overflow-x:auto;">
                <table style="width:100%;border-collapse:collapse;">
                    <thead>
                        <tr style="background:rgba(255,255,255,0.03);">
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Tanggal</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Masuk</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Pulang</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Durasi</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Foto</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">GPS</th>
                            <th style="padding:12px 20px;text-align:left;font-size:12px;color:rgba(255,255,255,0.4);font-weight:600;text-transform:uppercase;letter-spacing:1px;">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($latestAbsensi as $item)
                        @php
                            $durMenit = ($item->jam_masuk && $item->jam_pulang)
                                ? \Carbon\Carbon::parse($item->jam_masuk)->diffInMinutes(\Carbon\Carbon::parse($item->jam_pulang))
                                : null;
                        @endphp
                        <tr style="border-top:1px solid rgba(255,255,255,0.05);">
                            <td style="padding:14px 20px;font-weight:600;color:var(--text-main);">{{ \Carbon\Carbon::parse($item->tanggal)->translatedFormat('d M Y') }}</td>
                            <td style="padding:14px 20px;color:#4ade80;font-variant-numeric:tabular-nums;font-weight:600;">{{ $item->jam_masuk ? \Carbon\Carbon::parse($item->jam_masuk)->format('H:i') : '-' }}</td>
                            <td style="padding:14px 20px;color:#f87171;font-variant-numeric:tabular-nums;font-weight:600;">{{ $item->jam_pulang ? \Carbon\Carbon::parse($item->jam_pulang)->format('H:i') : '-' }}</td>
                            <td style="padding:14px 20px;color:rgba(255,255,255,0.5);font-size:13px;">
                                @if($durMenit) {{ floor($durMenit/60) }}j {{ $durMenit % 60 }}m @else — @endif
                            </td>
                            <td style="padding:14px 20px;">
                                @if($item->foto_masuk)
                                    <img src="{{ asset('storage/'.$item->foto_masuk) }}" style="width:36px;height:36px;border-radius:8px;object-fit:cover;cursor:pointer;border:2px solid rgba(99,102,241,0.4);" onclick="showPhotoModal('{{ asset('storage/'.$item->foto_masuk) }}')" title="Klik untuk perbesar">
                                @else
                                    <span style="color:rgba(255,255,255,0.2);font-size:12px;">—</span>
                                @endif
                            </td>
                            <td style="padding:14px 20px;">
                                @if($item->lat_masuk)
                                    <a href="https://maps.google.com/?q={{ $item->lat_masuk }},{{ $item->lon_masuk }}" target="_blank" style="color:#6366f1;font-size:18px;" title="{{ $item->lat_masuk }}, {{ $item->lon_masuk }}">
                                        <i class="fa-solid fa-location-dot"></i>
                                    </a>
                                @else
                                    <span style="color:rgba(255,255,255,0.2);">—</span>
                                @endif
                            </td>
                            <td style="padding:14px 20px;">
                                <span style="background:{{ $item->status == 'hadir' ? 'rgba(34,197,94,0.15)' : 'rgba(251,191,36,0.15)' }};color:{{ $item->status == 'hadir' ? '#4ade80' : '#fbbf24' }};padding:4px 10px;border-radius:6px;font-size:12px;font-weight:600;">
                                    {{ ucfirst($item->status ?? 'hadir') }}
                                </span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" style="padding:40px;text-align:center;color:rgba(255,255,255,0.3);">
                                <i class="fa-solid fa-calendar-xmark" style="font-size:32px;display:block;margin-bottom:12px;"></i>
                                Belum ada riwayat absensi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- ======================================================
     MODAL PREVIEW FOTO
     ====================================================== -->
<div id="photoModal" style="display:none;position:fixed;inset:0;background:rgba(0,0,0,0.85);z-index:9999;align-items:center;justify-content:center;padding:20px;" onclick="this.style.display='none'">
    <div style="max-width:480px;width:100%;border-radius:20px;overflow:hidden;position:relative;" onclick="event.stopPropagation()">
        <img id="photoModalImg" style="width:100%;display:block;" alt="Foto Selfie">
        <button onclick="document.getElementById('photoModal').style.display='none'" style="position:absolute;top:12px;right:12px;background:rgba(0,0,0,0.6);border:none;color:white;width:36px;height:36px;border-radius:50%;font-size:18px;cursor:pointer;">×</button>
    </div>
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
                        @if($absensi && !$absensi->jam_pulang)
                            <option value="lembur">Lembur (Masih di Lokasi Kerja)</option>
                        @endif
                    </select>
                    @if($absensi && !$absensi->jam_pulang)
                        <small style="color:#fbbf24;display:block;margin-top:6px;"><i class="fa-solid fa-circle-exclamation"></i> Pilih "Lembur" hanya jika Anda diminta OT sebelum jam pulang.</small>
                    @endif
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
                        <input type="text" name="no_hp" value="{{ $karyawan->no_hp }}" class="form-control" placeholder="Contoh: 08123456789" style="background:rgba(0,0,0,0.2);border:1px solid rgba(255,255,255,0.1);color:var(--text-main);border-radius:10px;padding-left:40px;">
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
    const days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
    const months = ['Jan','Feb','Mar','Apr','Mei','Jun','Jul','Agu','Sep','Okt','Nov','Des'];
    const pad = n => String(n).padStart(2,'0');
    
    const timeStr = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())}`;
    const dateStr = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
    
    const clk = document.getElementById('realtimeClock');
    const dt = document.getElementById('realtimeDate');
    const ot = document.getElementById('overlayTime');
    if (clk) clk.textContent = timeStr;
    if (dt) dt.textContent = dateStr;
    if (ot) ot.textContent = `${timeStr} — ${dateStr}`;
}
setInterval(updateClock, 1000);
updateClock();

// ========= GPS DETECTION =========
let currentLat = null, currentLon = null, currentAddress = '';

function detectGPS() {
    if (!navigator.geolocation) {
        document.getElementById('gpsStatusText').textContent = 'Browser tidak mendukung GPS.';
        return;
    }
    document.getElementById('gpsStatusText').textContent = '📡 Mendeteksi lokasi GPS...';
    navigator.geolocation.getCurrentPosition(async (pos) => {
        currentLat = pos.coords.latitude;
        currentLon = pos.coords.longitude;
        const acc = Math.round(pos.coords.accuracy);
        
        // Reverse geocode (optional, tanpa API key)
        let locText = `${currentLat.toFixed(5)}, ${currentLon.toFixed(5)} (akurasi ±${acc}m)`;
        try {
            const r = await fetch(`https://nominatim.openstreetmap.org/reverse?lat=${currentLat}&lon=${currentLon}&format=json`);
            if (r.ok) {
                const d = await r.json();
                const addr = d.display_name?.split(',').slice(0,3).join(',') ?? locText;
                locText = addr + ` (±${acc}m)`;
                currentAddress = addr;
            }
        } catch(e) {}

        // Set value hidden input izin lembur
        const izinLat = document.getElementById('izinLat');
        const izinLon = document.getElementById('izinLon');
        if(izinLat) izinLat.value = currentLat;
        if(izinLon) izinLon.value = currentLon;

        document.getElementById('gpsStatusText').innerHTML = `✅ <strong style="color:#4ade80;">Lokasi ditemukan:</strong> ${locText}`;

        const ol = document.getElementById('overlayLoc');
        if (ol) ol.textContent = '📍 ' + locText;
        document.getElementById('gpsStatus').style.borderColor = 'rgba(34,197,94,0.4)';
        checkReadyToSubmit();
    }, (err) => {
        document.getElementById('gpsStatusText').textContent = `❌ Gagal mendapat lokasi: ${err.message}. Pastikan izin lokasi diaktifkan.`;
        document.getElementById('gpsStatus').style.borderColor = 'rgba(239,68,68,0.4)';
    }, { enableHighAccuracy: true, timeout: 15000 });
}

// ========= CAMERA =========
let stream = null, photoTaken = false;

async function startCamera() {
    try {
        stream = await navigator.mediaDevices.getUserMedia({ video: { facingMode: 'user', width:640, height:480 }, audio: false });
        const video = document.getElementById('cameraFeed');
        video.srcObject = stream;
        video.style.display = 'block';
        document.getElementById('cameraPlaceholder').style.display = 'none';
        document.getElementById('cameraTimestampOverlay').style.display = 'block';
        document.getElementById('btnStartCamera').style.display = 'none';
        document.getElementById('btnCapture').style.display = 'flex';
        detectGPS();
    } catch (err) {
        alert('Gagal membuka kamera: ' + err.message + '\nPastikan izin kamera diaktifkan di browser.');
    }
}

function capturePhoto() {
    const video = document.getElementById('cameraFeed');
    const canvas = document.getElementById('captureCanvas');
    const ctx = canvas.getContext('2d');
    canvas.width = video.videoWidth || 640;
    canvas.height = video.videoHeight || 480;
    
    // Mirror (selfie)
    ctx.translate(canvas.width, 0);
    ctx.scale(-1, 1);
    ctx.drawImage(video, 0, 0);
    ctx.setTransform(1, 0, 0, 1, 0, 0);

    // Overlay timestamp pada foto
    const now = new Date();
    const pad = n => String(n).padStart(2,'0');
    const ts = `${pad(now.getHours())}:${pad(now.getMinutes())}:${pad(now.getSeconds())} - ${now.getDate()}/${now.getMonth()+1}/${now.getFullYear()}`;
    const label = `{{ $karyawan->nama_lengkap }} | {{ $karyawan->nip }}`;
    
    ctx.fillStyle = 'rgba(0,0,0,0.55)';
    ctx.fillRect(0, canvas.height - 60, canvas.width, 60);
    ctx.fillStyle = 'white';
    ctx.font = 'bold 14px Arial';
    ctx.fillText(label, 12, canvas.height - 38);
    ctx.font = '12px Arial';
    ctx.fillStyle = 'rgba(255,255,255,0.8)';
    ctx.fillText('📍 ' + (currentAddress || `${currentLat?.toFixed(5) ?? '?'}, ${currentLon?.toFixed(5) ?? '?'}`), 12, canvas.height - 22);
    ctx.fillText('🕐 ' + ts, 12, canvas.height - 7);

    // Stop stream
    if (stream) stream.getTracks().forEach(t => t.stop());
    
    // Tampilkan preview
    const dataUrl = canvas.toDataURL('image/jpeg', 0.85);
    const preview = document.getElementById('photoPreview');
    preview.src = dataUrl;
    preview.style.display = 'block';
    document.getElementById('cameraFeed').style.display = 'none';
    document.getElementById('cameraTimestampOverlay').style.display = 'none';
    document.getElementById('btnCapture').style.display = 'none';
    document.getElementById('btnRetake').style.display = 'flex';
    photoTaken = true;
    checkReadyToSubmit();
}

function retakePhoto() {
    document.getElementById('photoPreview').style.display = 'none';
    document.getElementById('btnRetake').style.display = 'none';
    document.getElementById('btnStartCamera').style.display = 'flex';
    document.getElementById('btnCapture').style.display = 'none';
    document.getElementById('cameraTimestampOverlay').style.display = 'none';
    photoTaken = false;
    checkReadyToSubmit();
    detectGPS();
}

function checkReadyToSubmit() {
    const ready = photoTaken && currentLat !== null;
    const btn = document.getElementById('btnSubmitAbsen');
    if (!btn) return;
    btn.disabled = !ready;
    btn.style.opacity = ready ? '1' : '0.4';
    btn.style.cursor = ready ? 'pointer' : 'not-allowed';
}

// ========= SUBMIT ABSEN =========
async function submitAbsen() {
    const btn = document.getElementById('btnSubmitAbsen');
    if (btn.disabled) return;
    
    const canvas = document.getElementById('captureCanvas');
    const fotoBase64 = canvas.toDataURL('image/jpeg', 0.85);

    const oldHtml = btn.innerHTML;
    btn.disabled = true;
    btn.innerHTML = '<i class="fa-solid fa-spinner fa-spin"></i> Menyimpan absensi...';

    try {
        const response = await fetch('/api/hr/absen', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({
                lat: currentLat,
                lon: currentLon,
                foto: fotoBase64
            })
        });

        const result = await response.json();
        if (response.ok) {
            btn.innerHTML = '<i class="fa-solid fa-circle-check"></i> ' + (result.message || 'Berhasil!');
            btn.style.background = 'linear-gradient(135deg,#22c55e,#16a34a)';
            setTimeout(() => location.reload(), 1500);
        } else {
            alert(result.message || 'Gagal melakukan absensi.');
            btn.disabled = false;
            btn.innerHTML = oldHtml;
        }
    } catch(e) {
        alert('Gagal terhubung ke server. Periksa koneksi internet Anda.');
        btn.disabled = false;
        btn.innerHTML = oldHtml;
    }
}

// ========= PHOTO MODAL =========
function showPhotoModal(src) {
    document.getElementById('photoModalImg').src = src;
    document.getElementById('photoModal').style.display = 'flex';
}
</script>
@endsection
