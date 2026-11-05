@extends('layout.main')
@section('content')
    <div class="container-fluid mt-4">
        <div class="container-xxl flex-grow-1 container-p-y">

            <!-- Card Header -->
            <div class="card shadow-sm">
                <div class="card-header bg-white py-3">
                    <div class="row align-items-center">
                        <div class="col">
                            <h3 class="mb-0 text-primary">Daftar IDUKA</h3>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCabangModal">
                                <i class="bi bi-plus-circle me-1"></i> Tambah Cabang
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Card Body -->
                <div class="card-body">
                    <!-- Flash Messages -->
                    @if (session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    @endif

                    @if ($errors->any())
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <strong>Validasi gagal:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                        </div>
                    @endif

                    <!-- Search Box -->
                    <div class="row mb-4">
                        <div class="col-md-6">
                            <form action="{{ route('hubin.iduka.daftar') }}" method="GET">
                                <div class="input-group">
                                    <span class="input-group-text bg-light border-end-0">
                                        <i class="bi bi-search"></i>
                                    </span>
                                    <input type="text" class="form-control border-start-0 ps-0"
                                        placeholder="Cari nama, NIP, atau email..." name="search"
                                        value="{{ $search ?? '' }}">
                                    <button class="btn btn-primary" type="submit">
                                        Cari
                                    </button>
                                    @if ($search ?? false)
                                        <a href="{{ route('hubin.iduka.daftar') }}" class="btn btn-outline-secondary">
                                            Reset
                                        </a>
                                    @endif
                                </div>
                            </form>
                        </div>
                        <div class="col-md-6 text-md-end mt-2 mt-md-0">
                            <span class="text-muted">Menampilkan {{ $data->count() }} dari {{ $data->total() }} data</span>
                        </div>
                    </div>

                    <!-- Table -->
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th scope="col" width="5%">No</th>
                                    <th scope="col" width="25%">Nama</th>
                                    <th scope="col" width="10%">Tipe</th>
                                    <th scope="col" width="15%">Latitude</th>
                                    <th scope="col" width="15%">Longitude</th>
                                    <th scope="col" width="10%">Radius (m)</th>
                                    <th scope="col" width="10%">Jam Masuk</th>
                                    <th scope="col" width="10%">Jam Pulang</th>
                                    <th scope="col" width="5%" class="text-center">Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse ($data as $iduka)
                                    <tr>
                                        <td>{{ $data->firstItem() + $loop->index }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-3">
                                                    <div
                                                        class="avatar-title rounded-circle {{ $iduka->is_pusat ? 'bg-primary' : 'bg-info' }} text-white">
                                                        {{ strtoupper(substr($iduka->nama, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="fw-semibold">{{ $iduka->nama }}</div>
                                                    <div class="small text-muted">{{ $iduka->email ?? '-' }}</div>
                                                    @if (!$iduka->is_pusat && $iduka->pusat)
                                                        <div class="small text-primary">
                                                            <i class="bi bi-building"></i> Cabang dari:
                                                            {{ $iduka->pusat->nama }}
                                                        </div>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            @if ($iduka->is_pusat)
                                                <span class="badge bg-primary">Pusat</span>
                                            @else
                                                <span class="badge bg-info">Cabang</span>
                                            @endif
                                        </td>
                                        <td>
                                            <span class="text-monospace">{{ $iduka->latitude ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="text-monospace">{{ $iduka->longitude ?? '-' }}</span>
                                        </td>
                                        <td>
                                            <span class="badge bg-info">{{ $iduka->radius ?? 0 }} m</span>
                                        </td>
                                        <td>{{ $iduka->jam_masuk ? \Carbon\Carbon::parse($iduka->jam_masuk)->format('H:i') : '08:00' }}
                                            WIB</td>
                                        <td>{{ $iduka->jam_pulang ? \Carbon\Carbon::parse($iduka->jam_pulang)->format('H:i') : '15:00' }}
                                            WIB</td>
                                        <td class="text-center">
                                            <div class="btn-group btn-group-sm" role="group">
                                                <button type="button" class="btn btn-outline-warning btn-edit"
                                                    data-id="{{ $iduka->id }}" data-nama="{{ $iduka->nama }}"
                                                    data-email="{{ $iduka->email }}"
                                                    data-latitude="{{ $iduka->latitude ?? '' }}"
                                                    data-longitude="{{ $iduka->longitude ?? '' }}"
                                                    data-radius="{{ $iduka->radius ?? '' }}"
                                                    data-jam_masuk="{{ $iduka->jam_masuk ?? '08:00' }}"
                                                    data-jam_pulang="{{ $iduka->jam_pulang ?? '15:00' }}"
                                                    data-is_pusat="{{ $iduka->is_pusat ? 1 : 0 }}"
                                                    data-id_pusat="{{ $iduka->id_pusat ?? '' }}" title="Edit">
                                                    <i class="bi bi-pencil"></i>
                                                </button>

                                                @if ($iduka->is_pusat)
                                                    <button type="button" class="btn btn-outline-info btn-add-cabang"
                                                        data-id="{{ $iduka->id }}" data-nama="{{ $iduka->nama }}"
                                                        title="Tambah Cabang">
                                                        <i class="bi bi-building-add"></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="9" class="text-center py-4">
                                            <div class="text-muted">
                                                <i class="bi bi-inbox display-4 d-block"></i>
                                                <span>Tidak ada data IDUKA</span>
                                            </div>
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>

                    <!-- Pagination -->
                    <div class="row align-items-center mt-4">
                        <div class="d-flex justify-content-end mt-3">
                            {{ $data->links('pagination::bootstrap-5') }}
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal Edit -->
        <div class="modal fade" id="editModal" tabindex="-1" aria-labelledby="editModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editModalLabel">Edit Data IDUKA</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="editForm">
                        <div class="modal-body">
                            <input type="hidden" id="editId" name="id">

                            <div class="mb-3">
                                <label for="editNama" class="form-label">Nama Lengkap <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="editNama" name="nama" required>
                            </div>

                            <div class="mb-3">
                                <label for="editEmail" class="form-label">Email</label>
                                <input type="email" class="form-control" id="editEmail" name="email">
                            </div>

                            <div class="mb-3">
                                <label for="tipeLokasi" class="form-label">Tipe Lokasi</label>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_pusat" id="is_pusat1"
                                        value="1">
                                    <label class="form-check-label" for="is_pusat1">
                                        Lokasi Pusat
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input" type="radio" name="is_pusat" id="is_pusat0"
                                        value="0">
                                    <label class="form-check-label" for="is_pusat0">
                                        Lokasi Cabang
                                    </label>
                                </div>
                            </div>

                            <div class="mb-3" id="idPusatField" style="display: none;">
                                <label for="id_pusat" class="form-label">Lokasi Pusat</label>
                                <select class="form-select" id="id_pusat" name="id_pusat">
                                    <option value="">-- Pilih Lokasi Pusat --</option>
                                    @foreach ($data as $item)
                                        @if ($item->is_pusat)
                                            <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>

                            <div class="mb-3">
                                <label for="editLatitude" class="form-label">Latitude</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="number" class="form-control" id="editLatitude" name="latitude"
                                        step="any" placeholder="-6.2088">
                                </div>
                                <small class="form-text text-muted">Contoh: -6.2088</small>
                            </div>

                            <div class="mb-3">
                                <label for="editLongitude" class="form-label">Longitude</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="number" class="form-control" id="editLongitude" name="longitude"
                                        step="any" placeholder="106.8456">
                                </div>
                                <small class="form-text text-muted">Contoh: 106.8456</small>
                            </div>

                            <div class="mb-3">
                                <label for="editRadius" class="form-label">Radius (meter)</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="number" class="form-control" id="editRadius" name="radius"
                                        placeholder="100">
                                </div>
                                <small class="form-text text-muted">Jari-jari area dalam meter</small>
                            </div>

                            <div class="mb-3">
                                <label for="jam_masuk" class="form-label">Jam Masuk</label>
                                <input type="time" class="form-control" id="jam_masuk" name="jam_masuk">
                                <div class="form-text">Default: 08:00</div>
                            </div>

                            <div class="mb-3">
                                <label for="jam_pulang" class="form-label">Jam Pulang</label>
                                <input type="time" class="form-control" id="jam_pulang" name="jam_pulang">
                                <div class="form-text">Default: 15:00</div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Modal Tambah Cabang -->
        <div class="modal fade" id="addCabangModal" tabindex="-1" aria-labelledby="addCabangModalLabel"
            aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="addCabangModalLabel">Tambah Lokasi Cabang Baru</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <form id="addCabangForm">
                        <div class="modal-body">
                            <div class="mb-3">
                                <label for="cabangNama" class="form-label">Nama Cabang <span
                                        class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="cabangNama" name="nama" required>
                            </div>

                            <select class="form-select select2 form-control" id="id_pusat_cabang" name="id_pusat" required>
                                <option value="">-- Pilih Lokasi Pusat --</option>
                                @foreach ($allIduka as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama }}</option>
                                @endforeach
                            </select>



                            <div class="mb-3">
                                <label for="cabangLatitude" class="form-label">Latitude <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="number" class="form-control" id="cabangLatitude" name="latitude"
                                        step="any" placeholder="-6.2088" required>
                                </div>
                                <small class="form-text text-muted">Contoh: -6.2088</small>
                            </div>

                            <div class="mb-3">
                                <label for="cabangLongitude" class="form-label">Longitude <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-geo-alt"></i></span>
                                    <input type="number" class="form-control" id="cabangLongitude" name="longitude"
                                        step="any" placeholder="106.8456" required>
                                </div>
                                <small class="form-text text-muted">Contoh: 106.8456</small>
                            </div>

                            <div class="mb-3">
                                <label for="cabangRadius" class="form-label">Radius (meter) <span
                                        class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-rulers"></i></span>
                                    <input type="number" class="form-control" id="cabangRadius" name="radius"
                                        placeholder="100" required>
                                </div>
                                <small class="form-text text-muted">Jari-jari area dalam meter</small>
                            </div>

                            <div class="mb-3">
                                <label for="cabangJamMasuk" class="form-label">Jam Masuk <span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="cabangJamMasuk" name="jam_masuk"
                                    value="08:00" required>
                                <div class="form-text">Default: 08:00</div>
                            </div>

                            <div class="mb-3">
                                <label for="cabangJamPulang" class="form-label">Jam Pulang <span
                                        class="text-danger">*</span></label>
                                <input type="time" class="form-control" id="cabangJamPulang" name="jam_pulang"
                                    value="15:00" required>
                                <div class="form-text">Default: 15:00</div>
                            </div>
                        </div>

                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-check-circle me-1"></i> Tambah Cabang
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <!-- Custom CSS -->
    <style>
        .avatar {
            position: relative;
            display: inline-block;
        }

        .avatar-title {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            font-weight: 500;
        }

        .table-hover tbody tr:hover {
            background-color: rgba(0, 123, 255, 0.05);
        }

        .btn-group-sm>.btn,
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.75rem;
            line-height: 1.5;
            border-radius: 0.2rem;
        }

        .card {
            border: none;
            border-radius: 0.5rem;
        }

        .card-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .table thead th {
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.5px;
            color: #6c757d;
            border-bottom: 2px solid #dee2e6;
        }

        .table td,
        .table th {
            padding: 1rem;
            vertical-align: middle;
        }

        .badge {
            font-weight: 500;
            padding: 0.35em 0.65em;
        }

        .text-monospace {
            font-family: SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", "Courier New", monospace;
            font-size: 0.9rem;
        }

        /* Custom Pagination Styling */
        .pagination {
            margin-bottom: 0;
        }

        .page-link {
            color: #0d6efd;
            padding: 0.5rem 0.75rem;
            font-size: 0.875rem;
        }

        .page-item.active .page-link {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }

        /* Modal Styling */
        .modal-content {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }

        .modal-header {
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
        }

        .modal-footer {
            border-top: 1px solid rgba(0, 0, 0, 0.05);
        }

        .input-group-text {
            background-color: #f8f9fa;
        }

        .form-label {
            font-weight: 500;
        }
    </style>


@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            $('.select2').each(function() {
                $(this).select2({
                    placeholder: "-- Pilih Lokasi Pusat --",
                    allowClear: true,
                    width: '100%',
                    dropdownParent: $(this).closest('.modal') // kalau di modal
                });
            });
        });
    </script>

    <!-- JavaScript -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Handle tipe lokasi change
            const isPusatRadios = document.querySelectorAll('input[name="is_pusat"]');
            const idPusatField = document.getElementById('idPusatField');

            function toggleIdPusatField() {
                const isPusat0 = document.getElementById('is_pusat0');
                if (isPusat0 && isPusat0.checked) {
                    idPusatField.style.display = 'block';
                } else {
                    idPusatField.style.display = 'none';
                }
            }

            isPusatRadios.forEach(radio => {
                radio.addEventListener('change', toggleIdPusatField);
            });

            // Handle edit button click
            document.querySelectorAll('.btn-edit').forEach(button => {
                button.addEventListener('click', function() {
                    // Get data attributes
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');
                    const email = this.getAttribute('data-email');
                    const latitude = this.getAttribute('data-latitude');
                    const longitude = this.getAttribute('data-longitude');
                    const radius = this.getAttribute('data-radius');
                    const jam_masuk = this.getAttribute('data-jam_masuk');
                    const jam_pulang = this.getAttribute('data-jam_pulang');
                    const is_pusat = this.getAttribute('data-is_pusat');
                    const id_pusat = this.getAttribute('data-id_pusat');

                    // Fill form fields
                    document.getElementById('editId').value = id;
                    document.getElementById('editNama').value = nama;
                    document.getElementById('editEmail').value = email;
                    document.getElementById('editLatitude').value = latitude;
                    document.getElementById('editLongitude').value = longitude;
                    document.getElementById('editRadius').value = radius;
                    document.getElementById('jam_masuk').value = jam_masuk;
                    document.getElementById('jam_pulang').value = jam_pulang;

                    // Set tipe lokasi
                    if (is_pusat === '1') {
                        document.getElementById('is_pusat1').checked = true;
                    } else {
                        document.getElementById('is_pusat0').checked = true;
                    }

                    // Set id_pusat jika ada
                    if (id_pusat) {
                        document.getElementById('id_pusat').value = id_pusat;
                    }

                    // Initialize toggle
                    toggleIdPusatField();

                    // Show modal
                    const editModal = new bootstrap.Modal(document.getElementById('editModal'));
                    editModal.show();
                });
            });

            // Handle form submission
            document.getElementById('editForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);
                const id = formData.get('id');

                fetch(`/hubin/iduka/${id}`, {
                        method: 'PUT',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nama: formData.get('nama'),
                            email: formData.get('email'),
                            latitude: formData.get('latitude'),
                            longitude: formData.get('longitude'),
                            radius: formData.get('radius'),
                            jam_masuk: formData.get('jam_masuk'),
                            jam_pulang: formData.get('jam_pulang'),
                            is_pusat: document.querySelector('input[name="is_pusat"]:checked')
                                .value,
                            id_pusat: document.getElementById('id_pusat').value || null
                        })
                    })
                    .then(response => {
                        if (!response.ok) throw new Error('Terjadi kesalahan saat memperbarui data');
                        return response.json();
                    })
                    .then(data => {
                        const editModal = bootstrap.Modal.getInstance(document.getElementById(
                            'editModal'));
                        editModal.hide();
                        alert('Data berhasil diperbarui!');
                        setTimeout(() => window.location.reload(), 1000);
                    })
                    .catch(error => {
                        alert('Gagal memperbarui data: ' + error.message);
                    });
            });

            // Handle add cabang button click
            document.querySelectorAll('.btn-add-cabang').forEach(button => {
                button.addEventListener('click', function() {
                    const id = this.getAttribute('data-id');
                    const nama = this.getAttribute('data-nama');

                    // Set id_pusat
                    document.getElementById('id_pusat_cabang').value = id;

                    // Show modal
                    const addCabangModal = new bootstrap.Modal(document.getElementById(
                        'addCabangModal'));
                    addCabangModal.show();
                });
            });

            // Handle add cabang form submission
            document.getElementById('addCabangForm').addEventListener('submit', function(e) {
                e.preventDefault();

                const formData = new FormData(this);

                fetch('/hubin/iduka/store-cabang', {
                        method: 'POST',
                        headers: {
                            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: JSON.stringify({
                            nama: formData.get('nama'),
                            id_pusat: formData.get('id_pusat'),
                            latitude: formData.get('latitude'),
                            longitude: formData.get('longitude'),
                            radius: formData.get('radius'),
                            jam_masuk: formData.get('jam_masuk'),
                            jam_pulang: formData.get('jam_pulang')
                        })
                    })
                    .then(response => {
                        console.log('Response status:', response.status);
                        console.log('Response headers:', response.headers);

                        if (!response.ok) {
                            return response.json().then(err => {
                                console.error('Error response:', err);
                                throw new Error(err.message ||
                                    'Terjadi kesalahan saat menambah cabang');
                            });
                        }
                        return response.json();
                    })
                    .then(data => {
                        console.log('Success response:', data);
                        const addCabangModal = bootstrap.Modal.getInstance(document.getElementById(
                            'addCabangModal'));
                        addCabangModal.hide();
                        alert('Lokasi cabang berhasil ditambahkan!');
                        setTimeout(() => window.location.reload(), 1000);
                    })
                    .catch(error => {
                        console.error('Fetch error:', error);
                        alert('Gagal menambah cabang: ' + error.message);
                    });
            });
        });
    </script>
@endpush

