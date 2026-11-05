<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Semua Surat Pengantar PKL</title>
    <style>
        @page {
            margin: 1.5cm 2cm;
            size: A4;
        }

        body {
            font-family: 'Times New Roman', Times, serif;
            font-size: 11pt;
            line-height: 1.3;
            margin: 0;
            padding: 0;
            color: #000;
        }

        .header {
            display: table;
            width: 100%;
            border-bottom: 2.5px solid #000;
            padding-bottom: 6px;
            margin-bottom: 12px;
        }

        .logo-cell {
            display: table-cell;
            width: 80px;
            vertical-align: middle;
            padding-right: 12px;
        }

        .logo {
            width: 80px !important;
            height: auto !important;
            max-width: none !important;
        }

        .header-text {
            display: table-cell;
            text-align: center;
            vertical-align: middle;
        }

        .header-text h1 {
            font-size: 12pt;
            margin: 0.5px 0;
            font-weight: bold;
            line-height: 1.2;
        }

        .header-text p {
            margin: 0.5px 0;
            font-size: 9.5pt;
            line-height: 1.15;
        }

        .content {
            margin-top: 12px;
        }

        .two-column-section {
            display: table;
            width: 100%;
            margin-bottom: 16px;
        }

        .left-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-right: 20px;
        }

        .right-column {
            display: table-cell;
            width: 50%;
            vertical-align: top;
            padding-left: 150px;
        }

        .date-right {
            text-align: left;
            margin-bottom: 16px;
            font-size: 11pt;
        }

        .letter-info {
            margin-bottom: 2px;
            line-height: 1.3;
            font-size: 11pt;
        }

        .recipient-box {
            margin-top: 0;
            margin-bottom: 0;
        }

        .recipient-box p {
            margin: 1px 0;
            line-height: 1.3;
            font-size: 11pt;
        }

        .recipient-location {
            padding-left: 0;
        }

        .body-text {
            text-align: justify;
            text-indent: 0;
            margin-top: 0;
            margin-bottom: 8px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0 12px 0;
        }

        table,
        th,
        td {
            border: 1px solid #000;
        }

        th {
            padding: 5px 4px;
            text-align: center;
            font-weight: bold;
            background-color: #fff;
            font-size: 10pt;
            line-height: 1.2;
        }

        td {
            padding: 5px 4px;
            text-align: left;
            font-size: 10pt;
            line-height: 1.2;
        }

        td:first-child {
            text-align: center;
            width: 5%;
        }

        td:nth-child(3),
        td:nth-child(4) {
            text-align: center;
        }

        /* --- Bagian tanda tangan diubah di sini --- */
        .signature-section {
            margin-top: 70px;
            text-align: right;
            position: relative;
            right: 0;
            width: calc(50% - 150px);
            margin-left: auto;
        }

        .signature-section p {
            margin: 1.5px 0;
            line-height: 1.2;
            font-size: 11pt;
        }

        .signature-section img {
            width: 350px;
            height: auto;
            display: inline-block;
            margin-top: 10px;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">
        <div class="logo-cell">
            @php
                $path = public_path('images/jawabarat.png');
                $logoBase64 = '';
                if (file_exists($path)) {
                    $logoBase64 = base64_encode(file_get_contents($path));
                }
            @endphp

            @if ($logoBase64)
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo Jawa Barat"
                    style="width: 80px; height: auto;">
            @else
                <p style="color:red;">Logo tidak ditemukan di server</p>
            @endif
        </div>

        <div class="header-text">
            <h1>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h1>
            <h1>DINAS PENDIDIKAN</h1>
            <h1>CABANG DINAS PENDIDIKAN WILAYAH XIII</h1>
            <h1>SMK NEGERI 1 KAWALI</h1>
            <p>Jalan Talagasari No.35 Tlp. (0265) 791727 E-Mail : smkn1kawali@gmail.com</p>
            <p>Kawali â€“ Kabupaten Ciamis 46253</p>
        </div>
    </div>

    <div class="content">
        <div class="two-column-section">
            <div class="left-column">
                <p class="letter-info">Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: /PK.06/SMKN1KWL</p>
                <p class="letter-info">Lampiran : -</p>
                <p class="letter-info">Perihal&nbsp;&nbsp;&nbsp;&nbsp;: Keterangan Pindah Tempat
                    Praktik<br>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Kerja
                    Lapangan (PKL)</p>
            </div>

            <div class="right-column">
                <p class="date-right">
                    Kawali, {{ \Carbon\Carbon::now()->translatedFormat('d F Y') }}
                </p>
                <div class="recipient-box">
                    <p>Kepada :</p>
                    <p>Yth. Pimpinan <strong>{{ strtoupper($iduka->nama_pimpinan ?? '-') }}</strong></p>
                    <p>Di</p>
                    <p class="recipient-location">{{ ucwords(strtolower($iduka->alamat ?? '-')) }}</p>
                </div>
            </div>
        </div>

        <p style="text-align: justify; margin-bottom: 0;">
            Yang bertanda tangan di bawah ini Kepala SMK Negeri 1 Kawali menerangkan
        </p>
        <p style="text-align: justify; margin-top: 0;">
            Bahwa :
        </p>

        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>NIS</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($pindah as $index => $item)
                    <tr>
                        <td style="text-align:center">{{ $index + 1 }}</td>
                        <td>{{ strtoupper($item->name) }}</td>
                        <td style="text-align:center">
                            {{ $item->kelas->kelas ?? '-' }} {{ $item->kelas->name_kelas ?? '' }}
                        </td>
                        <td style="text-align:center">{{ $item->nip ?? '-' }}</td>
                        <td>{{ $item->kelas->konke->name_konke ?? '-' }}</td>
                        <td></td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <p class="body-text">
            Bermaksud mengundurkan diri sebagai siswa peserta Praktik Kerja Lapangan (PKL)
            dari .......................... untuk pindah ke .......................... yang beralamat di
            ..........................................................., dengan alasan
            ....................................................

        </p>

        <p class="body-text">
           Dengan demikian mohon memakluminya. Atas perhatian Bapak/ Ibu kami ucapkan terima kasih.
        </p>


        <div class="signature-section">
            @php
                $ttdPath = public_path('images/ttd_kepsek_baru.jpeg');
                $ttdBase64 = '';
                if (file_exists($ttdPath)) {
                    $ttdBase64 = base64_encode(file_get_contents($ttdPath));
                }
            @endphp

            @if ($ttdBase64)
                <img src="data:image/png;base64,{{ $ttdBase64 }}" alt="TTD Kepala Sekolah">
            @else
                <p style="color:red; text-align:right;">TTD Kepala Sekolah tidak ditemukan</p>
            @endif
        </div>
    </div>
</body>

</html>

