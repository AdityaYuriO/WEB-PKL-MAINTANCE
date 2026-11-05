@extends('layout.main')

@section('title', 'Data Pembimbing Institusi')

@section('content')

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Permohonan Domain</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 50px;
        }
        .container {
            width: 80%;
            margin: auto;
            padding: 20px;
            border: 1px solid #000;
            display: flex;
            align-items: flex-start;
        }
        .left-column {
            width: 20%;
            text-align: center;
        }
        .left-column img {
            width: 100%;
            max-width: 150px;
        }
        .right-column {
            width: 75%;
            padding-left: 20px;
        }
        .header {
            text-align: center;
            font-weight: bold;
        }
        .sub-header {
            text-align: center;
            font-size: 14px;
        }
        .content {
            margin-top: 20px;
            line-height: 1.6;
        }
        .signature {
            margin-top: 50px;
            text-align: right;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="left-column">
            <img src="{{ asset('images/logo.png') }}" alt="Logo">
        </div>
        <div class="right-column">
            <div class="header">
                PEMERINTAH KABUPATEN OGAN ILIR <br>
                DINAS PENDIDIKAN DAN KEBUDAYAAN <br>
                SD NEGERI 04 PAYARAMAN KEC. PAYARAMAN
            </div>
            <div class="sub-header">
                Alamat : Desa Tanjung Lalang Kecamatan Payaraman Kabupaten Ogan Ilir 30664
            </div>
            
            <div class="content">
                <p>No: 420/244/SDN.04-PYR/2017</p>
                <p>Hal: Permohonan Domain sch.id</p>
                <p>Lampiran: 1 berkas</p>
                <p>Tanjung Lalang, 01 November 2017</p>
                <br>
                <p>Dengan Hormat,</p>
                <p>Yang bertanda tangan di bawah ini:</p>
                <p>Nama: <strong>NURIPAH, S.Pd</strong></p>
                <p>NIP: 19660804 199008 2 001</p>
                <p>Jabatan: Kepala Sekolah</p>
                <br>
                <p>Bermaksud mengajukan permohonan pembelian domain <strong>sdn04payaraman.sch.id</strong> untuk keperluan
                    pembuatan website sekolah SDN 04 Payaraman Kecamatan Payaraman Kabupaten Ogan Ilir, sebagai persyaratan terlampir.
                </p>
                <p>Demikian permohonan ini kami sampaikan, atas kerjasama dan terkabulnya permohonan ini, kami sampaikan terima kasih.</p>
            </div>
            
            <div class="signature">
                <p>Hormat Kami,</p>
                <p>Kepala Sekolah</p>
                <p>SDN 04 PAYARAMAN</p>
                <br><br>
                <p>( tanda tangan )</p>
                <p><strong>NURIPAH S.Pd</strong></p>
                <p>NIP. 19660804 199008 2 001</p>
            </div>
        </div>
    </div>
</body>
</html>


@endsection
