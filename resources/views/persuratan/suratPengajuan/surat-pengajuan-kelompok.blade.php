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
    </style>
</head>

<body>

    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/jawabarat.png'))) }}" alt="Logo Jawa Barat" />
        <div class="teks">
            <h3>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h3>
            <h3>DINAS PENDIDIKAN</h3>
            <h3>CABANG DINAS PENDIDIKAN WILAYAH XIII</h3>
            <h3>SMK NEGERI 1 KAWALI</h3>
            <p>Jalan Talagasari No.35 Tlp. (0265) 791727 E-Mail : smkn1kawali@gmail.com</p>
            <p>Kawali – Kabupaten Ciamis 46253</p>
        </div>
        <hr>
        <p style="text-align: right;"> {{ $suratPengantar->tempat }}, {{ \Carbon\Carbon::parse($suratPengantar->tanggalbuat)->translatedFormat('d F Y') }}

    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Nomor</td>
            <td style="padding: 2px; width: 60%;">: {{ $suratPengantar->nomor ?? '-' }} </td>
            <td style="padding: 2px; width: 40%;">Kepada :</td>
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Lampiran</td>
            <td style="padding: 2px; width: 60%;">: -</td>
            <td style="padding: 2px; width: 40%;">Yth. Pimpinan {{ $pengajuan->iduka->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Perihal</td>
            <td style="padding: 2px; width: 60%;">: {{ $suratPengantar->perihal ?? '-' }}</td>
            <td style="padding: 2px; width: 40%;">Di<br>Tempat </td>
        </tr>
    </table>
    <br>

    <p style="margin-bottom:20px; font-size: 12px; text-align: left;">Menindaklanjuti surat balasan perihal penerimaan siswa–siswi kami untuk melaksanakan Praktik Kerja Lapangan (PKL) di Institusi / Perusahaan Saudara, kami tugaskan siswa sebagai berikut :</p>

    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%; font-size: 12px;">
        <tr style="text-align: center;">
            <th style="text-align: center;  width: 5%;">NO</th>
            <th style="text-align: center;  width: 30%;">NAMA</th>
            <th style="text-align: center">KELAS</th>
            <th style="text-align: center; width:15%">NIS</th>
            <th style="text-align: center; width: 25%;">KONSENTRASI KEAHLIAN</th>
            <th style="text-align: center">KET</th>
        </tr>
        @foreach($pengajuans as $index => $pengajuan)
        <tr>
            <td style="width: 5%; text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center; width: 30%;">{{ $pengajuan->dataPribadi->name }}</td>
            <td align="center">{{ $pengajuan->dataPribadi->kelas->kelas }} {{ $pengajuan->dataPribadi->kelas->name_kelas }}</td>
            <td align="center">{{ $pengajuan->dataPribadi->nip }} </td>
            <td style="text-align: center;">{{ $pengajuan->dataPribadi->konkes->name_konke }}</td>
            <td></td>
        </tr>
        @endforeach


    </table>
    <br>

    <p style="margin-bottom:20px; font-size: 12px; text-align: justify;">
        {{ $suratPengantar->deskripsi ?? '-' }}
    </p>

    @if(file_exists(public_path('images/ttd.png')))
    <div class="ttd">
        <img src="{{ public_path('images/ttd.png') }}" alt="Tanda Tangan" width="150">
    </div>
    @else
    <p>Tanda tangan belum tersedia</p>
    @endif




    {{--
    <table width="100%" style="font-size: 14px;">
        <tr>
            <td style="vertical-align: top; width: 60%;">
                <p><strong>Nomor</strong> : {{ $suratPengantar->nomor ?? '-' }}</p>
    <p><strong>Lampiran</strong> : -</p>
    <p><strong>Perihal</strong> :{{ $suratPengantar->perihal?? '-' }}<br>
        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kerja Lapangan (PKL)</p>
    </td>
    <td style="vertical-align: top; width: 40%; text-align: right;">
        <p>Kepada :</p>
        <p>Yth. Pimpinan {{ $pengajuan->iduka->nama ?? '-' }} <br>
            Di<br>
            Tempat </p>
    </td>
    </tr>
    </table> --}}

    {{-- <table>
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

    <p style="text-align: justify;">Mengajukan tempat Praktik Kerja Lapangan (PKL) Tahun pelajaran 2025/2026, di :</p>

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
    <p style="text-align: right; margin-right: 75px;">Kawali, .............2025</p>

    <table class="sign-table">
        <tr>
            <td>Mengetahui,<br>Orang Tua/Wali<br>
                <div class="space"></div>
                <strong>{{ $dataPribadi->name_ayh ?? '-' }}</strong>
            </td>
            <td>Yang Mengajukan,<br>Siswa<br>
                <div class="space"></div>
                <strong>{{ $dataPribadi->name ?? '-' }}</strong>
            </td>
        </tr>
        <tr>
            <td colspan="2">Disetujui,<br>Kaprog<br>
                <div class="space"></div>
                @if(isset($kaprog))
                <strong>{{ $kaprog->nama }}</strong><br>
                NIP: {{ $kaprog->nip ?? '-' }}
                @else
                <strong>(...........................................)</strong><br>
                NIP: .........................................
                @endif
            </td>
        </tr>
    </table> --}}

</body>

</html>