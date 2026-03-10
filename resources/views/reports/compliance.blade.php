@extends('layouts.app')

@section('content')
<div class="mb-5 d-flex justify-content-between align-items-center">
    <div>
        <h2 class="fw-bold mb-1">{{ __('ui.sop_compliance_monitor') }}</h2>
        <p class="text-secondary">Real-time tracking of branch operational standards.</p>
    </div>
    <form action="{{ route('reports.compliance') }}" method="GET" class="d-flex gap-2">
        <input type="date" name="date" class="form-control border-0 shadow-sm" value="{{ $date }}" onchange="this.form.submit()" style="border-radius: 10px;">
    </form>
</div>

<div class="row g-4 mb-5">
    @foreach($complianceData as $data)
    <div class="col-md-4">
        <div class="card border-0 shadow-sm p-4 h-100" style="border-radius: 20px;">
            <div class="d-flex justify-content-between align-items-start mb-4">
                <div>
                    <h5 class="fw-bold mb-0 text-dark">{{ $data['branch']->name }}</h5>
                    <span class="badge rounded-pill bg-light text-secondary mt-1">{{ $data['branch']->code }}</span>
                </div>
                <div class="position-relative d-inline-flex">
                    <div class="text-{{ $data['percentage'] == 100 ? 'success' : ($data['percentage'] > 50 ? 'warning' : 'danger') }} fw-bold h4 m-0">
                        {{ $data['percentage'] }}%
                    </div>
                </div>
            </div>

            <div class="progress mb-4" style="height: 10px; border-radius: 5px; background: #f0f0f0;">
                <div class="progress-bar bg-{{ $data['percentage'] == 100 ? 'success' : ($data['percentage'] > 50 ? 'warning' : 'danger') }}" 
                     role="progressbar" style="width: {{ $data['percentage'] }}%"></div>
            </div>

            <div class="d-flex justify-content-between small text-muted mb-4">
                <span>{{ $data['completed'] }} / {{ $data['required'] }} SOPs Done</span>
                <span>{{ $data['required'] - $data['completed'] }} Pending</span>
            </div>

            <hr class="my-3 opacity-50">

            <div class="logs-container" style="max-height: 200px; overflow-y: auto;">
                <h6 class="small fw-bold text-uppercase text-secondary mb-3">Recent Activity Today</h6>
                @forelse($data['logs'] as $log)
                <div class="d-flex gap-3 mb-3 pb-2 border-bottom border-light">
                    <div class="flex-shrink-0">
                        @if($log->photo_path)
                            <img src="{{ asset('storage/' . $log->photo_path) }}" class="rounded shadow-sm" style="width: 40px; height: 40px; object-fit: cover; cursor: pointer;" onclick="window.open(this.src)">
                        @else
                            <div class="bg-light rounded d-flex align-items-center justify-content-center" style="width: 40px; height: 40px;">
                                <i data-lucide="image-off" class="text-muted" style="width: 16px;"></i>
                            </div>
                        @endif
                    </div>
                    <div>
                        <div class="small fw-bold {{ $log->status == 'DONE' ? 'text-success' : 'text-danger' }}">
                            {{ $log->sop->name }}
                        </div>
                        <div class="text-secondary" style="font-size: 0.75rem;">
                            {{ $log->user->name ?? 'Staff' }} • {{ $log->created_at->format('H:i') }}
                        </div>
                    </div>
                </div>
                @empty
                <div class="text-center py-3 text-muted small">No activity recorded yet for this branch.</div>
                @endforelse
            </div>
        </div>
    </div>
    @endforeach
</div>

<div class="card border-0 shadow-sm" style="border-radius: 20px;">
    <div class="card-body p-4">
        <h5 class="fw-bold mb-4">Branch Assignment Summary</h5>
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr class="text-secondary small text-uppercase">
                        <th>Branch</th>
                        <th>Assigned SOPs</th>
                        <th class="text-end">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($complianceData as $data)
                    <tr>
                        <td class="fw-bold">{{ $data['branch']->name }}</td>
                        <td>
                            <div class="d-flex flex-wrap gap-1">
                                @foreach($data['branch']->sops->take(3) as $sop)
                                    <span class="badge bg-light text-dark font-normal" style="font-size: 0.7rem;">{{ $sop->name }}</span>
                                @endforeach
                                @if($data['branch']->sops->count() > 3)
                                    <span class="badge bg-light text-muted font-normal" style="font-size: 0.7rem;">+{{ $data['branch']->sops->count() - 3 }} more</span>
                                @endif
                            </div>
                        </td>
                        <td class="text-end">
                            <a href="#" class="btn btn-light btn-sm px-3" style="border-radius: 8px;">Manage Assignments</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
