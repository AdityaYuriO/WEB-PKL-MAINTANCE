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
        Schema::create('pembimbing_idukas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iduka_id')->constrained('idukas')->onDelete('cascade');
            $table->string('name');
            $table->string('nip')->unique();
            $table->string('no_hp');
            $table->string('password');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pembimbing_idukas');
    }
};
