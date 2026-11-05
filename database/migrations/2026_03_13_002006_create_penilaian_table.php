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
        Schema::create('penilaian', function (Blueprint $table) {
            $table->id();

            $table->foreignId('users_id')->constrained('users')->cascadeOnDelete();

            $table->foreignId('id_tujuan_pembelajaran')
                ->constrained('tujuan_pembelajaran')
                ->cascadeOnDelete();

            $table->enum('ketercapaian_indikator', ['Ya', 'Tidak']);

            $table->enum('jenis_penilaian', [
                'guru_pembimbing',
                'instruktur_iduka'
            ]);

            $table->integer('nilai');
            $table->text('deskripsi')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('penilaian');
    }
};
