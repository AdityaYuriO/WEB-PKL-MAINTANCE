@extends('layout.main')

@section('content')
    <!-- Gaya CSS Tambahan untuk Menyesuaikan Template (Bisa dipindah ke file css terpisah) -->
    <style>
        :root {
            --primary-color: #4e73df;
            --secondary-color: #858796;
            --success-color: #1cc88a;
            --warning-color: #f6c23e;
            --danger-color: #e74a3b;
            --light-bg: #f8f9fc;
        }

        body {
            font-family: 'Poppins', sans-serif;
            /* Pastikan font Poppins dimuat di layout utama */
            background-color: var(--light-bg);
            color: #5a5c69;
        }

        /* Card Styling */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 .15rem 1.75rem 0 rgba(58, 59, 69, .15);
            margin-bottom: 1.5rem;
        }

        .card-header {
            background-color: #fff;
            border-bottom: 1px solid #e3e6f0;
            padding: 1rem 1.25rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .card-title {
            font-weight: 600;
            color: var(--primary-color);
            margin: 0;
            font-size: 1.1rem;
            text-transform: uppercase;
        }

        /* Table Styling */
        .table thead th {
            border-top: none;
            border-bottom: 2px solid #e3e6f0;
            font-size: 0.85rem;
            text-transform: uppercase;
            color: var(--primary-color);
            padding: 0.75rem 1.5rem;
            background-color: #fff;
        }

        .table td {
            padding: 0.75rem 1.5rem;
            vertical-align: middle;
            font-size: 0.9rem;
            border-bottom: 1px solid #e3e6f0;
        }

        .table tbody tr:hover {
            background-color: #f8f9fc;
        }

        /* Button Styling */
        .btn-circle {
            border-radius: 50%;
            width: 32px;
            height: 32px;
            padding: 0;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            font-size: 0.8rem;
            transition: all 0.2s;
        }

        .btn-primary-custom {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
            color: white;
            border-radius: 0.35rem;
            font-weight: 500;
            box-shadow: 0 2px 2px 0 rgba(78, 115, 223, 0.2);
        }

        .btn-primary-custom:hover {
            background-color: #2e59d9;
            color: white;
            box-shadow: 0 4px 4px 0 rgba(78, 115, 223, 0.3);
        }

        .btn-warning-custom {
            background-color: var(--warning-color);
            border: none;
            color: white;
        }

        .btn-warning-custom:hover {
            background-color: #dda20a;
        }

        .btn-danger-custom {
            background-color: var(--danger-color);
            border: none;
            color: white;
        }

        .btn-danger-custom:hover {
            background-color: #be2617;
        }

        /* Modal Styling */
        .modal-header {
            background-color: var(--light-bg);
            border-bottom: 1px solid #e3e6f0;
        }

        .modal-title {
            font-weight: 700;
            color: var(--primary-color);
        }

        .form-label {
            font-weight: 600;
            font-size: 0.85rem;
            color: #5a5c69;
        }
    </style>

    <div class="container-xxl flex-grow-1 container-p-y">

        <!-- Main Card -->
        <div class="card shadow mb-4">
            <div class="card-header py-3 d-flex flex-row align-items-center justify-content-between">
                <h6 class="m-0 font-weight-bold card-title">Data Tujuan Pembelajaran</h6>
                <button class="btn btn-primary-custom btn-sm" data-bs-toggle="modal" data-bs-target="#modalCreate">
                    <i class="fas fa-plus fa-sm text-white-50 me-2"></i> Tambah Data
                </button>
            </div>
            <div class="card-body">
                <div class="table-responsive text-nowrap">
                    <table class="table table-bordered" width="100%" cellspacing="0">
                        <thead>
                            <tr>
                                <th style="width: 50px;">No</th>
                                <th>Konke</th>
                                <th>Tujuan Pembelajaran</th>
                                <th style="width: 150px; text-align: center;">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($tujuan as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        <span class="badge bg-light text-primary border border-primary px-3 py-2">
                                            {{ $item->konke->name_konke ?? '-' }}
                                        </span>
                                    </td>
                                    <td>{{ $item->tujuan_pembelajaran }}</td>
                                    <td class="text-center">
                                        <button class="btn btn-warning-custom btn-circle me-1" data-bs-toggle="modal"
                                            data-bs-target="#edit{{ $item->id }}" title="Edit">
                                            <i class="fas fa-pen"></i>
                                        </button>

                                        <button class="btn btn-danger-custom btn-circle" data-bs-toggle="modal"
                                            data-bs-target="#delete{{ $item->id }}" title="Hapus">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </td>
                                </tr>

                                <!-- Include Modals (Keeping your logic of including modals inside loop) -->
                                @include('penilaian.tujuan_pembelajaran.edit')
                                @include('penilaian.tujuan_pembelajaran.delete')

                            @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4">Tidak ada data ditemukan.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

    <!-- MODAL CREATE -->
    <div class="modal fade" id="modalCreate" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <form action="{{ route('tujuan-pembelajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title"><i class="fas fa-plus-circle me-2"></i>Tambah Tujuan Pembelajaran</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Konke</label>
                            <select name="id_konke" class="form-select" required>
                                <option value="" disabled selected>-- Pilih Konke --</option>
                                @foreach ($konkes as $konke)
                                    <option value="{{ $konke->id }}">{{ $konke->name_konke }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Deskripsi Tujuan</label>
                            <textarea name="tujuan_pembelajaran" class="form-control" rows="4" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary-custom btn-sm">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

