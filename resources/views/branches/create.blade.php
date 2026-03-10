@extends('layouts.app')

@section('content')
<div class="mb-5">
    <a href="/branches" class="btn btn-light btn-sm px-3 mb-3 d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
        <i data-lucide="arrow-left" style="width: 16px;"></i> Back to List
    </a>
    <h2 class="fw-bold mb-1">Add New Branch</h2>
    <p class="text-secondary">Expand your business presence by adding a new outlet.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm border-0">
            <form action="{{ route('branches.store') }}" method="POST">
                @csrf
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Branch Code</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                               placeholder="e.g. BR-001" style="border-radius: 10px; padding: 12px;" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Branch Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" style="border-radius: 10px; padding: 12px;" required>
                            <option value="CABANG">Branch / Cabang</option>
                            <option value="PUSAT">Head Office / Pusat</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Branch Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               placeholder="e.g. Tangerang City Branch" style="border-radius: 10px; padding: 12px;" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" placeholder="Enter full address..." style="border-radius: 10px; padding: 12px;"></textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               placeholder="e.g. 021-1234567" style="border-radius: 10px; padding: 12px;">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold w-100 shadow-sm" style="border-radius: 12px;">
                        Add Branch
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
