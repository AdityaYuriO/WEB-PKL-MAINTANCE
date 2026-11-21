<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('data_pribadis', function (Blueprint $table) {
            $table->enum('ttd_ortu', ['ayah', 'ibu'])->nullable()->after('pekerjaan_ibu');
        });
    }

    public function down()
    {
        Schema::table('data_pribadis', function (Blueprint $table) {
            $table->dropColumn('ttd_ortu');
        });
    }

};
