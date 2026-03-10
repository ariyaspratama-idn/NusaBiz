@extends('layouts.app')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-5">
    <div>
        <h2 class="fw-bold mb-1">Branches</h2>
        <p class="text-secondary m-0">Oversee and manage your branch locations.</p>
    </div>
    <a href="/branches/create" class="btn btn-primary d-inline-flex align-items-center gap-2 px-4 py-2 shadow-sm" style="border-radius: 12px; text-decoration: none;">
        <i data-lucide="plus-circle" style="width: 20px;"></i> Add New Branch
    </a>
</div>

<div class="row">
    @foreach($branches as $branch)
    <div class="col-md-4 mb-4">
        <div class="card p-4">
            <div class="d-flex align-items-start justify-content-between mb-3">
                <div class="stats-icon" style="background: rgba(79, 70, 229, 0.1); color: #4f46e5;">
                    <i data-lucide="map-pin"></i>
                </div>
                <span class="badge rounded-pill {{ $branch->is_active ? 'bg-success-subtle text-success' : 'bg-danger-subtle text-danger' }} px-3 py-2">
                    {{ $branch->is_active ? 'Active' : 'Inactive' }}
                </span>
            </div>
            <h5 class="fw-bold mb-1">{{ $branch->name }}</h5>
            <p class="text-secondary small mb-1">{{ $branch->code }}</p>
            <p class="text-secondary small mb-3">{{ $branch->address ?? 'No address provided' }}</p>
            <div class="mt-auto pt-3 border-top">
                <div class="d-flex align-items-center justify-content-between">
                    <span class="small text-muted">{{ $branch->type }}</span>
                    <a href="/branches/{{ $branch->id }}/edit" class="btn btn-light btn-sm px-3 shadow-sm" style="border-radius: 8px;">Settings</a>
                </div>
            </div>
        </div>
    </div>
    @endforeach
</div>
@endsection
