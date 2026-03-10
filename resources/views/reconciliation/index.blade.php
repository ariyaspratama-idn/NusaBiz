@extends('layouts.app')

@section('content')
<div class="row mb-5">
    <div class="col-md-8">
        <h2 class="fw-bold mb-1">Rekonsiliasi Bank 🏦</h2>
        <p class="text-secondary">Cocokkan mutasi bank dengan catatan internal Anda secara otomatis.</p>
    </div>
    <div class="col-md-4 text-md-end">
        <form action="{{ route('reconciliation.upload') }}" method="POST" enctype="multipart/form-data" class="d-flex gap-2 justify-content-end">
            @csrf
            <input type="file" name="statement" class="form-control form-control-sm" style="width: 200px;" accept=".csv">
            <button type="submit" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
                <i data-lucide="upload-cloud"></i> Upload CSV
            </button>
        </form>
    </div>
</div>

<div class="row">
    <div class="col-md-12 mb-4">
        <div class="card p-4 border-0 shadow-sm" style="border-radius: 20px;">
            <div class="d-flex align-items-center justify-content-between mb-4">
                <h5 class="fw-bold m-0 text-dark">Mutasi Belum Cocok</h5>
                <form action="{{ route('reconciliation.auto_match') }}" method="POST">
                    @csrf
                    <button type="submit" class="btn btn-success d-inline-flex align-items-center gap-2 px-4 shadow-sm" style="border-radius: 12px;">
                        <i data-lucide="zap"></i> Auto-Match Cerdas
                    </button>
                </form>
            </div>
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead class="bg-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Keterangan</th>
                            <th>Nominal</th>
                            <th>Referensi</th>
                            <th class="text-end">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($unreconciledStatements as $stmt)
                        <tr>
                            <td>{{ $stmt->date }}</td>
                            <td>{{ $stmt->description }}</td>
                            <td class="fw-bold">Rp {{ number_format($stmt->amount, 0, ',', '.') }}</td>
                            <td><span class="badge bg-light text-dark">{{ $stmt->reference ?? '-' }}</span></td>
                            <td class="text-end">
                                <button class="btn btn-outline-primary btn-sm px-3" style="border-radius: 8px;">Manual Match</button>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-5 text-muted">
                                <i data-lucide="check-circle" class="mb-2 d-block mx-auto" style="width: 48px; opacity: 0.1;"></i>
                                Semua mutasi sudah terekonsiliasi.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
