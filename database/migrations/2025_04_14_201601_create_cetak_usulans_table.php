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
        Schema::create('cetak_usulans', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('iduka_id');
            $table->enum('status', ['proses', 'sudah'])->default('proses');
            $table->timestamps();

            // Optional: relasi ke tabel siswa & iduka
            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduka_id')->references('id')->on('idukas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('cetak_usulans');
    }
};
