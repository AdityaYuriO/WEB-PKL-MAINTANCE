<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Lembar Observasi dan Penilaian PKL</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #000; padding: 4px; }
        .no-border { border: none !important; }
        .header-table td { border: none; }
        .catatan { height: 60px; }
        .catatan-long { height: 120px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .gray { background: #eee; }
    </style>
</head>
<body>
    <h3 class="center">LEMBAR OBSERVASI DAN PENILAIAN</h3>
    <table class="header-table" style="margin-bottom:10px">
        <tr><td>Nama Peserta Didik</td><td>: {{ $siswa->name }}</td></tr>
        <tr><td>Konsentrasi Keahlian</td><td>: {{ $siswa->konke->nama_konsentrasi ?? '-' }}</td></tr>
        <tr><td>IDUKA Tempat PKL</td><td>: {{ $iduka }}</td></tr>
        <tr><td>Nama Instruktur</td><td>: .............................................</td></tr>
        <tr><td>Nama Guru Pembimbing</td><td>: {{ $guru }}</td></tr>
    </table>
    <table>
        <thead>
            <tr class="gray">
                <th>No</th>
                <th>Tujuan Pembelajaran / Indikator</th>
                <th>Ketercapaian Ya/Tidak</th>
                <th>Instruktur Iduka Nilai (0-100)</th>
                <th>Guru Pembimbing Nilai (0-100)</th>
                <th>Deskripsi</th>
            </tr>
        </thead>
        <tbody>
            @php $no = 1; @endphp
            @foreach($penilaianGuru as $g)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $g->tujuanPembelajaran->tujuan_pembelajaran ?? '-' }}</td>
                    <td>{{ $g->ketercapaian_indikator }}</td>
                    <td></td>
                    <td>{{ $g->nilai }}</td>
                    <td>{{ $g->deskripsi }}</td>
                </tr>
                @php $no++; @endphp
            @endforeach
            @foreach($penilaianIduka as $i)
                <tr>
                    <td>{{ $no }}</td>
                    <td>{{ $i->tujuanPembelajaran->tujuan_pembelajaran ?? '-' }}</td>
                    <td>{{ $i->ketercapaian_indikator }}</td>
                    <td>{{ $i->nilai }}</td>
                    <td></td>
                    <td>{{ $i->deskripsi }}</td>
                </tr>
                @php $no++; @endphp
            @endforeach
        </tbody>
    </table>
    <br>
    <table>
        <tr>
            <td class="bold">Skor Guru Pembimbing</td>
            <td>{{ $skorGuru }}</td>
        </tr>
        <tr>
            <td class="bold">Skor Instruktur Iduka</td>
            <td>{{ $skorIduka }}</td>
        </tr>
        <tr>
            <td class="bold">Nilai Akhir PKL</td>
            <td>{{ $skorAkhir }}</td>
        </tr>
        <tr>
            <td class="bold">Predikat</td>
            <td>{{ $predikat }}</td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="bold">Catatan Guru Pembimbing</td>
            <td class="catatan-long"></td>
        </tr>
        <tr>
            <td class="bold">Catatan Instruktur IDUKA</td>
            <td class="catatan-long"></td>
        </tr>
    </table>
    <br>
    <table>
        <tr>
            <td class="bold">Rentang Nilai</td>
            <td class="bold">Predikat</td>
        </tr>
        <tr><td>86 - 100</td><td>Sangat Baik</td></tr>
        <tr><td>71 - 85</td><td>Baik</td></tr>
        <tr><td>56 - 70</td><td>Cukup</td></tr>
    </table>
    <br>
    <div style="margin-top:40px">
        <table class="no-border" style="width:100%">
            <tr>
                <td class="no-border" style="width:50%">Guru Pembimbing<br><br><br>.............................................</td>
                <td class="no-border center" style="width:50%">Instruktur IDUKA,<br><br><br>.............................................</td>
            </tr>
        </table>
    </div>
</body>
</html>