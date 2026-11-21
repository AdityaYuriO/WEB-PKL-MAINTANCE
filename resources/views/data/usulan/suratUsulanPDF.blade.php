<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Surat Pengajuan PKL</title>
    <style>
        @page {
            size: 210mm 330mm;
            /* Ukuran kertas F4 */
            margin: 40px;
        }

        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        h3,
        p {
            text-align: center;
        }

        .kop-surat {
            text-align: center;
        }

        .kop-surat img {
            position: absolute;
            left: 40px;
            height: 100px;
            margin-left: 20px;
        }

        .teks {
            margin-left: 120px;
        }

        .kop-surat h3,
        .kop-surat h4,
        .kop-surat p {
            margin: 0;
            text-align: center;
        }

        .kop-surat h3 {
            font-size: 18px;
            font-weight: bold;
        }

        .kop-surat h4 {
            font-size: 16px;
        }

        .kop-surat p {
            font-size: 14px;
        }

        hr {
            border: 1px solid black;
            margin-top: 5px;
        }


        h3,
        h4,
        p {
            margin: 0;
            padding: 0;
        }

        table {
            width: 100%;
            border: none;
            border-collapse: collapse;
            margin-top: 20px;
        }

        th,
        td {
            padding: 10px;
            text-align: left;
            vertical-align: top;
        }

        td:first-child {
            width: 200px;
        }

        td:nth-child(2) {
            width: 5px;
        }

        .signature {
            text-align: center;
            margin-top: 50px;
            word-wrap: break-word;
        }

        .space {
            height: 80px;
            display: block;
        }

        .sign-table {
            width: 100%;
            margin-top: 30px;
            border: none;
        }

        .sign-table td {
            text-align: center;
            vertical-align: top;
            width: 50%;
        }

        .sign-table td strong {
            display: block;
            max-width: 200px;
            margin: 0 auto;
            word-wrap: break-word;
        }

        .sign-table td .space {
            display: block;
            height: 80px;
        }

        .sign-table td {
            vertical-align: top;
            padding: 10px;
        }
    </style>
</head>

<body>

    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/jawabarat.png'))) }}"
            alt="Logo Jawa Barat" />
        <div class="teks">
            <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h3>CABANG DINAS PENDIDIKAN WILAYAH XIII</h3>
            <h3>SMK NEGERI 1 KAWALI</h3>
            <p>Jalan Talagasari No.35 Tlp. (0265) 791727 E-Mail : smkn1kawali@gmail.com</p>
            <p>Kawali – Kabupaten Ciamis 46253</p>
        </div>
        <hr>
        <h5><u>SURAT PENGAJUAN MENCARI TEMPAT PKL TP 2026/2027</u></h5>
    </div>

    <table>
        <tr>
            <td>Nama</td>
            <td>:</td>
            <td>{{ $dataPribadi->name ?? '-' }}</td>
        </tr>
        <tr>
            <td>NIS</td>
            <td>:</td>
            <td>{{ $dataPribadi->nip ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kelas</td>
            <td>:</td>
            <td>{{ $dataPribadi->kelas->kelas ?? '-' }}{{ $dataPribadi->kelas->name_kelas ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kompetensi Keahlian</td>
            <td>:</td>
            <td>{{ $dataPribadi->konkes->name_konke ?? '-' }}</td>
        </tr>
    </table>

    <p style="text-align: justify;">Mengajukan tempat Praktik Kerja Lapangan (PKL) Tahun pelajaran 2026/2027, di :</p>

    <table>
        <tr>
            <td>Nama Institusi/Perusahaan</td>
            <td>:</td>
            <td>{{ $usulanIduka->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $usulanIduka->alamat ?? '-' }}</td>
        </tr>
        <tr>
            <td>Kompetensi Keahlian</td>
            <td>:</td>
            <td>{{ $usulanIduka->bidang_industri ?? '-' }}</td>
        </tr>
    </table>
    <p style="text-align: justify;">Demikian ajuan ini, untuk dipergunakan sebagaimana mestinya.</p>
    <br>
    <p style="text-align: right; margin-right: 75px;">Kawali, ............. {{ date('Y') }}</p>

    <br><br><br>

    <table width="100%" class="sign-table" style="text-align: center;">
        <tr>
            <td width="33%">
                Menyetujui,<br>
                @if ($dataPribadi->ttd_ortu === 'ayah')
                    Orang Tua (Ayah)<br><br><br><br>
                    <strong>{{ $dataPribadi->name_ayh }}</strong>
                @elseif($dataPribadi->ttd_ortu === 'ibu')
                    Orang Tua (Ibu)<br><br><br><br>
                    <strong>{{ $dataPribadi->name_ibu }}</strong>
                @else
                    Wali ({{ $dataPribadi->ttd_ortu }})<br><br><br><br>
                    <strong>{{ $dataPribadi->ttd_ortu_nama }}</strong>
                @endif
            </td>
<td width="33%">&nbsp;</td>
            <td width="33%">
                Yang Mengajukan,<br>
                Siswa<br><br><br><br>
                <strong>{{ $dataPribadi->name ?? '-' }}</strong>
            </td>
        </tr>

        <tr>
            <td colspan="3"><br><br></td>
        </tr>

        <tr>
            <td colspan="3" width="100%">
                Menyetujui,<br>
                Ketua KK {{ $singkatanJurusan }}<br><br><br>
                @if (isset($kaprog))
                    <strong>{{ $kaprog->name }}</strong><br>
                    NIP: {{ $kaprog->guru->nip ?? '-' }}
                @else
                    <strong>(...........................................)</strong><br>
                    NIP: .........................................
                @endif
            </td>
        </tr>
    </table>


</body>

</html>
