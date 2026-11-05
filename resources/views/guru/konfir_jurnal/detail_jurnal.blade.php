<div class="jurnal-detail">
    <div class="row">
        <div class="col-md-8">
            <!-- Student Information -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="bg-primary bg-opacity-10 rounded-circle p-2 me-3">
                            <i class="bi bi-person-fill text-primary"></i>
                        </div>
                        <div>
                            <h6 class="text-muted mb-1">Nama Siswa/Mahasiswa</h6>
                            <p class="fw-semibold mb-0">{{ $journal->user->name ?? 'Tidak diketahui' }}</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Activity Details -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-calendar3 me-1"></i> Tanggal Kegiatan
                            </h6>
                            <p class="mb-0">
                                {{ \Carbon\Carbon::parse($journal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                            </p>
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-clock me-1"></i> Waktu Kegiatan
                            </h6>
                            <p class="mb-0">
                                <span class="badge bg-primary">{{ $journal->jam_mulai }} -
                                    {{ $journal->jam_selesai }}</span>
                            </p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <h6 class="text-muted mb-1">
                            <i class="bi bi-file-text me-1"></i> Uraian Kegiatan
                        </h6>
                        <div class="bg-light p-3 rounded">
                            <p class="mb-0">{!! nl2br(e($journal->uraian)) !!}</p>
                        </div>
                    </div>

                    <!-- Tambahan field baru -->
                    <div class="row mb-3">
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-lightbulb me-1"></i> Termasuk Pengetahuan Baru
                            </h6>
                            @if ($journal->is_pengetahuan_baru)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </div>
                        <div class="col-6">
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-book me-1"></i> Kegiatan dalam Mapel Sekolah
                            </h6>
                            @if ($journal->is_dalam_mapel)
                                <span class="badge bg-success">Ya</span>
                            @else
                                <span class="badge bg-secondary">Tidak</span>
                            @endif
                        </div>
                    </div>

                    @if ($journal->rejected_reason)
                        <div>
                            <h6 class="text-muted mb-1">
                                <i class="bi bi-exclamation-triangle me-1"></i> Alasan Ditolak
                            </h6>
                            <div class="bg-danger bg-opacity-10 p-3 rounded border border-danger border-opacity-25">
                                <p class="mb-0 text-danger">{{ $journal->rejected_reason }}</p>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <!-- Validation Status -->
            <div class="card mb-3 border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-shield-check me-1"></i> Status Jurnal
                    </h6>

                    <div class="text-center mb-3">
                        @if ($journal->status == 'rejected')
                            <span class="badge bg-danger fs-6 p-2">❌ Ditolak</span>
                            @if ($journal->rejected_reason)
                                <div class="mt-2 small text-danger text-start">
                                    <i class="bi bi-info-circle"></i> Alasan: {{ $journal->rejected_reason }}
                                </div>
                            @endif
                        @elseif($journal->status == 'approved')
                            <!-- Tampilan baru seperti pada gambar -->
                            <button class="btn btn-success" disabled>
                                <i class="bi bi-check-circle-fill me-2"></i> DISETUJUI
                            </button>
                            <div class="mt-2 small text-success">
                                <i class="bi bi-check-circle"></i> Disetujui oleh:
                                @if ($journal->approved_pembimbing_at)
                                    Pembimbing
                                @elseif($journal->approved_iduka_at)
                                    IDUKA
                                @else
                                    Pembimbing
                                @endif
                            </div>
                        @else
                            <span class="badge bg-warning fs-6 p-2">⏳ Menunggu Persetujuan</span>
                        @endif
                    </div>

                    <h6 class="text-muted mb-3 mt-4">
                        <i class="bi bi-people me-1"></i> Detail Validasi
                    </h6>

                    <div class="d-grid gap-2">
                        @if ($journal->status == 'approved')
                            <!-- Jika sudah disetujui, tampilkan hanya yang sudah menyetujui -->
                            @if ($journal->validasi_pembimbing === 'sudah')
                                <div class="alert alert-success d-flex align-items-center mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <span>Disetujui Pembimbing</span>
                                </div>
                            @endif
                            @if ($journal->validasi_iduka === 'sudah')
                                <div class="alert alert-success d-flex align-items-center mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <span>Disetujui IDUKA</span>
                                </div>
                            @endif
                        @else
                            <!-- Jika belum disetujui, tampilkan status masing-masing -->
                            @if ($journal->validasi_pembimbing === 'sudah')
                                <div class="alert alert-success d-flex align-items-center mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <span>Disetujui Pembimbing</span>
                                </div>
                            @elseif($journal->validasi_pembimbing === 'ditolak')
                                <div class="alert alert-danger d-flex align-items-center mb-0">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    <span>Ditolak Pembimbing</span>
                                </div>
                            @else
                                <div class="alert alert-warning d-flex align-items-center mb-0">
                                    <i class="bi bi-clock-fill me-2"></i>
                                    <span>Menunggu Pembimbing</span>
                                </div>
                            @endif

                            @if ($journal->validasi_iduka === 'sudah')
                                <div class="alert alert-success d-flex align-items-center mb-0">
                                    <i class="bi bi-check-circle-fill me-2"></i>
                                    <span>Disetujui IDUKA</span>
                                </div>
                            @elseif($journal->validasi_iduka === 'ditolak')
                                <div class="alert alert-danger d-flex align-items-center mb-0">
                                    <i class="bi bi-x-circle-fill me-2"></i>
                                    <span>Ditolak IDUKA</span>
                                </div>
                            @else
                                <div class="alert alert-warning d-flex align-items-center mb-0">
                                    <i class="bi bi-clock-fill me-2"></i>
                                    <span>Menunggu IDUKA</span>
                                </div>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Documentation -->
            @if ($journal->foto)
                <div class="card mb-3 border-0 shadow-sm">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">
                            <i class="bi bi-camera me-1"></i> Dokumentasi Kegiatan
                        </h6>
                        <div class="text-center">
                            <img src="{{ asset($journal->foto) }}" alt="Dokumentasi kegiatan"
                                class="img-fluid rounded shadow-sm" style="max-height: 200px; cursor: pointer;"
                                onclick="showImageModal('{{ asset($journal->foto) }}')">
                        </div>
                    </div>
                </div>
            @endif


            <!-- Additional Info -->
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <h6 class="text-muted mb-3">
                        <i class="bi bi-info-circle me-1"></i> Informasi Tambahan
                    </h6>

                    <div class="d-flex align-items-center mb-2">
                        <i class="bi bi-calendar-plus text-muted me-2"></i>
                        <small class="text-muted">Ditambahkan:</small>
                    </div>
                    <p class="ms-4 mb-3">
                        {{ \Carbon\Carbon::parse($journal->created_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}
                    </p>

                    @if ($journal->updated_at != $journal->created_at)
                        <div class="d-flex align-items-center mb-2">
                            <i class="bi bi-arrow-clockwise text-muted me-2"></i>
                            <small class="text-muted">Diperbarui:</small>
                        </div>
                        <p class="ms-4 mb-0">
                            {{ \Carbon\Carbon::parse($journal->updated_at)->locale('id')->isoFormat('D MMMM YYYY HH:mm') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk menampilkan gambar besar -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Dokumentasi Kegiatan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center">
                <img id="modalImage" src="" alt="Dokumentasi Kegiatan" class="img-fluid">
            </div>
        </div>
    </div>
</div>

<script>
    function showImageModal(src) {
        document.getElementById('modalImage').src = src;
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>
