@extends('layout.main')

@section('content')
<div class="container-fluid">
    <div class="content-wrapper">
        <div class="container-xxl flex-grow-1 container-p-y">
            <div class="row">
                <div class="card mb-3">
                    <div class="card-body d-flex justify-content-between align-items-center flex-wrap">
                        <h5 class="mb-0">Detail Pengajuan PKL</h5>

                        <div class="d-flex align-items-center gap-2 flex-wrap">
                            <button id="btn-select-mode" class="btn btn-warning me-2">
                                <i class="bi bi-check2-square me-1"></i> Pilih Siswa
                            </button>

                            <form id="form-cetak" action="{{ route('persuratan.suratPengantar.cetakPilihan') }}" method="POST" target="_blank" class="mb-0">
                                @csrf
                                <input type="hidden" name="iduka_id" value="{{ $iduka_id }}">
                                <button id="btn-cetak" type="submit" class="btn btn-warning d-none">
                                    <i class="bi bi-printer me-1"></i> Cetak
                                </button>
                            </form>

                            @if($pengajuanUsulans->isNotEmpty())
                                <a href="{{ route('semua.surat.pdf', $iduka_id) }}" class="btn btn-success">
                                    <i class="bi bi-download me-1"></i> Surat Pengantar
                                </a>
                            @endif
                        </div>
                    </div>
                </div>

            </div>
            <div class="col-md-12 mt-3">
                @if($pengajuanUsulans->isEmpty())
                <p class="text-center">Tidak ada pengajuan yang tersedia untuk Institusi ini.</p>
                @else
                {{-- Looping Daftar Siswa --}}
                @foreach($pengajuanUsulans as $pengajuan)
                <div class="card mb-3 shadow-sm" style="padding: 20px; border-radius: 10px;">
                    <div class="d-flex justify-content-between align-items-center">
                        <div class="d-flex align-items-center">
                            <input type="checkbox" name="selected_siswa[]" value="{{ $pengajuan->id }}" class="form-check-input me-3 checkbox-siswa" style="display: none;">
                            <div>
                                <div class="mb-0" style="font-size: 18px">
                                    <strong>{{ $pengajuan->dataPribadi->name ?? 'Nama Tidak Tersedia' }}</strong>
                                </div>
                                <div>
                                    Kelas:  {{ $pengajuan->dataPribadi->kelas->name_kelas ?? '-' }}
                                </div>
                                <div>
                                    Status: {{ ucfirst($pengajuan->status) }}
                                </div>
                            </div>
                        </div>

                        <div>
                            <a href="{{ route('persuratan.suratPengajuan.detailSuratPengajuan', $pengajuan->id) }}" class="btn btn-info">
                                Lihat Detail
                            </a>
                            @if($pengajuan->status === 'sudah')
                            <button class="btn btn-success" disabled>Sudah</button>
                            @else
                            <button class="btn btn-primary btn-proses" data-id="{{ $pengajuan->id }}">Kirim</button>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
                @endif
            </div>
        </div>
    </div>
</div>
</div>
@endsection

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const buttons = document.querySelectorAll('.btn-proses');
        const selectButton = document.getElementById('btn-select-mode');
        const cetakButton = document.getElementById('btn-cetak');
        const checkboxes = document.querySelectorAll('.checkbox-siswa');
        const formCetak = document.getElementById('form-cetak');
        let selectModeActive = false;

        // Fungsi untuk cek apakah ada siswa yang dicentang
        function updateCetakButtonVisibility() {
            const adaYangDipilih = Array.from(checkboxes).some(cb => cb.checked);
            if (adaYangDipilih) {
                cetakButton.classList.remove('d-none');
            } else {
                cetakButton.classList.add('d-none');
            }
        }

        // Toggle mode pilih siswa
        selectButton.addEventListener('click', function (e) {
    e.preventDefault();
    selectModeActive = !selectModeActive;

    checkboxes.forEach(cb => {
        cb.style.display = selectModeActive ? 'block' : 'none';
        cb.checked = false;
    });

    updateCetakButtonVisibility();

    if (selectModeActive) {
        selectButton.innerHTML = '<i class="bi bi-x-lg fs-5 text-secondary" title="Batal Pilih"></i>';
        selectButton.classList.remove('btn-warning'); // Hapus background
        selectButton.classList.add('border-0', 'bg-transparent', 'p-0'); // Tambah gaya minimal
    } else {
        selectButton.innerHTML = '<i class="bi bi-check2-square me-1"></i> Pilih Siswa';
        selectButton.classList.remove('border-0', 'bg-transparent', 'p-0');
        selectButton.classList.add('btn-warning');
    }
});

        // Deteksi setiap kali user klik checkbox
        checkboxes.forEach(cb => {
            cb.addEventListener('change', function () {
                updateCetakButtonVisibility();
            });
        });

        // Handle submit cetak pilihan
        formCetak.addEventListener('submit', function (e) {
            formCetak.querySelectorAll('input[name="ids[]"]').forEach(el => el.remove());

            let selected = 0;
            checkboxes.forEach(cb => {
                if (cb.checked) {
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'ids[]';
                    hiddenInput.value = cb.value;
                    formCetak.appendChild(hiddenInput);
                    selected++;
                }
            });

            if (selected === 0) {
                e.preventDefault();
                Swal.fire('Pilih minimal satu siswa!', '', 'warning');
            }
        });

        // Tombol "Sudah Proses"
        buttons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.dataset.id;
                const buttonRef = this;

                Swal.fire({
                    title: 'Apakah surat pengantar sudah di-print?',
                    text: "Data akan dikirim ke Kaprog!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Sudah!'
                }).then((result) => {
                    if (result.isConfirmed) {
                        axios.post(`/review/pengajuan/proses/${id}`)
                            .then(response => {
                                Swal.fire({
                                    title: 'Berhasil!',
                                    text: response.data.message,
                                    icon: 'success',
                                    timer: 2000,
                                    showConfirmButton: false
                                });

                                buttonRef.textContent = 'Sudah';
                                buttonRef.disabled = true;
                                buttonRef.classList.replace('btn-primary', 'btn-success');
                            })
                            .catch(error => {
                                Swal.fire({
                                    title: 'Gagal!',
                                    text: 'Terjadi kesalahan saat memproses.',
                                    icon: 'error'
                                });
                            });
                    }
                });
            });
        });
    });
</script>




@endpush
