@extends('layout.main')

@section('content')
<div class="container mt-4">
    <h4 class="mb-3">Form Pindah Tempat PKL</h4>

    <form action="{{ route('pindah-pkl.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="alasan" class="form-label">Alasan Pindah</label>
            <textarea name="alasan" id="alasan" class="form-control" rows="3" required></textarea>
        </div>

        <div class="mb-3">
            <label for="tempat_baru" class="form-label">Tempat PKL Baru</label>
            <input type="text" name="tempat_baru" id="tempat_baru" class="form-control" required>
        </div>

        <div class="mb-3">
            <label for="surat_persetujuan" class="form-label">Upload Surat Persetujuan</label>
            <input type="file" name="surat_persetujuan" id="surat_persetujuan" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">Kirim Pengajuan</button>
    </form>
</div>
@endsection

