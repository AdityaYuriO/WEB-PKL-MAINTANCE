<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('history_pkl', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('iduka_lama_id')->nullable();
            $table->unsignedBigInteger('iduka_baru_id')->nullable();
            $table->date('tgl_pindah')->nullable();
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('iduka_lama_id')->references('id')->on('idukas')->onDelete('set null');
            $table->foreign('iduka_baru_id')->references('id')->on('idukas')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('history_pkl');
    }
};
