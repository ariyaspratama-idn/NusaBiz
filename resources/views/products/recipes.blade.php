@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="mb-4">
        <a href="{{ route('products.index') }}" class="text-muted text-decoration-none small">← Kembali ke Master Produk</a>
        <h1 class="h2 text-white mt-2">🧪 Resep / BoM: {{ $product->name }}</h1>
        <p class="text-muted small">Definisikan bahan apa saja yang digunakan saat produk ini dijual sehingga stok bahan otomatis berkurang.</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <div class="row">
        <div class="col-lg-4">
            <div class="card bg-dark text-white border-secondary" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="mb-4">➕ Tambah Bahan</h5>
                    <form action="{{ route('products.addRecipe', $product->id) }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label class="small text-muted mb-2">Pilih Bahan Baku</label>
                            <select name="material_id" class="form-control bg-black text-white border-secondary" required>
                                @foreach($allProducts as $p)
                                    <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->code }})</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="small text-muted mb-2">Jumlah per Satuan Produk</label>
                            <input type="number" step="0.01" name="quantity" class="form-control bg-black text-white border-secondary" placeholder="Contoh: 0.5 atau 1" required>
                        </div>
                        <button type="submit" class="btn btn-primary w-100 mt-2">Simpan ke Resep</button>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-8">
            <div class="card bg-dark text-white border-secondary" style="border-radius: 20px;">
                <div class="card-body p-4">
                    <h5 class="mb-4">📋 Daftar Bahan (Resep)</h5>
                    <div class="table-responsive">
                        <table class="table table-dark table-hover mb-0">
                            <thead>
                                <tr>
                                    <th>BAHAN BAKU</th>
                                    <th>JUMLAH GUNA</th>
                                    <th width="100">AKSI</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($product->recipes as $r)
                                <tr>
                                    <td>
                                        <div class="fw-bold">{{ $r->material->name }}</div>
                                        <small class="text-muted">{{ $r->material->code }}</small>
                                    </td>
                                    <td>{{ $r->quantity }}</td>
                                    <td>
                                        <form action="{{ route('products.removeRecipe', [$product->id, $r->id]) }}" method="POST">
                                            @csrf @method('DELETE')
                                            <button class="btn btn-link text-danger p-0">Hapus</button>
                                        </form>
                                    </td>
                                </tr>
                                @endforeach
                                @if($product->recipes->count() == 0)
                                <tr>
                                    <td colspan="3" class="text-center py-5 text-muted small italic">
                                        Belum ada resep. Saat produk ini dibeli di POS, hanya stok produk ini sendiri yang akan berkurang.
                                    </td>
                                </tr>
                                @endif
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
