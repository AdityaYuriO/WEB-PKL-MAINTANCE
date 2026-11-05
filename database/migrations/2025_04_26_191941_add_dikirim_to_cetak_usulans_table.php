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
        Schema::table('cetak_usulans', function (Blueprint $table) {
            $table->enum('dikirim', ['belum', 'sudah'])->default('belum');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cetak_usulans', function (Blueprint $table) {
            $table->dropColumn('dikirim');
        });
    }
};
