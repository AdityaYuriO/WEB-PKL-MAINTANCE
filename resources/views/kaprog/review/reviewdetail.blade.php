@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">

            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Detail Pengajuan PKL ke: {{ $iduka->nama }}</h5>
                        <a href="{{ route('kaprog.review.pengajuan') }}" class="btn btn-secondary btn-sm">
                            <span class="d-none d-sm-inline">Kembali</span>
                        </a>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    @if ($pengajuans->isEmpty())
                        <p class="text-center">Tidak ada pengajuan yang tersedia untuk Institusi ini.</p>
                    @else
                        @foreach ($pengajuans as $pengajuan)
                        <div class="card mb-3 shadow-sm p-3 rounded">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="fw-bold" style="font-size: 18px">
                                        {{ $pengajuan->dataPribadi->name ?? 'Nama Tidak Tersedia' }}
                                    </div>
                                    <div>
                                        Kelas: {{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}
                                    </div>
                                    <div>
                                        Status: {{ ucfirst($pengajuan->status) }}
                                    </div>
                                    @if($pengajuan->dikirim === 'menunggu')
                                    <div class="text-danger mt-1" style="font-size: 13px;">
                                         Siswa mengajukan pembatalan!
                                    </div>
                                    @endif
                                </div>

                                <div class="d-inline-block position-relative">
                                    {{-- Desktop actions --}}
                                    <div class="d-none d-md-flex gap-2">
                                        <a href="{{ route('persuratan.suratPengajuan.detailSuratPengajuan', $pengajuan->id) }}" class="btn btn-primary rounded-pill">
                                            Lihat Detail
                                        </a>

                                        @if ($pengajuan->dikirim === 'menunggu')
                                            <form action="{{ route('kaprog.pembatalan.terima', $pengajuan->id) }}" method="POST" id="form-terima-{{ $pengajuan->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-success rounded-pill" onclick="confirmTerimaPembatalan('{{ $pengajuan->id }}')">Terima Pembatalan</button>
                                            </form>
                                            <form action="{{ route('kaprog.pembatalan.tolak', $pengajuan->id) }}" method="POST" id="form-tolak-{{ $pengajuan->id }}">
                                                @csrf
                                                <button type="button" class="btn btn-danger rounded-pill" onclick="confirmTolakPembatalan('{{ $pengajuan->id }}')">Tolak Pembatalan</button>
                                            </form>
                                        @elseif ($pengajuan->dikirim === 'belum')
                                            <form action="{{ route('kaprog.pengajuan.prosesPengajuan', $pengajuan->id) }}" method="POST" id="form-{{ $pengajuan->id }}">
                                                @csrf
                                                <input type="hidden" name="iduka_id" value="{{ $iduka->id }}">
                                                <button type="button" class="btn btn-primary rounded-pill" onclick="confirmKirim('{{ $pengajuan->id }}')">Kirim</button>
                                            </form>
                                        @elseif ($pengajuan->dikirim === 'batal')
                                            <button class="btn btn-secondary rounded-pill" disabled>Telah Dibatalkan</button>
                                        @endif
                                    </div>

                                    {{-- Mobile dropdown --}}
                                    <div class="dropdown d-md-none">
                                        <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                            <i class="bi bi-three-dots-vertical"></i>
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <a href="{{ route('persuratan.suratPengajuan.detailSuratPengajuan', $pengajuan->id) }}" class="dropdown-item text-success">
                                                    Lihat Detail
                                                </a>
                                            </li>

                                            @if ($pengajuan->status === 'diterima')
                                                <li><button class="dropdown-item text-muted" disabled>Sudah Dikirim</button></li>
                                            @elseif ($pengajuan->dikirim === 'menunggu')
                                                <li>
                                                    <form action="{{ route('kaprog.pengajuan.setujuiPembatalan', $pengajuan->id) }}" method="POST" onsubmit="return confirm('Setujui pembatalan pengajuan ini?')">
                                                        @csrf
                                                        @method('PUT')
                                                        <button class="dropdown-item text-danger">Setujui Batal</button>
                                                    </form>
                                                </li>
                                            @else
                                                <li>
                                                    <form action="{{ route('kaprog.pengajuan.prosesPengajuan', $pengajuan->id) }}" method="POST" id="form-mobile-{{ $pengajuan->id }}">
                                                        @csrf
                                                        <input type="hidden" name="iduka_id" value="{{ $iduka->id }}">
                                                        <button type="button" class="dropdown-item text-primary" onclick="confirmKirimMobile('{{ $pengajuan->id }}')">Kirim</button>
                                                    </form>
                                                </li>
                                            @endif
                                        </ul>
                                    </div>
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
<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    function confirmKirim(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Ingin mengirim pengajuan ini ke IDUKA?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-' + id).submit();
            }
        });
    }

    function confirmKirimMobile(id) {
        Swal.fire({
            title: 'Yakin?',
            text: "Ingin mengirim pengajuan ini ke IDUKA?",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Kirim!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-mobile-' + id).submit();
            }
        });
    }

    function confirmTerimaPembatalan(id) {
        Swal.fire({
            title: 'Terima Pembatalan?',
            text: "Siswa akan batal dari pengajuan ini.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#28a745',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, Terima',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-terima-' + id).submit();
            }
        });
    }

    function confirmTolakPembatalan(id) {
        Swal.fire({
            title: 'Tolak Pembatalan?',
            text: "Pengajuan siswa akan tetap diteruskan ke IDUKA.",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#dc3545',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Ya, Tolak',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('form-tolak-' + id).submit();
            }
        });
    }


    // Jika ada flash message dari server, tampilkan
    @if(session('success'))
    Swal.fire({
        title: 'Berhasil!',
        text: '{{ session('
        success ') }}',
        icon: 'success',
        timer: 1500,
        showConfirmButton: false
    });
    @endif

    @if(session('error'))
    Swal.fire({
        title: 'Gagal!',
        text: '{{ session('
        error ') }}',
        icon: 'error',
        showConfirmButton: true
    });
    @endif

    @if(session('info'))
    Swal.fire({
        title: 'Info!',
        text: '{{ session('
        info ') }}',
        icon: 'info',
        showConfirmButton: true
    });
    @endif
</script>
@endpush
