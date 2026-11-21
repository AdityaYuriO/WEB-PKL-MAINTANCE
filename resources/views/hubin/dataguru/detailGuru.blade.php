@foreach ($guru as $g)
<!-- Modal Detail Guru -->
<div class="modal fade" id="detailGuruModal{{ $g->id }}" tabindex="-1" aria-labelledby="detailGuruModalLabel{{ $g->id }}" aria-hidden="true">
  <div class="modal-dialog modal-lg">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="detailGuruModalLabel{{ $g->id }}">Detail Guru: {{ $g->nama }}</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <table class="table table-hover">
          <tr>
            <td><i class="bi bi-person-vcard-fill me-2"></i>Nama Guru</td>
            <td>:</td>
            <td>{{ $g->nama }}</td>
          </tr>
          <tr>
            <td><i class="bi bi-person-badge me-2"></i>NIK</td>
            <td>:</td>
            <td>{{ $g->nik }}</td>
          </tr>
          <tr>
            <td>NIP/NUPTK</td>
            <td>:</td>
            <td>{{ $g->nip }}</td>
          </tr>
          <tr>
            <td>Tempat, Tanggal Lahir</td>
            <td>:</td>
            <td>{{ $g->tempat_lahir }}, {{ \Carbon\Carbon::parse($g->tanggal_lahir)->translatedFormat('d F Y') }}</td>
          </tr>
          <tr>
            <td>Jenis Kelamin</td>
            <td>:</td>
            <td>{{ $g->jenis_kelamin }}</td>
          </tr>
          <tr>
            <td>Alamat</td>
            <td>:</td>
            <td>{{ $g->alamat }}</td>
          </tr>
          <tr>
            <td>Konsentrasi Keahlian</td>
            <td>:</td>
            <td>{{ $g->konke?->name_konke ?? '-' }}</td>
          </tr>
          <tr>
            <td>Email</td>
            <td>:</td>
            <td>{{ $g->email }}</td>
          </tr>
          <tr>
            <td>No HP</td>
            <td>:</td>
            <td>{{ $g->no_hp }}</td>
          </tr>
          <tr>
            <td>Password</td>
            <td>:</td>
            <td><i class="bi bi-shield-lock-fill"></i> (terenkripsi)</td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
      </div>
    </div>
  </div>
</div>
@endforeach
