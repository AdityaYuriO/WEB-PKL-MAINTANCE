<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('riwayat_kelas', function (Blueprint $table) {
            $table->id();

            // Kalau user dihapus, riwayatnya ikut terhapus (mengikuti pola FK lain di proyek ini)
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');

            // Kalau kelas dihapus, JANGAN ikut hapus riwayat -> set null saja supaya histori tetap ada
            $table->foreignId('kelas_lama_id')->nullable()->constrained('kelas')->nullOnDelete();
            $table->foreignId('kelas_baru_id')->nullable()->constrained('kelas')->nullOnDelete();

            $table->foreignId('tahun_ajaran_id')->nullable()->constrained('tahun_ajaran')->nullOnDelete();

            // 'naik'  = pindah tingkat (mis. XI -> XII)
            // 'lulus' = jadi alumni (kelas_baru_id = null)
            $table->enum('jenis', ['naik', 'lulus']);

            $table->foreignId('dilakukan_oleh')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('riwayat_kelas');
    }
};
