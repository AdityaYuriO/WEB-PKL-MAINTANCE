<div class="modal fade" id="edit{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('tujuan-pembelajaran.update', $item->id) }}" method="POST">

                @csrf
                @method('PUT')

                <div class="modal-header">
                    <h5>Edit Tujuan</h5>
                </div>

                <div class="modal-body">

                    <div class="mb-3">
                        <label>Konke</label>
                        <select name="id_konke" class="form-control">

                            @foreach ($konkes as $konke)
                                <option value="{{ $konke->id }}" @if ($item->id_konke == $konke->id) selected @endif>

                                    {{ $konke->nama_konke }}

                                </option>
                            @endforeach

                        </select>
                    </div>

                    <div class="mb-3">
                        <label>Tujuan Pembelajaran</label>
                        <textarea name="tujuan_pembelajaran" class="form-control">
{{ $item->tujuan_pembelajaran }}
</textarea>
                    </div>

                </div>

                <div class="modal-footer">
                    <button class="btn btn-success">Update</button>
                </div>

            </form>

        </div>
    </div>
</div>
