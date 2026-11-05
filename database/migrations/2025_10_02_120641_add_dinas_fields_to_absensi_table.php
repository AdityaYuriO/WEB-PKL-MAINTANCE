<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDinasFieldsToAbsensiTable extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->string('jenis_dinas')->nullable()->after('keterangan_izin');
            $table->text('keterangan_dinas')->nullable()->after('jenis_dinas');
            $table->string('status_dinas')->nullable()->after('keterangan_dinas');
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn(['jenis_dinas', 'keterangan_dinas', 'status_dinas']);
        });
    }
}
