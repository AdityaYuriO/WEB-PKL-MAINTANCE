<?php
require_once __DIR__ . '/vendor/autoload.php';
 $app = require_once __DIR__ . '/bootstrap/app.php';
 $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
 $kernel->bootstrap();

try {
    // Check database connection
    DB::connection()->getPdo();
    echo "Database connection: OK<br>";

    // Check user table
    $userCount = DB::table('users')->count();
    echo "Total users: " . $userCount . "<br>";

    // Check absensi table
    $absensiCount = DB::table('absensis')->count();
    echo "Total absensi records: " . $absensiCount . "<br>";

    // Check iduka table
    $idukaCount = DB::table('idukas')->count();
    echo "Total idukas: " . $idukaCount . "<br>";

    // Check guru table
    $guruCount = DB::table('gurus')->count();
    echo "Total gurus: " . $guruCount . "<br>";

} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "<br>";
}
