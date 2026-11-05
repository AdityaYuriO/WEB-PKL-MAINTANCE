@extends('layout.main')

@section('content')
<div class="container">
    <h2>Daftar Cetak Usulan</h2>
    <a href="{{ route('hubin.daftarcetak.download') }}" class="btn btn-success mb-3">
    Download Excel
</a>

    @foreach($cetakUsulans as $kelas => $items)
        <h4 class="mt-4">{{ $kelas }}</h4>
        <table class="table table-bordered">
            <thead>
                <tr>
                    <th>Nama Siswa</th>
                    <th>Nama IDUKA</th>
                    <th>ALAMAT IDUKA</th>
                    <th>Status</th>
                    <th>Dikirim</th>
                </tr>
            </thead>
            <tbody>
                @foreach($items as $item)
                @if ( $item->user->status == "aktif" )
                    <tr>
                        <td>{{ $item->dataPribadi->name ?? '-' }}</td>
                        <td>{{ $item->iduka->nama ?? '-' }}</td>
                        <td>{{ $item->iduka->alamat ?? '-' }}</td>
                        <td>{{ $item->status }}</td>
                        <td>{{ $item->dikirim ?? '-' }}</td>
                    </tr>
                @endif
                @endforeach
            </tbody>
        </table>
    @endforeach
</div>
@endsection
