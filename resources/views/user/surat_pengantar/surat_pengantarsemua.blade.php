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
            height: 145px;
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

       /* .signature-container {
            margin-top: 30px;
            text-align: left;
            margin-left: 30px;
        }

        .signature-position {
            font-weight: bold;
            margin-bottom: 10px;
        }

        .signature-box {
            border: 1px solid black;
            border-radius: 15px;
            padding: 10px 20px;
            display: inline-block;
            max-width: 300px;
        }

        .signature-box img {
            max-width: 100%;
            height: auto;
            display: block;
        } */




    </style>
</head>

<body>
    <div class="kop-surat">
        <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/jawabarat.png'))) }}" alt="Logo Jawa Barat" />
        <div class="teks">
            <p style="font-size: 20px;">PEMERINTAH DAERAH PROVINSI JAWA BARAT</p>
            <p style="font-size: 20px;">DINAS PENDIDIKAN</p>
            <p style="font-size: 20px;">CABANG DINAS PENDIDIKAN WILAYAH XIII</p>
            <p style="font-size: 20px;"><b>SMK NEGERI 1 KAWALI</b></p>
            <p >Jalan Talagasari No. 35 Tlp. (0265) 791727 Fax. (0265) 2797676 <br> e-Mail : smkn1kawali@gmail.com</p>
            <p>Kawali – 46253</p>
        </div>
        <hr>
        <p style="text-align: right;"> Kawali, {{ $tanggal }}

    </div>

    <table style="width: 100%; border-collapse: collapse; font-size: 14px;">
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Nomor</td>
            <!-- <td style="padding: 2px; width: 60%;">: {{ $suratPengantar->nomor ?? '-' }} </td> -->
            <td style="padding: 2px; width: 60%;">: </td>
            <td style="padding: 2px; width: 40%;">Kepada :</td>
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Lampiran</td>
            <td style="padding: 2px; width: 60%;">: </td>
            @if ($pengajuans->isNotEmpty())
            <td style="padding: 2px; width: 40%;">Yth. Pimpinan {{ $pengajuans->first()->iduka->nama ?? '-' }} </td>
            @endif
        </tr>
        <tr>
            <td style="padding: 0px; width: 10%; vertical-align: top;">Perihal</td>
            <!-- <td style="padding: 2px; width: 60%;">: {{ $suratPengantar->perihal ?? '-' }}</td> -->
            <td style="padding: 2px; width: 60%;">: Permohonan Praktik Kerja Lapangan (PKL)</td>
            <td style="padding: 2px; width: 40%;">Di<br>Tempat </td>
        </tr>
    </table>
    <br>

    <p style="margin-bottom:20px; font-size: 14px; text-align: justify; line-height: 1.5;">Sehubungan dengan pelaksanaan kegiatan Praktik Kerja Lapangan (PKL), dengan ini kami bermaksud mengajukan Permohonan Praktik Kerja Lapangan (PKL) yang akan dimulai pada tanggal <b>13 Oktober 2025 sampai dengan 14 Februari 2026</b> di Perusahaan/ Instansi Bapak/Ibu bagi peserta didik SMKN 1 Kawali atas nama:</p>


    <table border="1" cellspacing="0" cellpadding="5" style="border-collapse: collapse; width: 100%; font-size: 14px;">
        <tr style="text-align: center;">
            <th style="text-align: center;  width: 5%;">NO</th>
            <th style="text-align: center;  width: 30%;">NAMA</th>
            <th style="text-align: center;  width: 15%">KELAS</th>
            <th style="text-align: center;  width: 15%">NIS</th>
            <th style="text-align: center;  width: 30%;">KONSENTRASI KEAHLIAN</th>
        </tr>
        @foreach($pengajuans as $index => $pengajuan)
        <tr>
            <td style="width: 5%; text-align: center;">{{ $index + 1 }}</td>
            <td style="text-align: center; width: 30%;">{{ $pengajuan->dataPribadi->name }}</td>
            <td style="text-align: center; width: 15%;">{{ $pengajuan->dataPribadi->kelas->kelas }} {{ $pengajuan->dataPribadi->kelas->name_kelas }}</td>
            <td style="text-align: center; width: 15%;">{{ $pengajuan->dataPribadi->nip }} </td>
            <td style="text-align: center; width: 30%;">{{ $pengajuan->dataPribadi->konkes->name_konke }}</td>
        </tr>
        @endforeach


    </table>
    <br>

    <p style="margin-bottom:20px; font-size: 14px; text-align: justify; line-height: 1.5;">
        <!-- {{ $suratPengantar->deskripsi ?? '-' }} -->
        Tanpa mengurangi rasa hormat,  kami berharap Bapak/Ibu pimpinan dapat memfasilitasi kegiatan PKL ini. Adapun untuk keputusannya dapat diinformasikan sesuai dengan format yang telah kami lampirkan dan dapat dikirimkan melalui <i>WhatsApp</i> ke nomor xxxx-xxxx-xxxx atas nama xxxxxxxxxx dan/ atau ke nomor 0813-2034-3842 atas nama MUHAMAD GOJALI, S.Pd. <br>
        Atas perhatian dan kerjasamanya kami ucapkan terima kasih.
    </p>
    <br><br>

    <div class="mt-4" style="max-width: 320px; margin-left: auto; margin-right: 40px; text-align: center;">
        <p class="fw-bold mb-1" style="font-size: 12px;">Plh. KEPALA SMK NEGERI 1 KAWALI</p>
        <div class="border rounded p-3" style="padding: 0;">
            <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/ttd_kepsek.png'))) }}"
                alt="Tanda Tangan Waka Kurikulum"
                class="img-fluid"
                style="max-height: 160px; object-fit: contain; display: block; margin: 0 auto;" />
        </div>
    </div>









</body>

</html>