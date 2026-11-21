<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Surat Pengajuan PKL</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: white; 
            color: black; 
            font-size: 14px; 
        }
        table {
            width: 100%; 
            border-collapse: collapse;
            margin-bottom: 20px;
            background-color: white; 
            color: black; 
        }
        .no-border-table, .no-border-table th, .no-border-table td {
            border: none; /* Menghilangkan border */
            background-color: white; 
            color: black; 
        }
        .border-table, .border-table th, .border-table td {
            border: 1px solid black;
            background-color: white; 
            color: black; 
        }
        th, td {
            padding: 6px; 
            text-align: left; 
            line-height: 1.2; 
        }
        th span {
            display: block;
            margin-left: 20px;
        }

        .isi {
            display: block;
            margin-left: 40px;   
        }
        .status {
            width: 10%;
        }
        .page th {
            font-weight: bold; 
            width: 50%; 
        }
        .page td {
            width: 50%; 
        }
        .form-title {
            text-align: center;
            font-size: 18px; 
            font-weight: bold;
            margin-bottom: 20px;
            color: black; 
        }
        .form-section {
            margin-bottom: 20px;
        }
        .form-section h2 {
            font-size: 16px; 
            margin-bottom: 10px;
            color: black; 
        }
        .form-section p {
            margin: 5px 0;
            color: black; 
            line-height: 1.2; 
        }
        .form-section .note {
            font-size: 14px; 
            color: black; 
        }
        .form-section .signature {
            text-align: right;
            margin-top: 20px;
            color: black; 
        }
        .page-break {
            page-break-before: always;
        }
    </style>
</head>
<body>
    <!-- Halaman Pertama -->
    <div class="page">
        <div class="form-title">DATA INSTITUSI/ PERUSAHAAN TEMPAT PKL</div>
        <!-- Tabel pertama tanpa border -->
        <table class="no-border-table">
            <tr>
                <th>1. Nama Institusi/ DUDI</th>
                <td>: {{ $pengajuan->iduka->nama }}</td>
            </tr>
            <tr>
                <th>2. Alamat Institusi / DUDI</th>
                <td>: {{ $pengajuan->iduka->alamat }}</td>
            </tr>
            <tr>
                <th>3. Bidang Usaha / Kerja</th>
                <td>: {{ $pengajuan->iduka->bidang_industri }}</td>
            </tr>
            <tr>
                <th>4. Nomor Telepon / HP Perusahaan></th>
                <td>: {{ $pengajuan->iduka->telepon }}</td>
            </tr>
            <tr>
                <th>5. Yang akan menandatangani sertifikat PKL</th>
                <td>

                </td>
            </tr>
            <tr>
                <th><span> A. Kepala/ Pimpinan institusi/ Perusahaan</span></th>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi">
                        Nama Lengkap
                    </div>
                </th>
                <td>: {{ $pengajuan->iduka->nama_pimpinan }}</td>
            </tr>
            
            <tr>
                <th style="font-weight: normal">
                    <div class="isi">
                        Induk Pegawai/ Nomor Induk Karyawan
                    </div>
                </th>
                <td>: {{ $pengajuan->iduka->nip_pimpinan }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                   <div class="isi"> Jabatan </div>
                </th>
                <td>: {{ $pengajuan->iduka->jabatan }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> HP / Telepon</div>
                </th>
                <td>: {{ $pengajuan->iduka->no_hp_pimpinan }}</td>
            </tr>
            <tr>
                <th><span>B. Pembimbing Institusi/ Perusahaan</span></th>
            </tr>
            <tr>
                <th style="font-weight: normal"> 
                    <div class="isi"> Nama Lengkap</div>
                </th>
                <td>: {{ $pengajuan->iduka->user->pembimbingpkl->name ?? '-' }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                     <div class="isi"> Induk Pegawai/ Nomor Induk Karyawan</div>
                    </th>
                <td>: {{ $pengajuan->iduka->user->pembimbingpkl->nip ?? '-' }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal"><div class="isi"> HP / Telepon </div>
                </th>
                <td>: {{ $pengajuan->iduka->user->pembimbingpkl->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <th>6. Apakah institusi / perusahaan akan menerbitkan surat keterangan atau sertifikat di cetak oleh perusahaan atau dibantu pihak sekolah?</th>
                <td>
                    <strong>: Cetak oleh perusahaan</strong>
                </td>
            </tr>
            <tr>
                <th>7. Apakah di institusi / perusahaan ada SOP (Standar Operasional Prosedur) / Aturan Kerja / Tata tertib?</th>
                <td>
                    <strong>: Ya</strong>
                </td>
            </tr>
            <tr>
                <th>8. Apakah di institusi / perusahaan menerapkan K3LH (kesehatan, keselamatan kerja, dan lingkungan hidup)?</th>
                <td>
                    <strong>: Ya</strong>
                </td>
            </tr>
        </table>
        <div class="notes">
            <h3>Ket :</h3>
            <ol>
                <li>
                    Seluruh data <strong>institusi/ perusahaan tempat PKL</strong> diinput melalui link 
                    <a href="https://bit.ly/DataDUDIKA2024" target="_blank">https://bit.ly/DataDUDIKA2024</a>
                </li>
                <li>
                    Jika cetak sertifikat atau surat keterangan PKL akan dibantu oleh pihak sekolah, mohon untuk dapat mengirimkan <strong>Logo dan Kop surat perusahaan</strong> untuk kami cantumkan di berkas Sertifikat.
                    <ul>
                        <li><em>Jika logo dalam bentuk file</em>, bisa di transfer via <strong>WhatsApp</strong>, email, atau flashdisk murid/guru yang <em>menerima berkas ini</em>.</li>
                        <li><em>Jika terdapat di web</em>, mohon diisikan alamat web-nya. <strong>Alamat web</strong>: .......................................</li>
                        <li><em>Jika logo terdapat di brosur, pamflet, atau bon</em>, mohon ijin untuk kami bawa atau kami foto sebagai contoh untuk kami <strong>desain ulang</strong>.</li>
                    </ul>
                </li>
                <li>
                    *) Pilih salah satu / <del>coret</del> yang tidak sesuai
                </li>
            </ol>
        </div>
    </div>

    <div class="page-break"></div>
    
    <div class="form-section">
        <h2>AKTIFITAS KERJA / KOMPETENSI YANG TERDAPAT DI INSTITUSI/ PERUSAHAAN</h2>
        <p>Konsentrasi Keahlian: <strong>Manajemen Perkantoran</strong></p>
        <table class="border-table">
            <tr>
                <th>1. Merancang dan Mengelola Basis Data</th>
                <th class="status">Status</th>
            </tr>
            <tr>
                <td>1. Membuat kelompok perintah yang termasuk dalam Data Definition Language, Data Manipulation Language dan Data Control Language dalam basis data.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>2. Menerapkan perintah Structured Query Language (SQL) bertingkat, function and stored procedure dan trigger dalam basis data.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>3. Menerapkan proses backup, restore dalam basis data.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <th>2. Pemrograman Berbasis Teks, Grafis, dan Multimedia</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>1. Membuat pemrograman terstruktur dan pemrograman berorientasi objek tingkat lanjut.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>2. Membuat pemodelan perangkat lunak berorientasi objek.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>3. Melakukan pemrograman antar muka grafis (Graphical User Interface) pengembangan perangkat lunak.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <th>3. Merancang Program Aplikasi Berbasis Web</th>
                <th>Status</th>
            </tr>
            <tr>
                <td>1. Menerapkan pembuatan program aplikasi halaman web menggunakan server side scripting (PHP).</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>2. Menerapkan teknologi framework dalam pembuatan aplikasi web.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>3. Menerapkan basis data dalam pemrograman Web.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>4. Menerapkan dokumentasi dan mempresentasikan hasil pembuatan aplikasi berbasis web yang telah dikembangkan.</td>
                <td><strong>✓</strong></td>
            </tr>
            <tr>
                <td>5. Membuat UI/UX dalam penerapan pemrograman web.</td>
                <td><strong>✓</strong></td>
            </tr>

        </table>
    </div>
    <div class="form-section signature" style="text-align: right;">
        <p>.................., <span style="margin-left: 20px;">............................. 2025</span></p>
        <p>Pimpinan institusi/ Perusahaan,</p>
        <br><br><br><br>
        <p>(<span style="text-decoration: underline dotted;">............................................</span>)</p>
        <p>NIP.</p>
    </div>
    
</body>
</html>