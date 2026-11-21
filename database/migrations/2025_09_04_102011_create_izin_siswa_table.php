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
        Schema::create('izin_siswa', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('iduka_id')->constrained('idukas')->onDelete('cascade');
            $table->date('tanggal_izin');
            $table->enum('jenis_izin', ['izin', 'sakit', 'cuti', 'lainnya'])->default('izin');
            $table->text('alasan');
            $table->string('file_pendukung')->nullable(); // untuk surat dokter, dll
            $table->enum('status', ['pending', 'disetujui', 'ditolak'])->default('pending');
            $table->text('catatan_iduka')->nullable(); // catatan dari IDUKA
            $table->foreignId('dikonfirmasi_oleh')->nullable()->constrained('users')->onDelete('set null');
            $table->timestamp('dikonfirmasi_pada')->nullable();
            $table->timestamps();

            // Index untuk query yang sering digunakan
            $table->index(['iduka_id', 'status']);
            $table->index(['user_id', 'tanggal_izin']);
            $table->index(['tanggal_izin', 'status']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('izin_siswa');
    }
};
