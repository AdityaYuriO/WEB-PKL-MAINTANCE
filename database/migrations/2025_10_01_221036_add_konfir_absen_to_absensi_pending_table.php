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
        // Dalam migration
        Schema::table('absensi_pending', function (Blueprint $table) {
            $table->string('dikonfirmasi_oleh')->nullable()->after('status_konfirmasi');
            $table->timestamp('waktu_konfirmasi')->nullable()->after('dikonfirmasi_oleh');
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
