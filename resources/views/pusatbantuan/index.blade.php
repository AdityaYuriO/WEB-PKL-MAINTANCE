@extends('layout.main')

@section('content')
<div class="container-fluid py-4 px-4">
    <div class="card border-0 shadow-sm w-100">
        <div class="card-body">
            <h4 class="mb-4">Pusat Bantuan - Surat Pengantar</h4>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            {{-- Form Input --}}
            <form action="{{ route('surat.store') }}" method="POST">
                @csrf
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nomor">Nomor</label>
                        <input type="text" name="nomor" id="nomor" value="{{ old('nomor', $data->nomor ?? '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="perihal">Perihal</label>
                        <input type="text" name="perihal" id="perihal" value="{{ old('perihal', $data->perihal ?? '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tempat">Tempat</label>
                        <input type="text" name="tempat" id="tempat" value="{{ old('tempat', $data->tempat ?? '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggalbuat">Tanggal Buat</label>
                        <input type="date" name="tanggalbuat" id="tanggalbuat" value="{{ old('tanggalbuat', $data->tanggalbuat ?? '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_awal">Tanggal Awal</label>
                        <input type="date" name="tanggal_awal" id="tanggal_awal" value="{{ old('tanggal_awal', $data->tanggal_awal ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="tanggal_akhir">Tanggal Akhir</label>
                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" value="{{ old('tanggal_akhir', $data->tanggal_akhir ?? '') }}" class="form-control">
                    </div>
                    <div class="col-md-12 mb-3">
                        <label for="deskripsi">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" class="form-control" rows="4" required>{{ old('deskripsi', $data->deskripsi ?? '') }}</textarea>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_instansi">Nama Instansi</label>
                        <input type="text" name="nama_instansi" id="nama_instansi" value="{{ old('nama_instansi', $data->nama_instansi ?? '') }}" class="form-control" required>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="nama_kepsek">Nama Kepala Sekolah</label>
                        <input type="text" name="nama_kepsek" id="nama_kepsek" value="{{ old('nama_kepsek', $data->nama_kepsek ?? '') }}" class="form-control" required>
                    </div>
                </div>

                <div class="">
                    <button type="submit" class="btn btn-primary btn-sm">
                        {{ $data ? 'Update' : 'Simpan' }}
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
