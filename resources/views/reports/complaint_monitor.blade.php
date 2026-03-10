@extends('layouts.app')

@section('content')
<div class="mb-5 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-1">Customer Complaint Feed</h2>
        <p class="text-secondary">Integrated view of all customer feedback and issues across branches.</p>
    </div>
    <form action="{{ route('reports.complaints_monitor') }}" method="GET" class="d-flex gap-2">
        <select name="branch_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()" style="border-radius: 10px; width: 200px;">
            <option value="">All Branches</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="row g-4">
    @forelse($complaints as $complaint)
    <div class="col-md-6">
        <div class="card border-0 shadow-sm overflow-hidden h-100" style="border-radius: 20px;">
            <div class="row g-0 h-100">
                <div class="col-md-4 bg-light d-flex align-items-center justify-content-center">
                    @if($complaint->photo_path)
                        <img src="{{ asset('storage/' . $complaint->photo_path) }}" class="img-fluid h-100 w-100" style="object-fit: cover; min-height: 200px; cursor: pointer;" onclick="window.open(this.src)">
                    @else
                        <div class="text-center p-3 text-muted">
                            <i data-lucide="{{ $complaint->source === 'GOOGLE_MAPS' ? 'map-pin' : 'image' }}" style="width: 48px; height: 48px; opacity: 0.2;"></i>
                            <div class="smaller mt-2">{{ $complaint->source }}</div>
                        </div>
                    @endif
                </div>
                <div class="col-md-8">
                    <div class="card-body p-4 d-flex flex-column h-100">
                        <div class="d-flex justify-content-between align-items-start mb-2">
                            <span class="badge bg-danger-subtle text-danger rounded-pill px-3">{{ $complaint->branch->name }}</span>
                            <small class="text-muted">{{ $complaint->created_at->diffForHumans() }}</small>
                        </div>
                        
                        <div class="mb-3">
                            @if($complaint->source === 'GOOGLE_MAPS')
                                <span class="badge bg-primary-subtle text-primary border-0 small">
                                    <i data-lucide="map" class="me-1" style="width: 12px;"></i> Google Maps Review
                                </span>
                            @else
                                <span class="badge bg-secondary-subtle text-secondary border-0 small">
                                    <i data-lucide="user-round" class="me-1" style="width: 12px;"></i> Manual Input
                                </span>
                            @endif
                        </div>

                        <p class="text-dark fw-medium mb-3 flex-grow-1">"{{ $complaint->description }}"</p>
                        
                        @if($complaint->external_url)
                        <div class="mb-3">
                            <a href="{{ $complaint->external_url }}" target="_blank" class="btn btn-sm btn-outline-primary py-1 px-3" style="border-radius: 8px; font-size: 0.75rem;">
                                <i data-lucide="external-link" class="me-1" style="width: 12px;"></i> View Original Review
                            </a>
                        </div>
                        @endif

                        <div class="mt-auto pt-3 border-top d-flex align-items-center justify-content-between">
                            <div class="small">
                                <span class="text-secondary">Reported by:</span><br>
                                <span class="fw-bold">{{ $complaint->user->name ?? ($complaint->source === 'GOOGLE_MAPS' ? 'Google Customer' : 'System') }}</span>
                            </div>
                            <button class="btn btn-light btn-sm px-3" style="border-radius: 8px;">Respond</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @empty
    <div class="col-12 text-center py-5">
        <div class="bg-light d-inline-flex p-4 rounded-circle mb-3">
            <i data-lucide="smile" class="text-muted" style="width: 48px; height: 48px;"></i>
        </div>
        <h5 class="text-muted">No complaints reported yet. Great job!</h5>
    </div>
    @endforelse
</div>

<div class="mt-5">
    {{ $complaints->links() }}
</div>
@endsection
