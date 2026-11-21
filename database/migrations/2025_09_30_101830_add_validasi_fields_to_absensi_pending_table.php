<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('absensi_pending', function (Blueprint $table) {
            // Tambahkan field untuk validasi IDUKA
            $table->string('validasi_iduka', 20)->default('pending')->after('status_konfirmasi');
            $table->timestamp('approved_iduka_at')->nullable()->after('approved_pembimbing_at');

            // Tambahkan field untuk alasan penolakan
            $table->text('alasan_penolakan')->nullable()->after('keterangan');

            // Index untuk performa query
            $table->index(['validasi_iduka', 'validasi_pembimbing']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pending', function (Blueprint $table) {
            $table->dropIndex(['validasi_iduka', 'validasi_pembimbing']);
            $table->dropColumn(['validasi_iduka', 'approved_iduka_at', 'alasan_penolakan']);
        });
    }
};
