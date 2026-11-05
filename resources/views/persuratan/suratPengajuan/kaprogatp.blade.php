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

        .no-border-table,
        .no-border-table th,
        .no-border-table td {
            border: none;
            /* Menghilangkan border */
            background-color: white;
            color: black;
        }

        .border-table,
        .border-table th,
        .border-table td {
            border: 1px solid black;
            background-color: white;
            color: black;
        }

        th,
        td {
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
                <td>: {{ $iduka->nama }}</td>
            </tr>
            <tr>
                <th>2. Alamat Institusi / DUDI</th>
                <td>: {{ $iduka->alamat }}</td>
            </tr>
            <tr>
                <th>3. Bidang Usaha / Kerja</th>
                <td>: {{ $iduka->bidang_industri }}</td>
            </tr>
            <tr>
                <th>4. Nomor Telepon / HP Perusahaan></th>
                <td>: {{ $iduka->telepon }}</td>
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
                <td>: {{ $iduka->nama_pimpinan }}</td>
            </tr>

            <tr>
                <th style="font-weight: normal">
                    <div class="isi">
                        Induk Pegawai/ Nomor Induk Karyawan
                    </div>
                </th>
                <td>: {{ $iduka->nip_pimpinan }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> Jabatan </div>
                </th>
                <td>: {{ $iduka->jabatan }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> HP / Telepon</div>
                </th>
                <td>: {{ $iduka->no_hp_pimpinan }}</td>
            </tr>
            <tr>
                <th><span>B. Pembimbing Institusi/ Perusahaan</span></th>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> Nama Lengkap</div>
                </th>
                <td>: {{ $iduka->user->pembimbingpkl->name ?? '-' }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> Induk Pegawai/ Nomor Induk Karyawan</div>
                </th>
                <td>: {{ $iduka->user->pembimbingpkl->nip ?? '-' }}</td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi"> HP / Telepon </div>
                </th>
                <td>: {{ $iduka->user->pembimbingpkl->no_hp ?? '-' }}</td>
            </tr>
            <tr>
                <th>6. Apakah institusi / perusahaan akan menerbitkan surat keterangan atau sertifikat di cetak oleh perusahaan atau dibantu pihak sekolah?</th>
                <td>
                    <strong>: @if ($iduka->kolom6 === 'Ya')
                        Cetak oleh perusahaan
                        @elseif ($iduka->kolom6 === 'Tidak')
                        Dibantu pihak sekolah
                        @else
                        -
                        @endif</strong>
                </td>
            </tr>
            <tr>
                <th>7. Apakah di institusi / perusahaan ada SOP (Standar Operasional Prosedur) / Aturan Kerja / Tata tertib?</th>
                <td>
                    <strong>: @if ($iduka->kolom6 === 'Ya')
                        Ya
                        @elseif ($iduka->kolom6 === 'Tidak')
                        Tidak
                        @else
                        -
                        @endif</strong>
                </td>
            </tr>
            <tr>
                <th>8. Apakah di institusi / perusahaan menerapkan K3LH (kesehatan, keselamatan kerja, dan lingkungan hidup)?</th>
                <td>
                    <strong>: @if ($iduka->kolom6 === 'Ya')
                        Ya
                        @elseif ($iduka->kolom6 === 'Tidak')
                        Tidak
                        @else
                        -
                        @endif</strong>
                </td>
            </tr>
            <tr>
                <th style="font-weight: normal">
                    <div class="isi">Logo Perusahaan</div></th>
                    <td>
                        @if($iduka->foto)
                        <img src="{{ asset('storage/' . $iduka->foto) }}"
                            alt="Foto Institusi"
                            class="img-thumbnail"
                            style="max-width: 200px; max-height: 200px;">

                        @else
                        <span class="text-muted">Tidak ada foto</span>
                        @endif
                    </td>
            </tr>
        </table>
        <div class="notes">
            <h3>Ket :</h3>
            <ol>
                <li>
                    Seluruh data <strong>institusi/ perusahaan tempat PKL</strong> diinput melalui link
                    <a href="https://bit.ly/DataIDUKA2026" target="_blank">https://bit.ly/DataIDUKA2026</a>
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
        @foreach ($iduka->atps->groupBy('konke_id') as $konkeId => $atpsGroup)
        @php
        $konkeName = $atpsGroup->first()->konke->name_konke ?? 'Tanpa Konke';
        @endphp

        <h3>Konsentrasi Keahlian: <strong>{{ $konkeName }}</strong></h3>

        <table class="border-table">
            @php
            $grouped = $atpsGroup->groupBy(function($item) {
            return $item->cp->cp ?? 'Tanpa CP';
            });
            @endphp

            @foreach ($grouped as $cpName => $atpList)
            <tr>
                <th>{{ $loop->iteration }}. {{ $cpName }}</th>
                <th class="status">Status</th>
            </tr>
            @foreach ($atpList as $index => $atp)
            <tr>
                <td>{{ $index + 1 }}. {{ $atp->atp->atp ?? '-' }}</td>
                <td>

                    @if($atp->is_selected == 1)
                    <strong style="color:green">TERPILIH</strong>
                    @else
                    <strong>-</strong>
                    @endif

                </td>


            </tr>
            @endforeach
            @endforeach

        </table>
        @endforeach
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
