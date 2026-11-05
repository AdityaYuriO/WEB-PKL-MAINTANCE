@extends('layout.main')
@section('content')
<!-- nyobaan ngepush -->
<div class="container mt-4">
    <div class="card mb-3">
        <div class="card-body d-flex justify-content-between align-items-center">
            <h5 class="mb-0">History Dikirim ke Institusi</h5>
            <a href="{{ route('persuratan.review.historyDikirim') }}" class="btn btn-sm btn-outline-secondary">Refresh</a>
        </div>
    </div>

    @if($dataDikirim->isEmpty())
        <div class="alert alert-warning text-center">Belum ada pengajuan yang dikirim.</div>
    @else
    <div class="card">
        <div class="card-body">
            <div class="table-responsive text-nowrap">
                <table class="table table-bordered table-striped align-middle">
                    <thead class="table-primary">
                        <tr>
                            <th class="text-center">No</th>
                            <th>Nama Siswa</th>
                            <th>Nama Institusi</th>
                            <th>Tanggal Kirim</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php $no = 1; @endphp
                        @foreach($dataDikirim as $item)
                            
                            <tr>
                                <td class="text-center">{{ $no++ }}</td>
                                <td>{{ $item->dataPribadi->name }}</td>
                                <td>{{ $item->iduka->nama }}</td>
                                <td>{{ \Carbon\Carbon::parse($item->created_at)->translatedFormat('d F Y') }}</td>
                            </tr>
                           
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    @endif
</div>

@endsection

