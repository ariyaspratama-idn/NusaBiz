@extends('layouts.admin')

@section('title', 'Daftar Izin & Cuti')
@section('page_title', 'Manajemen Izin & Cuti Karyawan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-main">Pengajuan Izin Terbaru</h3>
        <div>
            <span class="badge badge-gray">Total Pengajuan: {{ $izins->total() }}</span>
        </div>
    </div>
    <div class="card-body" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>Karyawan</th>
                    <th>Jenis</th>
                    <th>Tanggal</th>
                    <th>Alasan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($izins as $izin)
                <tr>
                    <td>
                        <div style="font-weight:600;">{{ $izin->karyawan->nama_lengkap }}</div>
                        <div style="font-size:11px; color:var(--text-muted);">{{ $izin->karyawan->nip }}</div>
                    </td>
                    <td>
                        <span class="badge badge-info">{{ strtoupper($izin->jenis_izin) }}</span>
                    </td>
                    <td>
                        <div style="font-size:13px;">{{ $izin->tanggal_mulai }}</div>
                        @if($izin->tanggal_selesai != $izin->tanggal_mulai)
                        <div style="font-size:11px; color:var(--text-muted);">s/d {{ $izin->tanggal_selesai }}</div>
                        @endif
                    </td>
                    <td><span title="{{ $izin->alasan }}">{{ Str::limit($izin->alasan, 30) }}</span></td>
                    <td>
                        @php
                            $statusBadge = match($izin->status) {
                                'disetujui' => 'badge-success',
                                'ditolak'   => 'badge-danger',
                                default     => 'badge-warning',
                            };
                        @endphp
                        <span class="badge {{ $statusBadge }}">{{ strtoupper($izin->status) }}</span>
                    </td>
                    <td>
                        @if($izin->status == 'pending')
                        <div style="display:flex; gap:5px;">
                            <form action="#" method="POST">
                                @csrf
                                <button class="btn btn-primary" style="padding:5px 10px; font-size:11px;"><i class="fa-solid fa-check"></i></button>
                            </form>
                            <form action="#" method="POST">
                                @csrf
                                <button class="btn btn-outline" style="padding:5px 10px; font-size:11px; border-color:var(--danger); color:var(--danger);"><i class="fa-solid fa-xmark"></i></button>
                            </form>
                        </div>
                        @else
                        <span style="font-size:11px; color:var(--text-muted);">Sudah diproses</span>
                        @endif
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px; color:var(--text-muted);">Belum ada pengajuan izin.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
        <div style="padding:20px;">
            {{ $izins->links() }}
        </div>
    </div>
</div>
@endsection
