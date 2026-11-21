<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('pindah_pkl', function (Blueprint $table) {
            $table->unsignedBigInteger('iduka_lama_id')->nullable()->after('siswa_id');
            $table->unsignedBigInteger('iduka_baru_id')->nullable()->after('iduka_lama_id');

            // optional: tambahkan relasi
            $table->foreign('iduka_lama_id')->references('id')->on('idukas')->onDelete('set null');
            $table->foreign('iduka_baru_id')->references('id')->on('idukas')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('pindah_pkl', function (Blueprint $table) {
            $table->dropForeign(['iduka_lama_id']);
            $table->dropForeign(['iduka_baru_id']);
            $table->dropColumn(['iduka_lama_id', 'iduka_baru_id']);
        });
    }
};
