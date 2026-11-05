
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pridasi Institusi / Perusahaan</title>
    <style>
        body { font-family: 'Times New Roman', Times, serif; font-size: 12pt; }
        .header { text-align: center; }
        .header h2, .header h3 { margin: 0; padding: 0; }
        .line { border-bottom: 2px solid black; margin: 10px 0; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        table, th, td { border: 1px solid black; text-align: center; padding: 5px; }
        .footer { margin-top: 50px; text-align: right; }
    </style>
</head>
<body>

    <!-- Header Surat -->
    <div class="header">
        <h2>SMK NEGERI 1 CONTOH</h2>
        <h3>Jl. Pendidikan No. 123, Kota ABC</h3>
        <p>Email: smkn1contoh@email.com | Telp: (021) 1234567</p>
        <div class="line"></div>
    </div>

    <!-- Judul Surat -->
    <h3 style="text-align: center; text-decoration: underline;">LAPORAN PRIDASI INSTITUSI</h3>
    <p style="text-align: center;">Tanggal: {{ now()->format('d-m-Y') }}</p>

    <!-- Tabel Data Pridasi Iduka -->
   <p>{{ $iduka->nama }}</p>
   <p>{{ $iduka->alamat }}</p>
   <p>{{ $iduka->kode_pos }}</p>
   <p>{{ $iduka->telepon }}</p>
   <p>{{ $iduka->email }}</p>
   <p>{{ $iduka->bidang_industri }}</p>
   <p>{{ $iduka->kerjasama }} {{ $iduka->kerjasama_lainnya }}</p>
   <p>{{ $iduka->kuota_pkl }}</p>
   <p>{{ $iduka->nama_pimpinan }}</p>
   <p>{{ $iduka->nip_pimpinan }}</p>
   <p>{{ $iduka->jabatan }}</p>
   <p>{{ $iduka->no_hp_pimpinan }}</p>
   
   <p>{{ $pembimbing->name }}</p>
   <p>{{ $pembimbing->nip }}</p>
   <p>{{ $pembimbing->no_hp }}</p>
   

    <!-- Tanda Tangan -->
    <div class="footer">
        <p>Kepala Sekolah</p>
        <br><br>
        <p><b>Drs. John Doe</b></p>
    </div>

</body>
</html>

