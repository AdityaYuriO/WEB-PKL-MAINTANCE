@extends('layout.main')

@section('content')
    <div class="container">
        <h3 class="mb-4 fw-bold text-primary">
            <i class="fas fa-user-tie me-2 mt-4"></i> Daftar Pembimbing (Guru)
        </h3>

        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <!-- Form Search -->
        <form method="GET" action="{{ route('pembimbing.siswa.index') }}" class="mb-4">
            <div class="input-group">
                <input type="text" name="search" value="{{ request('search') }}" class="form-control"
                    placeholder="Cari nama pembimbing...">
                <button class="btn btn-primary" type="submit">
                    <i class="fas fa-search"></i> Cari
                </button>
            </div>
        </form>


        <div class="row">
            @foreach ($pembimbings as $pembimbing)
                <div class="col-md-6 col-lg-4 mb-4">
                    <div class="card h-100 border-0 shadow-sm" style="border-radius: 15px; transition: all 0.3s ease;"
                        onmouseover="this.style.transform='translateY(-5px)'; this.style.boxShadow='0 10px 25px rgba(0,0,0,0.1)'"
                        onmouseout="this.style.transform='translateY(0)'; this.style.boxShadow='0 2px 10px rgba(0,0,0,0.1)'">

                        <div class="card-body text-center p-4">
                            <!-- Foto avatar -->
                            <img src="https://ui-avatars.com/api/?name={{ urlencode($pembimbing->nama) }}&background=667eea&color=fff&size=80&rounded=true"
                                alt="Profile" class="rounded-circle mb-3" style="width: 80px; height: 80px;">

                            <h5 class="fw-bold text-dark mb-1">{{ $pembimbing->nama }}</h5>
                            <p class="text-muted small mb-0">NIP. {{ $pembimbing->nip ?? '-' }}</p>
                            <p class="text-muted small">{{ $pembimbing->konke->nama ?? '-' }}</p>

                            <div class="d-grid gap-2 mt-3">
                                <button class="btn btn-outline-primary" data-bs-toggle="modal"
                                    data-bs-target="#detailModal{{ $pembimbing->id }}">
                                    <i class="fas fa-eye me-2"></i>Lihat Detail
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Modal Detail Pembimbing -->
                <div class="modal fade" id="detailModal{{ $pembimbing->id }}" tabindex="-1"
                    aria-labelledby="detailModalLabel{{ $pembimbing->id }}" aria-hidden="true">
                    <div class="modal-dialog modal-xl">
                        <div class="modal-content" style="border-radius: 15px;">
                            <div class="modal-header bg-primary text-white"
                                style="border-top-left-radius: 15px; border-top-right-radius: 15px;">
                                <h5 class="modal-title fw-bold" id="detailModalLabel{{ $pembimbing->id }}">
                                    <i class="fas fa-user-tie me-2"></i> Detail Pembimbing
                                </h5>
                                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                    aria-label="Close"></button>
                            </div>

                            <div class="modal-body p-4">
                                <div class="row">
                                    <!-- Profile Pembimbing -->
                                    <div class="col-md-4 text-center mb-3">
                                        <img src="https://ui-avatars.com/api/?name={{ urlencode($pembimbing->nama) }}&background=667eea&color=fff&size=120&rounded=true"
                                            alt="Profile" class="rounded-circle mb-3" style="width: 120px; height: 120px;">
                                        <h5 class="fw-bold">{{ $pembimbing->nama }}</h5>
                                        <p class="text-muted small">NIP. {{ $pembimbing->nip ?? '-' }}</p>
                                        <span class="badge bg-info">{{ $pembimbing->konke->nama ?? '-' }}</span>
                                    </div>

                                    <!-- Informasi + Daftar Siswa -->
                                    <div class="col-md-8">
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="fas fa-users me-2"></i> Daftar Siswa Bimbingan
                                        </h6>

                                        @if ($pembimbing->siswas->count() > 0)
                                            <div class="table-responsive text-nowrap">
                                                <table class="table table-bordered align-middle">
                                                    <thead class="table-light">
                                                        <tr>
                                                            <th>No</th>
                                                            <th>Nama</th>
                                                            <th>Kelas</th>
                                                            <th>Jurusan</th>
                                                            <th>IDUKA</th>
                                                            <th>Aksi</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody>
                                                        @php $no = 0; @endphp
                                                        @foreach ($pembimbing->siswas as $s)
                                                        @if ($s->status == "aktif")
                                                            <tr>
                                                                @php $no++; @endphp
                                                                <td>{{ $no }}</td>
                                                                <td>{{ $s->name }}</td>
                                                                <td>{{ $s->kelas->kelas ?? '-' }}</td>
                                                                <td>{{ $s->kelas->name_kelas ?? '-' }}</td>
                                                                <td>{{ $s->idukaDiterima->nama ?? '-' }}</td>
                                                                <td>
                                                                    <form
                                                                        action="{{ route('pembimbing.destroy', [$pembimbing->id, $s->id]) }}"
                                                                        method="POST"
                                                                        onsubmit="return confirm('Hapus siswa ini dari bimbingan?')">
                                                                        @csrf
                                                                        @method('DELETE')
                                                                        <button type="submit"
                                                                            class="btn btn-sm btn-danger">
                                                                            <i class="fas fa-trash"></i>
                                                                        </button>
                                                                    </form>
                                                                </td>
                                                            </tr>
                                                            @endif
                                                        @endforeach
                                                    </tbody>
                                                </table>
                                            </div>
                                        @else
                                            <p class="text-muted fst-italic">Belum ada siswa bimbingan.</p>
                                        @endif

                                        <hr>

                                        <!-- Tambah siswa -->
                                        <h6 class="fw-bold text-secondary mb-3">
                                            <i class="fas fa-plus-circle me-2"></i> Tambah Siswa ke Bimbingan
                                        </h6>
                                        <form action="{{ route('pembimbing.store', $pembimbing->id) }}" method="POST">
                                            @csrf
                                            <div class="row g-2">
                                                <div class="col-md-8">
                                                    <div class="mb-3">
                                                        <label for="siswa_id" class="form-label fw-bold text-secondary">
                                                            Pilih Siswa
                                                        </label>
                                                        <select name="siswa_id" id="siswa_id{{ $pembimbing->id }}"
                                                            class="form-select select2" required>
                                                            <option value="">-- Pilih siswa --</option>
                                                            @foreach ($siswas as $s)
                                                                @if ( $s->status == "aktif" )
                                                                <option value="{{ $s->id }}">
                                                                    {{ $s->name }}
                                                                    @if ($s->kelas)
                                                                        - {{ $s->kelas->name_kelas }}
                                                                    @endif
                                                                    @if ($s->iduka)
                                                                        - {{ $s->iduka->nama_iduka }}
                                                                    @endif
                                                                </option>
                                                                @endif
                                                            @endforeach
                                                        </select>
                                                    </div>


                                                </div>
                                                <div class="col-md-4">
                                                    <button type="submit" class="btn btn-success w-100">
                                                        <i class="fas fa-plus me-2"></i> Tambah
                                                    </button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>

                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal"
                                    style="border-radius: 10px;">
                                    <i class="fas fa-times me-2"></i> Tutup
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>

        <!-- Pagination -->
        <div class="d-flex justify-content-center mt-4">
            {{ $pembimbings->links('pagination::bootstrap-5') }}
        </div>
    </div>


@endsection
 @push('scripts')
        <script>
            $(document).ready(function() {
                $('.select2').each(function() {
                    $(this).select2({
                        dropdownParent: $(this).closest('.modal'),
                        placeholder: "-- Pilih siswa --",
                        allowClear: true,
                        width: '100%'
                    });
                });
            });
        </script>
    @endpush

