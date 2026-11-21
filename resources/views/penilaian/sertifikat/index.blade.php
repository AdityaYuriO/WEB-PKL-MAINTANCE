<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Sertifikat PKL</title>

<style>
    @page {
        margin: 0cm;
    }
    
    body {
        margin: 0;
        padding: 0;
        font-family: 'Times New Roman', Times, serif;
    }

    /* ===== BACKGROUND IMAGE ===== */
    .bg {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        z-index: -1;
    }

    /* ===== CONTENT CONTAINER ===== */
    .content {
        position: relative;
        z-index: 1;
        padding: 50px 80px 20px 80px; /* Ditambah agar tidak mepet atas bingkai */
    }

    /* ===== KOP SERTIFIKAT ===== */
    .kop-table {
        width: 100%;
        border-bottom: 3px solid #000;
        margin-bottom: 2px;
        padding-bottom: 15px; /* Menambah jarak antara alamat dan garis */
    }
    .kop-table-border {
        border-top: 1px solid #000;
        margin-bottom: 15px;
    }
    .kop-table td {
        vertical-align: middle;
    }
    .kop-logo {
        width: 15%;
        text-align: left;
    }
    .kop-logo img {
        max-width: 90px;
        max-height: 90px;
    }
    .kop-text {
        width: 85%;
        text-align: center;
        padding-right: 15%; /* Untuk mengimbangi logo di kiri agar teks tetap di tengah (kalo perlu) */
    }
    .kop-iduka {
        font-size: 26px;
        font-weight: bold;
        text-transform: uppercase;
        margin-bottom: 5px;
        color: #000;
    }
    .kop-alamat {
        font-size: 14px;
        color: #333;
    }

    /* ===== JUDUL SERTIFIKAT ===== */
    .cert-title {
        text-align: center;
        font-size: 36px;
        font-weight: bold;
        letter-spacing: 4px;
        margin-top: 10px;
        margin-bottom: 20px; /* Tambahan jarak agar tidak mepet */
        color: #0f766e;
    }
    .cert-subtitle {
        text-align: center;
        font-size: 16px;
        margin-bottom: 15px;
        font-style: italic;
    }

    /* ===== BIODATA ===== */
    .biodata {
        margin: 0 auto 15px auto;
        width: 75%;
    }
    .biodata table {
        width: 100%;
        font-size: 17px;
        line-height: 1.4;
    }
    .biodata td {
        vertical-align: top;
    }
    .biodata-label {
        width: 35%;
        font-weight: bold;
    }
    .biodata-colon {
        width: 3%;
        text-align: center;
    }
    .biodata-value {
        width: 62%;
    }

    /* ===== BODY TEXT ===== */
    .body-text {
        width: 90%;
        margin: 0 auto 40px auto; /* Ditambahkan agar tidak terlalu mepet dengan Footer */
        text-align: justify;
        font-size: 16px;
        line-height: 1.6;
        color: #222;
    }
    .predikat {
        font-weight: bold;
        color: #0f766e;
        font-size: 18px;
    }

    /* ===== FOOTER (FOTO & TTD) ===== */
    .footer-section {
        width: 90%;
        margin: 0 auto;
        position: relative;
        height: 160px;
    }

    /* 3x4 Photo placeholder (3cm x 4cm ~ 113px x 151px) */
    .foto-box {
        position: absolute;
        left: 0;
        top: 0;
        width: 113px;
        height: 151px;
        border: 2px dashed #333; /* Mengubah garis menjadi putus-putus */
        text-align: center;
    }
    .foto-box span {
        display: block;
        margin-top: 60px;
        color: #666;
        font-size: 12px;
    }

    /* Signature Box */
    .signature-box {
        position: absolute;
        right: 0;
        top: 0;
        width: 250px;
        text-align: center;
    }
    .signature-date {
        font-size: 16px;
        margin-bottom: 5px;
    }
    .signature-title {
        font-size: 16px;
        font-weight: bold;
    }
    .signature-space {
        height: 60px; /* Dikurangi agar muat */
    }
    .pimpinan-name {
        font-size: 16px;
        font-weight: bold;
        text-decoration: underline;
    }
</style>
</head>

<body>

    <!-- BACKGROUND -->
    <img src="{{ asset('images/templat-sertifikat.png') }}" class="bg">

    <!-- CONTENT -->
    <div class="content">

        <!-- KOP SERTIFIKAT -->
        <table class="kop-table">
            <tr>
                <td class="kop-logo">
                    @if(!empty($foto_iduka))
                        <img src="{{ asset('storage/' . $foto_iduka) }}">
                    @endif
                </td>
                <td class="kop-text">
                    <div class="kop-iduka">{{ $nama_iduka ?? 'NAMA IDUKA' }}</div>
                    <div class="kop-alamat">{{ $alamat_iduka ?? 'Alamat Iduka Belum Diatur' }}</div>
                </td>
            </tr>
        </table>
        <div class="kop-table-border"></div>

        <!-- JUDUL -->
        <div class="cert-title">SERTIFIKAT</div>
        <div class="cert-subtitle">Diberikan kepada:</div>

        <!-- BIODATA -->
        <div class="biodata">
            <table>
                <tr>
                    <td class="biodata-label">Nama Siswa</td>
                    <td class="biodata-colon">:</td>
                    <td class="biodata-value"><b>{{ $nama ?? 'Nama Siswa' }}</b></td>
                </tr>
                <tr>
                    <td class="biodata-label">Nomor Induk Siswa</td>
                    <td class="biodata-colon">:</td>
                    <td class="biodata-value">{{ $nis ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="biodata-label">Tempat, Tanggal Lahir</td>
                    <td class="biodata-colon">:</td>
                    <td class="biodata-value">{{ $tempat_lahir ?? '-' }}, {{ $tgl_lahir ?? '-' }}</td>
                </tr>
                <tr>
                    <td class="biodata-label">Asal Sekolah</td>
                    <td class="biodata-colon">:</td>
                    <td class="biodata-value">{{ $asal_sekolah ?? 'SMKN 1 Kawali' }}</td>
                </tr>
                <tr>
                    <td class="biodata-label">Tahun Pelajaran</td>
                    <td class="biodata-colon">:</td>
                    <td class="biodata-value">{{ $tahun_ajaran ?? '-' }}</td>
                </tr>
            </table>
        </div>

        <!-- PARAGRAF ISI -->
        <div class="body-text">
            Telah melaksanakan Praktik Kerja Lapangan (PKL) untuk Konsentrasi Keahlian
            <b>{{ $konsentrasi ?? 'Konsentrasi' }}</b> selama {{ $lama ?? 'waktu' }} dari tanggal
            <b>{{ $tanggal_mulai ?? 'tanggal mulai' }}</b> sampai dengan <b>{{ $tanggal_selesai ?? 'tanggal selesai' }}</b>
            dengan nilai yang tercantum di Rapor dengan Predikat :
            @if(!empty($predikat))
                <span class="predikat">{{ $predikat }}</span>
            @endif
        </div>

        <!-- FOOTER: FOTO & TTD -->
        <div class="footer-section">
            <div class="foto-box">
                <span>Foto 3x4</span>
            </div>

            <div class="signature-box">
                <div class="signature-date">{{ $kota_iduka ?? 'Kawali' }}, 14 Februari 2026</div>
                <div class="signature-title">Pimpinan IDUKA,</div>
                <div class="signature-space"></div>
                <div class="pimpinan-name">{{ $nama_pimpinan ?? 'Nama Pimpinan' }}</div>
            </div>
        </div>

    </div>

</body>
</html>