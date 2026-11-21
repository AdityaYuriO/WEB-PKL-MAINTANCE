@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Histori Download Surat Balasan</h5>
                        <a class="nav-link btn btn-primary" href="{{ route('persuratan.suratBalasan') }}">
                            <i class="fas fa-arrow-left me-2"></i> Kembali
                        </a>
                    </div>
                </div>

                <div class="col-md-12 mt-3">
                    @if($histori->isEmpty())
                    <div class="alert alert-info text-center mt-4" role="alert">
                        <i class="fas fa-info-circle me-2"></i> Belum ada histori download surat balasan.
                    </div>
                    @else
                    @foreach($histori as $iduka_id => $historyGroup)
                    @php
                    $iduka = $historyGroup->first()->pengajuanPkl->iduka;
                    @endphp

                    <div class="card mb-4 shadow-sm">
                        <div class="card-header bg-light">
                            <div class="d-flex justify-content-between align-items-center">
                                <h5 class="mb-0">
                                    <i class="fas fa-building me-2"></i> {{ $iduka->nama }}
                                </h5>
                            </div>
                        </div>
                        
                        <div class="card-body">
                            <div class="table-responsive text-nowrap">
                                <table class="table table-hover">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Nama Siswa</th>
                                            <th>Kelas</th>
                                            <th>Tanggal Download</th>
                                            <th>Status</th>
                                            <th>Status Surat</th>
                                           <th>PDF</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach($historyGroup as $index => $history)
                                        @php
                                        $pengajuan = $history->pengajuanPkl;
                                        $siswa = $pengajuan->dataPribadi;
                                        @endphp
                                        <tr>
                                            <td>{{ $index + 1 }}</td>
                                            <td>{{ $siswa->name ?? '' }}</td>
                                            <td>{{ $siswa->kelas->kelas }} {{ $siswa->kelas->name_kelas ?? '-' }}</td>
                                            <td>{{ $history->created_at->format('d/m/Y H:i') ?? '' }}</td>
                                            <td>{{ $pengajuan->status ?? '' }}</td>
                                            <td>
                                                @if($history->status_surat == 'proses')
                                                <span class="badge bg-warning cursor-pointer update-status" 
                                                      data-history-id="{{ $history->id }}">Proses</span>
                                                @else
                                                <span class="badge bg-success">Sudah</span>
                                                @endif
                                            </td>
                                            <td><a href="{{ route('persuratan.suratBalasan.download', $pengajuan->id) }}" class="btn btn-danger btn-sm">
                                                <i class="bi bi-filetype-pdf"></i>
                                              </a></td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
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
<script>
    $(document).ready(function() {
        $('.update-status').click(function() {
            const historyId = $(this).data('history-id');
            const badgeElement = $(this);
            
            if (confirm('Apakah Anda yakin ingin mengubah status surat menjadi "Sudah"?')) {
                $.ajax({
                    url: '/update-status-surat',
                    type: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        history_id: historyId
                    },
                    success: function(response) {
                        if (response.success) {
                            badgeElement.removeClass('bg-warning').addClass('bg-success');
                            badgeElement.text('Sudah');
                            badgeElement.removeClass('cursor-pointer update-status');
                            toastr.success('Status surat berhasil diubah');
                        }
                    },
                    error: function(xhr) {
                        toastr.error('Terjadi kesalahan saat mengubah status');
                    }
                });
            }
        });
    });
</script>
@endpush
