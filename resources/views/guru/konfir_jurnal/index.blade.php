@extends('layout.main')

@section('content')
    <div class="container-fluid py-3 px-2 px-md-4">
        <div class="container-xxl flex-grow-1 container-p-y">

            {{-- ===== PAGE HEADER ===== --}}
            <div class="mb-3 mb-md-4">
                <h3 class="fw-bold page-title mb-1">
                    <i class="fas fa-clipboard-check text-primary me-2"></i>
                    Persetujuan Jurnal - {{ Auth::user()->role === 'kaprog' ? 'Kepala Program' : 'Guru/Pembimbing' }}
                </h3>
                <p class="text-muted mb-3 mb-md-4 small">Daftar jurnal yang membutuhkan persetujuan</p>

                {{-- Tab Navigation --}}
                <ul class="nav nav-tabs nav-tabs-responsive mb-3 mb-md-4">
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('approval.index') ? 'active' : '' }}"
                            href="{{ route('approval.index') }}">
                            <i class="fas fa-clock me-1"></i>
                            <span class="d-none d-sm-inline">Menunggu Persetujuan</span>
                            <span class="d-inline d-sm-none">Menunggu</span>
                            <span class="badge bg-primary rounded-pill ms-1">{{ $jurnals->total() }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link {{ Request::routeIs('approval.riwayat') ? 'active' : '' }}"
                            href="{{ route('approval.riwayat') }}">
                            <i class="fas fa-history me-1"></i> Riwayat
                        </a>
                    </li>
                </ul>
            </div>

            {{-- Alert Messages --}}
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <strong>Berhasil!</strong> {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <strong>Error!</strong> {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            {{-- ===== CARD LIST JURNAL ===== --}}
            <div class="card shadow-sm border-0 rounded-4 p-3 p-md-4">
                @if ($jurnals->count() > 0)

                    {{-- Info jumlah data --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 mb-3">
                        <small class="text-muted">
                            Menampilkan <strong>{{ $jurnals->firstItem() }}</strong> -
                            <strong>{{ $jurnals->lastItem() }}</strong>
                            dari <strong>{{ $jurnals->total() }}</strong> jurnal
                        </small>
                    </div>

                    <div class="row g-3 g-md-4">
                        @foreach ($jurnals as $jurnal)
                            <div class="col-12 col-md-6 col-lg-4">
                                <div class="card h-100 border-0 rounded-4 shadow-sm card-hover">
                                    <div class="card-body d-flex flex-column p-3 p-md-4">

                                        <div class="mb-2">
                                            <small class="text-muted">
                                                <i class="fas fa-calendar-alt me-1"></i>
                                                {{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                            </small>
                                        </div>

                                        <h6 class="fw-semibold mb-2">
                                            Kegiatan Harian - {{ $jurnal->user ? $jurnal->user->name : 'User tidak ditemukan' }}
                                        </h6>

                                        <p class="text-truncate-3 text-muted mb-3 small">
                                            {{ Str::limit($jurnal->uraian, 120) }}
                                        </p>

                                        <div class="d-flex align-items-center gap-3 mb-2 small text-muted flex-wrap">
                                            <span>
                                                <i class="fas fa-clock me-1"></i>
                                                {{ $jurnal->jam_mulai }} - {{ $jurnal->jam_selesai }}
                                            </span>
                                            @if ($jurnal->foto)
                                                <span>
                                                    <i class="fas fa-image me-1"></i> Dengan foto
                                                </span>
                                            @endif
                                        </div>

                                        <div class="mb-3">
                                            <span class="badge bg-warning text-dark">
                                                <i class="fas fa-hourglass-half me-1"></i> Menunggu Persetujuan
                                            </span>
                                        </div>

                                        {{-- Tombol Aksi --}}
                                        <div class="mt-auto d-flex flex-column flex-sm-row gap-2 action-buttons">
                                            <button type="button"
                                                class="btn btn-sm btn-outline-primary rounded-pill px-3 flex-fill view-detail"
                                                data-id="{{ $jurnal->id }}">
                                                <i class="fas fa-eye me-1"></i> Detail
                                            </button>

                                            <form action="{{ route('approval.approve', $jurnal->id) }}" method="POST"
                                                class="approve-form flex-fill d-flex">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm btn-success rounded-pill px-3 shadow-sm w-100"
                                                    onclick="return confirmApproval('{{ $jurnal->user ? $jurnal->user->name : 'User' }}')">
                                                    <i class="fas fa-check me-1"></i> Setujui
                                                </button>
                                            </form>

                                            <button type="button"
                                                class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm flex-fill"
                                                data-bs-toggle="modal"
                                                data-bs-target="#rejectModal{{ $jurnal->id }}">
                                                <i class="fas fa-times me-1"></i> Tolak
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Modal Tolak -->
                            <div class="modal fade" id="rejectModal{{ $jurnal->id }}" tabindex="-1" aria-hidden="true">
                                <div class="modal-dialog modal-dialog-centered">
                                    <div class="modal-content border-0 shadow">
                                        <div class="modal-header">
                                            <h5 class="modal-title">Alasan Penolakan</h5>
                                            <button type="button" class="btn-close" data-bs-dismiss="modal"
                                                aria-label="Close"></button>
                                        </div>
                                        <form action="{{ route('approval.reject', $jurnal->id) }}" method="POST"
                                            class="reject-form">
                                            @csrf
                                            <div class="modal-body">
                                                <div class="mb-0">
                                                    <label class="form-label">
                                                        Alasan Penolakan <span class="text-danger">*</span>
                                                    </label>
                                                    <textarea class="form-control" name="rejected_reason" rows="3"
                                                        placeholder="Masukkan alasan penolakan..." required></textarea>
                                                    <div class="invalid-feedback">
                                                        Alasan penolakan wajib diisi.
                                                    </div>
                                                </div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-light"
                                                    data-bs-dismiss="modal">Batal</button>
                                                <button type="submit"
                                                    class="btn btn-danger rounded-pill px-4 shadow-sm">
                                                    Tolak Jurnal
                                                </button>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    {{-- ===== PAGINATION ===== --}}
                    @if ($jurnals->hasPages())
                        <div class="mt-4 pt-3 border-top">
                            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center gap-3">
                                <small class="text-muted text-center text-md-start">
                                    Halaman <strong>{{ $jurnals->currentPage() }}</strong>
                                    dari <strong>{{ $jurnals->lastPage() }}</strong>
                                </small>
                                <nav aria-label="Pagination jurnal" class="pagination-wrap">
                                    {{ $jurnals->onEachSide(1)->links('pagination::bootstrap-5') }}
                                </nav>
                            </div>
                        </div>
                    @endif

                @else
                    {{-- Empty State --}}
                    <div class="text-center py-5">
                        <div class="empty-icon mb-3">
                            <i class="fas fa-clipboard-check"></i>
                        </div>
                        <h5 class="fw-bold mt-3">Tidak ada jurnal yang perlu disetujui</h5>
                        <p class="text-muted mb-3">Semua jurnal telah diproses.</p>
                        <a href="{{ route('approval.riwayat') }}" class="btn btn-primary rounded-pill px-4">
                            <i class="fas fa-history me-1"></i> Lihat Riwayat Persetujuan
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal Detail Jurnal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Detail Jurnal</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body" id="modalBody">
                    <!-- Konten akan diisi oleh JavaScript -->
                </div>
            </div>
        </div>
    </div>

    <!-- Modal untuk menampilkan gambar -->
    <div class="modal fade" id="imageModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Foto Kegiatan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <img id="modalImage" src="" alt="Foto Kegiatan" class="img-fluid rounded">
                </div>
            </div>
        </div>
    </div>

    <style>
        /* ===========================
           BASE STYLES
        =========================== */
        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }

        .card-hover {
            transition: transform .2s ease, box-shadow .2s ease;
        }
        .card-hover:hover {
            transform: translateY(-2px);
            box-shadow: 0 .5rem 1rem rgba(0, 0, 0, .08) !important;
        }

        .empty-icon {
            width: 80px;
            height: 80px;
            margin: 0 auto;
            background: #ede9fe;
            border-radius: 20px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .empty-icon i {
            font-size: 36px;
            color: #7c3aed;
        }

        /* ===========================
           PAGINATION CUSTOM
        =========================== */
        .pagination-wrap .pagination {
            margin-bottom: 0;
            flex-wrap: wrap;
            gap: 4px;
        }
        .pagination-wrap .page-link {
            border-radius: 8px !important;
            border: 1px solid #e2e8f0;
            color: #4c1d95;
            font-weight: 500;
            font-size: 13px;
            padding: 6px 12px;
            transition: all .15s;
            min-width: 36px;
            text-align: center;
        }
        .pagination-wrap .page-link:hover {
            background: #ede9fe;
            border-color: #c4b5fd;
            color: #4c1d95;
        }
        .pagination-wrap .page-item.active .page-link {
            background: linear-gradient(135deg, #6c63ff, #7c3aed);
            border-color: #6c63ff;
            color: #fff;
            box-shadow: 0 2px 8px rgba(108, 99, 255, .35);
        }
        .pagination-wrap .page-item.disabled .page-link {
            color: #cbd5e1;
            background: #f8fafc;
        }

        /* ===========================
           TAB RESPONSIVE
        =========================== */
        .nav-tabs-responsive {
            border-bottom: 2px solid #ede9fe;
            flex-wrap: nowrap;
            overflow-x: auto;
            overflow-y: hidden;
            -webkit-overflow-scrolling: touch;
            scrollbar-width: none;
        }
        .nav-tabs-responsive::-webkit-scrollbar {
            display: none;
        }
        .nav-tabs-responsive .nav-item {
            flex-shrink: 0;
        }
        .nav-tabs-responsive .nav-link {
            white-space: nowrap;
            font-size: 14px;
            border: none;
            color: #64748b;
            padding: 10px 16px;
            border-bottom: 2px solid transparent;
            margin-bottom: -2px;
            transition: all .2s;
        }
        .nav-tabs-responsive .nav-link:hover {
            color: #4c1d95;
            background: #f5f3ff;
            border-radius: 8px 8px 0 0;
        }
        .nav-tabs-responsive .nav-link.active {
            color: #4c1d95 !important;
            font-weight: 600;
            border-bottom: 2px solid #6c63ff !important;
            background: transparent;
        }

        /* ===========================
           ACTION BUTTONS
        =========================== */
        .action-buttons .btn {
            white-space: nowrap;
        }

        /* ===========================
           PAGE TITLE
        =========================== */
        .page-title {
            font-size: 20px;
            color: #1e1b4b;
            line-height: 1.3;
        }

        /* ============================================================ */
        /* ===== MOBILE RESPONSIVE =====                                  */
        /* ============================================================ */

        /* === Tablet & Mobile (≤ 991px) === */
        @media (max-width: 991.98px) {
            .pagination-wrap {
                width: 100%;
                display: flex;
                justify-content: center;
            }
            .pagination-wrap .pagination {
                justify-content: center;
            }
        }

        /* === Mobile (≤ 767px) === */
        @media (max-width: 767.98px) {
            .page-title {
                font-size: 16px;
            }

            /* Tombol aksi full-width stacking */
            .action-buttons {
                gap: 6px !important;
            }
            .action-buttons .btn {
                font-size: 13px;
                padding: .45rem .75rem;
            }

            /* Card body padding lebih nyaman */
            .card-body { padding: 1rem !important; }

            /* Tabs lebih compact */
            .nav-tabs-responsive .nav-link {
                font-size: 12.5px;
                padding: 8px 12px;
            }

            /* Modal lebih ramah */
            .modal-dialog { margin: .5rem; }
            .modal-header { padding: .85rem 1rem; }
            .modal-body { padding: .9rem 1rem; }
            .modal-footer { padding: .75rem 1rem; gap: 6px; }
            .modal-footer .btn { flex: 1; }

            /* Pagination lebih compact */
            .pagination-wrap .page-link {
                padding: 5px 10px;
                font-size: 12px;
                min-width: 32px;
            }
        }

        /* === Extra small (≤ 575px) === */
        @media (max-width: 575.98px) {
            .page-title {
                font-size: 15px;
            }

            .nav-tabs-responsive .nav-link {
                font-size: 12px;
                padding: 7px 10px;
            }
            .nav-tabs-responsive .nav-link .badge {
                font-size: 10px;
                padding: 2px 6px;
            }

            /* Sembunyikan beberapa tombol pagination kalau terlalu panjang */
            .pagination-wrap .pagination .page-item:not(.active):not(:first-child):not(:last-child) {
                display: none;
            }
            .pagination-wrap .pagination .page-item:first-child,
            .pagination-wrap .pagination .page-item:last-child,
            .pagination-wrap .pagination .page-item.active {
                display: inline-block;
            }

            .empty-icon {
                width: 64px;
                height: 64px;
            }
            .empty-icon i { font-size: 28px; }

            /* Card body lebih compact */
            .card-body { padding: .85rem !important; }
        }
    </style>

    <script>
        function confirmApproval(userName) {
            return confirm(`Apakah Anda yakin ingin menyetujui jurnal dari ${userName}?`);
        }

        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        document.addEventListener('DOMContentLoaded', function() {
            let activeModal = null;

            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const jurnalId = this.getAttribute('data-id');
                    const modalBody = document.getElementById('modalBody');
                    const modalElement = document.getElementById('detailModal');

                    if (activeModal) {
                        activeModal.hide();
                    }

                    modalBody.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2 small text-muted">Memuat detail jurnal...</p>
                        </div>
                    `;

                    modalElement.removeAttribute('aria-hidden');
                    modalElement.setAttribute('aria-modal', 'true');

                    activeModal = new bootstrap.Modal(modalElement);
                    activeModal.show();

                    fetch(`/approval/${jurnalId}/detail`, {
                            method: 'GET',
                            headers: {
                                'X-Requested-With': 'XMLHttpRequest',
                                'Accept': 'application/json',
                                'X-CSRF-TOKEN': getCsrfToken()
                            },
                            credentials: 'same-origin'
                        })
                        .then(response => {
                            if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                modalBody.innerHTML = data.data;
                                initializeModalEvents();
                            } else {
                                throw new Error(data.message || 'Gagal memuat detail jurnal');
                            }
                        })
                        .catch(error => {
                            console.error('Error:', error);
                            modalBody.innerHTML = `
                                <div class="alert alert-danger m-3">
                                    <i class="bi bi-exclamation-triangle me-2"></i>
                                    <strong>Error:</strong> ${error.message}
                                    <div class="mt-3">
                                        <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="retryFetch(${jurnalId})">
                                            <i class="bi bi-arrow-clockwise me-1"></i> Coba Lagi
                                        </button>
                                    </div>
                                </div>
                            `;
                        });
                });
            });

            document.getElementById('detailModal').addEventListener('hidden.bs.modal', function() {
                this.setAttribute('aria-hidden', 'true');
                this.removeAttribute('aria-modal');
                activeModal = null;
            });
        });

        function retryFetch(jurnalId) {
            const modalBody = document.getElementById('modalBody');

            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2 small text-muted">Memuat ulang detail jurnal...</p>
                </div>
            `;

            fetch(`/approval/${jurnalId}/detail`, {
                    method: 'GET',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': getCsrfToken()
                    },
                    credentials: 'same-origin'
                })
                .then(response => {
                    if (!response.ok) throw new Error(`HTTP error! status: ${response.status}`);
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        modalBody.innerHTML = data.data;
                        initializeModalEvents();
                    } else {
                        throw new Error(data.message || 'Gagal memuat detail jurnal');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    modalBody.innerHTML = `
                        <div class="alert alert-danger m-3">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>Error:</strong> ${error.message}
                            <div class="mt-3">
                                <button type="button" class="btn btn-outline-danger btn-sm rounded-pill px-3" onclick="retryFetch(${jurnalId})">
                                    <i class="bi bi-arrow-clockwise me-1"></i> Coba Lagi
                                </button>
                                <button type="button" class="btn btn-secondary btn-sm ms-2" data-bs-dismiss="modal">
                                    Tutup
                                </button>
                            </div>
                        </div>
                    `;
                });
        }

        function initializeModalEvents() {
            document.querySelectorAll('#detailModal img[onclick^="showImageModal"]').forEach(img => {
                img.addEventListener('click', function() {
                    showImageModal(this.src);
                });
            });

            const tooltipTriggerList = [].slice.call(document.querySelectorAll('#detailModal [data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        function showImageModal(src) {
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            imageModal.removeAttribute('aria-hidden');
            imageModal.setAttribute('aria-modal', 'true');
            modalImage.src = src;

            const modal = new bootstrap.Modal(imageModal);
            modal.show();

            imageModal.addEventListener('hidden.bs.modal', function() {
                this.setAttribute('aria-hidden', 'true');
                this.removeAttribute('aria-modal');
                modalImage.src = '';
            }, { once: true });
        }

        // Handle form submissions
        const approveForms = document.querySelectorAll('.approve-form');
        approveForms.forEach(form => {
            form.addEventListener('submit', function() {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
            });
        });

        const rejectForms = document.querySelectorAll('.reject-form');
        rejectForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const textarea = this.querySelector('textarea[name="rejected_reason"]');
                if (!textarea.value.trim()) {
                    e.preventDefault();
                    textarea.classList.add('is-invalid');
                    return false;
                }

                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
            });
        });

        document.querySelectorAll('textarea[name="rejected_reason"]').forEach(textarea => {
            textarea.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        setTimeout(function() {
            document.querySelectorAll('.alert').forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>
@endsection
