@extends('layout.main')
@section('content')
<style>
    @media (max-width: 768px) {
        .table th:nth-child(2),
        .table td:nth-child(2) {
            min-width: 400px; 
            max-width: 100%; 
            white-space: normal; 
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
        .table th:nth-child(3),
        .table td:nth-child(3) {
            min-width: 200px; 
            max-width: 100%; 
            white-space: normal; 
            word-wrap: break-word;
            overflow-wrap: break-word;
        }
    }

    .alert {
        display: none;
        position: fixed;
        top: 20px;
        right: 20px;
        background-color: #ffc107;
        color: #333;
        padding: 10px 20px;
        border-radius: 5px;
        box-shadow: 0 0 10px rgba(0, 0, 0, 0.2);
        z-index: 1000;
    }

    .alert .bi-exclamation-circle-fill {
        margin-right: 10px;
    }

    .alert .close-btn {
        margin-left: 10px;
        cursor: pointer;
        font-size: 18px;
    }

</style>
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">Data Konsentrasi Keahlian</h5>
                            <div class="d-flex gap-2">
                                <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                                    <i class="bi bi-search"></i>
                                    <span class="d-none d-md-inline">Cari</span>
                                </button>
                
                                <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahKonkeModal">
                                    <i class="bi bi-plus-lg"></i> <span class="d-none d-md-inline">Tambah</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="searchModalLabel">Cari Konsentrasi Keahlian</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <form action="#">
                                        <input type="text" name="search" class="form-control" placeholder="Cari Konsentrasi Keahlian...">
                                    </form>
                                </div>
                                <div class="modal-footer">
                                    <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                
                <div class="card">
                    <div class="card-body">
                        @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif   
                        <div class="table-responsive text-nowrap">
                            <table class="table table-striped" style="text-align: center">
                                <thead>
                                    <tr>
                                        <th>No</th>
                                        <th>Konsentrasi keahlian</th>
                                        <th>Program Keahlian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($konke as $index => $k)
                                    <tr>
                                        <td>{{ $konke->firstItem() + $loop->index }}</td>
                                        <td>{{ $k->name_konke }}
                                            <small class="text-muted d-block">ID : {{ $k->id ?? '-' }}</small>
                                        </td>
                                        <td>{{ $k->proker->name }}</td>
                                        <td>
                                            <div class="d-flex gap-1 justify-content-center flex-nowrap">
                                                <button class="btn btn-warning btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#editKonkeModal{{ $k->id }}">
                                                <i class="bi bi-pen"></i>
                                            </button>
                                            <form action="{{ route('konke.destroy', $k->id) }}" method="POST" class="delete-form d-inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="button" class="delete-btn btn btn-danger btn-sm">
                                                    <i class="bi bi-trash3"></i>
                                                </button>
                                            </form>                                            
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <div class="d-flex justify-content-end mt-3">
                            {{ $konke->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>

{{-- Modal Tambah Data --}}
<div class="modal fade" id="tambahKonkeModal" tabindex="-1" aria-labelledby="tambahKonkeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Tambah Konsentrasi Keahlian</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('konke.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Konsentrasi Keahlian*</label>
                        <input type="text" class="form-control" name="name_konke" required>
                        <small class="form-text text-muted"><i>Nama ini akan terlihat oleh semua pengguna, pastikan sudah benar!</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Keahlian*</label>
                        <select class="form-control" name="proker_id" required>
                            <option value="">Pilih Program Keahlian</option>
                            @foreach($proker as $p)
                                <option value="{{ $p->id }}">{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted"><i>Pilih Program Keahlian yang sesuai!</i></small>                        
                    </div>                            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Data</button>
                </div>
   Â Â Â Â Â Â Â Â Â </form>
        </div>
    </div>
</div>
{{-- Modal Edit Data --}}
@foreach ($konke as $k)
<div class="modal fade" id="editKonkeModal{{ $k->id }}" tabindex="-1" aria-labelledby="editKonkeModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h1 class="modal-title fs-5">Edit Konsentrasi Keahlian</h1>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form action="{{ route('konke.update', $k->id) }}" method="POST">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Konsentrasi Keahlian*</label>
                        <input type="text" class="form-control" name="name_konke" value="{{ $k->name_konke }}" required>
                        <small class="form-text text-muted"><i>Nama ini akan terlihat oleh semua pengguna, pastikan sudah benar!</i></small>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Program Keahlian*</label>
                        <select class="form-control" name="proker_id" required>
                            <option value="">Pilih Program Keahlian</option>
                            @foreach($proker as $p)
                            <option value="{{ $p->id }}" {{ $k->proker_id == $p->id ? 'selected' : '' }}>{{ $p->name }}</option>
                            @endforeach
                        </select>
                        <small class="form-text text-muted"><i>Pilih Program Keahlian yang sesuai!</i></small>                        
                    </div>                            
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endforeach
{{-- alert hapus --}}

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function () {
        // SweetAlert konfirmasi hapus
        document.querySelectorAll('.delete-btn').forEach(button => {
            button.addEventListener('click', function (event) {
                event.preventDefault();  // Mencegah tombol submit langsung

                Swal.fire({
                    title: "Apakah kamu yakin?",
                    text: "Data ini tidak bisa dikembalikan!",
                    icon: "warning",
                    showCancelButton: true,
                    confirmButtonColor: "#3085d6",
                    cancelButtonColor: "#d33",
                    confirmButtonText: "Ya, Hapus!",
                    cancelButtonText: "Batal"
                }).then((result) => {
                    if (result.isConfirmed) {
                        this.closest('form').submit(); // Submit form terdekat
                    }
                });
            });
        });

        // Notifikasi sukses jika ada session('success')
        @if (session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: @json(session('success')),
            timer: 2000,
            showConfirmButton: false
        });
        @endif
    });
</script>

<script>

    document.addEventListener("DOMContentLoaded", function() {
        const searchInput = document.querySelector("input[name='search']");
        const tableRows = document.querySelectorAll("tbody tr");

        searchInput.addEventListener("keyup", function() {
            const searchValue = this.value.toLowerCase();

            tableRows.forEach(row => {
                const text = row.textContent.toLowerCase();
                row.style.display = text.includes(searchValue) ? "" : "none";
            });
        });
    });

    </script>
@endsection


