@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <!-- Header Section -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h4 class="fw-bold mb-1">Jurnal PKL</h4>
                <p class="text-muted mb-0">Daftar jurnal yang membutuhkan persetujuan</p>
            </div>
            <button class="btn btn-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createJournalModal">
                <i class="fas fa-plus-circle me-1"></i>
                Tambah Jurnal
            </button>
        </div>

        <!-- Tabs Navigation -->
        <ul class="nav nav-pills mb-4" role="tablist">
            <li class="nav-item" role="presentation">
                <button class="nav-link active d-flex align-items-center" id="pending-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-jurnal" type="button" role="tab">
                    <i class="fas fa-history me-2"></i>
                    <span>Menunggu Persetujuan</span>
                    @if($activeJurnals->count() > 0)
                        <span class="badge bg-warning text-dark ms-2">{{ $activeJurnals->count() }}</span>
                    @endif
                </button>
            </li>
            <li class="nav-item" role="presentation">
                <button class="nav-link d-flex align-items-center" id="history-tab" data-bs-toggle="tab"
                    data-bs-target="#tab-riwayat" type="button" role="tab">
                    <i class="fas fa-check-circle me-2"></i>
                    <span>Riwayat</span>
                </button>
            </li>
        </ul>

        <div class="tab-content">
            <!-- Tab Jurnal Aktif (Menunggu Persetujuan) -->
            <div class="tab-pane fade show active" id="tab-jurnal" role="tabpanel">
                @forelse($activeJurnals as $jurnal)
                    <div class="card shadow-sm mb-3 border-0 card-hover">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Date -->
                                    <div class="text-muted mb-2">
                                        <small>{{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="fw-bold mb-3">Kegiatan Harian - {{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('D MMMM YYYY') }}</h5>

                                    <!-- Description -->
                                    <p class="text-muted mb-3">{{ Str::limit($jurnal->uraian, 150) }}</p>

                                    <!-- Time and Photo Info -->
                                    <div class="d-flex align-items-center gap-4 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <span class="text-muted">{{ $jurnal->jam_mulai }} - {{ $jurnal->jam_selesai }}</span>
                                        </div>
                                        @if($jurnal->foto)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-camera text-primary me-2"></i>
                                                <span class="text-muted">Dengan foto</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Status Badge -->
                                    @if($jurnal->status === 'rejected')
                                        <span class="badge bg-danger px-3 py-2">
                                            <i class="fas fa-exclamation-circle me-1"></i>
                                            DITOLAK
                                        </span>
                                    @else
                                        <span class="badge bg-warning text-dark px-3 py-2">
                                            <i class="fas fa-history me-1"></i>
                                            MENUNGGU PERSETUJUAN
                                        </span>
                                    @endif
                                </div>

                                <!-- Action Buttons -->
                                <div class="col-md-4 d-flex align-items-center justify-content-end">
                                    <div class="d-flex gap-2">
                                        <button type="button" class="btn btn-outline-primary"
                                            onclick="showJournalDetail({{ $jurnal->id }})"
                                            title="Lihat Detail">
                                            Lihat Detail
                                        </button>
                                        <button type="button" class="btn btn-outline-secondary"
                                            onclick="alert('Fitur sedang diperbaiki, silakan coba lagi nanti.')"
                                            title="Edit">
                                            <i class="fas fa-edit"></i>
                                        </button>
                                        <button type="button" class="btn btn-outline-danger"
                                            onclick="confirmDelete({{ $jurnal->id }})"
                                            title="Hapus">
                                            <i class="fas fa-trash-alt"></i>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow-sm border-0 bg-light" style="border: 2px dashed #dee2e6 !important;">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-book-open text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                            <h5 class="fw-bold text-muted">Belum Ada Jurnal</h5>
                            <p class="text-muted mb-0">Kamu belum memiliki jurnal yang masuk kriteria ini.</p>
                        </div>
                    </div>
                @endforelse
            </div>

            <!-- Tab Riwayat -->
            <div class="tab-pane fade" id="tab-riwayat" role="tabpanel">
                @forelse($historyJurnals as $jurnal)
                    <div class="card shadow-sm mb-3 border-0 card-hover" style="border-left: 4px solid #22c55e !important;">
                        <div class="card-body p-4">
                            <div class="row">
                                <div class="col-md-8">
                                    <!-- Date -->
                                    <div class="text-muted mb-2">
                                        <small>{{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</small>
                                    </div>

                                    <!-- Title -->
                                    <h5 class="fw-bold mb-3">Kegiatan Harian - {{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('D MMMM YYYY') }}</h5>

                                    <!-- Description -->
                                    <p class="text-muted mb-3">{{ Str::limit($jurnal->uraian, 150) }}</p>

                                    <!-- Time and Photo Info -->
                                    <div class="d-flex align-items-center gap-4 mb-3">
                                        <div class="d-flex align-items-center">
                                            <i class="fas fa-clock text-primary me-2"></i>
                                            <span class="text-muted">{{ $jurnal->jam_mulai }} - {{ $jurnal->jam_selesai }}</span>
                                        </div>
                                        @if($jurnal->foto)
                                            <div class="d-flex align-items-center">
                                                <i class="fas fa-camera text-primary me-2"></i>
                                                <span class="text-muted">Dengan foto</span>
                                            </div>
                                        @endif
                                    </div>

                                    <!-- Status Badge -->
                                    <span class="badge bg-success px-3 py-2">
                                        <i class="fas fa-check-circle me-1"></i>
                                        DISETUJUI
                                    </span>
                                    @if($jurnal->approved_by)
                                        <small class="text-muted ms-2">Oleh: {{ $jurnal->approved_by }}</small>
                                    @endif
                                </div>

                                <!-- Action Button -->
                                <div class="col-md-4 d-flex align-items-center justify-content-end">
                                    <button type="button" class="btn btn-outline-primary"
                                        onclick="showJournalDetail({{ $jurnal->id }})"
                                        title="Lihat Detail">
                                        Lihat Detail
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="card shadow-sm border-0 bg-light" style="border: 2px dashed #dee2e6 !important;">
                        <div class="card-body text-center py-5">
                            <i class="fas fa-history text-muted mb-3" style="font-size: 4rem; opacity: 0.5;"></i>
                            <h5 class="fw-bold text-muted">Belum Ada Riwayat</h5>
                            <p class="text-muted mb-0">Buku riwayat jurnalmu masih kosong.</p>
                        </div>
                    </div>
                @endforelse
            </div>
        </div>
    </div>

    <!-- Create Journal Modal -->
    <div class="modal fade" id="createJournalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-plus-circle me-2"></i>Tambah Jurnal Baru
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <form action="{{ route('jurnal.store') }}" method="POST" enctype="multipart/form-data"
                    id="createJournalForm">
                    @csrf
                    <div class="modal-body p-4">
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    <i class="fas fa-calendar-alt me-1"></i>Tanggal
                                </label>
                                <input type="date" class="form-control form-control-lg" name="tgl"
                                    value="{{ old('tgl', date('Y-m-d')) }}" max="{{ date('Y-m-d') }}" required>
                                <small class="text-muted">Hanya tanggal hari ini dan sebelumnya yang dapat dipilih</small>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    <i class="fas fa-image me-1"></i>Foto Kegiatan
                                </label>
                                <input type="file" class="form-control form-control-lg" name="foto"
                                    accept="image/*" id="createFotoInput">
                                <small class="text-muted">Format: JPG, PNG, GIF (Max: 2MB)</small>
                            </div>
                        </div>

                        <div class="row g-3 mt-1">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    <i class="fas fa-clock me-1"></i>Jam Mulai
                                </label>
                                <input type="time" class="form-control form-control-lg" name="jam_mulai"
                                    value="{{ old('jam_mulai') }}" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                    <i class="fas fa-history me-1"></i>Jam Selesai
                                </label>
                                <input type="time" class="form-control form-control-lg" name="jam_selesai"
                                    value="{{ old('jam_selesai') }}" required>
                            </div>
                        </div>

                        <div class="mt-3">
                            <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                                <i class="fas fa-file-alt me-1"></i>Uraian Kegiatan
                            </label>
                            <textarea class="form-control form-control-lg" name="uraian" rows="5"
                                placeholder="Tuliskan uraian kegiatan yang dilakukan..." required>{{ old('uraian') }}</textarea>
                            <small class="text-muted">Maksimal 1000 karakter</small>
                        </div>

                        <div class="mt-4 p-3 bg-light rounded">
                            <div class="form-check mb-3">
                                <input type="checkbox" class="form-check-input" name="is_pengetahuan_baru"
                                    id="is_pengetahuan_baru" value="1"
                                    {{ old('is_pengetahuan_baru') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_pengetahuan_baru">
                                    <i class="fas fa-lightbulb text-warning text-warning me-1"></i>
                                    Termasuk pengetahuan baru
                                </label>
                            </div>

                            <div class="form-check">
                                <input type="checkbox" class="form-check-input" name="is_dalam_mapel"
                                    id="is_dalam_mapel" value="1" {{ old('is_dalam_mapel') ? 'checked' : '' }}>
                                <label class="form-check-label fw-semibold" for="is_dalam_mapel">
                                    <i class="fas fa-book-open text-primary text-primary me-1"></i>
                                    Kegiatan ada dalam mapel sekolah
                                </label>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-primary px-4 shadow-sm">
                            <i class="fas fa-save me-1"></i>Simpan Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- View Journal Modal -->
    <div class="modal fade" id="viewJournalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-info text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-eye me-2"></i>Detail Jurnal
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4" id="journalDetailContent">
                    <!-- Content will be loaded here -->
                </div>
            </div>
        </div>
    </div>

    <!-- Edit Journal Modal -->
    <div class="modal fade" id="editJournalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-warning text-dark">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-edit me-2"></i>Edit Jurnal
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editJournalForm" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div class="modal-body p-4" id="editJournalContent">
                        <!-- Content will be loaded via AJAX -->
                        <div class="text-center py-5">
                            <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-3 text-muted">Memuat data jurnal...</p>
                        </div>
                    </div>
                    <div class="modal-footer bg-light">
                        <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                            <i class="fas fa-times-circle me-1"></i>Batal
                        </button>
                        <button type="submit" class="btn btn-warning text-dark px-4 shadow-sm fw-semibold">
                            <i class="fas fa-save me-1"></i>Update Jurnal
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal fade" id="deleteJournalModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-danger text-white border-0">
                    <h5 class="modal-title fw-bold">
                        <i class="fas fa-exclamation-triangle me-2"></i>Konfirmasi Hapus
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <div class="text-center mb-3">
                        <i class="fas fa-trash-alt3-fill text-danger" style="font-size: 4rem;"></i>
                    </div>
                    <p class="text-center mb-0 fs-5">Apakah Anda yakin ingin menghapus jurnal ini?</p>
                    <p class="text-center text-muted mt-2">Data yang dihapus tidak dapat dikembalikan.</p>
                </div>
                <div class="modal-footer bg-light border-0">
                    <button type="button" class="btn btn-secondary px-4" data-bs-dismiss="modal">
                        <i class="fas fa-times-circle me-1"></i>Batal
                    </button>
                    <form id="deleteJournalForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger px-4 shadow-sm fw-semibold">
                            <i class="fas fa-trash-alt3 me-1"></i>Ya, Hapus
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <style>
        /* Nav Pills Styling */
        .nav-pills .nav-link {
            color: #6c757d;
            background-color: transparent;
            border-radius: 8px;
            padding: 0.75rem 1.5rem;
            font-weight: 500;
            transition: all 0.3s ease;
        }

        .nav-pills .nav-link:hover {
            background-color: #f8f9fa;
        }

        .nav-pills .nav-link.active {
            color: #fff;
            background-color: #0d6efd;
        }

        .nav-pills .nav-link .badge {
            font-size: 0.75rem;
            padding: 0.25rem 0.5rem;
        }

        /* Card Styling */
        .card {
            border-radius: 12px;
            transition: transform 0.2s ease, box-shadow 0.2s ease;
        }

        .card:hover {
            transform: translateY(-2px);
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1) !important;
        }

        /* Badge Styling */
        .badge {
            font-weight: 600;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            border-radius: 6px;
        }

        /* Button Styling */
        .btn {
            font-weight: 500;
            transition: all 0.3s ease;
            border-radius: 8px;
        }

        .btn:hover {
            transform: translateY(-2px);
        }

        .btn-outline-primary:hover {
            background-color: #0d6efd;
            border-color: #0d6efd;
            color: #fff;
        }

        .btn-outline-secondary:hover {
            background-color: #6c757d;
            border-color: #6c757d;
            color: #fff;
        }

        .btn-outline-danger:hover {
            background-color: #dc3545;
            border-color: #dc3545;
            color: #fff;
        }

        /* Modal Styling */
        .modal-content {
            border-radius: 15px;
            overflow: hidden;
        }

        .modal-header {
            padding: 1.25rem 1.5rem;
        }

        .form-control:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 0.2rem rgba(99, 102, 241, 0.25);
        }

        .form-control-lg {
            padding: 0.75rem 1rem;
            font-size: 1rem;
        }

        .form-check-input:checked {
            background-color: #6366f1;
            border-color: #6366f1;
        }

        /* Shadow Utilities */
        .shadow-sm {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075) !important;
        }

        .shadow {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15) !important;
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .card-body .row {
                flex-direction: column;
            }

            .card-body .col-md-4 {
                margin-top: 1rem;
                justify-content: flex-start !important;
            }

            .d-flex.gap-2 {
                width: 100%;
            }

            .d-flex.gap-2 .btn {
                flex: 1;
            }
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Menambahkan CSRF token ke header AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            // Validasi form create
            const createForm = document.getElementById('createJournalForm');
            if (createForm) {
                createForm.addEventListener('submit', function(e) {
                    const jamMulai = this.querySelector('input[name="jam_mulai"]').value;
                    const jamSelesai = this.querySelector('input[name="jam_selesai"]').value;

                    if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Jam selesai harus lebih dari jam mulai.',
                            confirmButtonColor: '#6366f1'
                        });
                        return false;
                    }
                });
            }

            // Function to show journal detail
            window.showJournalDetail = function(id) {
                const modal = new bootstrap.Modal(document.getElementById('viewJournalModal'));
                const modalBody = document.getElementById('journalDetailContent');

                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-info" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat detail jurnal...</p>
                    </div>
                `;

                modal.show();

                fetch(`/jurnal/${id}/show`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'application/json'
                        }
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            modalBody.innerHTML = data.data;
                        } else {
                            throw new Error(data.message || 'Failed to load journal details');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalBody.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            ${error.message}
                            <button onclick="showJournalDetail(${id})" class="btn btn-sm btn-outline-danger mt-2 d-block">
                                <i class="fas fa-sync-alt me-1"></i>Coba Lagi
                            </button>
                        </div>
                    `;
                    });
            };

            // Function to edit journal
            window.editJournal = function(id) {
                const modal = new bootstrap.Modal(document.getElementById('editJournalModal'));
                const modalBody = document.getElementById('editJournalContent');
                const form = document.getElementById('editJournalForm');

                modalBody.innerHTML = `
                    <div class="text-center py-5">
                        <div class="spinner-border text-warning" role="status" style="width: 3rem; height: 3rem;">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                        <p class="mt-3 text-muted">Memuat data jurnal...</p>
                    </div>
                `;

                form.action = `/jurnal/${id}`;
                modal.show();

                fetch(`/jurnal/${id}/edit`, {
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Accept': 'text/html'
                        }
                    })
                    .then(response => response.text())
                    .then(html => {
                        modalBody.innerHTML = html;
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        modalBody.innerHTML = `
                        <div class="alert alert-danger">
                            <i class="fas fa-exclamation-triangle me-2"></i>
                            Gagal memuat data jurnal. Silakan coba lagi.
                            <button onclick="editJournal(${id})" class="btn btn-sm btn-outline-danger mt-2 d-block">
                                <i class="fas fa-sync-alt me-1"></i>Coba Lagi
                            </button>
                        </div>
                    `;
                    });
            };

            // PERBAIKAN: Validasi dan kirim form edit via AJAX
            const editForm = document.getElementById('editJournalForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    e.preventDefault(); // Cegah pengiriman form default

                    const jamMulai = this.querySelector('input[name="jam_mulai"]').value;
                    const jamSelesai = this.querySelector('input[name="jam_selesai"]').value;

                    if (jamMulai && jamSelesai && jamSelesai <= jamMulai) {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Perhatian!',
                            text: 'Jam selesai harus lebih dari jam mulai.',
                            confirmButtonColor: '#6366f1'
                        });
                        return false;
                    }

                    // Siapkan data untuk dikirim via AJAX
                    const formData = new FormData(this);
                    const submitButton = this.querySelector('button[type="submit"]');
                    const originalButtonText = submitButton.innerHTML;

                    // Tampilkan indikator loading
                    submitButton.disabled = true;
                    submitButton.innerHTML =
                        '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Menyimpan...';

                    // Debug: Log form data
                    console.log('Submitting form with data:');
                    for (let pair of formData.entries()) {
                        console.log(pair[0] + ': ' + pair[1]);
                    }

                    fetch(this.action, {
                            method: 'POST',
                            body: formData,
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')
                                    .getAttribute('content')
                            }
                        })
                        .then(response => {
                            // Debug: Log response status
                            console.log('Response status:', response.status);

                            // Jika response adalah redirect, itu berarti berhasil
                            if (response.redirected) {
                                return {
                                    success: true,
                                    redirect: response.url
                                };
                            }
                            return response.json();
                        })
                        .then(data => {
                            // Debug: Log response data
                            console.log('Response data:', data);

                            if (data.success || data.redirect) {
                                // Tutup modal
                                const modal = bootstrap.Modal.getInstance(document.getElementById(
                                    'editJournalModal'));
                                modal.hide();

                                // Tampilkan pesan sukses
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: 'Jurnal berhasil diperbarui.',
                                    confirmButtonColor: '#6366f1',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    // Muat ulang halaman untuk menampilkan data yang diperbarui
                                    window.location.reload();
                                });
                            } else {
                                // Tangani error validasi atau error lainnya
                                let errorMessage = 'Terjadi kesalahan saat memperbarui jurnal.';
                                if (data.message) {
                                    errorMessage = data.message;
                                } else if (data.errors) {
                                    // Tampilkan error validasi spesifik
                                    const firstError = Object.values(data.errors)[0][0];
                                    errorMessage = firstError;
                                }

                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: errorMessage,
                                    confirmButtonColor: '#6366f1'
                                });
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            Swal.fire({
                                icon: 'error',
                                title: 'Gagal!',
                                text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                                confirmButtonColor: '#6366f1'
                            });
                        })
                        .finally(() => {
                            // Kembalikan tombol ke keadaan semula
                            submitButton.disabled = false;
                            submitButton.innerHTML = originalButtonText;
                        });
                });
            }

            // Function to confirm delete
            window.confirmDelete = function(id) {
                const modal = new bootstrap.Modal(document.getElementById('deleteJournalModal'));
                const form = document.getElementById('deleteJournalForm');
                form.action = `/jurnal/${id}`;
                modal.show();
            };

            // Handle tab persistence
            const hash = window.location.hash;
            if (hash) {
                const tabTrigger = document.querySelector(`button[data-bs-target="${hash}"]`);
                if (tabTrigger) {
                    const tab = new bootstrap.Tab(tabTrigger);
                    tab.show();
                }
            }

            // Update URL when tab changes
            document.querySelectorAll('button[data-bs-toggle="tab"]').forEach(tabButton => {
                tabButton.addEventListener('shown.bs.tab', function(e) {
                    const target = e.target.getAttribute('data-bs-target');
                    history.pushState(null, null, target);
                });
            });
        });
    </script>
@endsection

