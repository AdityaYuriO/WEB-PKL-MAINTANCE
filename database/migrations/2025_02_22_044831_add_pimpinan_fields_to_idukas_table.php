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
        Schema::table('idukas', function (Blueprint $table) {
            $table->string('nama_pimpinan')->nullable()->after('nama'); 
            $table->string('nip_pimpinan')->nullable()->after('nama_pimpinan');
            $table->string('jabatan')->nullable()->after('nip_pimpinan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->dropColumn(['nama_pimpinan', 'nip_pimpinan', 'jabatan']);
        });
    }
};
