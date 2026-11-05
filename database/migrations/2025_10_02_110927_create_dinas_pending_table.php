<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDinasPendingTable extends Migration
{
    public function up()
    {
        Schema::create('dinas_pending', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('iduka_id')->constrained('idukas');
            $table->date('tanggal');
            $table->string('jenis_dinas'); // misal: 'perusahaan', 'sekolah', 'instansi_pemerintah'
            $table->text('keterangan');
            $table->string('status_konfirmasi')->default('pending'); // pending, disetujui, ditolak
            $table->text('alasan_penolakan')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('dinas_pending');
    }
}
