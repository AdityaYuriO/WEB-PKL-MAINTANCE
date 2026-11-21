@extends('layout.main')
@section('content')
    <!DOCTYPE html>
    <html lang="en">
    <head>
        <title>Surat Pengajuan</title>
        <style>
            .card-hover {
                transition: transform 0.3s ease, background-color 0.3s ease, color 0.3s ease;
                height: 80px;
                flex-direction: column;
                justify-content: center;
                display: flex;
                padding: 20px;
                border-radius: 10px;
                background-color: #7e7dfb;
                color: white;
            }
            .card-hover:hover {
                transform: scale(1.01);
                background-color: white !important;
                color: #7e7dfb !important;
            }
            .card-hover:hover .btn-hover {
                 background-color:#7e7dfb;
                 color: white;
                 border-color: #7e7dfb;
             }
             .btn-hover {
                 background-color: white;
                 color: #7e7dfb;
                 transition: background-color 0.3s ease, color 0.3s ease, border-color 0.3s ease;
                 border-radius: 50px;
                 border: 2px solid #7e7dfb;
             }
             .btn-hover:hover {
                 background-color: white;
                 color: #7e7dfb;
                 border-color: white;
             }
             .mb-3 {
                margin-bottom: 5px !important;
            }
            .select-checkbox {
                display: none;
                margin-right: 10px; /* Kasih jarak 10px */
            }
        </style>
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    </head>
    <body>
        <div class="container-fluid">
            <div class="content-wrapper">
                <div class="container-xxl flex-grow-1 container-p-y">
                    <form id="pdfForm" method="GET" action="{{ route('download.pdf') }}">
                        @csrf
                        <div class="row">
                            @if(session()->has('success'))
                            <div class="alert alert-success alert-dismissible fade show" role="alert">
                                {{ session('success') }}
                                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                            </div>
                        @endif   
                            <div class="col-md-12 mt-3">
                                <div class="d-flex justify-content-between align-items-center mb-4">
                                    <div class="d-flex gap-2">
                                        <select class="form-select w-auto" id="filterIduka">
                                            <option value="all">Pilih Jurusan</option>
                                            <option value="tkr">TKR</option>
                                            <option value="tkj">TKJ</option>
                                            <option value="pplg">PPLG</option>
                                            <option value="dpib">DPIB</option>
                                            <option value="mplb">MPLB</option>
                                            <option value="ak">AK</option>
                                            <option value="sp">SP</option>
                                        </select>
                                        <select class="form-select w-auto" id="filterKelas" disabled>
                                        <option value="all">Pilih Kelas</option>
                                    </select>
                                    </div>
                                    <div class="button-group">
                                        <button id="toggleSelectButton" type="button" class="btn btn-secondary">Pilih Data</button>
                                        <button id="printPdfButton" type="submit" class="btn btn-danger" style="display: none;">
                                            <i class="bi bi-file-earmark-pdf"></i> Cetak PDF
                                        </button>
                                    </div>
                                </div>

                                <div class="form-check mb-3" id="selectAllWrapper" style="display: none;">
                                    <input type="checkbox" id="selectAll" class="form-check-input">
                                    <label for="selectAll" class="form-check-label">Pilih Semua</label>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <input type="checkbox" class="select-checkbox" name="selectedIds[]" value="1" style="display: none;">
                                    <div class="card mb-3 shadow-sm card-hover" style="width: 100%;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="mb-0" style="font-size: 18px">Nama Siswa 1</div>
                                                <div>Kelas A</div>
                                            </div>
                                            <a href="{{ route('detail.suratpengajuan') }}" class="btn btn-hover rounded-pill">Detail</a>
                                        </div>
                                    </div>
                                </div>

                                <div class="d-flex align-items-center mb-3">
                                    <input type="checkbox" class="select-checkbox" name="selectedIds[]" value="2" style="display: none;">
                                    <div class="card mb-3 shadow-sm card-hover" style="width: 100%;">
                                        <div class="d-flex justify-content-between align-items-center">
                                            <div>
                                                <div class="mb-0" style="font-size: 18px">Nama Siswa 2</div>
                                                <div>Kelas B</div>
                                            </div>
                                            <a href="#" class="btn btn-hover rounded-pill">Detail</a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <script>
            const toggleButton = document.getElementById('toggleSelectButton');
            const printButton = document.getElementById('printPdfButton');
            const checkboxes = document.querySelectorAll('.select-checkbox');
            const selectAllWrapper = document.getElementById('selectAllWrapper');
            const selectAll = document.getElementById('selectAll');

            const jurusanKelas = {
                tkj: ["XI TKJ 1", "XI TKJ 2", "XI TKJ 3"],
                tkr: ["XI TKR 1", "XI TKR 2"],
                pplg: ["XI PPLG 1", "XI PPLG 2"],
                dpib: ["XI DPIB 1"],
                mplb: ["XI MPLB 1", "XI MPLB 2"],
                ak: ["XI AK 1"],
                sp: ["XI SP 1"]
            };

            document.getElementById("filterIduka").addEventListener("change", function () {
                const selectedJurusan = this.value;
                const kelasSelect = document.getElementById("filterKelas");
                kelasSelect.innerHTML = '<option value="all">Pilih Kelas</option>';
                
                if (selectedJurusan !== "all" && jurusanKelas[selectedJurusan]) {
                    jurusanKelas[selectedJurusan].forEach(kelas => {
                        let option = document.createElement("option");
                        option.value = kelas.toLowerCase().replace(/\s+/g, "-");
                        option.textContent = kelas;
                        kelasSelect.appendChild(option);
                    });
                    kelasSelect.disabled = false;
                } else {
                    kelasSelect.disabled = true;
                }
            });
            
            toggleButton.addEventListener('click', () => {
                const isVisible = checkboxes[0].style.display !== 'none';
                checkboxes.forEach(cb => cb.style.display = isVisible ? 'none' : 'block');
                selectAllWrapper.style.display = isVisible ? 'none' : 'block';
                toggleButton.textContent = isVisible ? 'Pilih Data' : 'Selesai Pilih';
                if (isVisible) printButton.style.display = 'none';
            });

            selectAll.addEventListener('change', () => {
                checkboxes.forEach(cb => cb.checked = selectAll.checked);
                printButton.style.display = selectAll.checked || Array.from(checkboxes).some(cb => cb.checked) ? 'inline-block' : 'none';
            });

            checkboxes.forEach(cb => {
                cb.addEventListener('change', () => {
                    printButton.style.display = Array.from(checkboxes).some(cb => cb.checked) ? 'inline-block' : 'none';
                });
            });
        </script>
    </body>
    </html>
@endsection
