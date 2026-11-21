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

        .kop-surat h3, .kop-surat h4, .kop-surat p {
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


        h3, h4, p {
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
        <p style="text-align: right;">Kawali, 21 Juni 2024</p>
    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 12px;">
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Nomor</td>
            <td style="padding: 2px; width: 60%;">: /PK.06/SMKN1KWL</td>
            <td style="padding: 2px; width: 40%;">Kepada :</td>
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Lampiran</td>
            <td style="padding: 2px; width: 60%;">: -</td>
            <td style="padding: 2px; width: 40%;">Yth. Pimpinan Inovindo Digital Media</td>
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Perihal</td>
            <td style="padding: 2px; width: 60%;">: Pengantar Pelaksanaan Praktik Kerja Lapangan (PKL)</td>
            <td style="padding: 2px; width: 40%;">Di<br>Bandung</td>
        </tr>
    </table>
    <br>

    <p style="margin-bottom:20px; font-size: 14px; text-align: justify; line-height: 1.5;">Sehubungan dengan pelaksanaan kegiatan Praktik Kerja Lapangan (PKL), dengan ini kami bermaksud mengajukan permohonan Praktik Kerja Lapangan (PKL) yang akan dimulai pada tanggal <b>13 Oktober 2025 sampai dengan 14 Februari 2026</b> di Perusahaan / Instansi Bapak/Ibu bagi peserta didik SMKN 1 Kawali atas nama :</p>

<table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%; font-size: 14px;">
    <tr style="text-align: center;">
        <th style="text-align: center;  width: 5%;">NO</th>
        <th style="text-align: center;  width: 30%;">NAMA</th>
        <th style="text-align: center">KELAS</th>
        <th style="text-align: center; width:15%">NIS</th>
        <th style="text-align: center; width: 25%;">KONSENTRASI KEAHLIAN</th>
    </tr>
    <tr>
        <td style="width: 5%; text-align: center;">1</td>
        <td style="text-align:center; width: 30%;">ADE FARHAN GUNAWAN</td>
        <td align="center">XII RPL 1</td>
        <td align="center; width:15%">222310217</td>
        <td style="text-align: center;  width: 25%;">Rekayasa Perangkat Lunak</td>
    </tr>
    
</table>
<br>

<p style="margin-bottom:20px; font-size: 14px; text-align: justify; line-height: 1.5;">
    Tanpa mengurangi rasa hormat,  kami berharap Bapak/Ibu pimpinan dapat memfasilitasi kegiatan PKL ini. Adapun untuk keputusannya dapat diinformasikan sesuai dengan format yang telah kami lampirkan dan dapat dikirimkan melalui <i>WhatsApp</i> ke nomor xxxx-xxxx-xxxx atas nama xxxxxxxxxx.
    Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
</p>

<p style="text-align: right; font-size: 14px; padding-right: 55px;">Plh. KEPALA SMK NEGERI 1 KAWALI</p>
</body>

</html>