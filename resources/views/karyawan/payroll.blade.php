@extends('layouts.admin')
@section('title', 'Laporan Gaji')
@section('page_title', 'Riwayat Penggajian')

@section('content')
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
@endsection
