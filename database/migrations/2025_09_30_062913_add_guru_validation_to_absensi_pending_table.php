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
        Schema::table('absensi_pending', function (Blueprint $table) {
            $table->enum('validasi_pembimbing', ['pending', 'disetujui', 'ditolak'])->default('pending')->after('status_konfirmasi');
            $table->timestamp('approved_pembimbing_at')->nullable()->after('validasi_pembimbing');
        });
    }

    public function down()
    {
        Schema::table('absensi_pending', function (Blueprint $table) {
            $table->dropColumn(['validasi_pembimbing', 'approved_pembimbing_at']);
        });
    }
};
