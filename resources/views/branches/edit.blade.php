@extends('layouts.app')

@section('content')
<div class="mb-5">
    <a href="/branches" class="btn btn-light btn-sm px-3 mb-3 d-inline-flex align-items-center gap-2" style="border-radius: 8px;">
        <i data-lucide="arrow-left" style="width: 16px;"></i> Back to List
    </a>
    <h2 class="fw-bold mb-1">Edit Branch: {{ $branch->name }}</h2>
    <p class="text-secondary">Update the information or status of this outlet.</p>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="card p-4 shadow-sm border-0">
            <form action="{{ route('branches.update', $branch->id) }}" method="POST">
                @csrf
                @method('PUT')
                
                <div class="row g-4">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Branch Code</label>
                        <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" 
                               value="{{ old('code', $branch->code) }}" placeholder="e.g. BR-001" style="border-radius: 10px; padding: 12px;" required>
                        @error('code')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Branch Type</label>
                        <select name="type" class="form-select @error('type') is-invalid @enderror" style="border-radius: 10px; padding: 12px;" required>
                            <option value="CABANG" {{ $branch->type == 'CABANG' ? 'selected' : '' }}>Branch / Cabang</option>
                            <option value="PUSAT" {{ $branch->type == 'PUSAT' ? 'selected' : '' }}>Head Office / Pusat</option>
                        </select>
                        @error('type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Branch Name</label>
                        <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" 
                               value="{{ old('name', $branch->name) }}" placeholder="e.g. Tangerang City Branch" style="border-radius: 10px; padding: 12px;" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-12">
                        <label class="form-label fw-semibold text-dark">Address</label>
                        <textarea name="address" class="form-control @error('address') is-invalid @enderror" 
                                  rows="3" placeholder="Enter full address..." style="border-radius: 10px; padding: 12px;">{{ old('address', $branch->address) }}</textarea>
                        @error('address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-dark">Phone Number</label>
                        <input type="text" name="phone" class="form-control @error('phone') is-invalid @enderror" 
                               value="{{ old('phone', $branch->phone) }}" placeholder="e.g. 021-1234567" style="border-radius: 10px; padding: 12px;">
                        @error('phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 text-center d-flex align-items-center justify-content-center">
                        <div class="form-check form-switch p-0 d-flex align-items-center gap-3">
                            <label class="form-check-label fw-semibold text-dark m-0" for="is_active">Is Branch Active?</label>
                            <input type="hidden" name="is_active" value="0">
                            <input class="form-check-input ms-0" type="checkbox" name="is_active" id="is_active" 
                                   value="1" {{ $branch->is_active ? 'checked' : '' }} style="width: 45px; height: 22px; cursor: pointer;">
                        </div>
                    </div>
                </div>

                <div class="mt-5 pt-4 border-top">
                    <button type="submit" class="btn btn-primary px-5 py-3 fw-bold w-100 shadow-sm" style="border-radius: 12px;">
                        Update Branch Details
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
