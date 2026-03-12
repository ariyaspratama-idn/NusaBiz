@extends('layouts.admin')

@section('title', 'Manajemen Penggajian')
@section('page_title', 'Payroll (Gaji Karyawan)')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Daftar Penggajian Periode {{ now()->format('F Y') }}</h3>
        <div class="card-tools">
            <button class="btn btn-primary" onclick="showGenerateModal()">
                <i class="fa-solid fa-calculator"></i> Hitung Gaji Baru
            </button>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Periode</th>
                        <th>Karyawan</th>
                        <th>Gaji Pokok</th>
                        <th>Lembur</th>
                        <th>Total diterima</th>
                        <th>Status</th>
                        <th>Dibayar Pada</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($payrolls as $p)
                    <tr>
                        <td class="font-weight-bold">{{ $p->periode_bulan }}</td>
                        <td>
                            <div class="d-flex align-items-center">
                                <div class="avatar-sm mr-2" style="width:32px; height:32px; background:var(--primary); border-radius:8px; display:flex; align-items:center; justify-content:center; color:white; font-size:12px;">
                                    {{ substr($p->karyawan->nama_lengkap, 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-weight-600">{{ $p->karyawan->nama_lengkap }}</div>
                                    <small class="text-muted">{{ $p->karyawan->jabatan }}</small>
                                </div>
                            </div>
                        </td>
                        <td>Rp {{ number_format($p->gaji_pokok, 0, ',', '.') }}</td>
                        <td>Rp {{ number_format($p->lembur, 0, ',', '.') }}</td>
                        <td class="text-primary font-weight-bold">Rp {{ number_format($p->total_gaji, 0, ',', '.') }}</td>
                        <td>
                            @if($p->status_pembayaran === 'paid')
                                <span class="badge badge-success">DIBAYAR</span>
                            @else
                                <span class="badge badge-warning">PENDING</span>
                            @endif
                        </td>
                        <td>{{ $p->tanggal_dibayar ? $p->tanggal_dibayar->format('d/m/Y') : '-' }}</td>
                        <td>
                            <div class="btn-group">
                                @if($p->status_pembayaran !== 'paid')
                                <form action="{{ route('hr.payroll.update', $p->id) }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="status" value="paid">
                                    <button type="submit" class="btn btn-sm btn-success" title="Tandai Sudah Dibayar">
                                        <i class="fa-solid fa-check"></i>
                                    </button>
                                </form>
                                @endif
                                <a href="#" class="btn btn-sm btn-outline ml-1" title="Cetak Slip Gaji">
                                    <i class="fa-solid fa-file-invoice-dollar"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="8" class="text-center py-5">
                            <img src="{{ asset('img/no-data.png') }}" style="width:120px; opacity:0.3; margin-bottom:15px;">
                            <p class="text-muted">Gaji belum dihitung untuk periode ini.</p>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="px-4 py-3 border-top">
            {{ $payrolls->links() }}
        </div>
    </div>
</div>

<!-- Modal Hitung Gaji -->
<div id="generateModal" style="display:none; position:fixed; inset:0; background:rgba(0,0,0,0.7); z-index:9999; backdrop-filter:blur(5px); justify-content:center; align-items:center;">
    <div class="card" style="width:100%; max-width:400px; margin:20px;">
        <div class="card-header">
            <h4 class="mb-0">Hitung Gaji Baru</h4>
        </div>
        <form action="" id="generateForm" method="POST">
            @csrf
            <div class="card-body">
                <div class="form-group mb-4">
                    <label class="d-block mb-2 font-weight-600">Pilih Periode Bulan</label>
                    <input type="month" id="periode_input" name="bulan" value="{{ date('Y-m') }}" class="form-control" style="background:var(--bg-main); border:1px solid var(--border); color:white; padding:12px; border-radius:10px; width:100%;">
                </div>
                <p class="small text-muted mb-0">Sistem akan otomatis menghitung gaji pokok dan lembur berdasarkan absensi karyawan pada periode tersebut.</p>
            </div>
            <div class="card-footer d-flex gap-2">
                <button type="button" class="btn btn-outline" style="flex:1" onclick="hideGenerateModal()">Batal</button>
                <button type="submit" class="btn btn-primary" style="flex:1">Mulai Hitung</button>
            </div>
        </form>
    </div>
</div>

<script>
function showGenerateModal() {
    document.getElementById('generateModal').style.display = 'flex';
}
function hideGenerateModal() {
    document.getElementById('generateModal').style.display = 'none';
}
document.getElementById('generateForm').onsubmit = function(e) {
    e.preventDefault();
    const bulan = document.getElementById('periode_input').value;
    window.location.href = "{{ url('admin-dashboard/hr/hitung-gaji') }}/" + bulan;
};
</script>
@endsection
