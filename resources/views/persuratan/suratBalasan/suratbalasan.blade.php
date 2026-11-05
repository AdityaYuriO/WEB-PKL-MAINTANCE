@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Review Surat Balasan Perusahaan</h5>
                        <a href="{{ route('persuratan.suratBalasan.history') }}" class="btn btn-success">
                            <i class="fas fa-history me-2"></i>Histori
                        </a>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    @if($pengajuanUsulans->isEmpty())
                    <div class="alert alert-info text-center mt-4" role="alert">
                         Semua pengajuan sudah berhasil dikirim ke Iduka, dan tidak ada pengajuan yang tersedia.
                    </div>
                    @else
                    @if(session('success'))
                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                        {{ session('success') }}
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    @endif

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

                    @foreach($pengajuanUsulans as $iduka_id => $pengajuanGroup)
                    @php
                    $diterima = $pengajuanGroup->where('status', 'diterima');
                    $ditolak = $pengajuanGroup->where('status', 'ditolak');
                    @endphp

                    @if($diterima->count() > 0 || $ditolak->count() > 0)
                    <div class="card mb-3 shadow-sm" style="padding: 30px; border-radius: 10px;">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <div class="mb-0" style="font-size: 18px"><strong>{{ $pengajuanGroup->first()->iduka->nama }}</strong></div>
                                <div class="d-flex gap-3 mt-2">
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle me-1"></i> Diterima: {{ $diterima->count() }}
                                    </span>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle me-1"></i> Ditolak: {{ $ditolak->count() }}
                                    </span>
                                </div>
                            </div>

                            {{-- Mobile View: Dropdown --}}
                            <div class="dropdown d-block d-md-none">
                                <button class="btn" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                   <i class="bi bi-three-dots-vertical"></i>                                </button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li>
                                        <a class="dropdown-item text-primary" href="{{ route('kaprog.review.reviewdetail', ['iduka_id' => $iduka_id]) }}">
                                            Detail
                                        </a>
                                    </li>
                                </ul>
                            </div>

                            {{-- Desktop View: Inline Buttons --}}
                            <div class="d-none d-md-flex" style="gap: 10px; align-items: center;">
                                <a href="{{ route('persuratan.suratBalasan.detailbalasan', ['iduka_id' => $iduka_id]) }}" class="btn btn-primary rounded-pill">
                                    <i class="fas fa-eye me-1"></i> Detail
                                </a>
                            </div>
                        </div>
                    </div>
                    @endif
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
