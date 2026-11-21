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
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('iduka_id')->nullable()->after('id'); // Tambahkan kolom
            $table->foreign('iduka_id')->references('id')->on('idukas')->onDelete('set null'); // Relasi ke IDUKA
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropForeign(['iduka_id']);
            $table->dropColumn('iduka_id');
        });
    }
};
