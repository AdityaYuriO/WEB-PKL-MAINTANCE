@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Page Header -->
            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="text-primary fw-bold mb-1">Tambah Data Guru</h4>
                    <small class="text-muted">Form input data guru baru</small>

                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                </div>
            </div>

            <!-- Form Card -->
            <div class="card shadow-sm border-0">
                <div class="card-header bg-primary text-white py-2">
                    <h6 class="mb-0 fw-semibold text-white">
                        <i class="fas fa-user-tie me-2"></i> Form Tambah Data Guru
                    </h6>
                </div>

                <div class="card-body p-4">
                    <form action="{{ route('user.guru.store') }}" method="POST" id="tambahGuruForm">
                        @csrf

                        <div class="row">
                            <!-- Column 1 -->
                            <div class="col-md-6">
                                <!-- Nama Guru -->
                                <div class="mb-3">
                                    <label for="nama" class="form-label fw-semibold">Nama Guru <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nama" name="nama" required>
                                </div>

                                <!-- NIK -->
                                <div class="mb-3">
                                    <label for="nik" class="form-label fw-semibold">NIK <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="nik" name="nik" required
                                        maxlength="16">
                                </div>

                                <!-- NIP/NUPTK -->
                                <div class="mb-3">
                                    <label for="nip" class="form-label fw-semibold">NIP / NUPTK</label>
                                    <input type="text" class="form-control" id="nip" name="nip">
                                </div>

                                <!-- Tempat Lahir -->
                                <div class="mb-3">
                                    <label for="tempat_lahir" class="form-label fw-semibold">Tempat Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="tempat_lahir" name="tempat_lahir"
                                        required>
                                </div>

                                <!-- Tanggal Lahir -->
                                <div class="mb-3">
                                    <label for="tanggal_lahir" class="form-label fw-semibold">Tanggal Lahir <span
                                            class="text-danger">*</span></label>
                                    <input type="date" class="form-control" id="tanggal_lahir" name="tanggal_lahir"
                                        required>
                                </div>

                                <!-- Jenis Kelamin -->
                                <div class="mb-3">
                                    <label for="jenis_kelamin" class="form-label fw-semibold">Jenis Kelamin <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="jenis_kelamin" name="jenis_kelamin" required>
                                        <option value="">Pilih Jenis Kelamin</option>
                                        <option value="Laki-laki">Laki-laki</option>
                                        <option value="Perempuan">Perempuan</option>
                                    </select>
                                </div>
                            </div>

                            <!-- Column 2 -->
                            <div class="col-md-6">
                                <!-- Alamat -->
                                <div class="mb-3">
                                    <label for="alamat" class="form-label fw-semibold">Alamat <span
                                            class="text-danger">*</span></label>
                                    <textarea class="form-control" id="alamat" name="alamat" rows="3" required></textarea>
                                </div>

                                <!-- Konsentrasi Keahlian -->
                                <div class="mb-3">
                                    <label for="konke_id" class="form-label fw-semibold">Konsentrasi Keahlian</label>
                                    <select class="form-select" id="konke_id" name="konke_id">
                                        <option value="">Pilih Konsentrasi</option>
                                        @foreach ($konke as $k)
                                            <option value="{{ $k->id }}">{{ $k->name_konke }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Email -->
                                <div class="mb-3">
                                    <label for="email" class="form-label fw-semibold">Email <span
                                            class="text-danger">*</span></label>
                                    <input type="email" class="form-control" id="email" name="email" required>
                                </div>

                                <!-- No HP -->
                                <div class="mb-3">
                                    <label for="no_hp" class="form-label fw-semibold">No HP <span
                                            class="text-danger">*</span></label>
                                    <input type="text" class="form-control" id="no_hp" name="no_hp" required>
                                </div>

                                <!-- Role -->
                                <div class="mb-3">
                                    <label for="role" class="form-label fw-semibold">Role <span
                                            class="text-danger">*</span></label>
                                    <select class="form-select" id="role" name="role" required>
                                        <option value="">Pilih Role</option>
                                        <option value="guru">Guru</option>
                                        <option value="kaprog">Kaprog</option>
                                        <option value="hubin">Hubin</option>
                                        <option value="psekolah">Pembimbing Sekolah</option>
                                    </select>
                                </div>

                                <!-- Password -->
                                <div class="mb-3">
                                    <label for="password" class="form-label fw-semibold">Password <span
                                            class="text-danger">*</span></label>
                                    <input type="password" class="form-control" id="password" name="password" required>
                                </div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="d-flex justify-content-end mt-3">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-1"></i> Simpan Data
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
