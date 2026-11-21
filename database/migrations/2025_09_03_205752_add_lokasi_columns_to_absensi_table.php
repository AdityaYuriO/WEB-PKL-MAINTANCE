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
        Schema::table('absensi', function (Blueprint $table) {
            // Titik koordinat saat absen masuk
            $table->decimal('latitude', 10, 7)->nullable()->after('keterangan');
            $table->decimal('longitude', 10, 7)->nullable()->after('latitude');

            // Titik koordinat saat absen pulang
            $table->decimal('latitude_pulang', 10, 7)->nullable()->after('longitude');
            $table->decimal('longitude_pulang', 10, 7)->nullable()->after('latitude_pulang');

            // Nama lokasi (optional, misalnya hasil reverse geocoding)
            $table->string('lokasi')->nullable()->after('longitude_pulang');
            $table->string('lokasi_pulang')->nullable()->after('lokasi');
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropForeign(['lokasi_iduka_id']);
            $table->dropColumn([
                'lokasi_iduka_id',
                'latitude',
                'longitude',
                'latitude_pulang',
                'longitude_pulang',
                'lokasi',
                'lokasi_pulang'
            ]);
        });
    }

};
