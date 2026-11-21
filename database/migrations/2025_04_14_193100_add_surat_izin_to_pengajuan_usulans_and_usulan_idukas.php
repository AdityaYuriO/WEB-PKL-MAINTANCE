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
        Schema::table('pengajuan_usulans', function (Blueprint $table) {
            $table->enum('surat_izin', ['belum', 'sudah'])->default('belum')->after('status');
        });

        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->enum('surat_izin', ['belum', 'sudah'])->default('belum')->after('status');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('pengajuan_usulans', function (Blueprint $table) {
            $table->dropColumn('surat_izin');
        });

        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->dropColumn('surat_izin');
        });
    }
};
