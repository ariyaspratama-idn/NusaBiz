@extends('layouts.app')

@section('content')
<div class="mb-5 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-1">Stock Order Monitor</h2>
        <p class="text-secondary">Tracking all incoming stock requests from branches.</p>
    </div>
    <form action="{{ route('reports.stock_monitor') }}" method="GET" class="d-flex gap-2">
        <select name="branch_id" class="form-select border-0 shadow-sm" onchange="this.form.submit()" style="border-radius: 10px; width: 200px;">
            <option value="">All Branches</option>
            @foreach($branches as $b)
                <option value="{{ $b->id }}" {{ $branchId == $b->id ? 'selected' : '' }}>{{ $b->name }}</option>
            @endforeach
        </select>
    </form>
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="bg-light">
                    <tr class="text-secondary small text-uppercase">
                        <th class="px-4 py-3">Date</th>
                        <th class="py-3">Branch</th>
                        <th class="py-3">Item Name</th>
                        <th class="py-3 text-center">Qty</th>
                        <th class="py-3">Reason</th>
                        <th class="py-3">Requested By</th>
                        <th class="py-3 text-end px-4">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($stocks as $stock)
                    <tr>
                        <td class="px-4 small fw-medium">{{ $stock->created_at->format('d M Y, H:i') }}</td>
                        <td class="fw-bold text-primary">{{ $stock->branch->name }}</td>
                        <td class="fw-bold">{{ $stock->item_name }}</td>
                        <td class="text-center"><span class="badge bg-light text-dark px-3 py-2" style="border-radius: 8px;">{{ $stock->quantity }}</span></td>
                        <td class="small text-secondary">{{ $stock->reason }}</td>
                        <td class="small">{{ $stock->user->name ?? 'Staff' }}</td>
                        <td class="text-end px-4">
                            <span class="badge rounded-pill bg-info-subtle text-info px-3">PENDING</span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="7" class="text-center py-5 text-muted">No stock requests found.</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<div class="mt-4">
    {{ $stocks->links() }}
</div>
@endsection
