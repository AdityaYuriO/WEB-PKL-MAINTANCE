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
        Schema::create('jurnals', function (Blueprint $table) {
            $table->id();
            $table->string('nis', 20)->nullable(); // NIS siswa
            $table->date('tgl'); // Tanggal kegiatan
            $table->text('uraian'); // Uraian kegiatan
            $table->time('jam_mulai'); // Jam mulai
            $table->time('jam_selesai'); // Jam selesai
            $table->enum('validasi_iduka', ['belum', 'sudah'])->default('belum'); // Validasi dari IDUKA
            $table->enum('validasi_pembimbing', ['belum', 'sudah'])->default('belum'); // Validasi dari pembimbing
            $table->string('foto')->nullable(); // Foto kegiatan
            $table->timestamps();

            // Foreign key constraint (jika diperlukan)
            $table->foreign('nis')->references('nip')->on('users')->onDelete('cascade')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('jurnals');
    }
};
