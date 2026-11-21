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
        Schema::create('absensi_pending', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('iduka_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis', ['masuk', 'pulang']);
            $table->time('jam');
            $table->decimal('latitude', 10, 8)->nullable();
            $table->decimal('longitude', 11, 8)->nullable();
            $table->enum('status', ['tepat_waktu', 'terlambat'])->nullable();
            $table->enum('status_konfirmasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('keterangan')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'tanggal']);
            $table->index(['iduka_id', 'status_konfirmasi']);
        });

        Schema::create('izin_pending', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('iduka_id')->constrained()->onDelete('cascade');
            $table->date('tanggal');
            $table->enum('jenis_izin', ['sakit', 'keperluan_keluarga', 'keperluan_sekolah', 'lainnya']);
            $table->text('keterangan');
            $table->enum('status_konfirmasi', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
            
            $table->index(['user_id', 'tanggal']);
            $table->index(['iduka_id', 'status_konfirmasi']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('absensi_pending');
        Schema::dropIfExists('izin_pending');
    }
};