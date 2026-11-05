@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Detail Data Nama Siswa</title>
        <style>
            .nav-tabs .nav-link.active {
                background-color: #9f9fff6c;
            }

            .nav-tabs .nav-link {
                background-color: white;
                color: white;
                border: 1px solid white;

            }

            .nav-tabs .nav-link:hover {
                background-color: 9f9fff;
                color: white;
            }

            .table td {
                vertical-align: middle;
            }

            .table td:first-child {
                width: 40%;
                text-align: left;
            }

            .table td:nth-child(2) {
                width: 1%;
                text-align: right;
            }

            .table td:last-child {
                width: 55%;
                text-align: left;
            }

            .btn-back {
                background-color: #7e7dfb;
                color: white;
                box-shadow: 0 8px 16px rgba(0, 0, 0, 0.2);
                border: none;
                padding: 10px 20px;
                border-radius: 5px;
                font-size: 16px;
                cursor: pointer;
                transition: transform 0.2s ease-in-out, box-shadow 0.2s ease-in-out, background-color 0.2s ease-in-out;
            }

            .btn-back:hover {
                background-color: #7e7dfb;
                color: white;
                transform: translateY(-3px);
                box-shadow: 0 12px 24px rgba(0, 0, 0, 0.25);
            }

            .btn-back:active {
                color: white;
                background-color: #6b6bfa !important;
                transform: translateY(3px);
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">
                        <div class="col-md-12 mt-3">
                            <div class="card shadow-sm border-0 mb-4">
                                @if(session()->has('success'))
                                    <div class="alert alert-success alert-dismissible fade show" role="alert">
                                        {{ session('success') }}
                                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                    </div>
                                @endif
                                <div class="card-header d-flex justify-content-between align-items-center">
                                    <ul class="nav nav-tabs card-header-tabs" id="myTab" role="tablist">
                                        <li class="nav-item">
                                            <a class="nav-link active" id="data-pribadi-tab" data-bs-toggle="tab"
                                                href="#dataPribadi" role="tab">
                                                Data Pribadi
                                            </a>
                                        </li>
                                        </li>
                                        <li class="nav-item">
                                            <a class="nav-link" id="data-orangtua-tab" data-bs-toggle="tab"
                                                href="#dataOrangTua" role="tab">
                                                Data Orang Tua
                                            </a>
                                        </li>
                                    </ul>
  @if(in_array(auth()->user()->role, ['hubin', 'kaprog']))
                                    <div class="dropdown">
                                        <button class="btn btn-back dropdown-toggle" type="button"
                                            data-bs-toggle="dropdown">
                                            Edit Data
                                        </button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li>
                                                <button class="btn " data-bs-toggle="modal"
                                                    data-bs-target="#editSiswaModal{{ $siswa->id }}">
                                                    Edit Data Pribadi
                                                </button>
                                                <button class="btn " data-bs-toggle="modal"
                                                    data-bs-target="#editOrtuModal{{ $siswa->id }}">
                                                    Edit Data Orangg Tua
                                                </button>
                                            </li>
                                        </ul>
                                    </div>
                                    @endif
                                </div>

                                <div class="card-body">
                                    <div class="tab-content" id="myTabContent">
                                        <div class="tab-pane fade show active" id="dataPribadi" role="tabpanel">
                                            <h3>Data Pribadi</h3>

                                            <table class="table table-striped">
                                                <tr>
                                                    <td>Nama Siswa</td>
                                                    <td>:</td>
                                                    <td>{{ $siswa->name }}</td>
                                                </tr>
                                                <tr>
                                                    <td>NIS</td>
                                                    <td>:</td>
                                                    <td>{{ $siswa->nip }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Kelas</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->kelas)->name_kelas ?? '-' }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Konsentrasi Keahlian</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->konke)->name_konke ?? '-' }}
                                                    </td>
                                                </tr>

                                                <tr>
                                                    <td>Alamat</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->dataPribadi)->alamat_siswa }}</td>
                                                </tr>
                                                <tr>
                                                    <td>No Tlp Siswa</td>
                                                    <td>:</td>
                                                    <td>{{optional($siswa->dataPribadi)->no_hp }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Jenis Kelamin</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->dataPribadi)->jk }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Agama</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->dataPribadi)->agama }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tempat Lahir</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->dataPribadi)->tempat_lhr }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Tanggal Lahir</td>
                                                    <td>:</td>
                                                    <td>{{ optional($siswa->dataPribadi)->tgl_lahir }}</td>
                                                </tr>
                                                <tr>
                                                    <td>Email</td>
                                                    <td>:</td>
                                                    <td>{{ $siswa->email }}</td>
                                                </tr>


                                                <tr>
                                                    <td>Password</td>
                                                    <td>:</td>
                                                    <td>********</td>
                                                </tr>

                                            </table>
                                        </div>

                                        <div class="tab-pane fade" id="dataOrangTua" role="tabpanel">
                                            <h3>Data Orang Tua</h3>
                                            <h5>Data Ayah</h5>
                                            @if ($siswa->dataPribadi)
                                                <table class="table table-striped">
                                                    <tr>
                                                        <td>Nama Ayah</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->name_ayh ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>NIK</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->nik_ayh ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tempat Lahir</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->tempat_lhr_ayh ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tanggal Lahir</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->tgl_lahir_ayh ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pekerjaan</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->pekerjaan_ayh ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                                <table class="table table-striped"><br>
                                                    <h5>Data Ibu</h5>

                                                    <tr>
                                                        <td>Nama Ibu</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->name_ibu ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>NIK</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->nik_ibu ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tempat Lahir</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->tempat_lhr_ibu ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Tanggal Lahir</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->tgl_lahir_ibu ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>Pekerjaan</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->pekerjaan_ibu ?? '-' }}</td>
                                                    </tr>
                                                </table>
                                                <table class="table table-striped"><br>
                                                    <h5>Kontak Orang Tua</h5>
                                                    <tr>
                                                        <td>Email </td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->email_ortu ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td>No Telepon</td>
                                                        <td>:</td>
                                                        <td>{{ $siswa->dataPribadi->no_tlp ?? '-' }}</td>
                                                    </tr>

                                                </table>
                                            @else
                                                <p><em>Data pribadi belum diisi oleh siswa.</em></p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="d-flex justify-content mt-3 mb-2">

                                         @if (auth()->user()->role == 'kaprog')
                                                           <a href="{{ route('kaprog.siswa.index') }}" class="btn btn-back shadow-sm">
                                            Kembali
                                        </a>
                                                        @elseif(auth()->user()->role == 'hubin')
                                                           <a href="{{ route('siswa.index') }}" class="btn btn-back shadow-sm">
                                            Kembali
                                        </a>
                                                        @elseif(auth()->user()->role == 'kepsek')
                                                            <a href="{{ route('kepsek.siswa.index') }}" class="btn btn-back shadow-sm">
                                            Kembali
                                        </a>
                                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @include('siswa.datasiswa.editSiswa')
        @include('orangtua.dataOrtu.editOrtu')
    </body>
    <div class="modal fade" id="editSiswaModal{{ $siswa->id }}" tabindex="-1" aria-labelledby="editSiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('siswa.updateSiswa', $siswa->id) }}" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Siswa</label>
                            <input type="text" class="form-control" name="name" value="{{ $siswa->name }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">NIS</label>
                            <input type="text" class="form-control" name="nip" value="{{ $siswa->nip }}" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-control" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                @foreach ($kelas as $kls)
                                    <option value="{{ $kls->id }}" {{ $siswa->kelas_id == $kls->id ? 'selected' : '' }}>
                                        {{ $kls->kelas }} {{ $kls->name_kelas }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label class="form-label"> Konsentrasi Keahlian</label>
                            <select class="form-control" name="konke_id" required>
                                <option value="">Pilih Konsentrasi Keahlian</option>
                                @foreach ($konke as $k)
                                    <option value="{{ $k->id }}" {{ $siswa->konke_id == $k->id ? 'selected' : '' }}>
                                        {{ $k->name_konke }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Alamat</label>
                            <input type="text" class="form-control" name="alamat" value="{{ optional($siswa->dataPribadi)->alamat_siswa }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">No Tlp Siswa</label>
                            <input type="text" class="form-control" name="no_hp" value="{{ optional($siswa->dataPribadi)->no_hp }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Jenis Kelamin*</label>
                            <select class="form-control" name="jk" required>
                                <option value="">Pilih Jenis Kelamin</option>
                                <option value="laki_laki"
                                    {{ old('jk', $siswa->dataPribadi->jk ?? '') == 'laki_laki' ? 'selected' : '' }}>Laki-laki</option>
                                <option value="perempuan"
                                    {{ old('jk', $siswa->dataPribadi->jk ?? '') == 'perempuan' ? 'selected' : '' }}>Perempuan</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Agama</label>
                            <select class="form-control" name="agama" id="agama-select" required>
                                <option value="">-- Pilih Agama --</option>
                                <option value="Islam" {{ optional($siswa->dataPribadi)->agama == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen Protestan" {{ optional($siswa->dataPribadi)->agama == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                <option value="Kristen Katolik" {{ optional($siswa->dataPribadi)->agama == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>
                                <option value="Hindu" {{ optional($siswa->dataPribadi)->agama == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ optional($siswa->dataPribadi)->agama == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ optional($siswa->dataPribadi)->agama == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                <option value="Lainnya" {{ !in_array(optional($siswa->dataPribadi)->agama, ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']) && optional($siswa->dataPribadi)->agama ? 'selected' : '' }}>Lainnya</option>
                            </select>
                        </div>

                        <div class="mb-3" id="agama-lainnya-container" style="display: none;">
                            <label class="form-label">Agama Lainnya</label>
                            <input type="text" class="form-control" name="agama_lainnya" id="agama-lainnya-input"
                                   value="{{ !in_array(optional($siswa->dataPribadi)->agama, ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu']) ? optional($siswa->dataPribadi)->agama : '' }}">
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Tampat Lahir</label>
                            <input type="text" class="form-control" name="tempat_lhr" value="{{ optional($siswa->dataPribadi)->tempat_lhr }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Tanggal lahir</label>
                            <input type="date" class="form-control" name="tgl_lahir" value="{{ optional($siswa->dataPribadi)->tgl_lahir }}" required>
                        </div>


                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" value="{{ $siswa->email }}" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password Baru
                                (Opsional)
                            </label>
                            <input type="password" class="form-control" name="password">
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan
                            Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editOrtuModal{{ $siswa->id }}" tabindex="-1" aria-labelledby="editOrtuModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit Data Orang Tua</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                @if ($siswa->dataPribadi)
                    <form action="{{ route('siswa.updateSiswa', $siswa->dataPribadi->id) }}" method="POST">
                    @else
                        <form action="#" method="POST">
                @endif

                @csrf
                @method('PUT')

                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Nama Ayah</label>
                        <input type="text" class="form-control" name="name_ayh"
                            value="{{ optional($siswa->dataPribadi)->name_ayh }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">NIK Ayah</label>
                        <input type="text" class="form-control" name="nik_ayh"
                            value="{{ optional($siswa->dataPribadi)->nik_ayh }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir Ayah</label>
                        <input type="text" class="form-control" name="tempat_lhr_ayh"
                            value="{{ optional($siswa->dataPribadi)->tempat_lhr_ayh }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir Ayah</label>
                        <input type="text" class="form-control" name="tgl_lahir_ayh"
                            value="{{ optional($siswa->dataPribadi)->tgl_lahir_ayh }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pekerjaan Ayah</label>
                        <input type="text" class="form-control" name="pekerjaan_ayh"
                            value="{{ optional($siswa->dataPribadi)->pekerjaan_ayh }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Nama Ibu</label>
                        <input type="text" class="form-control" name="name_ibu"
                            value="{{ optional($siswa->dataPribadi)->name_ibu }}" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">NIK Ibu</label>
                        <input type="text" class="form-control" name="nik_ibu"
                            value="{{ optional($siswa->dataPribadi)->nik_ibu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tempat Lahir Ibu</label>
                        <input type="text" class="form-control" name="tempat_lhr_ibu"
                            value="{{ optional($siswa->dataPribadi)->tempat_lhr_ibu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Tanggal Lahir Ibu</label>
                        <input type="text" class="form-control" name="tgl_lahir_ibu"
                            value="{{ optional($siswa->dataPribadi)->tgl_lahir_ibu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Pekerjaan Ibu</label>
                        <input type="text" class="form-control" name="pekerjaan_ibu"
                            value="{{ optional($siswa->dataPribadi)->pekerjaan_ibu }}" required>
                    </div>


                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="text" class="form-control" name="email_ortu"
                            value="{{ optional($siswa->dataPribadi)->email_ortu }}" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">No Telepon</label>
                        <input type="text" class="form-control" name="no_tlp"
                            value="{{ optional($siswa->dataPribadi)->no_tlp }}" required>
                    </div>

                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">Simpan
                        Perubahan</button>
                </div>
                </form>
            </div>
        </div>
    </div>

    </html>
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const selectAgama = document.getElementById('agama-select');
            const inputLainnya = document.getElementById('agama-lainnya-input');
            const containerLainnya = document.getElementById('agama-lainnya-container');
            const form = selectAgama.closest('form');

            // Fungsi untuk menampilkan/menyembunyikan input lainnya
            function toggleLainnya() {
                if (selectAgama.value === 'Lainnya') {
                    containerLainnya.style.display = 'block';
                    inputLainnya.required = true;

                    // Jika ada nilai sebelumnya yang bukan agama standar, set ke input lainnya
                    const currentAgama = "{{ optional($siswa->dataPribadi)->agama }}";
                    const agamaStandar = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];

                    if (currentAgama && !agamaStandar.includes(currentAgama)) {
                        inputLainnya.value = currentAgama;
                    }
                } else {
                    containerLainnya.style.display = 'none';
                    inputLainnya.required = false;
                }
            }

            // Set initial state
            toggleLainnya();

            // Change listener
            selectAgama.addEventListener('change', toggleLainnya);

            // Before submit, replace 'agama' value if needed
            form.addEventListener('submit', function (e) {
                if (selectAgama.value === 'Lainnya' && inputLainnya.value.trim() !== '') {
                    // Buat hidden input untuk menyimpan nilai akhir
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'agama';
                    hiddenInput.value = inputLainnya.value.trim();
                    form.appendChild(hiddenInput);

                    // Nonaktifkan select agar tidak ikut terkirim
                    selectAgama.disabled = true;
                }
            });
        });
        </script>
@endsection
