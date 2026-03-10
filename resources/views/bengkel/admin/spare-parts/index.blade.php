@extends('layouts.app')

@section('title', 'Manajemen Spare Parts')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Spare Parts</h2>
    <table class="min-w-full">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Stok</th>
            </tr>
        </thead>
        <tbody>
            @foreach($spareParts as $sparePart)
            <tr>
                <td>{{ $sparePart->name }}</td>
                <td>{{ $sparePart->stock }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
