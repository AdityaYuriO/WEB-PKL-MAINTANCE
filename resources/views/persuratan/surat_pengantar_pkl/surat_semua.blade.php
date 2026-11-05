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
            width: 145px;
            /* Diperbesar lagi */
            vertical-align: middle;
            padding-right: 12px;
        }

        .logo {
            width: 145px !important;
            /* Diperbesar dengan !important */
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

        .signature-section {
            margin-top: 20px;
            text-align: center;
        }

        .signature-section p {
            margin: 1.5px 0;
            line-height: 1.2;
            font-size: 11pt;
        }

        .signature-space {
            height: 50px;
        }

        .signature-name {
            font-weight: bold;
            text-decoration: underline;
        }

        .page-break {
            page-break-after: always;
        }
    </style>
</head>

<body>
    <div class="header">

        <div class="logo-cell">
            @if (!empty($logoBase64))
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo Jawa Barat"
                    style="width: 140px; height: auto;">
            @else
                <!-- Fallback jika logo tidak ditemukan -->
                <div
                    style="width: 140px; height: 140px; border: 1px solid #ddd; display: flex; align-items: center; justify-content: center; background-color: #f9f9f9;">
                    <span style="color: #666; font-size: 10px; text-align: center;">LOGO<br>PEMERINTAH<br>DAERAH</span>
                </div>
            @endif
        </div>




        <div class="header-text">
            <h1>PEMERINTAH DAERAH PROVINSI JAWA BARAT</h1>
            <h1>DINAS PENDIDIKAN</h1>
            <h1>CABANG DINAS PENDIDIKAN WILAYAH XIII</h1>
            <h1>SMK NEGERI 1 KAWALI</h1>
            <p>Jalan Talagasari No.35 Tlp. (0265) 791727 E-Mail : smkn1kawali@gmail.com</p>
            <p>Kawali – Kabupaten Ciamis 46253</p>
        </div>
    </div>

    <div class="content">
        <div class="two-column-section">
            <div class="left-column">
                <p class="letter-info">Nomor&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;: ………………..</p>
                <p class="letter-info">Lampiran : -</p>
                <p class="letter-info">Perihal&nbsp;&nbsp;&nbsp;&nbsp;: Pengantar Pelaksanaan
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

        <p style="text-align: justify; text-indent: 0; margin-bottom: 0;">
            Menindaklanjuti surat balasan perihal penerimaan siswa–siswi kami untuk melaksanakan
            Praktik Kerja Lapangan (PKL) di Institusi/ Perusahaan Saudara,
        </p>
        <p style="text-align: justify; text-indent: 0; margin-top: 0;">
            kami tugaskan siswa sebagai berikut :
        </p>






        <table border="1" cellspacing="0" cellpadding="5" width="100%">
            <thead>
                <tr>
                    <th>No</th>
                    <th>Nama Siswa</th>
                    <th>Kelas</th>
                    <th>NIPD</th>
                    <th>Kompetensi Keahlian</th>
                    <th>Ket</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($siswa as $index => $item)
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
            Untuk melaksanakan Praktik Kerja Lapangan dalam rangka Pendidikan Sistem Ganda (PSG) pada Institusi/
            Perusahaan saudara selama 5 (lima) bulan, dengan waktu pelaksanaan dari tanggal 13 September 2025 s.d 14
            Februari 2026. Pada akhir kegiatan Praktik Kerja Lapangan, siswa diwajibkan menyerahkan Jurnal Kegiatan PKL
            yang telah diketahui dan disetujui oleh pihak institusi/ perusahaan, sebagai salah satu acuan dalam menilai
            kegiatan PKL siswa.
        </p>

        <p class="body-text">
            Sehubungan dengan hal tersebut kami mohon bantuan Saudara untuk membantu dalam pengisian dokumen (Jurnal dan
            Sertifikat) untuk keperluan administrasi kami.
        </p>

        <p class="body-text">
            Atas perhatian, bantuan dan kerjasama Saudara, kami ucapkan terima kasih.
        </p>
        <div class="signature-section">
            <p>Kepala Sekolah</p>
            <div class="signature-space"></div>
            <p class="signature-name">CEPY WAHYUDIN, A.Md., S.Kom., M.Kom.</p>
            <p>Penata Tk. I</p>
            <p>NIP. 19840825 201001 1 01</p>
        </div>
    </div>
</body>

</html>
