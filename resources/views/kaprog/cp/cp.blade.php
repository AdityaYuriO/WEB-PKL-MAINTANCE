@extends('layout.main')

@section('content')
<div class="container mt-4">

    {{-- Form Pencarian dan Tombol Tambah --}}
    <div class="card">
        <div class="card-body">
            <!-- 💻 Desktop Layout -->
            <div class="d-none d-md-flex justify-content-between align-items-center mb-2">
                <form action="{{ route('cp.index') }}" method="GET" class="d-flex" style="width: 100%; max-width: 500px;">
                    <input type="text" name="search" class="form-control me-2" placeholder="Cari Tujuan Pembelajaran..." value="{{ request('search') }}">
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-search"></i>
                    </button>
                </form>
                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#tambahCpTpModal">
                    Tambah CP & ATP
                </button>
            </div>
    
            <!-- 📱 Mobile Layout -->
            <div class="d-block d-md-none">
                <form action="{{ route('cp.index') }}" method="GET" class="mb-2">
                    <div class="input-group">
                        <input type="text" name="search" class="form-control" placeholder="Cari Tujuan Pembelajaran..." value="{{ request('search') }}">
                        <button class="btn btn-outline-primary" type="submit">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <button type="button" class="btn btn-primary w-100" data-bs-toggle="modal" data-bs-target="#tambahCpTpModal">
                    Tambah CP & ATP
                </button>
            </div>
        </div>
    </div>
      
    

    {{-- Menampilkan CP dan ATP dalam satu card per konsentrasi --}}
    @foreach ($konke as $konsentrasi)
    <div class="card mt-3">
        <div class="card-header">
            <h4>Konsentrasi: {{ $konsentrasi->name_konke }}</h4>
        </div>

        <div class="card-body">
            @foreach ($konsentrasi->cp as $index => $item)
            <div class="mb-3">
                <h5>{{ $index + 1 }}. {{ $item->cp }}</h5>
                <ul>
                    @foreach ($item->atp as $atpIndex => $atp)
                    <li>{{ $index + 1 }}.{{ $atpIndex + 1 }} - {{ $atp->atp }}</li>
                    @endforeach
                </ul>
            </div>
            @endforeach
        </div>

        <div class="card-footer">
            {{-- Tombol Edit dan Hapus --}}
            <button class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#editCpModal-{{ $konsentrasi->id }}">
                <i class="bi bi-pencil-square"></i> Edit
            </button>

            <form id="delete-form-{{ $konsentrasi->id }}" action="{{ route('cp.destroy', $konsentrasi->id) }}" method="POST" class="d-inline">
                @csrf
                @method('DELETE')
                <button type="button" class="btn btn-sm btn-danger" onclick="confirmDelete('delete-form-{{ $konsentrasi->id }}')">
                    <i class="bi bi-trash3"></i> Hapus
                </button>
            </form>
        </div>

{{-- Modal Edit CP & ATP --}}
<div class="modal fade" id="editCpModal-{{ $konsentrasi->id }}" tabindex="-1" aria-labelledby="editCpModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <form action="{{ route('cp.update', $konsentrasi->id) }}" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Edit CP dan ATP</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>

                <div class="modal-body">
                    @foreach($konsentrasi->cp as $cpIndex => $item)
                        {{-- Input CP --}}
                        <div class="mb-3">
                            <label for="cp" class="form-label">Deskripsi CP</label>
                            <input type="text" class="form-control" name="cp[{{ $cpIndex }}]" value="{{ $item->cp }}" required>
                        </div>

                        {{-- Input ATP --}}
                        <label class="form-label">Tujuan Pembelajaran (ATP)</label>
                        <div id="tpFieldsEdit-{{ $item->id }}">
                            @foreach($item->atp as $atpIndex => $atp)
                                <div class="input-group mb-2">
                                    <input type="text" name="atp[{{ $cpIndex }}][]" class="form-control" value="{{ $atp->atp }}" required>
                                    <button type="button" class="btn btn-secondary" onclick="removeField(this)">-</button>
                                </div>
                            @endforeach
                        </div>

                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addTpField('tpFieldsEdit-{{ $item->id }}', {{ $cpIndex }})">
                            + Tambah ATP
                        </button>
                    @endforeach
                </div>

                {{-- Tombol Simpan --}}
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </div>
        </form>
    </div>
</div>


    </div>
    @endforeach

    {{-- Modal Tambah CP & ATP --}}
    <div class="modal fade" id="tambahCpTpModal" tabindex="-1" aria-labelledby="tambahCpTpModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form action="{{ route('cp.store') }}" method="POST">
                @csrf
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Tambah CP dan ATP</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>

                    <div class="modal-body">
                        <div id="cpContainer">
                            <!-- CP & ATP akan ditambahkan di sini -->
                        </div>

                        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addCpField()">+ Tambah CP</button>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Data</button>
                    </div>
                </div>
            </form>
        </div>
    </div>

</div>

<script>
    // Menambahkan CP dan ATP secara dinamis di modal tambah
let cpIndex = 0;

function addCpField() {
    cpIndex++;
    let cpContainer = document.getElementById("cpContainer");
    let newCpField = document.createElement("div");
    newCpField.classList.add("mb-3", "border", "p-3", "rounded");
    newCpField.innerHTML = `
        <label class="form-label">CP ke-${cpIndex}</label>
        <input type="text" class="form-control mb-2" name="cp[]" placeholder="Masukkan CP" required>
        <div id="tpFields-${cpIndex}">
            <label class="form-label">Tujuan Pembelajaran (ATP)</label>
            <div class="input-group mb-2">
                <input type="text" name="atp[${cpIndex}][]" class="form-control" placeholder="Masukkan ATP" required>
                <button type="button" class="btn btn-secondary" onclick="removeField(this)">-</button>
            </div>
        </div>
        <button type="button" class="btn btn-sm btn-primary mt-2" onclick="addTpField('tpFields-${cpIndex}', ${cpIndex})">+ Tambah ATP</button>
    `;
    cpContainer.appendChild(newCpField);
}
 
    // Tambah ATP di form edit
    function addTpField(containerId, cpIndex) {
        let container = document.getElementById(containerId);
        let newField = document.createElement("div");
        newField.classList.add("input-group", "mb-2");
        newField.innerHTML = `
            <input type="text" name="atp[${cpIndex}][]" class="form-control" placeholder="Masukkan ATP" required>
            <button type="button" class="btn btn-secondary" onclick="removeField(this)">-</button>
        `;
        container.appendChild(newField);
    }

    // Hapus input
    function removeField(button) {
        button.parentElement.remove();
    }

    // Konfirmasi Hapus
    function confirmDelete(formId) {
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: 'Data yang dihapus tidak dapat dikembalikan!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, Hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById(formId).submit();
            }
        });
    }
</script>


@endsection