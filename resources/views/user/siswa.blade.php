@extends('layout.main')

@section('content')
    <div class="container-fluid">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row mb-3">
                <div class="col-12">
                    <h4 class="text-primary fw-bold mb-1">Tambah Data Siswa</h4>
                    <small class="text-muted">Form input data siswa baru</small>
                </div>
            </div>

            <div class="row">
                <div class="col-12">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-primary text-white py-2">
                            <h6 class="mb-0 fw-semibold text-white">
                                <i class="fas fa-user-plus me-2"></i>Form Tambah Data Siswa
                            </h6>
                        </div>

                        <div class="card-body p-4">
                            <!-- Form -->
                            <form action="{{ route('user.siswa.store') }}" method="POST">
                                @csrf

                                <div class="row">
                                    <!-- Kolom kiri -->
                                    <div class="col-md-6">
                                        <!-- Nama -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Nama Lengkap</label>
                                            <input type="text" name="name" class="form-control"
                                                placeholder="Masukkan nama lengkap" required>
                                        </div>

                                        <!-- NIP -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">NIP/NIS</label>
                                            <input type="text" name="nip" class="form-control"
                                                placeholder="Masukkan NIP/NIS" required>
                                        </div>

                                        <!-- Kelas -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Kelas</label>
                                            <select name="kelas_id" class="form-select" required>
                                                <option value="">Pilih Kelas</option>
                                                @foreach ($kelas as $k)
                                                    <option value="{{ $k->id }}">
                                                        {{$k->name_kelas }}
                                                    </option>
                                                @endforeach
                                            </select>

                                             <div class="mb-3">
                                            <label class="form-label">Tahun Ajaran</label>
                                            <input type="text" name="tahun_ajaran" class="form-control"
                                                placeholder="2025/2026" required>
                                        </div>
                                        </div>
                                    </div>

                                    <!-- Kolom kanan -->
                                    <div class="col-md-6">
                                        <!-- Email -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Email</label>
                                            <input type="email" name="email" class="form-control"
                                                placeholder="contoh@email.com" required>
                                        </div>

                                        <!-- Password -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Password</label>
                                            <input type="password" name="password" class="form-control"
                                                placeholder="Masukkan password" required>
                                        </div>

                                        <!-- Konsentrasi Keahlian -->
                                        <div class="mb-3">
                                            <label class="form-label fw-semibold">Konsentrasi Keahlian</label>
                                            <select name="konke_id" class="form-select" required>
                                                <option value="">Pilih Konsentrasi</option>
                                                @foreach ($konke as $kk)
                                                    <option value="{{ $kk->id }}">{{ $kk->name_konke }}</option>
                                                @endforeach
                                            </select>
                                        </div>
                                    </div>
                                </div>

                                <!-- Tombol -->
                                <div class="row mt-4">
                                    <div class="col-12 d-flex justify-content-end gap-2">
                                        <a href="{{ route('siswa.index') }}" class="btn btn-secondary">
                                            <i class="fas fa-times me-1"></i>Batal
                                        </a>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-save me-1"></i>Simpan Data
                                        </button>
                                    </div>
                                </div>
                            </form>
                            <!-- End Form -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
