@extends('layouts.app')

@section('content')
<style>
    .glass-card {
        background: rgba(255, 255, 255, 0.05);
        backdrop-filter: blur(10px);
        border: 1px solid rgba(255, 255, 255, 0.1);
        border-radius: 20px;
        padding: 24px;
        box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        color: white;
    }
    .status-badge {
        padding: 4px 12px;
        border-radius: 99px;
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
    }
    .status-unpaid { background: rgba(244, 63, 94, 0.2); color: #f43f5e; border: 1px solid rgba(244, 63, 94, 0.3); }
    .status-partial { background: rgba(245, 158, 11, 0.2); color: #f59e0b; border: 1px solid rgba(245, 158, 11, 0.3); }
    
    .table-dark-custom {
        width: 100%;
        border-collapse: separate;
        border-spacing: 0 8px;
    }
    .table-dark-custom th {
        color: #94a3b8;
        font-weight: 500;
        font-size: 0.85rem;
        padding: 12px;
        text-align: left;
    }
    .table-dark-custom td {
        background: rgba(15, 23, 42, 0.3);
        padding: 16px 12px;
        font-size: 0.95rem;
    }
    .table-dark-custom tr td:first-child { border-top-left-radius: 12px; border-bottom-left-radius: 12px; }
    .table-dark-custom tr td:last-child { border-top-right-radius: 12px; border-bottom-right-radius: 12px; }

    .btn-pay {
        background: #6366f1;
        color: white;
        border: none;
        padding: 8px 16px;
        border-radius: 10px;
        font-weight: 500;
        cursor: pointer;
        transition: all 0.2s;
    }
    .btn-pay:hover { background: #818cf8; transform: translateY(-2px); }

    /* Modal Styling */
    .modal-content-custom {
        background: #1e293b;
        border: 1px solid rgba(255,255,255,0.1);
        border-radius: 24px;
        color: white;
    }
    input, select {
        background: rgba(15, 23, 42, 0.5);
        border: 1px solid rgba(255,255,255,0.1);
        color: white;
        border-radius: 10px;
        padding: 10px;
        width: 100%;
    }
</style>

<div class="container py-5">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h2 text-white mb-1">📖 Buku Kasbon (Hutang Pelanggan)</h1>
            <p class="text-muted">Pantau terus siapa yang belum bayar kopi hari ini.</p>
        </div>
        <div class="text-end">
            <div class="glass-card py-2 px-4">
                <span class="text-muted d-block small">Total Piutang</span>
                <span class="h4 text-warning">Rp {{ number_format($debts->sum('amount'), 0, ',', '.') }}</span>
            </div>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show bg-success text-white border-0 mb-4" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    @endif

    <div class="glass-card">
        <table class="table-dark-custom">
            <thead>
                <tr>
                    <th>WAKTU</th>
                    <th>PELANGGAN</th>
                    <th>DETAIL</th>
                    <th>NOMINAL</th>
                    <th>STATUS</th>
                    <th>AKSI</th>
                </tr>
            </thead>
            <tbody>
                @foreach($debts as $debt)
                <tr>
                    <td>{{ $debt->transaction_date->format('d/m/Y') }}</td>
                    <td>
                        <div class="fw-bold">{{ $debt->contact->name ?? 'Pelanggan Umum' }}</div>
                        <small class="text-muted">{{ $debt->branch->name }}</small>
                    </td>
                    <td><small>{{ Str::limit($debt->description, 30) }}</small></td>
                    <td class="text-white fw-bold">Rp {{ number_format($debt->amount, 0, ',', '.') }}</td>
                    <td>
                        <span class="status-badge {{ $debt->payment_status === 'UNPAID' ? 'status-unpaid' : 'status-partial' }}">
                            {{ $debt->payment_status }}
                        </span>
                    </td>
                    <td>
                        <button class="btn-pay" onclick="openPayModal({{ $debt->id }}, '{{ $debt->contact->name ?? 'Pelanggan Umum' }}', {{ $debt->amount }}, {{ $debt->branch_id }})">
                            💸 Bayar
                        </button>
                    </td>
                </tr>
                @endforeach
                @if($debts->count() == 0)
                <tr>
                    <td colspan="6" class="text-center py-5 text-muted">
                        Semua pelanggan sudah lunas. Dompet aman! ☕
                    </td>
                </tr>
                @endif
            </tbody>
        </table>
    </div>
</div>

<!-- Modal Pembayaran -->
<div class="modal fade" id="payModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content modal-content-custom shadow-lg">
            <form id="payForm" method="POST">
                @csrf
                <div class="modal-header border-0 pb-0">
                    <h5 class="modal-title h4">Pelunasan Kasbon</h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="mb-4">
                        <label class="text-muted small d-block mb-1">Pelanggan</label>
                        <div id="modalCustomerName" class="h5"></div>
                    </div>
                    
                    <div class="mb-3">
                        <label class="text-muted small d-block mb-2">Pilih Akun Kas Masuk</label>
                        @php
                            $accounts = \App\Models\Account::where('type', 'CASH')->orWhere('type', 'BANK')->get();
                        @endphp
                        <select name="account_id" required>
                            @foreach($accounts as $acc)
                                <option value="{{ $acc->id }}">{{ $acc->name }} ({{ $acc->code }})</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="text-muted small d-block mb-2">Jumlah Bayar (Hutang: Rp <span id="modalDebtAmount"></span>)</label>
                        <input type="number" name="amount_paid" id="modalInputAmount" required>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-link text-white text-decoration-none" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn-pay px-4 py-2">Catat Pembayaran & Jurnal</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPayModal(id, name, amount, branchId) {
        const modal = new bootstrap.Modal(document.getElementById('payModal'));
        document.getElementById('modalCustomerName').innerText = name;
        document.getElementById('modalDebtAmount').innerText = amount.toLocaleString('id-ID');
        document.getElementById('modalInputAmount').value = amount;
        document.getElementById('modalInputAmount').max = amount;
        document.getElementById('payForm').action = `/debts/${id}/pay`;
        modal.show();
    }
</script>
@endsection
