@extends('layout.main')

@section('content')
<div class="container-fluid py-4">
    <div class="card border-0 shadow-sm mb-4 card-hover">
        <div class="card-body p-4">
            <h5 class="mb-4 fw-bold"><i class="fas fa-user-circle text-primary me-2"></i>Profil Saya</h5>

            @if(session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            <form action="{{ route('profile.update') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <div class="row align-items-center mb-4">
                    <div class="col-auto">
                        <img src="{{ Auth::user()->profile_photo ? asset(Auth::user()->profile_photo) : asset('images/default.jpg') }}"
                            alt="Foto Profil"
                            class="rounded-circle shadow-sm"
                            width="100" height="100">
                    </div>
                    <div class="col">
                        <label class="form-label fw-medium">Foto Profil</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-camera text-muted"></i></span>
                            <input type="file" name="profile_photo" class="form-control border-start-0 ps-0" id="profilePhotoInput">
                        </div>
                        @error('profile_photo')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label fw-medium">Nama Lengkap</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-id-badge text-muted"></i></span>
                        <input type="text" name="name" class="form-control border-start-0 ps-0" value="{{ old('name', $user->name) }}">
                    </div>
                    @error('name')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    @if(auth()->user()->role === 'siswa')
                        <label class="form-label fw-medium">NIS</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                            <input type="text" name="nip" class="form-control border-start-0 ps-0" value="{{ old('nip', $user->nip) }}" placeholder="Masukkan NIS">
                        </div>
                    @elseif(in_array(auth()->user()->role, ['iduka', 'industri']))
                        <label class="form-label fw-medium">Email</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-envelope text-muted"></i></span>
                            <input type="email" name="nip" class="form-control border-start-0 ps-0" value="{{ old('nip', $user->nip) }}" placeholder="Masukkan email">
                        </div>
                    @else
                        <label class="form-label fw-medium">NIP</label>
                        <div class="input-group">
                            <span class="input-group-text bg-light border-end-0"><i class="fas fa-hashtag text-muted"></i></span>
                            <input type="text" name="nip" class="form-control border-start-0 ps-0" value="{{ old('nip', $user->nip) }}" placeholder="Masukkan NIP">
                        </div>
                    @endif
                    @error('nip')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-4 fw-medium shadow-sm"><i class="fas fa-save me-2"></i>Simpan Profil</button>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm card-hover">
        <div class="card-body p-4">
            <h5 class="mb-4 fw-bold"><i class="fas fa-lock text-primary me-2"></i>Keamanan & Password</h5>

            <form action="{{ route('profile.update.password') }}" method="POST">
                @csrf

                <!-- Password Saat Ini -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Password Saat Ini</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-key text-muted"></i></span>
                        <input type="password" name="current_password" class="form-control border-start-0 ps-0" id="current_password" placeholder="••••••••">
                        <button type="button" class="btn btn-light border toggle-password" data-target="current_password" tabindex="-1">
                            <i class="bi bi-eye-slash text-muted"></i>
                        </button>
                    </div>
                    @error('current_password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                </div>

                <!-- Password Baru -->
                <div class="mb-3">
                    <label class="form-label fw-medium">Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-asterisk text-muted"></i></span>
                        <input type="password" name="new_password" class="form-control border-start-0 ps-0" id="new_password" placeholder="••••••••">
                        <button type="button" class="btn btn-light border toggle-password" data-target="new_password" tabindex="-1">
                            <i class="bi bi-eye-slash text-muted"></i>
                        </button>
                    </div>
                    @error('new_password')<div class="text-danger mt-1 small">{{ $message }}</div>@enderror
                </div>

                <!-- Konfirmasi Password Baru -->
                <div class="mb-4">
                    <label class="form-label fw-medium">Konfirmasi Password Baru</label>
                    <div class="input-group">
                        <span class="input-group-text bg-light border-end-0"><i class="fas fa-asterisk text-muted"></i></span>
                        <input type="password" name="new_password_confirmation" class="form-control border-start-0 ps-0" id="new_password_confirmation" placeholder="••••••••">
                        <button type="button" class="btn btn-light border toggle-password" data-target="new_password_confirmation" tabindex="-1">
                            <i class="bi bi-eye-slash text-muted"></i>
                        </button>
                    </div>
                </div>

                <div class="d-flex justify-content-end">
                    <button class="btn btn-primary px-4 fw-medium shadow-sm"><i class="fas fa-sync-alt me-2"></i>Perbarui Password</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal untuk cropping gambar -->
<div class="modal fade" id="cropModal" tabindex="-1" aria-labelledby="cropModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="cropModalLabel">Crop Foto Profil</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="img-container">
                    <img id="imageToCrop" src="" alt="Image to crop">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-primary" id="cropImageBtn">Crop</button>
            </div>
        </div>
    </div>
</div>

<!-- Include CropperJS CSS and JS -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css" rel="stylesheet">
<link href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.css" rel="stylesheet">
<script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.5.12/cropper.min.js"></script>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Initialize variables
    let cropper;
    const profilePhotoInput = document.getElementById('profilePhotoInput');
    const cropModal = new bootstrap.Modal(document.getElementById('cropModal'));
    const imageToCrop = document.getElementById('imageToCrop');
    const cropImageBtn = document.getElementById('cropImageBtn');

    // Preview elements
    const currentPhoto = document.querySelector('.rounded-circle.shadow-sm');

    // When file input changes
    profilePhotoInput.addEventListener('change', function(e) {
        if (e.target.files.length === 0) return;

        const file = e.target.files[0];
        const reader = new FileReader();

        reader.onload = function(event) {
            // Show the cropping modal
            imageToCrop.src = event.target.result;
            cropModal.show();
        };

        reader.readAsDataURL(file);
    });

    // When modal is shown, initialize cropper
    document.getElementById('cropModal').addEventListener('shown.bs.modal', function() {
        cropper = new Cropper(imageToCrop, {
            aspectRatio: 1, // 1:1 aspect ratio
            viewMode: 1,
            autoCropArea: 0.8,
            responsive: true,
            movable: false,
            zoomable: false,
            rotatable: false,
            scalable: false
        });
    });

    // When modal is hidden, destroy cropper
    document.getElementById('cropModal').addEventListener('hidden.bs.modal', function() {
        if (cropper) {
            cropper.destroy();
        }
    });

    // Crop button click handler
    cropImageBtn.addEventListener('click', function() {
        // Get cropped canvas
        const canvas = cropper.getCroppedCanvas({
            width: 300,
            height: 300,
            minWidth: 100,
            minHeight: 100,
            maxWidth: 1000,
            maxHeight: 1000,
            fillColor: '#fff',
            imageSmoothingEnabled: true,
            imageSmoothingQuality: 'high',
        });

        if (canvas) {
            // Convert canvas to blob
            canvas.toBlob(function(blob) {
                // Create a new file input
                const newFile = new File([blob], "profile.jpg", { type: "image/jpeg" });

                // Create a DataTransfer to make a new FileList
                const dataTransfer = new DataTransfer();
                dataTransfer.items.add(newFile);

                // Set the new file to the input
                profilePhotoInput.files = dataTransfer.files;

                // Update preview
                const url = URL.createObjectURL(blob);
                currentPhoto.src = url;

                // Hide modal
                cropModal.hide();
            }, 'image/jpeg', 0.9);
        }
    });

    // Toggle password visibility
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
@endsection
