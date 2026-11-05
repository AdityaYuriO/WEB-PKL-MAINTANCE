@extends('layout.main')
@section('content')
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card mb-3">
                        <div class="card-body">
                            <h5 class="mb-0">Data Diri Pembimbing</h5>
                        </div>
                    </div>
                    <div class="card shadow mb-4">
                        <div class="card-body">
                            @if ($errors->any())
                                <div style="color: red;">
                                    <ul>
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif

                            @if(session()->has('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif   
                            <form action="{{ route('iduka.pembimbing.store') }}" method="POST">
                                @csrf
                            
                                <div class="mb-3">
                                    <label class="form-label">Nama Lengkap</label>
                                    <input type="text" name="name" class="form-control"
                                        value="{{ old('name', $pembimbing->name ?? '') }}" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label">NIP</label>
                                    <input type="text" name="nip" class="form-control"
                                        value="{{ old('nip', $pembimbing->nip ?? '') }}" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label">No Telepon</label>
                                    <input type="text" name="no_hp" class="form-control"
                                        value="{{ old('no_hp', $pembimbing->no_hp ?? '') }}" required>
                                </div>
                            
                                <div class="mb-3">
                                    <label class="form-label">Password</label>
                                    <input type="password" class="form-control" name="password" placeholder="{{ $pembimbing->id ? 'Kosongkan jika tidak diubah' : 'Password baru' }}">
                                </div>
                            
                                <button type="submit" class="btn btn-primary">
                                    {{ $pembimbing->id ? 'Perbarui' : 'Simpan' }}
                                </button>
                            </form>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
