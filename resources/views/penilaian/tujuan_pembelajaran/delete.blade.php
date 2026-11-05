{{-- <div class="modal fade" id="delete{{ $item->id }}">
    <div class="modal-dialog">
        <div class="modal-content">

            <form action="{{ route('tujua', $item->id) }}" method="POST">

                @csrf
                @method('DELETE')

                <div class="modal-body">
                    Yakin ingin menghapus data ini?
                </div>

                <div class="modal-footer">
                    <button class="btn btn-danger">Hapus</button>
                </div>

            </form>

        </div>
    </div>
</div> --}}
