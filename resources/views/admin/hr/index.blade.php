@extends('layouts.admin')

@section('title', 'Manajemen Karyawan')
@section('page_title', 'Sumber Daya Manusia (HR)')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-main">Daftar Karyawan</h3>
        <button class="btn btn-primary" onclick="document.getElementById('modalKaryawan').style.display='block'">
            <i class="fa-solid fa-plus"></i> Tambah Karyawan
        </button>
    </div>
    <div class="card-body" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>NIP</th>
                    <th>Nama Lengkap</th>
                    <th>Departemen</th>
                    <th>Jabatan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse($karyawans as $k)
                <tr>
                    <td><code>{{ $k->nip }}</code></td>
                    <td>{{ $k->nama_lengkap }}</td>
                    <td>{{ $k->departemen ?? '-' }}</td>
                    <td>{{ $k->jabatan ?? '-' }}</td>
                    <td>
                        <span class="badge {{ $k->status == 'aktif' ? 'badge-success' : 'badge-danger' }}">
                            {{ strtoupper($k->status) }}
                        </span>
                    </td>
                    <td>
                        <button class="btn btn-outline" style="padding:5px 10px;"><i class="fa-solid fa-eye"></i></button>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align:center; padding:40px;">Belum ada data karyawan.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Sederhana (Contoh) -->
<div id="modalKaryawan" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:2000; align-items:center; justify-content:center;">
    <div class="card" style="width:500px; margin:auto; margin-top:100px;">
        <div class="card-header">Tambah Karyawan Baru</div>
        <form action="{{ route('hr.karyawan.store') }}" method="POST" style="padding:20px;">
            @csrf
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">NIP</label>
                <input type="text" name="nip" class="form-control" style="width:100%; padding:10px; background:var(--bg-main); border:1px solid var(--border); color:white; border-radius:8px;" required>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Nama Lengkap</label>
                <input type="text" name="nama_lengkap" class="form-control" style="width:100%; padding:10px; background:var(--bg-main); border:1px solid var(--border); color:white; border-radius:8px;" required>
            </div>
            <div style="margin-bottom:15px;">
                <label style="display:block; margin-bottom:5px;">Gaji Pokok</label>
                <input type="number" name="gaji_pokok" class="form-control" style="width:100%; padding:10px; background:var(--bg-main); border:1px solid var(--border); color:white; border-radius:8px;" required>
            </div>
            <div style="display:flex; justify-content:flex-end; gap:10px;">
                <button type="button" class="btn btn-outline" onclick="this.closest('#modalKaryawan').style.display='none'">Batal</button>
                <button type="submit" class="btn btn-primary">Simpan</button>
            </div>
        </form>
    </div>
</div>
@endsection
