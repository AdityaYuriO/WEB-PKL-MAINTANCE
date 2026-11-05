<h3>Laporan Iduka: {{ $iduka->nama }}</h3>
<p>Alamat: {{ $iduka->alamat ?? '-' }}</p>
<p>Pembimbing: {{ $iduka->nama_pimpinan ?? '-' }}</p>

<table border="1" cellpadding="5">
    <thead>
        <tr>
            <th>No</th>
            <th>Nama Siswa</th>
            <th>Kelas</th>
        </tr>
    </thead>
    <tbody>
        @foreach($siswa as $index => $s)
            <tr>
                <td>{{ $loop->iteration }}</td>
                <td>{{ $s->name }}</td>
                <td>{{ $s->kelas ?? '-' }}</td>
                <td>{{ $s->kelas->name_kelas ?? '-' }}</td>
            </tr>
        @endforeach
    </tbody>
</table>
