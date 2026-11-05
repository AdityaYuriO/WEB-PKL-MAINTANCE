@extends('layout.main')
@section('content')
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Tujuan Pembelajaran Institusi</title>
    <style>
        .card-content {
            transition: transform 0.3s ease-in-out;
        }

        .card-content:hover {
            transform: scale(1.02);
        }

        .custom-checkbox {
            appearance: none;
            width: 18px;
            height: 18px;
            border: 2px solid #ccc;
            border-radius: 4px;
            background-color: white;
            cursor: not-allowed;
            display: inline-block;
            position: relative;
        }

        /* Warna hijau jika checkbox tercentang */
        .custom-checkbox:checked {
            background-color: #28a745;
            border-color: #28a745;
        }

        /* Tambahkan centang */
        .custom-checkbox:checked::after {
            content: '\2713';
            font-size: 14px;
            color: white;
            font-weight: bold;
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
        }

        .modal-body {
            max-height: 60vh;
            overflow-y: auto;
        }

        /* Style untuk tampilan yang lebih rapi */
        .atp-item {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 5px;
        }

        .atp-content {
            flex-grow: 1;
            padding-right: 15px;
        }

        .atp-checkbox {
            flex-shrink: 0;
        }

        /* Style untuk modal tambah */
        .tp-row {
            display: flex;
            align-items: center;
        }

        .tp-content {
            flex-grow: 1;
            padding-left: 20px;
        }

        .tp-checkbox {
            width: 30px;
            text-align: end;
        }

        .jurusan-btn.active {
            background-color: #0d6efd;
            border-color: #0d6efd;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="content-wrapper">
            <div class="container-xxl flex-grow-1 container-p-y">
                <div class="row">
                    <div class="card">
                        <div class="card-body">
                            <div class="d-flex justify-content-between align-items-center mb-2">
                                <h5 class="mb-0">Tujuan Pembelajaran</h5>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-warning btn-sm" data-bs-toggle="modal" data-bs-target="#searchModal">
                                        <i class="bi bi-search"></i>
                                        <span class="d-none d-md-inline">Search</span>
                                    </button>
                                    <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#tambahTpModal">
                                        <i class="bi bi-plus-lg"></i> <span class="d-none d-md-inline">Tambah TP</span>
                                    </button>
                                    <button type="button" class="btn btn-info btn-sm" data-bs-toggle="modal" data-bs-target="#updateTpModal">
                                        <i class="bi bi-pencil-square"></i> <span class="d-none d-md-inline">Update TP</span>
                                    </button>
                                    <button type="button" class="btn btn-success btn-sm" id="btnCetak">
                                        <i class="bi bi-printer"></i> <span class="d-none d-md-inline">Cetak</span>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    @if($idukaAtps->isEmpty())
                    <div class="alert alert-warning mt-3 d-flex justify-content-center align-items-center text-center">
                        <span style="text-align: center">Belum mengisi CP & ATP</span>
                    </div>
                    @else


                    @foreach($idukaAtps->groupBy('konke_id') as $konke_id => $konke_items)
                    <div class="card card-content mt-3">
                        <div class="card-header">
                            <b>{{ $konke_items->first()->konke->name_konke ?? '-'}}</b> <!-- Menampilkan Nama Konke -->
                        </div>
                        <div class="card-body">
                            @foreach($konke_items->groupBy('cp.cp') as $cp_name => $items)
                            <div class="mb-3">
                                <b>{{ $loop->iteration }}. {{ $cp_name }}</b>

                                @foreach($items as $item)

                                <div class="atp-item ms-3">
                                    <span class="atp-content"><b>{{ $item->atp->kode_atp }}</b> {{ $item->atp->atp }}</span>
                                    <div class="atp-checkbox">
                                        <input type="checkbox"
                                            name="tp_check[]"
                                            value="{{ $item->atp->id }}"
                                            class="tp-check custom-checkbox"
                                            {{ $item->is_selected ? 'checked' : '' }}
                                            disabled>
                                    </div>
                                </div>

                                @endforeach


                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endforeach
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Tambah -->
    <div class="modal fade" id="tambahTpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Tujuan Pembelajaran Institusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('iduka_atp.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="iduka_id" id="iduka_id" value="{{ $iduka->id ?? '' }}">
                    <input type="hidden" name="iduka_id" id="iduka_id" value="{{ auth()->user()->iduka_id }}">
                    <input type="hidden" name="konke_id" id="konke_id">

                    <div class="modal-body">
                        <div class="d-flex justify-content-center mb-3">
                            <!-- Wrapper for sliding konke buttons -->
                            <div class="d-flex flex-nowrap overflow-auto">
                                @foreach($konkes as $konke)
                                <button type="button" class="btn btn-primary m-1 jurusan-btn" data-konke-id="{{ $konke->id }}">
                                    {{ $konke->name_konke }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div id="cpTpContainer">
                            <label class="d-flex justify-content-end me-2">Check All
                                <input type="checkbox" id="checkAllTambah">
                            </label>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama Tujuan Pembelajaran</th>
                                        <th>Checklist</th>
                                    </tr>
                                </thead>
                                <tbody id="tp-tambah-body">
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Silakan pilih kompetensi keahlian</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary btn-sm">Simpan Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Modal Update -->
    <div class="modal fade" id="updateTpModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-scrollable">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Update Tujuan Pembelajaran Institusi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="{{ route('iduka_atp.update') }}" method="POST">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="iduka_id" value="{{ auth()->user()->iduka_id }}">
                    <input type="hidden" name="konke_id" id="konke_id_update">

                    <div class="modal-body">
                        <div class="d-flex justify-content-center mb-3">
                            <!-- Wrapper for sliding konke buttons -->
                            <div class="d-flex flex-nowrap overflow-auto" id="konke-buttons-update">
                                @foreach($konkes as $konke)
                                <button type="button" class="btn btn-outline-primary m-1 jurusan-btn-update" data-konke-id="{{ $konke->id }}">
                                    {{ $konke->name_konke }}
                                </button>
                                @endforeach
                            </div>
                        </div>

                        <div id="cpTpContainerUpdate">
                            <label class="d-flex justify-content-end me-2">Check All
                                <input type="checkbox" id="checkAllUpdate">
                            </label>
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Nama Tujuan Pembelajaran</th>
                                        <th>Checklist</th>
                                    </tr>
                                </thead>
                                <tbody id="tp-update-body">
                                    <tr>
                                        <td colspan="2" class="text-center text-muted">Loading...</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-info btn-sm">Update Data</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

</body>
@if(session('success'))
<script>
    document.addEventListener("DOMContentLoaded", function() {
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session('success') }}',
            showConfirmButton: false,
            timer: 2000
        });
    });
</script>
@endif

<script>
    document.addEventListener("DOMContentLoaded", function() {
        // Check All untuk Modal Tambah
        document.getElementById("checkAllTambah").addEventListener("click", function() {
            document.querySelectorAll("#tp-tambah-body .tp-check").forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Check All untuk Modal Update
        document.getElementById("checkAllUpdate").addEventListener("click", function() {
            document.querySelectorAll("#tp-update-body .tp-check").forEach(checkbox => {
                checkbox.checked = this.checked;
            });
        });

        // Fungsi untuk load semua data TP saat modal Update dibuka
        const updateModal = document.getElementById('updateTpModal');
        updateModal.addEventListener('show.bs.modal', function () {
            // Reset button states
            document.querySelectorAll(".jurusan-btn-update").forEach(btn => {
                btn.classList.remove("active");
                btn.classList.add("btn-outline-primary");
                btn.classList.remove("btn-primary");
            });

            loadAllUpdateData();
        });

        // Fungsi untuk load semua data TP yang sudah ada
        function loadAllUpdateData() {
            const tpUpdateBody = document.getElementById("tp-update-body");
            tpUpdateBody.innerHTML = "<tr><td colspan='2' class='text-center text-muted'>Loading semua data...</td></tr>";

            fetch('/get-all-iduka-atp')
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok: ' + response.statusText);
                    }
                    return response.json();
                })
                .then(data => {
                    console.log("All data received:", data);
                    tpUpdateBody.innerHTML = "";

                    if (!data || data.length === 0) {
                        tpUpdateBody.innerHTML = "<tr><td colspan='2' class='text-center text-warning'>Belum ada data TP yang tersimpan. Silakan tambah data terlebih dahulu.</td></tr>";
                        return;
                    }

                    // Simpan semua konke_id dalam array untuk form submission
                    let allKonkeIds = [];

                    // Tampilkan data berdasarkan konke
                    data.forEach(konkeData => {
                        allKonkeIds.push(konkeData.konke_id);

                        // Header Konke
                        let konkeHeader = `<tr><td colspan="2"><b style="font-size: 16px; color: #0d6efd;">${konkeData.konke_name}</b></td></tr>`;
                        tpUpdateBody.innerHTML += konkeHeader;

                        // Loop CP
                        konkeData.cps.forEach(cp => {
                            let cpRow = `<tr><td colspan="2"><b style="margin-left: 20px;">${cp.cp}</b></td></tr>`;
                            tpUpdateBody.innerHTML += cpRow;

                            // Loop ATP
                            cp.atps.forEach(atp => {
                                let atpRow = `
                                    <tr>
                                        <td class="tp-content" style="padding-left: 40px;"><b>${atp.kode_atp}</b> ${atp.atp}</td>
                                        <td class="tp-checkbox text-end">
                                            <input type='checkbox' class='tp-check' name='tp_check[]' value='${atp.id}' ${atp.is_selected ? 'checked' : ''} data-konke-id="${konkeData.konke_id}">
                                        </td>
                                    </tr>`;
                                tpUpdateBody.innerHTML += atpRow;
                            });
                        });
                    });

                    // Set konke_id pertama sebagai default (untuk single konke update)
                    if (allKonkeIds.length > 0) {
                        document.getElementById("konke_id_update").value = allKonkeIds[0];
                    }
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                    tpUpdateBody.innerHTML = `<tr><td colspan='2' class='text-danger text-center'>
                        <i class="bi bi-exclamation-triangle"></i> Gagal memuat data.<br>
                        <small>Error: ${error.message}</small><br>
                        <small>Pastikan route '/get-all-iduka-atp' sudah terdaftar di web.php</small>
                    </td></tr>`;
                });
        }

        // Event delegation untuk Modal TAMBAH
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("jurusan-btn")) {
                event.preventDefault();

                // Remove active class from all buttons
                document.querySelectorAll(".jurusan-btn").forEach(btn => {
                    btn.classList.remove("active");
                });

                // Add active class to clicked button
                event.target.classList.add("active");

                const konke_id = event.target.getAttribute("data-konke-id");
                document.getElementById("konke_id").value = konke_id;

                const tpTambahBody = document.getElementById("tp-tambah-body");
                tpTambahBody.innerHTML = "<tr><td colspan='2' class='text-center text-muted'>Loading...</td></tr>";

                fetch(`/get-cp-atp/${konke_id}`)
                    .then(response => response.json())
                    .then(data => {
                        tpTambahBody.innerHTML = "";

                        if (data.length === 0) {
                            tpTambahBody.innerHTML = "<tr><td colspan='2' class='text-center text-muted'>Tidak ada data CP & ATP.</td></tr>";
                            return;
                        }

                        data.forEach(cp => {
                            let cpRow = `<tr><td><b>${cp.cp}</b></td><td></td></tr>`;
                            tpTambahBody.innerHTML += cpRow;

                            cp.atp.forEach(atp => {
                                let atpRow = `
                                    <tr>
                                        <td class="tp-content"><b>${atp.kode_atp}</b> ${atp.atp}</td>
                                        <td class="tp-checkbox text-end">
                                            <input type='checkbox' class='tp-check' name='tp_check[]' value='${atp.id}' ${atp.is_selected ? 'checked' : ''}>
                                        </td>
                                    </tr>`;
                                tpTambahBody.innerHTML += atpRow;
                            });
                        });
                    })
                    .catch(error => {
                        console.error("Error fetching data:", error);
                        tpTambahBody.innerHTML = "<tr><td colspan='2' class='text-danger'>Gagal memuat data.</td></tr>";
                    });
            }
        });

        // Event delegation untuk Modal UPDATE - filter by konke
        document.addEventListener("click", function(event) {
            if (event.target.classList.contains("jurusan-btn-update")) {
                event.preventDefault();

                // Remove active class from all update buttons
                document.querySelectorAll(".jurusan-btn-update").forEach(btn => {
                    btn.classList.remove("active");
                    btn.classList.add("btn-outline-primary");
                    btn.classList.remove("btn-primary");
                });

                // Add active class to clicked button
                event.target.classList.remove("btn-outline-primary");
                event.target.classList.add("btn-primary");
                event.target.classList.add("active");

                const konke_id = event.target.getAttribute("data-konke-id");
                document.getElementById("konke_id_update").value = konke_id;

                // Filter tampilan berdasarkan konke_id
                filterUpdateDataByKonke(konke_id);
            }
        });

        // Fungsi untuk filter data berdasarkan konke
        function filterUpdateDataByKonke(konke_id) {
            const tpUpdateBody = document.getElementById("tp-update-body");
            tpUpdateBody.innerHTML = "<tr><td colspan='2' class='text-center text-muted'>Loading...</td></tr>";

            fetch(`/get-cp-atp/${konke_id}`)
                .then(response => response.json())
                .then(data => {
                    tpUpdateBody.innerHTML = "";

                    if (data.length === 0) {
                        tpUpdateBody.innerHTML = "<tr><td colspan='2' class='text-center text-muted'>Tidak ada data CP & ATP.</td></tr>";
                        return;
                    }

                    data.forEach(cp => {
                        let cpRow = `<tr><td><b>${cp.cp}</b></td><td></td></tr>`;
                        tpUpdateBody.innerHTML += cpRow;

                        cp.atp.forEach(atp => {
                            let atpRow = `
                                <tr>
                                    <td class="tp-content"><b>${atp.kode_atp}</b> ${atp.atp}</td>
                                    <td class="tp-checkbox text-end">
                                        <input type='checkbox' class='tp-check' name='tp_check[]' value='${atp.id}' ${atp.is_selected ? 'checked' : ''}>
                                    </td>
                                </tr>`;
                            tpUpdateBody.innerHTML += atpRow;
                        });
                    });
                })
                .catch(error => {
                    console.error("Error fetching data:", error);
                    tpUpdateBody.innerHTML = "<tr><td colspan='2' class='text-danger'>Gagal memuat data.</td></tr>";
                });
        }

        // Tombol Cetak
        document.getElementById("btnCetak").addEventListener("click", function() {
            window.open('/cetak-atp-langsung', '_blank');
        });
    });
</script>

</html>
@endsection
