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
        Schema::table('absensi_pending', function (Blueprint $table) {
            $table->index(['tanggal', 'status_konfirmasi'], 'idx_tanggal_status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pending', function (Blueprint $table) {
            //
        });
    }
};
