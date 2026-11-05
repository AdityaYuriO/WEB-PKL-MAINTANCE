<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('pindah_pkl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('siswa_id');
            $table->unsignedBigInteger('iduka_id');
            $table->unsignedBigInteger('konke_id');
            $table->enum('status', ['menunggu', 'diterima', 'ditolak'])->default('menunggu');
            $table->timestamps();

            $table->foreign('siswa_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduka_id')->references('id')->on('idukas')->onDelete('cascade');
            $table->foreign('konke_id')->references('id')->on('konkes')->onDelete('cascade'); // 🔥 ini juga
        });
    }


    public function down(): void
    {
        Schema::dropIfExists('pindah_pkl');
    }
};
