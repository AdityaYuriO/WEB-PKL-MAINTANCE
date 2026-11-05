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
        Schema::create('monitoring', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('iduka_id');
            $table->text('saran')->nullable();
            $table->text('perikiraan_siswa_diterima')->nullable();
            $table->string('foto')->nullable();
            $table->timestamps();

            // Foreign key constraint
            $table->foreign('iduka_id')->references('id')->on('idukas')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('monitoring');
    }
};