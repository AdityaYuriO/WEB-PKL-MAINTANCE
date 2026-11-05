<table>
    <thead>
        <tr>
            <th colspan="4">Rekap Absensi Tanggal {{ $tanggal }}</th>
        </tr>
        <tr>
            <th>Kelas</th>
            <th>Total Siswa</th>
            <th>Hadir</th>
            <th>Tingkat Kehadiran (%)</th>
        </tr>
    </thead>
    <tbody>
        @foreach ($data as $row)
            <tr>
                <td>{{ $row['kelas'] }}</td>
                <td>{{ $row['total_siswa'] }}</td>
                <td>{{ $row['hadir'] }}</td>
                <td>{{ $row['persentase'] }}%</td>
            </tr>
        @endforeach
    </tbody>
</table>
