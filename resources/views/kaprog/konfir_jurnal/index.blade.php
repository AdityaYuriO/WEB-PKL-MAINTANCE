@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="mb-4">
            {{-- PERUBAHAN: Judul dinamis berdasarkan role --}}
            <h3 class="fw-bold">Persetujuan Jurnal - {{ Auth::user()->role === 'kaprog' ? 'Kepala Program' : 'Guru/Pembimbing' }} ??</h3>
            <p class="text-muted">Daftar jurnal yang membutuhkan persetujuan</p>

            {{-- Tab Navigation --}}
            <ul class="nav nav-tabs mb-4">
                <li class="nav-item">
                    <a class="nav-link {{ Request::routeIs('approval.index') ? 'active' : '' }}"
                        href="{{ route('approval.index') }}">
                        <i class="fas fa-clock me-1"></i> Menunggu Persetujuan
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

        <div class="card shadow-sm p-4">
            @if ($jurnals->count() > 0)
                <div class="row g-4">
                    @foreach ($jurnals as $jurnal)
                        <div class="col-md-6 col-lg-4">
                            <div class="card h-100 border-0 rounded-4 shadow-sm card-hover">
                                <div class="card-body d-flex flex-column">
                                    <div class="mb-2">
                                        <small class="text-muted">
                                            {{ \Carbon\Carbon::parse($jurnal->tgl)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}
                                        </small>
                                    </div>
                                    <h6 class="fw-semibold">
                                        Kegiatan Harian - {{ $jurnal->user ? $jurnal->user->name : 'User tidak ditemukan' }}
                                    </h6>
                                    <p class="text-truncate-3 text-muted mb-3">
                                        {{ Str::limit($jurnal->uraian, 120) }}
                                    </p>
                                    <div class="d-flex align-items-center gap-3 mb-3 small text-muted">
                                        <span> {{ $jurnal->jam_mulai }} - {{ $jurnal->jam_selesai }}</span>
                                        @if ($jurnal->foto)
                                            <span>?? Dengan foto</span>
                                        @endif
                                    </div>
                                    <div class="mb-3">
                                        {{-- PERUBAHAN: Tampilkan status tunggal --}}
                                        <span class="badge bg-warning text-dark">? Menunggu Persetujuan</span>
                                    </div>
                                    <div class="mt-auto d-flex gap-2">
                                        <button type="button" class="btn btn-sm btn-outline-primary rounded-pill px-3 view-detail"
                                            data-id="{{ $jurnal->id }}">
                                            Lihat Detail
                                        </button>

                                        {{-- PERUBAHAN: Gunakan route yang sesuai --}}
                                        <form action="{{ route('approval.approve', $jurnal->id) }}" method="POST"
                                            class="d-inline approve-form">
                                            @csrf
                                            <button type="submit" class="btn btn-sm btn-success rounded-pill px-3 shadow-sm"
                                                onclick="return confirmApproval('{{ $jurnal->user ? $jurnal->user->name : 'User' }}')">
                                                Setujui
                                            </button>
                                        </form>

                                        <button type="button" class="btn btn-sm btn-danger rounded-pill px-3 shadow-sm" data-bs-toggle="modal"
                                            data-bs-target="#rejectModal{{ $jurnal->id }}">
                                            Tolak
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Modal Tolak -->
                        <div class="modal fade" id="rejectModal{{ $jurnal->id }}" tabindex="-1" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content border-0 shadow">
                                    <div class="modal-header">
                                        <h5 class="modal-title">Alasan Penolakan</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal"
                                            aria-label="Close"></button>
                                    </div>
                                    {{-- PERUBAHAN: Gunakan route yang sesuai --}}
                                    <form action="{{ route('approval.reject', $jurnal->id) }}" method="POST"
                                        class="reject-form">
                                        @csrf
                                        <div class="modal-body">
                                            <div class="mb-3">
                                                <label class="form-label">Alasan Penolakan <span
                                                        class="text-danger">*</span></label>
                                                <textarea class="form-control" name="rejected_reason" rows="3" placeholder="Masukkan alasan penolakan..."
                                                    required></textarea>
                                                <div class="invalid-feedback">
                                                    Alasan penolakan wajib diisi.
                                                </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light"
                                                data-bs-dismiss="modal">Batal</button>
                                            <button type="submit" class="btn btn-danger rounded-pill px-4 shadow-sm">Tolak Jurnal</button>
                                        </div>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>

                 <div class="card-footer bg-transparent border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <small class="text-muted">
                        Menampilkan {{ $jurnals->firstItem() }} - {{ $jurnals->lastItem() }} dari {{ $jurnals->total() }} data
                    </small>
                    <nav>
                        {{ $jurnals->links('pagination::bootstrap-5') }}
                    </nav>
                </div>
            </div>
            @else
                <div class="text-center py-5">
                    <div style="font-size: 50px;"></div>
                    <h5 class="fw-bold mt-3">Tidak ada jurnal yang perlu disetujui</h5>
                    <p class="text-muted">Semua jurnal telah diproses.</p>
                    {{-- PERUBAHAN: Gunakan route yang sesuai --}}
                    <a href="{{ route('approval.riwayat') }}" class="btn btn-primary mt-2">
                        Lihat Riwayat Persetujuan
                    </a>
                </div>
            @endif
        </div>
    </div>

    <!-- Modal Detail Jurnal -->
    <div class="modal fade" id="detailModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg">
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
                    <img id="modalImage" src="" alt="Foto Kegiatan" class="img-fluid">
                </div>
            </div>
        </div>
    </div>

    <script>
        function confirmApproval(userName) {
            return confirm(`Apakah Anda yakin ingin menyetujui jurnal dari ${userName}?`);
        }

        // Handle modal detail
        function getCsrfToken() {
            return document.querySelector('meta[name="csrf-token"]').getAttribute('content');
        }

        document.addEventListener('DOMContentLoaded', function() {
            // Simpan instance modal yang sedang aktif
            let activeModal = null;

            document.querySelectorAll('.view-detail').forEach(button => {
                button.addEventListener('click', function() {
                    const jurnalId = this.getAttribute('data-id');
                    const modalBody = document.getElementById('modalBody');
                    const modalElement = document.getElementById('detailModal');

                    // Jika modal sudah aktif, tutup dulu
                    if (activeModal) {
                        activeModal.hide();
                    }

                    // Show loading spinner
                    modalBody.innerHTML = `
                        <div class="text-center py-4">
                            <div class="spinner-border text-primary" role="status">
                                <span class="visually-hidden">Loading...</span>
                            </div>
                            <p class="mt-2">Memuat detail jurnal...</p>
                        </div>
                    `;

                    // Atur atribut aksesibilitas modal
                    modalElement.removeAttribute('aria-hidden');
                    modalElement.setAttribute('aria-modal', 'true');

                    // Show modal
                    activeModal = new bootstrap.Modal(modalElement);
                    activeModal.show();

                    // Fetch detail
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
                            if (!response.ok) {
                                throw new Error(`HTTP error! status: ${response.status}`);
                            }
                            return response.json();
                        })
                        .then(data => {
                            if (data.success) {
                                modalBody.innerHTML = data.data;

                                // Inisialisasi ulang event handlers untuk elemen dalam modal
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

            // Atur event untuk mengembalikan atribut aksesibilitas saat modal ditutup
            document.getElementById('detailModal').addEventListener('hidden.bs.modal', function() {
                this.setAttribute('aria-hidden', 'true');
                this.removeAttribute('aria-modal');
                activeModal = null;
            });
        });

        // Fungsi untuk mencoba kembali mengambil data
        function retryFetch(jurnalId) {
            const modalBody = document.getElementById('modalBody');

            // Show loading spinner
            modalBody.innerHTML = `
                <div class="text-center py-4">
                    <div class="spinner-border text-primary" role="status">
                        <span class="visually-hidden">Loading...</span>
                    </div>
                    <p class="mt-2">Memuat ulang detail jurnal...</p>
                </div>
            `;

            // Fetch detail lagi
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
                    if (!response.ok) {
                        throw new Error(`HTTP error! status: ${response.status}`);
                    }
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

        // Fungsi untuk menginisialisasi event handlers di dalam modal
        function initializeModalEvents() {
            // Event handler untuk menampilkan gambar dalam modal terpisah
            document.querySelectorAll('#detailModal img[onclick^="showImageModal"]').forEach(img => {
                img.addEventListener('click', function() {
                    showImageModal(this.src);
                });
            });

            // Inisialisasi semua tooltip dalam modal
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('#detailModal [data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(function(tooltipTriggerEl) {
                return new bootstrap.Tooltip(tooltipTriggerEl);
            });
        }

        // Fungsi untuk menampilkan gambar dalam modal terpisah
        function showImageModal(src) {
            const imageModal = document.getElementById('imageModal');
            const modalImage = document.getElementById('modalImage');

            // Atur atribut aksesibilitas modal
            imageModal.removeAttribute('aria-hidden');
            imageModal.setAttribute('aria-modal', 'true');

            // Set sumber gambar
            modalImage.src = src;

            // Tampilkan modal
            const modal = new bootstrap.Modal(imageModal);
            modal.show();

            // Atur event untuk mengembalikan atribut aksesibilitas saat modal ditutup
            imageModal.addEventListener('hidden.bs.modal', function() {
                this.setAttribute('aria-hidden', 'true');
                this.removeAttribute('aria-modal');
                // Reset gambar untuk mengosongkan memori
                modalImage.src = '';
            }, {
                once: true
            });
        }

        // Handle form submissions
        const approveForms = document.querySelectorAll('.approve-form');
        approveForms.forEach(form => {
            form.addEventListener('submit', function(e) {
                const submitBtn = this.querySelector('button[type="submit"]');
                submitBtn.disabled = true;
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
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
                submitBtn.innerHTML =
                    '<span class="spinner-border spinner-border-sm me-1"></span>Memproses...';
            });
        });

        // Remove invalid class on textarea input
        const textareas = document.querySelectorAll('textarea[name="rejected_reason"]');
        textareas.forEach(textarea => {
            textarea.addEventListener('input', function() {
                if (this.value.trim()) {
                    this.classList.remove('is-invalid');
                }
            });
        });

        // Auto hide alerts after 5 seconds
        setTimeout(function() {
            const alerts = document.querySelectorAll('.alert');
            alerts.forEach(alert => {
                const bsAlert = new bootstrap.Alert(alert);
                bsAlert.close();
            });
        }, 5000);
    </script>

    <style>
        .text-truncate-3 {
            display: -webkit-box;
            -webkit-line-clamp: 3;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
@endsection


