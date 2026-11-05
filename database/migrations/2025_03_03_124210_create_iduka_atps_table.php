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
        Schema::create('iduka_atps', function (Blueprint $table) {
            $table->id();
            $table->foreignId('iduka_id')->constrained('idukas')->onDelete('cascade');
            $table->foreignId('cp_id')->constrained('cps')->onDelete('cascade');
            $table->foreignId('atp_id')->constrained('atps')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('iduka_atps');
    }
};
