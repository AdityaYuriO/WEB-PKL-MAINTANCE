@extends('layout.main')

@section('title', 'Tambah Pembimbing Institusi')

@section('content')

@if (session('message'))
    <div class="bg-green-100 text-green-700 p-3 mb-4">
        {{ session('message') }}
    </div>
@endif

@if ($errors->any())
    <div class="bg-red-100 text-red-700 p-3 mb-4">
        <ul>
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
@endif

<form action="{{ route('pembimbing.store') }}" method="POST" class="bg-white p-6 rounded shadow-md">
    @csrf

    <div class="mb-4">
        <label class="block font-medium">Nama</label>
        <input type="text" name="name" class="w-full p-2 border rounded" required>
    </div>

    <div class="mb-4">
        <label class="block font-medium">NIP</label>
        <input type="text" name="nip" class="w-full p-2 border rounded" required>
    </div>

    <div class="mb-4">
        <label class="block font-medium">No HP</label>
        <input type="text" name="no_hp" class="w-full p-2 border rounded" required>
    </div>

    <div class="mb-4">
        <label class="block font-medium">Password</label>
        <input type="password" name="password" class="w-full p-2 border rounded" required>
    </div>

    <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded">Simpan</button>
</form>

@endsection
