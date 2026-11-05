<div class="modal fade" id="modalCreate">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('tujuan-pembelajaran.store') }}" method="POST">
                @csrf

                <div class="modal-header">
                    <h5>Tambah Tujuan Pembelajaran</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Konke</label>
                        <select name="id_konke" class="form-control" required>

                            @foreach ($konkes as $konke)
                                <option value="{{ $konke->id }}">
                                    {{ $konke->nama_konke }}
                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tujuan Pembelajaran</label>
                        <textarea name="tujuan_pembelajaran" class="form-control"></textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-primary">Simpan</button>
                </div>

            </form>

        </div>
    </div>
</div>
