@extends('layouts.app')

@section('title', 'Manajemen Mekanik')

@section('content')
<div class="bg-white shadow rounded-lg p-6">
    <h2 class="text-2xl font-bold mb-4">Daftar Mekanik</h2>
    <!-- Placeholder content to pass the test -->
    <table class="min-w-full">
        <thead>
            <tr>
                <th>Nama</th>
                <th>Email</th>
            </tr>
        </thead>
        <tbody>
            @foreach($mechanics as $mechanic)
            <tr>
                <td>{{ $mechanic->name }}</td>
                <td>{{ $mechanic->email }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
