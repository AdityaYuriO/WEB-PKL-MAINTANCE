@extends('layout.main')

@section('content')
    <div class="container-xxl flex-grow-1 container-p-y">
        <div class="card shadow-sm">
            <div class="card-header bg-white">
                <div class="d-flex justify-content-between align-items-center">
                    <div>
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-exclamation-triangle-fill text-warning me-2"></i>
                            Siswa Belum Mengisi Jurnal
                        </h4>
                        <small class="text-muted">
                            @if (auth()->user()->role == 'hubin')
                                Hubin: {{ auth()->user()->name }}
                            @elseif(auth()->user()->role == 'kaprog')
                                Kepala Program: {{ auth()->user()->name }}
                            @elseif(auth()->user()->role == 'kepsek')
                                Kepala Sekolah: {{ auth()->user()->name }}
                            @endif
                        </small>
                    </div>
                    <div>
                        <span class="badge bg-warning text-dark fs-6">
                            {{ $siswaBelumIsi->count() }} Siswa
                        </span>
                    </div>
                </div>
            </div>

            <div class="card-body">
                <div class="alert alert-info d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2 fs-5"></i>
                    <div>
                        <strong>Perhatian!</strong> Daftar siswa yang belum mengisi jurnal pada tanggal
                        <strong>{{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</strong>
                    </div>
                </div>

                @if ($siswaBelumIsi->count() > 0)
                    <!-- Tampilan untuk semua role -->
                    <div class="table-responsive text-nowrap">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>NIS</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>IDUKA</th>
                                    <th>Email</th>
                                    {{-- <th class="text-center">Aksi</th> --}}
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswaBelumIsi as $index => $siswa)
                                    <tr>
                                        <td class="px-4">{{ $siswaBelumIsi->firstItem() + $index }}</td>
                                        <td>{{ $siswa->nip }}</td>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <div class="avatar avatar-sm me-2">
                                                    <span class="avatar-initial rounded-circle bg-label-primary">
                                                        {{ strtoupper(substr($siswa->name, 0, 1)) }}
                                                    </span>
                                                </div>
                                                <span>{{ $siswa->name }}</span>
                                            </div>
                                        </td>
                                        <td>{{ $siswa->kelas ? $siswa->kelas->name_kelas : '-' }}</td>
                                        <td>
                                            @if ($siswa->iduka)
                                                {{ $siswa->iduka->nama }}
                                            @else
                                                <span class="text-muted">Belum ditentukan</span>
                                            @endif
                                        </td>
                                        <td>{{ $siswa->email ?: '-' }}</td>
                                        {{-- <td class="text-center">
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-outline-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                <i class="bi bi-three-dots-vertical"></i>
                                            </button>
                                            <ul class="dropdown-menu">
                                                <li>
                                                    <a class="dropdown-item" href="mailto:{{ $siswa->email }}?subject=Pengingat%20Pengisian%20Jurnal%20PKL&body=Halo%20{{ $siswa->name }}%2C%0D%0Amohon%20segera%20mengisi%20jurnal%20PKL%20hari%20ini.%0D%0ATerima%20kasih.">
                                                        <i class="bi bi-envelope text-info me-2"></i>Email
                                                    </a>
                                                </li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <button class="dropdown-item" onclick="sendBulkReminder({{ $siswa->id }})">
                                                        <i class="bi bi-bell text-warning me-2"></i>Kirim Pengingat
                                                    </button>
                                                </li>
                                            </ul>
                                        </div>
                                    </td> --}}
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    <div class="card-footer bg-transparent border-0">
                        <div class="d-flex justify-content-between align-items-center">
                            <small class="text-muted">
                                Menampilkan {{ $siswaBelumIsi->firstItem() }} - {{ $siswaBelumIsi->lastItem() }} dari
                                {{ $siswaBelumIsi->total() }} data
                            </small>
                            <nav>
                                {{ $siswaBelumIsi->links('pagination::bootstrap-5') }}
                            </nav>
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-check-circle-fill text-success" style="font-size: 4rem;"></i>
                        <h5 class="mt-3">Semua siswa sudah mengisi jurnal hari ini</h5>
                        <p class="text-muted">Tidak ada siswa yang belum mengisi jurnal pada tanggal
                            {{ \Carbon\Carbon::parse($today)->locale('id')->isoFormat('dddd, D MMMM YYYY') }}</p>
                    </div>
                @endif
            </div>
        </div>
    </div>

    <!-- Modal untuk konfirmasi pengiriman pengingat -->
    <div class="modal fade" id="reminderModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow">
                <div class="modal-header bg-primary text-white">
                    <h5 class="modal-title fw-bold">
                        <i class="bi bi-envelope me-2"></i>Kirim Pengingat Email
                    </h5>
                    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                        aria-label="Close"></button>
                </div>
                <div class="modal-body p-4">
                    <form id="reminderForm">
                        @csrf
                        <input type="hidden" id="siswaId" name="siswa_id" value="">

                        <div class="mb-3">
                            <label class="form-label fw-semibold">Pesan Pengingat</label>
                            <textarea class="form-control" id="reminderMessage" name="message" rows="4" required>Halo {nama_siswa}, mohon segera mengisi jurnal PKL hari ini. Terima kasih.</textarea>
                            <div class="form-text">Pesan ini akan dikirimkan melalui email ke siswa yang belum mengisi
                                jurnal.</div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer bg-light">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="button" class="btn btn-primary" onclick="submitReminder()">
                        <i class="bi bi-envelope me-1"></i>Kirim Email
                    </button>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Set CSRF token untuk AJAX
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });
        });

        function sendBulkReminder(siswaId = null) {
            $('#siswaId').val(siswaId || '');

            if (siswaId) {
                // Jika hanya satu siswa
                const siswa = @json($siswaBelumIsi);
                const siswaData = siswa.find(s => s.id == siswaId);

                if (siswaData) {
                    const defaultMessage =
                        `Halo ${siswaData.name}, mohon segera mengisi jurnal PKL hari ini. Terima kasih.`;
                    $('#reminderMessage').val(defaultMessage);
                }
            } else {
                // Jika semua siswa
                $('#reminderMessage').val('Halo {nama_siswa}, mohon segera mengisi jurnal PKL hari ini. Terima kasih.');
            }

            const modal = new bootstrap.Modal(document.getElementById('reminderModal'));
            modal.show();
        }

        function submitReminder() {
            const form = document.getElementById('reminderForm');
            const formData = new FormData(form);
            const submitBtn = form.closest('.modal-footer').querySelector('button[type="button"]');
            const originalText = submitBtn.innerHTML;

            // Tampilkan loading
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2"></span>Mengirim...';

            fetch('/pembimbing/jurnal/kirim-pengingat', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Tutup modal
                        bootstrap.Modal.getInstance(document.getElementById('reminderModal')).hide();

                        // Tampilkan pesan sukses
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil!',
                            text: data.message,
                            confirmButtonColor: '#6366f1',
                            timer: 3000,
                            showConfirmButton: false
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal!',
                            text: data.message || 'Terjadi kesalahan saat mengirim pengingat.',
                            confirmButtonColor: '#6366f1'
                        });
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: 'Terjadi kesalahan jaringan. Silakan coba lagi.',
                        confirmButtonColor: '#6366f1'
                    });
                })
                .finally(() => {
                    // Kembalikan tombol ke keadaan semula
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                });
        }
    </script>
@endsection

