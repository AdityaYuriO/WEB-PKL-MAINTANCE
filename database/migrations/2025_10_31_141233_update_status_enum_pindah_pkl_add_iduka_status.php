<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement("ALTER TABLE pindah_pkl MODIFY COLUMN status ENUM('menunggu', 'menunggu_surat', 'siap_kirim', 'ditolak_persuratan', 'ditolak', 'diterima_iduka', 'ditolak_iduka')");
    }

    public function down()
    {
        DB::statement("ALTER TABLE pindah_pkl MODIFY COLUMN status ENUM('menunggu', 'menunggu_surat', 'siap_kirim', 'ditolak_persuratan', 'ditolak')");
    }
};
