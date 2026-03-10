@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="h2 text-white">📦 Master Produk</h1>
        <a href="{{ route('products.create') }}" class="btn btn-primary">➕ Tambah Produk</a>
    </div>

    @if(session('success'))
        <div class="alert alert-success bg-success text-white border-0">{{ session('success') }}</div>
    @endif

    <div class="row">
        @foreach($products as $product)
        <div class="col-md-4 mb-4">
            <div class="card bg-dark text-white border-secondary h-100" style="border-radius: 20px; overflow: hidden;">
                <div class="card-body">
                    <div class="d-flex justify-content-between">
                        <span class="badge bg-secondary mb-2">{{ $product->category->name }}</span>
                        <span class="text-white-50 small">{{ $product->code }}</span>
                    </div>
                    <h3 class="h5 mb-3">{{ $product->name }}</h3>
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <span class="text-muted small">Harga Jual</span>
                        <span class="h5 mb-0 text-info">Rp {{ number_format($product->selling_price, 0, ',', '.') }}</span>
                    </div>
                    <hr class="border-secondary opacity-25">
                    <div class="d-grid gap-2">
                        <a href="{{ route('products.recipes', $product->id) }}" class="btn btn-outline-info btn-sm">
                            🧪 Kelola Bahan / Resep (BoM)
                        </a>
                        <div class="d-flex gap-2">
                            <a href="{{ route('products.edit', $product->id) }}" class="btn btn-outline-light btn-sm flex-grow-1">Edit</a>
                            <form action="{{ route('products.destroy', $product->id) }}" method="POST" class="flex-grow-1">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm w-100" onclick="return confirm('Hapus produk?')">Hapus</button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>
    <div class="mt-4">
        {{ $products->links() }}
    </div>
</div>
@endsection
