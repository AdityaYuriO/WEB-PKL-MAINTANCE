@extends('layout.main')
@section('content')
    <div class="container-fluid"><br>
        <div class="card shadow mb-4">
            <div class="card-body">

                <h1 class="h3 mb-2 text-gray-800">Data Diri Persuratan  </h1>

                @if ($errors->any())
                    <div style="color: red;">
                        <ul>
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('persuratan.data_pribadi.store') }}" method="POST">
                    @csrf
                    <input type="hidden" name="id" value="{{  old('id', $dataPersuratan->id ?? '') }}">
                   
                    <div class="mb-3">
                        <label for="name" class="form-label">Nama Lengkap</label>
                        <input type="text" name="name" id="name" class="form-control"
                        value="{{ old('name', auth()->user()->name ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP / NIK</label>
                        <input type="text"  name="nip" id="nip"  class="form-control"
                            value="{{ old('nip', auth()->user()->nip ?? '') }}" required>
                    </div>
         
                    <div class="mb-3">
                        <label for="alamat" class="form-label">Alamat Lengkap</label>
                        <textarea name="alamat" id="alamat" class="form-control" required>{{ old('alamat', $dataPersuratan->alamat ?? '') }}</textarea>
                    </div>
                    

                    <div class="mb-3">
                        <label for="no_hp" class="form-label">No Telpon</label>
                        <input type="text" name="no_hp" id="no_hp" class="form-control"
                        value="{{ old('no_hp', $dataPersuratan->no_hp ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                            <label for="jk" class="form-label">Jenis Kelamin</label>
                            <select class="form-control" class="form-select" id="jk" name="jk"
                                required>
                                <option value="Laki-laki"
                                    {{ old('jk', $dataPersuratan->jk ?? '') == 'Laki-laki' ? 'selected' : '' }}>
                                    Laki-laki</option>
                                <option value="Perempuan"
                                    {{ old('jk', $dataPersuratan->jk ?? '') == 'Perempuan' ? 'selected' : '' }}>
                                    Perempuan</option>
                            </select>
                        </div>

                   
                        <div class="mb-3">
                            <label class="form-label">Agama*</label>
                            <select name="agama" class="form-control" id="agama-select" required>
                                <option value="" disabled {{ old('agama', $dataPribadi->agama ?? '') == '' ? 'selected' : '' }}>-- Pilih Agama --</option>
                                <option value="Islam" {{ old('agama', $dataPribadi->agama ?? '') == 'Islam' ? 'selected' : '' }}>Islam</option>
                                <option value="Kristen Protestan" {{ old('agama', $dataPribadi->agama ?? '') == 'Kristen Protestan' ? 'selected' : '' }}>Kristen Protestan</option>
                                <option value="Kristen Katolik" {{ old('agama', $dataPribadi->agama ?? '') == 'Kristen Katolik' ? 'selected' : '' }}>Kristen Katolik</option>
                                <option value="Hindu" {{ old('agama', $dataPribadi->agama ?? '') == 'Hindu' ? 'selected' : '' }}>Hindu</option>
                                <option value="Buddha" {{ old('agama', $dataPribadi->agama ?? '') == 'Buddha' ? 'selected' : '' }}>Buddha</option>
                                <option value="Konghucu" {{ old('agama', $dataPribadi->agama ?? '') == 'Konghucu' ? 'selected' : '' }}>Konghucu</option>
                                <option value="Lainnya" 
                                    {{ !in_array(old('agama', $dataPribadi->agama ?? ''), ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu', '']) 
                                    && old('agama', $dataPribadi->agama ?? '') 
                                    ? 'selected' : '' }}>
                                    Lainnya
                                </option>
                            </select>
                        </div>
                        
                        <div class="mb-3" id="agama-lainnya-container" style="display: none;">
                            <label class="form-label">Agama Lainnya*</label>
                            <input type="text" class="form-control" name="agama_lainnya" id="agama-lainnya-input" 
                                   value="{{ !in_array(old('agama', $dataPribadi->agama ?? ''), ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu', '']) 
                                          ? old('agama', $dataPribadi->agama ?? '') 
                                          : '' }}">
                        </div>
                    <div class="mb-3">
                        <label for="tempat_lahir" class="form-label">Tempat Lahir</label>
                        <input type="text" name="tempat_lahir" id="tempat_lahir" class="form-control"
                    value="{{ old('tempat_lahir', $dataPersuratan->tempat_lahir ?? '') }}" required>
                    </div>
                    <div class="mb-3">
                        <label for="tgl_lahir" class="form-label">Tanggal Lahir</label>
                        <input type="date" name="tgl_lahir" id="tgl_lahir" class="form-control"
                    value="{{ old('tgl_lahir', $dataPersuratan->tgl_lahir ?? '') }}" required>
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">Email</label>
                        <input type="email" name="email" id="email" class="form-control"
                        value="{{ old('email', auth()->user()->email ?? '') }}" required>
                    </div><br>

                    <div class="mb-3">
                        <label class="form-label">Password </label>
                         <div class="input-group">
                        <input type="password" id="password-showhide" class="form-control" name="password" placeholder="Password">
 <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-showhide" tabindex="-1">
            <i class="bi bi-eye-slash"></i>
        </button>
    </div>
                    </div>

                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<script>
    // SweetAlert Sukses
    @if(session('success'))
        Swal.fire({
            icon: 'success',
            title: 'Berhasil!',
            text: '{{ session("success") }}',
            showConfirmButton: false,
            timer: 2000
        });
    @endif

    // Konfirmasi submit form
    document.addEventListener('DOMContentLoaded', function () {
        const form = document.querySelector('form');

        form.addEventListener('submit', function (e) {
            e.preventDefault(); // Cegah submit langsung

            Swal.fire({
                title: 'Simpan Data?',
                text: "Pastikan data sudah benar.",
                icon: 'question',
                showCancelButton: true,
                confirmButtonText: 'Ya, simpan!',
                cancelButtonText: 'Batal',
            }).then((result) => {
                if (result.isConfirmed) {
                    form.submit(); // Submit jika disetujui
                }
            });
        });
    });
</script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const agamaSelect = document.getElementById('agama-select');
        const agamaLainnyaContainer = document.getElementById('agama-lainnya-container');
        const agamaLainnyaInput = document.getElementById('agama-lainnya-input');
        const form = agamaSelect.closest('form');
    
        // Fungsi untuk menampilkan/menyembunyikan input lainnya
        function toggleAgamaLainnya() {
            const selectedValue = agamaSelect.value;
            const agamaStandar = ['Islam', 'Kristen Protestan', 'Kristen Katolik', 'Hindu', 'Buddha', 'Konghucu'];
            
            // Cek apakah nilai saat ini termasuk agama standar
            const currentAgama = "{{ old('agama', $dataPribadi->agama ?? '') }}";
            const isCustomAgama = currentAgama && !agamaStandar.includes(currentAgama);
    
            if (selectedValue === 'Lainnya' || isCustomAgama) {
                agamaLainnyaContainer.style.display = 'block';
                agamaLainnyaInput.required = true;
                
                if (isCustomAgama && selectedValue !== 'Lainnya') {
                    // Jika data yang ada bukan agama standar, set select ke Lainnya
                    agamaSelect.value = 'Lainnya';
                    agamaLainnyaInput.value = currentAgama;
                }
            } else {
                agamaLainnyaContainer.style.display = 'none';
                agamaLainnyaInput.required = false;
            }
        }
    
        // Inisialisasi pertama kali
        toggleAgamaLainnya();
    
        // Event listener untuk perubahan select
        agamaSelect.addEventListener('change', toggleAgamaLainnya);
    
        // Handler sebelum form submit
        form.addEventListener('submit', function(e) {
            if (agamaSelect.value === 'Lainnya') {
                // Buat hidden input untuk menyimpan nilai akhir
                const hiddenInput = document.createElement('input');
                hiddenInput.type = 'hidden';
                hiddenInput.name = 'agama';
                hiddenInput.value = agamaLainnyaInput.value.trim();
                form.appendChild(hiddenInput);
                
                // Nonaktifkan select asli agar tidak ikut terkirim
                agamaSelect.disabled = true;
            }
        });
         document.querySelectorAll('.toggle-password').forEach(button => {
        button.addEventListener('click', function () {
            const targetId = this.getAttribute('data-target');
            const input = document.getElementById(targetId);
            const icon = this.querySelector('i');

            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('bi-eye-slash');
                icon.classList.add('bi-eye');
            } else {
                input.type = 'password';
                icon.classList.remove('bi-eye');
                icon.classList.add('bi-eye-slash');
            }
        });
    });
    });
    </script>
@endpush

