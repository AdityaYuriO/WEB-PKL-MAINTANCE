<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateKonkeIdRelationInPengajuanUsulansTable extends Migration
{
    public function up()
    {
        // 1. Pastikan foreign key constraint lama dihapus dulu jika ada
        Schema::table('pengajuan_usulans', function (Blueprint $table) {
            // Hapus foreign key constraint lama jika ada
            $table->dropForeign(['konke_id']);
        });

        // 2. Ubah kolom konke_id untuk memastikan tipe datanya sama dengan id di konkes
        Schema::table('pengajuan_usulans', function (Blueprint $table) {
            // Sesuaikan tipe data dengan id di tabel konkes
            $table->unsignedBigInteger('konke_id')->change();
            
            // Tambahkan foreign key constraint baru
            $table->foreign('konke_id')
                  ->references('id')
                  ->on('konkes')
                  ->onDelete('cascade')
                  ->onUpdate('cascade');
        });
    }

    public function down()
    {
        Schema::table('pengajuan_usulans', function (Blueprint $table) {
            // Hapus foreign key constraint baru
            $table->dropForeign(['konke_id']);
            
            // Kembalikan ke foreign key constraint lama jika diperlukan
            // (Anda perlu menyesuaikan ini dengan struktur sebelumnya)
            // $table->foreign('konke_id')
            //       ->references('konke_id')
            //       ->on('data_pribadi')
            //       ->onDelete('cascade')
            //       ->onUpdate('cascade');
        });
    }
}