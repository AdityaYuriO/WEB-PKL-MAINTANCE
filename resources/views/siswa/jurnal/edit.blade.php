{{-- File: resources/views/siswa/jurnal/partials/edit.blade.php --}}

<div class="row g-3">
    <div class="col-md-6">
        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
            <i class="fas fa-calendar-alt me-1"></i>Tanggal
        </label>
        <input type="date" class="form-control form-control-lg" name="tgl" value="{{ old('tgl', $jurnal->tgl) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
            <i class="fas fa-image me-1"></i>Foto Kegiatan
        </label>
        <input type="file" class="form-control form-control-lg" name="foto" accept="image/*" id="editFotoInput">
        @if($jurnal->foto)
            <div class="mt-2 p-2 bg-light rounded">
                <small class="text-muted d-flex align-items-center">
                    <i class="fas fa-image-fill text-primary me-2"></i>
                    <span>Foto saat ini: </span>
                    <a href="{{ asset($jurnal->foto) }}" target="_blank" class="text-primary ms-1 text-decoration-none fw-semibold">
                        <i class="fas fa-eye me-1"></i>Lihat foto
                    </a>
                </small>
            </div>
        @else
            <small class="text-muted d-block mt-1">
                <i class="fas fa-info-circle me-1"></i>Belum ada foto. Upload foto baru jika diperlukan.
            </small>
        @endif
        <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF (Max: 2MB)</small>
    </div>
</div>

<div class="row g-3 mt-1">
    <div class="col-md-6">
        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
            <i class="fas fa-clock me-1"></i>Jam Mulai
        </label>
        <input type="time" class="form-control form-control-lg" name="jam_mulai" value="{{ old('jam_mulai', $jurnal->jam_mulai) }}" required>
    </div>
    <div class="col-md-6">
        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
            <i class="fas fa-history me-1"></i>Jam Selesai
        </label>
        <input type="time" class="form-control form-control-lg" name="jam_selesai" value="{{ old('jam_selesai', $jurnal->jam_selesai) }}" required>
    </div>
</div>

<div class="mt-3">
    <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
        <i class="fas fa-file-alt me-1"></i>Uraian Kegiatan
    </label>
    <textarea class="form-control form-control-lg" name="uraian" rows="5" 
        placeholder="Tuliskan uraian kegiatan yang dilakukan..." required>{{ old('uraian', $jurnal->uraian) }}</textarea>
    <small class="text-muted">
        <i class="fas fa-info-circle me-1"></i>Maksimal 1000 karakter
    </small>
</div>

<div class="mt-4 p-3 bg-light rounded">
    <div class="form-check mb-3">
        <input type="checkbox" class="form-check-input" name="is_pengetahuan_baru" 
            id="edit_is_pengetahuan_baru" value="1" 
            {{ old('is_pengetahuan_baru', $jurnal->is_pengetahuan_baru) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="edit_is_pengetahuan_baru">
            <i class="fas fa-lightbulb text-warning text-warning me-1"></i>
            Termasuk pengetahuan baru
        </label>
    </div>

    <div class="form-check">
        <input type="checkbox" class="form-check-input" name="is_dalam_mapel" 
            id="edit_is_dalam_mapel" value="1" 
            {{ old('is_dalam_mapel', $jurnal->is_dalam_mapel) ? 'checked' : '' }}>
        <label class="form-check-label fw-semibold" for="edit_is_dalam_mapel">
            <i class="fas fa-book-open text-primary text-primary me-1"></i>
            Kegiatan ada dalam mapel sekolah
        </label>
    </div>
</div>

@if($jurnal->status === 'rejected')
    <div class="alert alert-danger mt-4 border-0 shadow-sm">
        <div class="d-flex align-items-center">
            <i class="fas fa-exclamation-triangle fs-3 me-3"></i>
            <div>
                <strong class="d-block mb-1">Jurnal Ditolak</strong>
                <small>Silakan perbaiki jurnal Anda dan kirim ulang untuk validasi.</small>
            </div>
        </div>
    </div>
@endif

<style>
    .form-control-lg {
        padding: 0.75rem 1rem;
        font-size: 1rem;
        border-radius: 0.5rem;
    }
    
    .form-control:focus {
        border-color: #ffc107;
        box-shadow: 0 0 0 0.2rem rgba(255, 193, 7, 0.25);
    }
    
    .form-check-input {
        width: 1.25rem;
        height: 1.25rem;
        cursor: pointer;
    }
    
    .form-check-input:checked {
        background-color: #6366f1;
        border-color: #6366f1;
    }
    
    .form-check-label {
        cursor: pointer;
        user-select: none;
    }
    
    .bg-light {
        background-color: #f8f9fa !important;
    }
    
    textarea.form-control {
        resize: vertical;
        min-height: 120px;
    }
    
    .alert-danger {
        animation: slideIn 0.3s ease;
    }
    
    @keyframes slideIn {
        from {
            opacity: 0;
            transform: translateY(-10px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }
</style>
