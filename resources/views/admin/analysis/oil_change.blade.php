@extends('layouts.admin')

@section('title', 'Analisis Pemeliharaan')
@section('page_title', 'Operasional: Analisis Pemeliharaan')

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="text-main">Histori Pemeliharaan Rutin</h3>
        <div class="badge badge-info"><i class="fa-solid fa-screwdriver-wrench"></i> Berdasarkan Data Booking</div>
    </div>
    <div class="card-body" style="padding:0;">
        <table>
            <thead>
                <tr>
                    <th>Objek/Kendaraan</th>
                    <th>Tanggal Pemeliharaan</th>
                    <th>Catatan Pemeliharaan</th>
                    <th>Jadwal Berikutnya</th>
                </tr>
            </thead>
            <tbody>
                @forelse($oilChanges as $oc)
                <tr>
                    <td><code>{{ $oc->plate_number }}</code></td>
                    <td>{{ \Carbon\Carbon::parse($oc->date)->format('d M Y') }}</td>
                    <td><span style="font-size:12px; color:var(--text-muted);">{{ $oc->notes }}</span></td>
                    <td>
                        @php $nextDate = \Carbon\Carbon::parse($oc->date)->addMonths(3); @endphp
                        <span class="badge {{ $nextDate->isPast() ? 'badge-danger' : 'badge-warning' }}">
                            {{ $nextDate->format('d M Y') }}
                        </span>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="4" style="text-align:center; padding:40px;">Belum ada data penggantian oli yang tercatat.</td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
