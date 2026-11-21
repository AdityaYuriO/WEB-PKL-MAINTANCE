@extends('layout.main')

@section('content')
    <div class="container my-4">
        <div class="row justify-content-center">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-transparent border-0">
                        <div class="d-flex align-items-center justify-content-between">
                            <div class="d-flex align-items-center">
                                <a href="{{ route('monitoring.index') }}" class="btn btn-outline-secondary me-3">
                                    <i class="fas fa-arrow-left"></i>
                                </a>
                                <div>
                                    <h5 class="card-title fw-bold mb-0">
                                        <i class="bx bx-desktop text-primary me-2"></i>
                                        Detail Monitoring
                                    </h5>
                                    <p class="text-muted mb-0">Informasi lengkap monitoring IDUKA</p>
                                </div>
                            </div>
                            <div class="btn-group">
                                <a href="{{ route('monitoring.edit', $monitoring->id) }}" class="btn btn-warning btn-sm">
                                    <i class="fas fa-edit me-1"></i> Edit
                                </a>
                                <button class="btn btn-danger btn-sm"
                                    onclick="confirmDelete({{ $monitoring->id }}, '{{ $monitoring->iduka->nama }}')">
                                    <i class="fas fa-trash me-1"></i> Hapus
                                </button>
                            </div>
                        </div>
                    </div>

                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                {{-- Informasi IDUKA --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold text-primary mb-3">
                                        <i class="bx bx-building me-2"></i>Informasi IDUKA
                                    </h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="30%" class="text-muted">Nama IDUKA</td>
                                            <td>: <strong>{{ $monitoring->iduka->nama ?? '-' }}</strong></td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Alamat</td>
                                            <td>: {{ $monitoring->iduka->alamat ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Telepon</td>
                                            <td>: {{ $monitoring->iduka->telepon ?? '-' }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Email</td>
                                            <td>: {{ $monitoring->iduka->email ?? '-' }}</td>
                                        </tr>
                                    </table>
                                </div>

                                {{-- Informasi Monitoring --}}
                                <div class="mb-4">
                                    <h6 class="fw-bold text-success mb-3">
                                        <i class="bx bx-calendar-check me-2"></i>Informasi Monitoring
                                    </h6>
                                    <table class="table table-borderless">
                                        <tr>
                                            <td width="40%" class="text-muted">Tanggal Input</td>
                                            <td>: {{ $monitoring->created_at->format('d F Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Terakhir Diperbarui</td>
                                            <td>: {{ $monitoring->updated_at->format('d F Y, H:i') }}</td>
                                        </tr>
                                        <tr>
                                            <td class="text-muted">Perkiraan Siswa</td>
                                            <td>:
                                                <span class="badge bg-info fs-6">
                                                    {{ $monitoring->perikiraan_siswa_diterima ?? 0 }} siswa
                                                </span>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>

                            {{-- Foto Monitoring (bisa banyak) --}}
                            <div class="mb-4">
                                <h6 class="fw-bold text-info mb-3">
                                    <i class="bx bx-camera me-2"></i>Foto Monitoring
                                </h6>

                                @php
                                    $fotos = json_decode($monitoring->foto, true);
                                @endphp

                                @if ($fotos && count($fotos) > 0)
                                    <div class="d-flex flex-wrap gap-2 justify-content-start">
                                        @foreach ($fotos as $foto)
                                            <div class="position-relative">
                                                <img src="{{ asset($foto) }}" alt="Foto Monitoring"
                                                    class="img-thumbnail shadow-sm"
                                                    style="width: 150px; height: 150px; object-fit: cover; cursor: pointer;"
                                                    onclick="showImageModal('{{ asset($foto) }}', '{{ $monitoring->iduka->nama ?? '-' }}')">
                                            </div>
                                        @endforeach
                                    </div>
                                    <p class="text-muted mt-2 mb-0"><small>Klik gambar untuk memperbesar</small></p>
                                @else
                                    <div class="text-center p-4 bg-light rounded">
                                        <i class="bx bx-image-alt fs-1 text-muted"></i>
                                        <p class="text-muted mt-2 mb-0">Tidak ada foto</p>
                                    </div>
                                @endif
                            </div>

                        </div>

                        {{-- Saran/Catatan --}}
                        <div class="mt-4">
                            <h6 class="fw-bold text-warning mb-3">
                                <i class="bx bx-comment-detail me-2"></i>Saran / Catatan Monitoring
                            </h6>
                            <div class="bg-light p-3 rounded">
                                @if ($monitoring->saran)
                                    <p class="mb-0">{{ $monitoring->saran }}</p>
                                @else
                                    <p class="text-muted mb-0 fst-italic">Tidak ada catatan</p>
                                @endif
                            </div>
                        </div>

                        {{-- Tombol Aksi --}}
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex gap-2 justify-content-end">
                                <a href="{{ route('monitoring.index') }}" class="btn btn-secondary">
                                    <i class="fas fa-list me-2"></i> Kembali ke Daftar
                                </a>
                                <a href="{{ route('monitoring.edit', $monitoring->id) }}" class="btn btn-warning">
                                    <i class="fas fa-edit me-2"></i> Edit Data
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Modal untuk menampilkan gambar besar --}}
    <div class="modal fade" id="imageModal" tabindex="-1">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="imageModalLabel">Foto Monitoring</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="" class="img-fluid rounded shadow">
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
        function showImageModal(imageUrl, title) {
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
