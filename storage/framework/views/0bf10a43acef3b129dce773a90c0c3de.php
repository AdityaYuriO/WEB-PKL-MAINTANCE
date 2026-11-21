
<?php $__env->startSection('content'); ?>
    <!DOCTYPE html>
    <html lang="en">

    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="X-UA-Compatible" content="ie=edge">
        <title>Data Siswa</title>

        <style>
            @media (max-width: 768px) {
                .table th:nth-child(2),
                .table td:nth-child(2) {
                    min-width: 180px;
                    max-width: 100%;
                    white-space: normal;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
                .table th:nth-child(5),
                .table td:nth-child(5) {
                    min-width: 180px;
                    max-width: 100%;
                    white-space: normal;
                    word-wrap: break-word;
                    overflow-wrap: break-word;
                }
            }
        </style>

    </head>

    <body>
        <div class="container-fluid">
            <div class="content-wrapper">

                <div class="container-xxl flex-grow-1 container-p-y">
                    <div class="row">

                        <div class="card mb-3">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <h5 class="mb-0">Data Siswa</h5>

                                    
                                    <div class="d-none d-md-flex gap-2">
                                        <a href="<?php echo e(route('siswa.download-template')); ?>" class="btn btn-success btn-sm">
                                            <i class="bi bi-download"></i>
                                        </a>

                                        <button type="button" class="btn btn-warning btn-sm"
                                            data-bs-toggle="modal" data-bs-target="#searchModal">
                                            <i class="bi bi-search"></i>
                                        </button>

                                        <?php if(in_array(auth()->user()->role, ['hubin', 'guru'])): ?>
                                            <div class="dropdown">
                                                <button class="btn btn-sm btn-primary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                    <i class="bi bi-plus-lg"></i>
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-end">
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                                                            Tambah Data Manual
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="<?php echo e(route('siswa.import')); ?>" method="POST" enctype="multipart/form-data">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="file" name="file" class="d-none" id="fileInput" required onchange="this.form.submit()">
                                                            <button type="button" class="dropdown-item" onclick="document.getElementById('fileInput').click();">
                                                                Import Excel
                                                            </button>
                                                        </form>
                                                    </li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>
                                    </div>

                                    
                                    <div class="d-flex d-md-none">
                                        <div class="dropdown">
                                            <button class="btn btn-sm" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                <i class="bx bx-dots-vertical-rounded"></i>
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li>
                                                    <a href="<?php echo e(route('siswa.download-template')); ?>" class="dropdown-item text-success">
                                                        <i class="bi bi-download me-1"></i> Download Template
                                                    </a>
                                                </li>
                                                <li>
                                                    <button type="button" class="dropdown-item text-warning" data-bs-toggle="modal" data-bs-target="#searchModal">
                                                        <i class="bi bi-search me-1"></i> Search
                                                    </button>
                                                </li>

                                                <?php if(in_array(auth()->user()->role, ['hubin', 'guru'])): ?>
                                                    <li>
                                                        <button type="button" class="dropdown-item" data-bs-toggle="modal" data-bs-target="#tambahSiswaModal">
                                                            <i class="bi bi-plus me-1"></i> Tambah Manual
                                                        </button>
                                                    </li>
                                                    <li>
                                                        <form action="<?php echo e(route('siswa.import')); ?>" method="POST" enctype="multipart/form-data">
                                                            <?php echo csrf_field(); ?>
                                                            <input type="file" name="file" class="d-none" id="fileInputMobile" required onchange="this.form.submit()">
                                                            <button type="button" class="dropdown-item" onclick="document.getElementById('fileInputMobile').click();">
                                                                <i class="bi bi-file-earmark-excel me-1"></i> Import Excel
                                                            </button>
                                                        </form>
                                                    </li>
                                                <?php endif; ?>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="modal fade" id="searchModal" tabindex="-1" aria-labelledby="searchModalLabel" aria-hidden="true">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h5 class="modal-title" id="searchModalLabel">Cari Data Siswa</h5>
                                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                    </div>
                                    <div class="modal-body">
                                        <form action="#">
                                            <input type="text" name="search" class="form-control" placeholder="Cari Siswa...">
                                        </form>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="submit" class="btn btn-primary btn-sm">Cari</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body">
                                <?php if($errors->any()): ?>
                                <div class="alert alert-danger">
                                    <ul>
                                        <?php $__currentLoopData = $errors->all(); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                            <li><?php echo e($error); ?></li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                                <div class="table-responsive text-nowrap">
                                    <table class="table table-hover">
                                        <thead style="text-align: center">
                                            <tr>
                                                <th>No</th>
                                                <th>Nama</th>
                                                <th>NIS</th>
                                                <th>Kelas</th>
                                                <th>Konsentrasi Keahlian</th>
                                                <th>Status</th>
                                                <th>Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody style="text-align: center">
                                            <?php $__currentLoopData = $siswa; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $s): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <tr>
                                                    <td><?php echo e($siswa->firstItem() + $loop->index); ?></td>                                                    <td><?php echo e($s->name); ?></td>
                                                    <td><?php echo e($s->nip); ?></td>
                                                    <td>
                                                        <?php echo e(optional($s->kelas)->name_kelas ?? '-'); ?>

                                                        <small class="text-muted d-block">ID : <?php echo e($s->kelas_id ?? '-'); ?></small>
                                                    </td>
                                                    <td><?php echo e(optional($s->konke)->name_konke ?? '-'); ?>

                                                        <small class="text-muted d-block">ID : <?php echo e($s->konke_id ?? '-'); ?></small>
                                                    </td>
                                                    <td></td>
                                                    <td>
                                                        <div class="d-flex gap-2 justify-content-center flex-nowrap">
                                                            <?php if(in_array(auth()->user()->role, ['hubin', 'guru', 'kaprog'])): ?>
                                                            <button class="btn btn-warning btn-sm d-flex align-items-center"
                                                                data-bs-toggle="modal" data-bs-target="#editSiswaModal<?php echo e($s->id); ?>">
                                                                <i class="bi bi-pen"></i>
                                                            </button>
                                                            <?php endif; ?>

                                                             <?php if(auth()->user()->role == 'kaprog'): ?>
                                                            <a href="<?php echo e(route('kaprog.siswa.detail', $s->id)); ?>" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        <?php elseif(auth()->user()->role == 'hubin'): ?>
                                                            <a href="<?php echo e(route('siswa.detail', $s->id)); ?>" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        <?php elseif(auth()->user()->role == 'kepsek'): ?>
                                                            <a href="<?php echo e(route('kepsek.siswa.detail', $s->id)); ?>" class="btn btn-info btn-sm d-flex align-items-center">
                                                                <i class="fas fa-eye"></i>
                                                            </a>
                                                        <?php endif; ?>
                                                            <?php if(in_array(auth()->user()->role, ['hubin', 'guru'])): ?>
                                                            <form action="<?php echo e(route('siswa.destroy', $s->id)); ?>" method="POST" class="form-hapus-siswa">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('DELETE'); ?>
                                                                <button type="submit" class="btn btn-danger btn-sm d-flex align-items-center">
                                                                    <i class="fas fa-trash"></i>
                                                                </button>
                                                            </form>
                                                            <?php endif; ?>
                                                        </div>
                                                    </td>
                                                </tr>


                                                
                                                <div class="modal fade" id="editSiswaModal<?php echo e($s->id); ?>"
                                                    tabindex="-1" aria-labelledby="editSiswaModalLabel"
                                                    aria-hidden="true">
                                                    <div class="modal-dialog">
                                                        <div class="modal-content">
                                                            <div class="modal-header">
                                                                <h5 class="modal-title">Edit Data Siswa</h5>
                                                                <button type="button" class="btn-close"
                                                                    data-bs-dismiss="modal"></button>
                                                            </div>
                                                            <form action="<?php echo e(route('siswa.update', $s->id)); ?>"
                                                                method="POST">
                                                                <?php echo csrf_field(); ?>
                                                                <?php echo method_field('PUT'); ?>
                                                                <div class="modal-body">
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Nama Lengkap Siswa*</label>
                                                                        <input type="text" class="form-control" name="name" value="<?php echo e($s->name); ?>" required>
                                                                        <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">NIS*</label>
                                                                        <input type="text" class="form-control" name="nip" value="<?php echo e($s->nip); ?>" required>
                                                                        <small class="form-text text-muted"><i>Isi NIS di sini, pastikan sudah benar!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Kelas*</label>
                                                                        <select class="form-control" name="kelas_id"
                                                                            required>
                                                                            <option value="">Pilih Kelas</option>
                                                                            <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($kls->id); ?>"
                                                                                    <?php echo e($s->kelas_id == $kls->id ? 'selected' : ''); ?>>
                                                                                    
                                                                                    <?php echo e($kls->name_kelas); ?>

                                                                                </option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                        <small class="form-text text-muted"><i>Pilih kelas yang sesuai!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label"> Konsentrasi
                                                                            Keahlian*</label>
                                                                        <select class="form-control" name="konke_id"
                                                                            required>
                                                                            <option value="">Pilih Konsentrasi
                                                                                Keahlian</option>
                                                                            <?php $__currentLoopData = $konke; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                                                <option value="<?php echo e($k->id); ?>"
                                                                                    <?php echo e($s->konke_id == $k->id ? 'selected' : ''); ?>>
                                                                                    <?php echo e($k->name_konke); ?>

                                                                                </option>
                                                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                                                        </select>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Email*</label>
                                                                        <input type="text" class="form-control" name="email" value="<?php echo e($s->email); ?>" required>
                                                                        <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                                                                    </div>
                                                                    <div class="mb-3">
                                                                        <label class="form-label">Password Baru
                                                                            (Opsional)</label>
                                                                              <div class="input-group">
                                                                        <input type="password"  id="password-edit-<?php echo e($s->id); ?>" class="form-control"
                                                                            name="password">
                                                                            <button type="button" class="btn btn-outline-secondary toggle-password" data-target="password-edit-<?php echo e($s->id); ?>" tabindex="-1">
                                                                            <i class="fas fa-eye-slash"></i>
                                                                        </button>
                                                                    </div>
                                                                        <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary btn-sm" data-bs-dismiss="modal">Tutup</button>
                                                                    <button type="submit" class="btn btn-primary btn-sm">Simpan</button>
                                                                </div>
                                                            </form>
                                                        </div>
                                                    </div>
                                                </div>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </tbody>
                                    </table>
                                </div>
                                <div class="d-flex justify-content-end mt-3">
                                    <?php echo e($siswa->links('pagination::bootstrap-5')); ?>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        </div>
        </div>
    </body>
    
    <div class="modal fade" id="tambahSiswaModal" tabindex="-1" aria-labelledby="tambahSiswaModalLabel"
        aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Tambah Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form action="<?php echo e(route('siswa.store')); ?>" method="POST">
                    <?php echo csrf_field(); ?>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label">Nama Lengkap Siswa*</label>
                            <input type="text" class="form-control" name="name" value="<?php echo e(old('name')); ?>" placeholder="Masukkan Nama Lengkap Siswa" required>
                            <small class="form-text text-muted"><i>Nama lengkap ini akan tercatat di sistem, pastikan sudah benar!</i></small>

                            <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">NIS*</label>
                            <input type="text" class="form-control" name="nip" value="<?php echo e(old('nip')); ?>" placeholder="Masukkan NISN" required>
                            <small class="form-text text-muted"><i>Isi NIS di sini, pastikan sudah benar!</i></small>
                            <?php $__errorArgs = ['nip'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Kelas</label>
                            <select class="form-control" name="kelas_id" required>
                                <option value="">Pilih Kelas</option>
                                <?php $__currentLoopData = $kelas; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $kls): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($kls->id); ?>" <?php echo e(old('kelas_id') == $kls->id ? 'selected' : ''); ?>> <?php echo e($kls->name_kelas); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <small class="form-text text-muted"><i>Pilih kelas yang sesuai!</i></small>
                            <?php $__errorArgs = ['kelas_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Konsentrasi Keahlian*</label>
                            <select class="form-control" name="konke_id" required>
                                <option value="">Pilih Konsentrasi Keahlian</option>
                                <?php $__currentLoopData = $konke; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $k): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($k->id); ?>" <?php echo e(old('konke_id') == $k->id ? 'selected' : ''); ?>>
                                        <?php echo e($k->name_konke); ?>

                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                            <?php $__errorArgs = ['konke_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Email*</label>
                            <input type="email" class="form-control" name="email" value="<?php echo e(old('email')); ?>" placeholder="Masukkan Email" required>
                            <small class="form-text text-muted"><i>Masukkan email aktif. Pastikan bisa diakses!</i></small>
                            <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="mb-3">
                            <label class="form-label">Password*</label>
                            <div class="input-group">
                                <input type="password" class="form-control" id="password-create" name="password"
                                    placeholder="Masukkan Password" required>
                                <button type="button" class="btn btn-outline-secondary toggle-password"
                                    data-target="password-create" tabindex="-1">
                                    <i class="fas fa-eye-slash"></i>
                                </button>
                            </div>
                            <small class="form-text text-muted"><i>Password minimal 8 karakter.</i></small>
                            <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="text-danger"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <?php echo $__env->make('siswa.datasiswa.createSiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>
    <?php echo $__env->make('siswa.datasiswa.editSiswa', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?>

    </html>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Toggle password visibility
            document.querySelectorAll('.toggle-password').forEach(button => {
                button.addEventListener('click', function() {
                    const targetId = this.getAttribute('data-target');
                    const input = document.getElementById(targetId);
                    const icon = this.querySelector('i');

                    if (input) {
                        if (input.type === 'password') {
                            input.type = 'text';
                            icon.classList.remove('bi-eye-slash');
                            icon.classList.add('bi-eye');
                        } else {
                            input.type = 'password';
                            icon.classList.remove('bi-eye');
                            icon.classList.add('bi-eye-slash');
                        }
                    }
                });
            });

            // Delete confirmation
            document.querySelectorAll('.form-hapus-siswa').forEach(form => {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    Swal.fire({
                        title: 'Yakin ingin menghapus?',
                        text: "Data siswa yang dihapus tidak bisa dikembalikan!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Ya, hapus!',
                        cancelButtonText: 'Batal'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            form.submit();
                        }
                    });
                });
            });

            // Search functionality
            const searchInput = document.querySelector("input[name='search']");
            if (searchInput) {
                const tableRows = document.querySelectorAll("tbody tr");
                searchInput.addEventListener("keyup", function() {
                    const searchValue = this.value.toLowerCase();
                    tableRows.forEach(row => {
                        const text = row.textContent.toLowerCase();
                        row.style.display = text.includes(searchValue) ? "" : "none";
                    });
                });
            }

            // Flash messages
            let successMessage = "<?php echo e(session('success')); ?>";
            let errorMessage = "<?php echo e(session('error')); ?>";

            if (successMessage) {
                Swal.fire({
                    icon: 'success',
                    title: 'Berhasil!',
                    text: successMessage,
                    timer: 2000,
                    showConfirmButton: false
                });
            }

            if (errorMessage) {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal!',
                    text: errorMessage,
                    showConfirmButton: true
                });
            }
        });
    </script>
<?php $__env->stopSection(); ?>


<?php echo $__env->make('layout.main', \Illuminate\Support\Arr::except(get_defined_vars(), ['__data', '__path']))->render(); ?><?php /**PATH C:\laragon\www\web-pkl-maintance\konek ke git\WEB-PKL-MAINTANCE\resources\views/siswa/datasiswa/datasiswa.blade.php ENDPATH**/ ?>