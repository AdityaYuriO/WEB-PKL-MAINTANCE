@extends('layout.main')

@section('content')
    <div class="container">

        @if (in_array(auth()->user()->role, ['kepsek', 'hubin']))
            <h5 class="fw-bold mt-5">
                <i class="bx bx-desktop text-primary"></i>
                Monitoring IDUKA
            </h5>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bx bx-check-circle me-2"></i>
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            @endif

            {{-- Kartu Statistik --}}
            <div class="row mb-4 g-3">
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-desktop text-primary fs-2 mb-2"></i>
                            <h6 class="text-muted">Total Monitoring</h6>
                            <h3 class="fw-bold text-primary">{{ $totalMonitoring }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-buildings text-info fs-2 mb-2"></i>
                            <h6 class="text-muted">Total IDUKA</h6>
                            <h3 class="fw-bold text-info">{{ $totalIduka }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-check-circle text-success fs-2 mb-2"></i>
                            <h6 class="text-muted">IDUKA Termonitor</h6>
                            <h3 class="fw-bold text-success">{{ $totalIdukaWithMonitoring }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-x-circle text-warning fs-2 mb-2"></i>
                            <h6 class="text-muted">Belum Termonitor</h6>
                            <h3 class="fw-bold text-warning">{{ $totalIdukaWithoutMonitoring }}</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-user-check text-success fs-2 mb-2"></i>
                            <h6 class="text-muted">Perkiraan Siswa Diterima</h6>
                            <h3 class="fw-bold text-success">{{ $totalPerkiraanSiswa }}</h3>
                        </div>
                    </div>
                </div>

                <!-- Tambahan baru -->
                <div class="col-md-3">
                    <div class="card text-center border-0 shadow-sm h-100">
                        <div class="card-body">
                            <i class="bx bx-user-x text-danger fs-2 mb-2"></i>
                            <h6 class="text-muted">Petugas Belum Monitoring</h6>

                            <h3 class="fw-bold text-danger">{{ $guruBelumMonitoring }}</h3>
                            <button class="btn btn-outline-danger btn-sm mt-2" data-bs-toggle="modal"
                                data-bs-target="#modalBelumMonitoring">
                                Lihat Daftar
                            </button>

                        </div>
                    </div>
                </div>
            </div>
        @endif





        {{-- Filter dan Tambah Data --}}
        <div class="card border-0 shadow-sm mb-4 my-4">
            <div class="card-body">
                <div class="row g-3 align-items-end">
                    <div class="col-md-8">
                        <form method="GET" action="{{ route('monitoring.index') }}">
                            <div class="row g-3 align-items-end">
                                <div class="col-md-6">
                                    <input type="text" name="nama_iduka" value="{{ request('nama_iduka') }}"
                                        class="form-control" placeholder="Cari IDUKA...">
                                </div>
                                <div class="col-md-6 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary">
                                        <i class="fas fa-search me-2"></i> Cari
                                    </button>
                                    <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">
                                        <i class="fas fa-sync-alt me-2"></i> Reset
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                    {{-- Tombol Tambah hanya untuk selain kaprog --}}
                    <div class="col-md-4 text-md-end">
                        @if (in_array(auth()->user()->role, ['kaprog', 'guru']))
                            <a href="{{ route('monitoring.create') }}" class="btn btn-success">
                                <i class="fas fa-plus me-2"></i> Tambah Monitoring
                            </a>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Tabel Monitoring --}}
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent border-0">
                <h5 class="card-title fw-bold mb-0">
                    <i class="bx bx-list-ul text-primary me-2"></i>
                    Data Monitoring
                </h5>
            </div>

            <div class="card-body p-0">
                <div class="table-responsive text-nowrap">
                    <table class="table table-hover mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="border-0 fw-semibold text-muted small px-4 py-3">NO</th>
                                <th class="border-0 fw-semibold text-muted small py-3">PEMONITORING</th>
                                <th class="border-0 fw-semibold text-muted small py-3">NAMA IDUKA</th>
                                <th class="border-0 fw-semibold text-muted small py-3">SARAN / CATATAN</th>
                                <th class="border-0 fw-semibold text-muted small py-3 text-center">PERKIRAAN SISWA DITERIMA
                                </th>
                                <th class="border-0 fw-semibold text-muted small py-3 text-center">FOTO</th>
                                <th class="border-0 fw-semibold text-muted small py-3 text-center">TANGGAL</th>
                                <th class="border-0 fw-semibold text-muted small py-3 text-center">AKSI</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($monitoring as $index => $item)
                                <tr>
                                    {{-- Nomor --}}
                                    <td class="px-4 align-middle">{{ $monitoring->firstItem() + $index }}</td>

                                    {{-- Dibuat oleh --}}
                                    <td class="align-middle">
                                        <div class="fw-semibold">{{ $item->guru->nama ?? '-' }}</div>
                                        <small class="text-muted">
                                            {{ $item->guru->konke->name_konke ?? '-' }}
                                        </small>
                                    </td>


                                    {{-- Nama IDUKA --}}
                                    <td class="align-middle">
                                        <div class="fw-semibold">{{ $item->iduka->nama ?? '-' }}</div>
                                        <small class="text-muted">{{ $item->iduka->alamat ?? '-' }}</small>
                                        <p>{{ optional($item->tgl)->format('d-m-Y') ?? '-' }}</p>
                                    </td>

                                    {{-- Saran --}}
                                    <td class="align-middle" style="max-width: 250px;">
                                        {{ Str::limit($item->saran, 100) ?: '-' }}
                                    </td>

                                    {{-- Perkiraan siswa diterima --}}
                                    <td class="text-center align-middle">
                                        <span class="badge bg-info fs-6">
                                            {{ $item->perikiraan_siswa_diterima ?? '-' }} siswa
                                        </span>
                                    </td>

                                    {{-- Foto --}}
                                    <td class="text-center align-middle">
                                        @if ($item->foto)
                                            @foreach (json_decode($item->foto, true) as $foto)
                                                <button class="btn btn-outline-primary btn-sm mb-1"
                                                    onclick="showImage('{{ asset($foto) }}', '{{ $item->iduka->nama }}')">
                                                    <i class="fas fa-image"></i> Lihat
                                                </button>
                                            @endforeach
                                        @else
                                            <span class="text-muted">-</span>
                                        @endif
                                    </td>


                                    {{-- Tanggal --}}
                                    <td class="text-center align-middle">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($item->tgl)->format('d-m-Y') }}
                                        </small>
                                    </td>



                                    {{-- Tombol Aksi --}}
                                    <td class="text-center align-middle">
                                        <div class="btn-group" role="group">
                                            {{-- Tombol lihat selalu tampil --}}
                                            <a href="{{ route('monitoring.show', $item->id) }}"
                                                class="btn btn-outline-info btn-sm">
                                                <i class="fas fa-eye"></i>
                                            </a>

                                            {{-- Tombol edit & hapus hanya tampil untuk guru atau kaprog --}}
                                            @if (in_array(auth()->user()->role, ['guru', 'kaprog']))
                                                <a href="{{ route('monitoring.edit', $item->id) }}"
                                                    class="btn btn-outline-warning btn-sm">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <button class="btn btn-outline-danger btn-sm"
                                                    onclick="confirmDelete({{ $item->id }}, '{{ $item->iduka->nama }}')">
                                                    <i class="fas fa-trash"></i>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="8" class="text-center py-4">
                                        <div class="text-muted">
                                            <i class="bx bx-folder-open fs-1"></i>
                                            <div class="mt-2">Belum ada data monitoring</div>
                                        </div>
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>

                </div>
            </div>

            @if ($monitoring->hasPages())
                <div class="card-footer bg-transparent border-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <small class="text-muted">
                            Menampilkan {{ $monitoring->firstItem() }} - {{ $monitoring->lastItem() }}
                            dari {{ $monitoring->total() }} data
                        </small>
                        <nav>
                            {{ $monitoring->links('pagination::bootstrap-5') }}
                        </nav>
                    </div>
                </div>
            @endif
        </div>
    </div>

    {{-- Modal untuk menampilkan gambar --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="modalBelumMonitoring" tabindex="-1" aria-labelledby="modalBelumMonitoringLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalBelumMonitoringLabel">Pembimbing Belum Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Tutup"></button>
                </div>
                <div class="modal-body">
                    @if ($guruBelumMonitoringData->isNotEmpty())
                        <table class="table table-bordered">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Nama</th>
                                    <th>NIP</th>
                                    <th>Email</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($guruBelumMonitoringData as $i => $guru)
                                    <tr>
                                        <td>{{ $i + 1 }}</td>
                                        <td>{{ $guru->name ?? '-' }}</td>
                                        <td>{{ $guru->nip ?? '-' }}</td>
                                        <td>{{ $guru->email ?? '-' }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    @else
                        <p class="text-muted">Semua guru/kaprog sudah membuat monitoring Ã¢Å“â€¦</p>
                    @endif

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>



    {{-- Modal konfirmasi hapus --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus data monitoring untuk <strong id="deleteItemName"></strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        function showImage(imageUrl, title) {
            document.getElementById('modalImage').src = imageUrl;
            document.getElementById('imageModalLabel').textContent = 'Foto Monitoring - ' + title;
            new bootstrap.Modal(document.getElementById('imageModal')).show();
        }

        function confirmDelete(id, name) {
            document.getElementById('deleteItemName').textContent = name;
            document.getElementById('deleteForm').action = '/monitoring/' + id;
            new bootstrap.Modal(document.getElementById('deleteModal')).show();
        }
    </script>
@endpush

