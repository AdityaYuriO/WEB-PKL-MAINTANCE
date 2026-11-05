@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pengajuan PKL</h5>
                    </div>
                </div>
                <div class="col-md-12 mt-3">
                    {{-- Jika tidak ada pengajuan untuk IDUKA --}}
                    @if($pengajuanUsulans->isEmpty())
                    <p class="text-center">Tidak ada pengajuan yang tersedia untuk INSTITUSI ini.</p>
                    @else
                    {{-- Looping Daftar Siswa --}}
                    @foreach($pengajuanUsulans as $pengajuan)
                    <div class="card mb-3 shadow-sm" style="padding: 20px; border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-0" style="font-size: 18px">
                                    <strong>{{ $pengajuan->dataPribadi->name ?? 'Nama Tidak Tersedia' }}</strong>
                                </div>
                                <div>
                                    Kelas: {{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}
                                </div>

                                <div>
                                    Status: {{ ucfirst($pengajuan->status) }}
                                </div>
                            </div>

                            <div>
                                @if (in_array($pengajuan->status, ['diterima', 'ditolak', 'batal']))
                                <span class="badge bg-success text-capitalize">{{ $pengajuan->status }}</span>


                                @elseif ($pengajuan->status === 'proses')
                                <!-- Tombol status PROSES -->
                                <div class="d-none d-md-flex gap-2">
                                    <button class="btn btn-success btn-update-status" data-id="{{ $pengajuan->id }}" data-status="diterima">Terima</button>
                                    <button class="btn btn-danger btn-update-status" data-id="{{ $pengajuan->id }}" data-status="ditolak">Tolak</button>
                                </div>

                                <div class="dropdown d-md-none">
                                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item text-success btn-update-status" data-id="{{ $pengajuan->id }}" data-status="diterima">Terima</button></li>
                                        <li><button class="dropdown-item text-danger btn-update-status" data-id="{{ $pengajuan->id }}" data-status="ditolak">Tolak</button></li>
                                    </ul>
                                </div>

                                @elseif ($pengajuan->status === 'menunggu')
                                <!-- Tombol status MENUNGGU -->
                                <div class="d-none d-md-flex gap-2">
                                    <button class="btn btn-success btn-approve-menunggu" data-id="{{ $pengajuan->id }}" data-status="batal">Terima Pembatalan</button>
                                    <button class="btn btn-warning btn-approve-menunggu" data-id="{{ $pengajuan->id }}" data-status="proses">Tolak Pembatalan</button>
                                </div>

                                <div class="dropdown d-md-none">
                                    <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-three-dots-vertical"></i>
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><button class="dropdown-item text-success btn-approve-menunggu" data-id="{{ $pengajuan->id }}" data-status="batal">Terima Pembatalan</button></li>
                                        <li><button class="dropdown-item text-warning btn-approve-menunggu" data-id="{{ $pengajuan->id }}" data-status="proses">Tolak Pembatalan</button></li>
                                    </ul>
                                </div>
                                @endif
                            </div>


                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
<!-- Axios & SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<meta name="csrf-token" content="{{ csrf_token() }}">
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        axios.defaults.headers.common['X-CSRF-TOKEN'] = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

        const buttons = document.querySelectorAll('.btn-update-status');

        buttons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const status = this.dataset.status;
                const statusText = status === 'diterima' ? 'Terima' : 'Tolak';
                const iconType = status === 'diterima' ? 'info' : 'warning';

                Swal.fire({
                    title: `${statusText} Pengajuan?`,
                    text: `Apakah kamu yakin ingin ${statusText.toLowerCase()} pengajuan ini?`,
                    icon: iconType,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.showLoading();

                        axios.put(`/kaprog/review/usulan-pkl/status/${id}`, {
                                status: status
                            })
                            .then(response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: `Pengajuan berhasil ${statusText.toLowerCase()}.`,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('review.usulan') }}";
                                });
                            })
                            .catch(error => {
                                if (error.response) {
                                    console.error('Server responded with error:', error.response.data);
                                } else if (error.request) {
                                    console.error('Request was made but no response:', error.request);
                                } else {
                                    console.error('Something happened:', error.message);
                                }

                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: `Pengajuan berhasil ${statusText.toLowerCase()}.`,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('review.usulan') }}";
                                });
                            });
                    }
                });
            });
        }) // === Tombol status MENUNGGU ===
        const menungguButtons = document.querySelectorAll('.btn-approve-menunggu');

        menungguButtons.forEach(button => {
            button.addEventListener('click', function() {
                const id = this.dataset.id;
                const status = this.dataset.status;
                const statusText = status === 'batal' ? 'Terima Pembatalan' : 'Tolak Pembatalan';
                const iconType = status === 'batal' ? 'info' : 'warning';

                Swal.fire({
                    title: `${statusText}?`,
                    text: `Apakah kamu yakin ingin ${statusText.toLowerCase()} ini?`,
                    icon: iconType,
                    showCancelButton: true,
                    confirmButtonText: 'Ya, lanjutkan!',
                    cancelButtonText: 'Batal',
                }).then((result) => {
                    if (result.isConfirmed) {
                        Swal.showLoading();

                        axios.post("{{ route('kaprog.persetujuan-pembatalan') }}", {
                                id: id,
                                status: status
                            })
                            .then(response => {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Berhasil!',
                                    text: `Status berhasil diperbarui menjadi ${status}.`,
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    window.location.href = "{{ route('review.usulan') }}";
                                });
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Gagal!',
                                    text: error.response?.data?.message || 'Terjadi kesalahan saat memperbarui status.',
                                });
                            });
                    }
                });
            });
        });
    });
</script>


@endpush
