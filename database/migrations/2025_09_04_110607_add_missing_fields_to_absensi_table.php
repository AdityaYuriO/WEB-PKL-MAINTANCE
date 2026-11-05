<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('absensi', function (Blueprint $table) {
            // Add missing check-in location fields
            $table->decimal('latitude_masuk', 10, 8)->nullable()->after('longitude');
            $table->decimal('longitude_masuk', 11, 8)->nullable()->after('latitude_masuk');
            $table->text('lokasi_masuk')->nullable()->after('longitude_masuk');
            
            // Add leave-specific fields
            $table->string('jenis_izin')->nullable()->after('status');
            $table->text('keterangan_izin')->nullable()->after('jenis_izin');
        });
    }

    public function down()
    {
        Schema::table('absensi', function (Blueprint $table) {
            $table->dropColumn([
                'latitude_masuk', 
                'longitude_masuk', 
                'lokasi_masuk',
                'jenis_izin', 
                'keterangan_izin'
            ]);
        });
    }
};