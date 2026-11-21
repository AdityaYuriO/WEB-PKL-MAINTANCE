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
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->unsignedBigInteger('konke_id')->after('user_id')->nullable();

            // Jika ada relasi ke tabel konkes, tambahkan foreign key
            $table->foreign('konke_id')->references('id')->on('konkes')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->dropForeign(['konke_id']);
            $table->dropColumn('konke_id');
        });
    }
};
