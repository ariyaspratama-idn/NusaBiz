@extends('layouts.app')

@section('title', 'Manajemen Layanan')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Layanan</h2>
    <table class="min-w-full">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Harga</th>
            </tr>
        </thead>
        <tbody>
            @foreach($services as $service)
            <tr>
                <td>{{ $service->name }}</td>
                <td>{{ $service->price }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
