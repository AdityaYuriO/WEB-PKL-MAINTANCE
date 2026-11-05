@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                        <h5 class="mb-0 text-center text-md-start w-100 w-md-auto">
                            Review Pengajuan Institusi / Perusahaan
                        </h5>
                        <!-- Mode Mobile (full lebar, dari kanan ke kiri) -->
                        <div class="d-flex d-md-none w-100">
                            <a class="btn btn-sm btn-success w-100" href="{{ route('kaprog.review.histori') }}">
                                Riwayat Pengajuan
                            </a>
                        </div>

                        <!-- Mode Desktop (kecil, di kanan tengah) -->
                        <div class="d-none d-md-flex justify-content-end align-items-center w-100">
                            <a class="btn btn-success btn-sm" href="{{ route('kaprog.review.histori') }}" data-bs-toggle="tooltip" title="Riwayat Pengajuan">
                                Riwayat
                            </a>
                        </div>

                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    @if($pengajuanUsulans->isEmpty())
                    <div class="alert alert-info text-center mt-4" role="alert">
                         Semua pengajuan sudah berhasil dikirim ke Iduka, dan tidak ada pengajuan yang tersedia.
                    </div>
                    @else

                    @if(session('info'))
                    <div class="alert alert-info alert-dismissible fade show" role="alert">
                        {{ session('info') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @if(session('error'))
                    <div class="alert alert-danger alert-dismissible fade show" role="alert">
                        <strong>Error!</strong> {{ session('error') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

                    @foreach($pengajuanUsulans->groupBy('iduka_id') as $iduka_id => $pengajuanGroup)
                    @php
                    // Catatan: kolom 'status' di cetak_usulans tidak pernah diubah jadi 'sudah' oleh kode manapun,
                    // jadi filter di sini dihapus. Yang menentukan "pending atau sudah dikirim" adalah kolom 'dikirim',
                    // dan itu sudah difilter di controller (KaprogController::reviewPengajuan).
                    $filteredPengajuan = $pengajuanGroup;
                    @endphp

                    @if($filteredPengajuan->count() > 0)
                    <div class="card mb-3 shadow-sm" style="padding: 30px; border-radius: 10px;">
                        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center gap-2">
                            @php
                                // Mendefinisikan jumlah pengajuan yang statusnya 'menunggu'
                                $jumlahMenunggu = $pengajuanGroup->where('dikirim', 'menunggu')->count();
                            @endphp

                            <div class="w-100">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div class="mb-0" style="font-size: 18px">
                                        <strong>{{ $pengajuanGroup->first()->iduka->nama }}</strong>
                                    </div>

                                    {{-- Titik tiga - mobile only --}}
                                    <div class="dropdown d-block d-md-none">
                                        <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a class="dropdown-item" href="{{ route('kaprog.review.reviewdetail', ['iduka_id' => $iduka_id]) }}">
                                                    <i class="fas fa-info-circle me-2"></i> Detail
                                                </a>
                                            </li>
                                            <li>
                                                <form action="{{ route('kaprog.review.kirimSemua', ['iduka_id' => $iduka_id]) }}" method="POST" class="formKirimSemua" data-menunggu="{{ $jumlahMenunggu }}">
                                                    @csrf
                                                    <button type="button" class="dropdown-item btn-kirim-semua">
                                                        <i class="fas fa-paper-plane me-2"></i> Kirim Semua
                                                    </button>
                                                </form>
                                            </li>
                                        </ul>
                                    </div>
                                </div>

                                <small class="text-muted">{{ $filteredPengajuan->count() }} siswa mengajukan ke sini</small>
                                @if($jumlahMenunggu > 0)
                                    <div class="text-danger mt-1" style="font-size: 13px;">
                                         {{ $jumlahMenunggu }} siswa sedang mengajukan pembatalan (menunggu)
                                    </div>
                                @endif
                            </div>

                            <div class="dropdown d-none d-md-block">
                                <button class="btn p-0 border-0" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded fs-4"></i>
                                </button>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a class="dropdown-item" href="{{ route('kaprog.review.reviewdetail', ['iduka_id' => $iduka_id]) }}">
                                            Detail
                                        </a>
                                    </li>
                                    <li>
                                        <form action="{{ route('kaprog.review.kirimSemua', ['iduka_id' => $iduka_id]) }}" method="POST" class="formKirimSemua" data-menunggu="{{ $jumlahMenunggu }}">
                                            @csrf
                                            <button type="button" class="dropdown-item btn-kirim-semua" style="white-space: nowrap;">Kirim</button>
                                        </form>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                    @endif

                    @endforeach
                    @endif

                    <div class="card">
                        @if($pengajuanUsulans->total() > 10) <!-- Mengecek apakah total data lebih dari 10 -->
                            <div class="d-flex justify-content-end mt-4">
                                {{ $pengajuanUsulans->links('pagination::bootstrap-5') }}
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kirimButtons = document.querySelectorAll('.btn-kirim-semua');

        kirimButtons.forEach(button => {
            button.addEventListener('click', function() {
                const form = this.closest('form');
                const jumlahMenunggu = parseInt(form.getAttribute('data-menunggu'));

                if (jumlahMenunggu > 0) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Tidak dapat mengirim!',
                        text: 'Terdapat siswa yang sedang mengajukan pembatalan.',
                        confirmButtonColor: '#d33'
                    });
                } else {
                    Swal.fire({
                        title: 'Yakin?',
                        text: "Ingin mengirim semua pengajuan ke INSTITUSI ini?",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#28a745',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Ya, Kirim!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                }
            });
        });
    });
</script>

@if(session('success'))
<script>
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('success') }}',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
</script>
@endif

