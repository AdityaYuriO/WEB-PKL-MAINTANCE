<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('hubin','siswa','kaprog','guru','iduka','persuratan','ppkl','orangtua','psekolah', 'kepsek') NOT NULL");
    }
    public function down(): void
    { // Balikkan ke enum sebelumnya, tanpa kepsek 
        DB::statement("ALTER TABLE users MODIFY COLUMN role ENUM('hubin','siswa','kaprog','guru','iduka','persuratan','ppkl','orangtua','psekolah') NOT NULL");
    }
};
