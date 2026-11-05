<?php
require_once __DIR__ . '/vendor/autoload.php';
 $app = require_once __DIR__ . '/bootstrap/app.php';
 $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
 $kernel->bootstrap();

try {
    // Cek relasi antara tabel absensi dan users
    echo "<h1>Relasi Tabel Absensi dan Users</h1>";

    try {
        $absensiWithUser = DB::table('absensi')
            ->join('users', 'absensi.user_id', '=', 'users.id')
            ->select('absensi.id', 'absensi.tanggal', 'users.name as user_name')
            ->limit(5)
            ->get();

        echo "<table border='1'>";
        echo "<tr><th>ID Absensi</th><th>Tanggal</th><th>Nama User</th></tr>";

        foreach ($absensiWithUser as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row->id) . "</td>";
            echo "<td>" . htmlspecialchars($row->tanggal) . "</td>";
            echo "<td>" . htmlspecialchars($row->user_name) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "<p style='color: green;'>Relasi absensi-users berhasil!</p>";

    } catch (\Exception $e) {
        echo "<p style='color: red;'>Error relasi absensi-users: " . $e->getMessage() . "</p>";
    }

    // Cek relasi antara tabel absensi dan lokasi_iduka
    echo "<h1>Relasi Tabel Absensi dan Lokasi IDUKA</h1>";

    try {
        $absensiWithLokasi = DB::table('absensi')
            ->leftJoin('lokasi_iduka', 'absensi.lokasi_iduka_id', '=', 'lokasi_iduka.id')
            ->select('absensi.id', 'absensi.tanggal', 'lokasi_iduka.nama as lokasi_nama')
            ->limit(5)
            ->get();

        echo "<table border='1'>";
        echo "<tr><th>ID Absensi</th><th>Tanggal</th><th>Nama Lokasi</th></tr>";

        foreach ($absensiWithLokasi as $row) {
            echo "<tr>";
            echo "<td>" . htmlspecialchars($row->id) . "</td>";
            echo "<td>" . htmlspecialchars($row->tanggal) . "</td>";
            echo "<td>" . htmlspecialchars($row->lokasi_nama) . "</td>";
            echo "</tr>";
        }

        echo "</table>";
        echo "<p style='color: green;'>Relasi absensi-lokasi_iduka berhasil!</p>";

    } catch (\Exception $e) {
        echo "<p style='color: red;'>Error relasi absensi-lokasi_iduka: " . $e->getMessage() . "</p>";
    }

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage();
}
