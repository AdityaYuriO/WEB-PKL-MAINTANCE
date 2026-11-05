<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->enum('ketercapaian_indikator', ['Ya', 'Tidak'])->nullable()->change();
            $table->integer('nilai')->nullable()->change();
        });
    }

    public function down(): void
    {
        Schema::table('penilaian', function (Blueprint $table) {
            $table->enum('ketercapaian_indikator', ['Ya', 'Tidak'])->nullable(false)->change();
            $table->integer('nilai')->nullable(false)->change();
        });
    }
};