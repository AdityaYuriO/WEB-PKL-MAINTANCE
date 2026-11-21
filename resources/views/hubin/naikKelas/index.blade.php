@extends('layout.main')
@section('content')
<div class="container-fluid py-4">

    <h4 class="mb-1">Naik Kelas & Kelulusan</h4>
    <p class="text-muted mb-4">
        Proses ini akan meluluskan (jadi alumni) semua siswa aktif di kelas XII,
        dan menaikkan semua siswa aktif di kelas XI ke kelas XII yang namanya cocok secara otomatis
        (misalnya "XI RPL 1" otomatis naik ke "XII RPL 1").
        Tindakan ini tidak bisa dibatalkan otomatis &mdash; pastikan sudah benar sebelum diproses.
    </p>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="row mb-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Siswa kelas XII (akan jadi alumni)</div>
                    <div class="h3 mb-0">{{ $jumlahCalonAlumni }} siswa</div>
                </div>
            </div>
        </div>
        <div class="col-md-6">
            <div class="card">
                <div class="card-body">
                    <div class="text-muted">Siswa kelas XI (akan naik ke XII)</div>
                    <div class="h3 mb-0">{{ $jumlahCalonNaik }} siswa</div>
                </div>
            </div>
        </div>
    </div>

    @if ($adaYangTidakKetemu)
        <div class="alert alert-warning">
            <strong>Ada kelas XI yang belum punya pasangan kelas XII dengan nama yang cocok</strong> (ditandai merah
            di tabel bawah). Buat dulu kelas XII-nya lewat menu Data Kelas dengan nama yang sesuai
            (misal "XI GIM" butuh kelas "XII GIM"), baru proses naik kelas bisa dijalankan.
        </div>
    @endif

    <form action="{{ route('naik-kelas.proses') }}" method="POST"
          onsubmit="return confirm('Yakin proses naik kelas & kelulusan sekarang? Tindakan ini tidak bisa dibatalkan otomatis.');">
        @csrf

        <div class="mb-3" style="max-width: 320px;">
            <label class="form-label">Tahun Ajaran Tujuan</label>
            <select name="tahun_ajaran_id" class="form-select" required>
                <option value="">-- Pilih Tahun Ajaran --</option>
                @foreach ($tahunAjaran as $ta)
                    <option value="{{ $ta->id }}">{{ $ta->tahun_ajaran }}</option>
                @endforeach
            </select>
        </div>

        <div class="card mb-4">
            <div class="card-header">Pemetaan Otomatis: Kelas XI &rarr; Kelas XII</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0 align-middle">
                    <thead>
                        <tr>
                            <th>Kelas XI Sekarang</th>
                            <th>Jumlah Siswa Aktif</th>
                            <th>Otomatis Naik ke</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($pemetaan as $p)
                            <tr class="{{ $p['tujuan'] ? '' : 'table-danger' }}">
                                <td>{{ $p['kelas']->name_kelas }}</td>
                                <td>{{ $p['kelas']->siswa_count }}</td>
                                <td>
                                    @if ($p['tujuan'])
                                        <span class="badge bg-success">{{ $p['tujuan']->name_kelas }}</span>
                                    @else
                                        <span class="badge bg-danger">Belum ada kelas tujuan</span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="3" class="text-center text-muted py-3">Tidak ada kelas XI.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-header">Kelas XII yang akan diluluskan (jadi alumni)</div>
            <div class="table-responsive">
                <table class="table table-bordered mb-0">
                    <thead>
                        <tr>
                            <th>Kelas XII</th>
                            <th>Jumlah Siswa Aktif</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($kelasXII as $k)
                            <tr>
                                <td>{{ $k->name_kelas }}</td>
                                <td>{{ $k->siswa_count }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="text-center text-muted py-3">Tidak ada kelas XII.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <button type="submit" class="btn btn-primary" {{ $adaYangTidakKetemu ? 'disabled' : '' }}>
            <i class="fas fa-arrow-up"></i> Proses Naik Kelas & Kelulusan
        </button>
        @if ($adaYangTidakKetemu)
            <span class="text-danger ms-2">Selesaikan dulu kelas yang belum punya pasangan di atas.</span>
        @endif
    </form>
</div>
@endsection
