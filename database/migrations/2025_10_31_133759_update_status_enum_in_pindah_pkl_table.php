<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('pindah_pkl', function (Blueprint $table) {

            // Tambahkan kembali dengan enum baru
            $table->enum('status', ['menunggu', 'menunggu_surat', 'siap_kirim', 'ditolak_persuratan', 'ditolak'])
                ->default('menunggu');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pindah_pkl', function (Blueprint $table) {
            $table->dropColumn('status');

            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])
                ->default('menunggu')
                ->after('konke_id');
        });
    }
};
