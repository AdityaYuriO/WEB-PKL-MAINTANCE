<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddPengetahuanBaruAndDalamMapelToJurnalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            // Tambahkan kolom is_pengetahuan_baru
            $table->boolean('is_pengetahuan_baru')
                  ->default(false)
                  ->after('rejected_reason')
                  ->comment('1: Pengetahuan baru, 0: Bukan pengetahuan baru');

            // Tambahkan kolom is_dalam_mapel
            $table->boolean('is_dalam_mapel')
                  ->default(false)
                  ->after('is_pengetahuan_baru')
                  ->comment('1: Ada dalam mapel sekolah, 0: Tidak ada dalam mapel');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('jurnals', function (Blueprint $table) {
            $table->dropColumn(['is_pengetahuan_baru', 'is_dalam_mapel']);
        });
    }
}
