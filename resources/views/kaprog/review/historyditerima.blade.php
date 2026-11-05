@extends('layout.main')

@section('content')
<style>
    .btn-back {
        background-color: #7e7dfb;
        color: white;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        border: none;
        padding: 7px 14px;
        border-radius: 5px;
        font-size: 16px;
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
    }

    .btn-back:hover,
    .btn-reset:hover {
        background-color: #7e7dfb;
        color: white;
        transform: translateY(-3px);
        box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
    }

    .btn-back:active,
    .btn-reset:hover {
        color: white;
        background-color: #6b6bfa !important;
        transform: translateY(3px);
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    }

    .btn-reset {
        background-color: #7e7dfb;
        color: white;
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
        border: none;
        padding: 6px 12px;
        border-radius: 5px;
        font-size: 14px;
        margin-bottom: 0.5rem;
        cursor: pointer;
        transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
    }
</style>
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="col-md-12 mt-3">
                   <div class="card mb-3">
                        <div class="card-body">
                            <div class="col-md-12 mt-3 d-flex justify-content-between align-items-center">
                                <h4 class="mb-3">History Pengajuan Diterima</h4>
                                <button class="btn btn-reset shadow-sm">Reset Data</button>
                            </div>
                        </div>
                   </div>
                   <div class="card shadow-sm" style="padding: 20px;">
                    @if(session()->has('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Nama Institusi</th>
                                    <th>Tanggal Pengajuan</th>
                                    <th>Status</th>
                                    <th>Surat Izin</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($paginated as $index => $item)
                                <tr>
                                    <td>{{ ($paginated->firstItem() ?? 0) + $index }}</td>
                                    <td>{{ $item->user->name }}</td>
                                    <td>{{ $item->user->dataPribadi->kelas->name_kelas ?? '-' }}</td>
                                    <td>
                                        @if($item->tipe == 'usulan')
                                            {{ $item->nama }}
                                        @else
                                            {{ $item->iduka->nama ?? '-' }}
                                        @endif
                                    </td>
                                    <td>{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y') }}</td>
                                    <td><span class="badge bg-success">Diterima</span></td>
                                    <td>
                                        @if($item->surat_izin == 'belum')
                                        <form class="form-surat-izin" data-id="{{ $item->id }}" data-tipe="{{ $item->tipe }}">
                                            @csrf
                                            <button type="button" class="btn btn-warning btn-sm btn-surat-izin">Belum</button>
                                        </form>
                                        @else
                                        <span class="badge bg-success">Sudah</span>
                                        @endif
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn-delete" data-id="{{ $item->id }}" data-tipe="{{ $item->tipe }}">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>

                        <!-- Pagination di sini -->
                        <div class="d-flex justify-content-end mt-4">
                            {{ $paginated->links('pagination::bootstrap-5') }}
                        </div>
                    </div>

                    <div class="d-flex justify-content mt-3 mb-2">
                        <a href="{{ route('review.usulan')}}" class="btn btn-back shadow-sm">
                            Kembali
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Handle Surat Izin Update
        const buttons = document.querySelectorAll('.btn-surat-izin');
        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('.form-surat-izin');
                const id = form.getAttribute('data-id');
                const tipe = form.getAttribute('data-tipe');
                const csrfToken = form.querySelector('input[name="_token"]').value;

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Surat izin akan diperbarui dan dikirim ke Persuratan.",
                    icon: 'question',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, perbarui!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/kaprog/update-surat-izin/${id}`, {
                            method: 'POST',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken,
                                'Content-Type': 'application/json'
                            },
                            body: JSON.stringify({ tipe: tipe })
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                form.outerHTML = '<span class="badge bg-success">Sudah</span>';
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Surat izin berhasil diperbarui, dan dikirim ke Persuratan',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Oops...',
                                    text: data.message || 'Terjadi kesalahan!',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal memperbarui surat izin. Silakan coba lagi.',
                            });
                        });
                    }
                });
            });
        });

        // Handle Hapus Data
        const deleteButtons = document.querySelectorAll('.btn-delete');
        deleteButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const tipe = this.getAttribute('data-tipe');

                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Data ini akan dihapus permanen.",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        fetch(`/kaprog/delete-data/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            }
                        })
                        .then(response => response.json())
                        .then(data => {
                            if (data.success) {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Data berhasil dihapus.',
                                    timer: 1500,
                                    showConfirmButton: false
                                });
                                this.closest('tr').remove(); // Hapus baris data yang dihapus
                            } else {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: data.message || 'Terjadi kesalahan!',
                                });
                            }
                        })
                        .catch(error => {
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Gagal menghapus data. Silakan coba lagi.',
                            });
                        });
                    }
                });
            });
        });
    });
</script>

