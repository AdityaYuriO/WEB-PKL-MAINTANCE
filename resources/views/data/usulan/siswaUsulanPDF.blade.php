<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Usulan Institusi / Perusahaan Baru</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
        }

        h2 {
            text-align: center;
        }

        .table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }

        .table th, .table td {
            border-bottom: 1px solid black; /* Hanya garis horizontal */
            padding: 8px;
        }

        .table th {
            text-align: left;
            background-color: #f2f2f2;
        }

        .table td:first-child {
            width: 35%;
            font-weight: bold;
        }

        .table td:nth-child(2) {
            width: 2%;
            text-align: center;
            border: none; /* Menghilangkan garis vertikal */
        }

        .table td:last-child {
            width: 63%;
        }

    </style>
</head>
<body>

    <h2>Detail Usulan Institusi / Perusahaan Baru</h2>

    <h3>Detail Data Siswa</h3>
    <table class="table">
    <tr>
            <td>Nama Siswa</td>
            <td>:</td>
            <td>{{ $dataPribadi->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>{{ $dataPribadi->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td>Konsentrasi Keahlian</td>
            <td>:</td>
            <td>{{ $dataPribadi->konkes->name_konke ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $dataPribadi->kelas->name_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $dataPribadi->alamat_siswa ?? '-' }}</td>
        </tr>
        <tr>
            <td>No HP</td>
            <td>:</td>
            <td>{{ $dataPribadi->no_hp ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $dataPribadi->jk ?? '-' }}</td>
        </tr>
        <tr>
            <td>Agama</td>
            <td>:</td>
            <td>{{ $dataPribadi->agama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $dataPribadi->tempat_lhr ?? '-' }}, {{ $dataPribadi->tgl_lahir ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $dataPribadi->email ?? '-' }}</td>
        </tr>
    </table>

    <h3>Detail Data Institusi / Perusahaan Baru</h3>
    <table class="table">
    <tr>
            <td>Nama Institusi / Perusahaan</td>
            <td>:</td>
            <td>{{ $usulanIduka->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Nama Pimpinan</td>
            <td>:</td>
            <td>{{ $usulanIduka->nama_pimpinan ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIP/NIP Pimpinan</td>
            <td>:</td>
            <td>{{ $usulanIduka->nip_pimpinan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Jabatan</td>
            <td>:</td>
            <td>{{ $usulanIduka->jabatan ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat Lengkap</td>
            <td>:</td>
            <td>{{ $usulanIduka->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kode Pos</td>
            <td>:</td>
            <td>{{ $usulanIduka->kode_pos ?? '-' }}</td>
        </tr>
        <tr>
            <td>No Telepon</td>
            <td>:</td>
            <td>{{ $usulanIduka->telepon ?? '-' }}</td>
        </tr>
        <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $usulanIduka->email ?? '-' }}</td>
        </tr>
        <tr>
            <td>Bidang Industri</td>
            <td>:</td>
            <td>{{ $usulanIduka->bidang_industri ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kerja Sama</td>
            <td>:</td>
            <td>{{ $usulanIduka->kerjasama ?? '-' }}</td>
        </tr>
    </table>

</body>
</html>
