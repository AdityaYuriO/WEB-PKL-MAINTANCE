<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('cps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('konke_id')->constrained('konkes')->onDelete('cascade'); // Relasi ke Konsentrasi Keahlian
            $table->text('cp');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('cps');
    }
};