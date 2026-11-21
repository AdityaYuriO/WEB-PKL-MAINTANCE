<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('data_pribadis', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('nip')->unique();
          
            $table->text('alamat_siswa');
            $table->string('no_hp')->unique();
            $table->string('jk');
            $table->string('agama');
            $table->string('tempat_lhr');
            $table->date('tgl_lahir');
            $table->foreignId('tahun_ajaran_id')->constrained()->onDelete('cascade');
            $table->string('email')->unique();

            $table->string('name_ayh');
            $table->string('nik_ayh')->unique();
            $table->string('tempat_lhr_ayh');
            $table->date('tgl_lahir_ayh');
            $table->string('pekerjaan_ayh')->nullable();
            $table->string('password');

            $table->string('name_ibu');
            $table->string('nik_ibu')->unique();
            $table->string('tempat_lhr_ibu');
            $table->date('tgl_lahir_ibu');
            $table->string('pekerjaan_ibu')->nullable();
     
            $table->string('email_ortu')->unique();
            $table->string('no_tlp')->nullable();
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('data_pribadis');
    }
};
