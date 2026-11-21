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
        Schema::create('nilai_akhir', function (Blueprint $table) {
            $table->id();

            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();

            $table->text('catatan_guru_pembimbing')->nullable();
            $table->text('catatan_instruktur_iduka')->nullable();

            $table->float('nilai_instruktur_iduka');
            $table->float('nilai_guru_pembimbing');

            $table->float('nilai_akhir');

            $table->enum('predikat', [
                'sangat_baik',
                'baik',
                'cukup'
            ]);

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('nilai_akhir');
    }
};
