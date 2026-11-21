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
        Schema::create('usulan_idukas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->string('nama');
            $table->string('nama_pimpinan');
            $table->string('nip_pimpinan');
            $table->string('jabatan');
            $table->text('alamat');
            $table->string('kode_pos');
            $table->string('telepon');
            $table->string('email')->unique();
            $table->string('bidang_industri');
            $table->string('kerjasama');
            $table->enum('status', ['proses', 'diterima', 'ditolak'])->default('proses');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usulan_idukas');
    }
};
