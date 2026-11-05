@extends('layout.main')
@section('content')

<!DOCTYPE html>
<html lang="en">

<head>
    <title>Review Pengajuan</title>
    <style>
        .card-hover {
            transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
            height: 70px;
            flex-direction: column;
            justify-content: center;
            display: flex;
        }

        .card-hover:hover {
            transform: scale(1.03);
            background-color: #7e7dfb !important;
            color: white !important;
        }

        .card-hover:hover .btn-hover {
            background-color: white;
            color: #7e7dfb;
            border-color: white;
        }

        .btn-hover {
            background-color: #7e7dfb;
            color: white;
            transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
            border-radius: 50px;
            border: 2px solid #7e7dfb;
        }

        .btn-hover:hover {
            background-color: white;
            color: #7e7dfb;
            border-color: white;
        }

        .dropdown-btn {
            color: #7e7dfb;
            font-size: 25px;
            border-radius: 50px;
        }

        .card-hover:hover .dropdown-btn {
            color: white !important;
        }

        .button-group {
            margin-bottom: 18px;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card mb-3">
                        <div class="card-body d-flex justify-content-between align-items-center">
                            <h5 class="mb-0">Review Pengajuan Institusi / Perusahaan</h5>

                            <div class="d-flex gap-2">
                                <a href="{{route('persuratan.review.historyDikirim')}}" class="btn btn-success btn-status btn-sm">
                                    <i class="bi bi-check-circle"></i>
                                    <span class="d-none d-md-inline">History Dikirim</span>
                                </a>

                            </div>
                        </div>
                    </div>
                    <div class="col-md-12 mt-3">

                        {{-- Jika tidak ada pengajuan --}}
                        @if($pengajuanUsulans->isEmpty())
                        <p class="text-center">Tidak ada pengajuan yang tersedia.</p>
                        @else

                        {{-- Looping Data Pengajuan --}}
                        @foreach($pengajuanUsulans as $iduka_id => $pengajuanGroup)
                        <div class="card mb-3 shadow-sm card-hover" style="padding: 30px; border-radius: 10px;">
                            <div class="d-flex justify-content-between align-items-center">
                                <div>
                                    <div class="mb-0" style="font-size: 18px">
                                        <strong>{{ $pengajuanGroup->first()->iduka->nama }}</strong>
                                    </div>
                                    <small class="text-muted">{{ $pengajuanGroup->count() }} siswa mengajukan ke sini</small>
                                </div>
                                <div>
                                    {{-- Tombol hanya tampil di desktop --}}
                                    <a href="{{ route('persuratan.review.detailUsulanPkl', ['iduka_id' => $iduka_id]) }}"
                                       class="btn btn-hover rounded-pill d-none d-md-inline-block">
                                        Detail
                                    </a>
                                    <button class="btn btn-hover rounded-pill btn-kirim d-none d-md-inline-block"
                                            data-iduka="{{ $iduka_id }}"
                                            data-nama="{{ htmlspecialchars($pengajuanGroup->first()->iduka->nama, ENT_QUOTES) }}">
                                        Kirim
                                    </button>
                                </div>
                        
                                {{-- Dropdown hanya muncul di mobile --}}
                                <div class="dropdown d-md-none ms-2">
                                    <button class="btn dropdown-btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                       <i class="bi bi-three-dots-vertical"></i>                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li>
                                            <a class="dropdown-item text-warning" 
                                               href="{{ route('persuratan.review.detailUsulanPkl', ['iduka_id' => $iduka_id]) }}">
                                                Detail
                                            </a>
                                        </li>
                                        <li>
                                            <button class="dropdown-item text-success btn-kirim"
                                                    data-iduka="{{ $iduka_id }}"
                                                    data-nama="{{ htmlspecialchars($pengajuanGroup->first()->iduka->nama, ENT_QUOTES) }}">
                                                Kirim
                                            </button>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>                        
                        @endforeach


                        @endif

                    </div>
                </div>
                {{-- <div class="card">
                    <div class="d-flex justify-content-end mt-3">
                        {{ $pengajuanUsulans->links('pagination::bootstrap-5') }}
                    </div>
                </div> --}}
            </div>
        </div>
    </div>

    @include('iduka.dataiduka.createiduka')
</body>

</html>

@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const kirimButtons = document.querySelectorAll('.btn-kirim');

        kirimButtons.forEach(button => {
            button.addEventListener('click', function() {
                const idukaId = this.dataset.iduka;
                const idukaNama = this.dataset.nama || 'IDUKA';

                Swal.fire({
                    title: `Kirim Semua Siswa untuk ${idukaNama}?`,
                    text: 'Data akan dikirim ke Kaprog',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Kirim!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/review/pengajuan/kirim-semua/${idukaId}`)
                            .then(response => {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.data.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(() => {
                                    location.reload(); // reload untuk update tampilan
                                });
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat mengirim data.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    });
</script>
@endpush