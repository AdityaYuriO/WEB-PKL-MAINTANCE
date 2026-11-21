<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Balasan PKL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            padding: 0;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header h2 {
            margin: 0;
            font-size: 20px;
            text-decoration: underline;
        }
        .content {
            line-height: 1.8;
            font-size: 14px;
            text-align: justify;
        }
        .info-table {
            width: 100%;
            margin-bottom: 20px;
        }
        .info-table td {
            padding: 4px 8px;
            vertical-align: top;
        }
        .info-table .label {
            width: 220px;
        }
        .footer {
            margin-top: 50px;
            text-align: right;
            font-size: 14px;
        }
        .footer p {
            margin: 4px 0;
        }
        .student-table {
            width: 100%;
            border-collapse: collapse;
            margin: 20px 0;
            font-size: 14px;
        }
        .student-table th, .student-table td {
            border: 1px solid #ddd;
            padding: 10px;
            text-align: center;
        }
        .student-table th {
            background-color: #f9f9f9;
        }
    </style>
</head>
<body>

    <div class="header">
        <h2>SURAT PERNYATAAN</h2>
    </div>

    <div class="content">
        <p>Yang bertandatangan di bawah ini:</p>

        @php
            $firstPengajuan = $pengajuans->first();
            $iduka = $firstPengajuan->iduka ?? null;
        @endphp

        @if($iduka)
        <table class="info-table">
            <tr>
                <td class="label">Nama Lengkap</td>
                <td>:</td>
                <td>{{ $iduka->nama }}</td>
            </tr>
            <tr>
                <td class="label">Perusahaan / Institusi</td>
                <td>:</td>
                <td>{{ $iduka->bidang_industri }}</td>
            </tr>
            <tr>
                <td class="label">Alamat Perusahaan / Institusi</td>
                <td>:</td>
                <td>{{ $iduka->alamat }}</td>
            </tr>
            <tr>
                <td class="label">Contact Person</td>
                <td>:</td>
                <td>{{ $iduka->telepon }}</td>
            </tr>
        </table>

        <p>
            Dengan ini menyatakan bahwa kami 
            <b>(Bersedia / Tidak Bersedia)</b> menerima siswa / siswi SMK Negeri 1 Kawali untuk 
            melaksanakan Praktik Kerja Lapangan (PKL) dari tanggal 
            <strong>13 Oktober 2025</strong> sampai dengan tanggal 
            <strong>14 Februari 2026</strong> dengan jumlah siswa {{ $pengajuans->count() }} orang.
        </p>

        <p>Nama - nama siswa / siswi tersebut adalah sebagai berikut:</p>

        <table class="student-table">
            <thead>
                <tr>
                    <th>NO</th>
                    <th>NAMA</th>
                    <th>KELAS</th>
                    <th>NIS</th>
                    <th>TEMPAT</th>
                    <th>KETERANGAN</th>
                </tr>
            </thead>
            <tbody>
                @foreach($pengajuans as $index => $pengajuan)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ $pengajuan->dataPribadi->name ?? '' }}</td>
                        <td>{{ $pengajuan->dataPribadi->kelas->kelas ?? '' }} {{ $pengajuan->dataPribadi->kelas->name_kelas ?? '' }}</td>
                        <td>{{ $pengajuan->dataPribadi->nip ?? '' }}</td>
                        <td>{{ $pengajuan->dataPribadi->alamat_siswa ?? '' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p>
            Diharapkan siswa / siswi bersangkutan dapat melaksanakan kegiatan sesuai peraturan dan ketentuan yang berlaku di Perusahaan / Institusi kami.
        </p>

        <p>
            Demikian surat pernyataan ini dibuat untuk dipergunakan sebagaimana mestinya.
        </p>

        <div class="footer">
            <table style="width: 100%;">
                <tr>
                    <td style="width: 76%;"></td>
                    <td style="text-align: center;">
                        <p>Ciamis, {{ $tanggalHariIni }}</p>
                        <br><br><br>
                        <p>....................</p>
                    </td>
                </tr>
            </table>
        </div>
        @else
            <p>Data IDUKA tidak tersedia.</p>
        @endif
    </div>

</body>
</html>
