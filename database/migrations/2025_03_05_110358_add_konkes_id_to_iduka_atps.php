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
        Schema::table('iduka_atps', function (Blueprint $table) {
            $table->unsignedBigInteger('konke_id')->after('iduka_id');
            $table->foreign('konke_id')->references('id')->on('konkes')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('iduka_atps', function (Blueprint $table) {
            $table->dropForeign(['konke_id']);
            $table->dropColumn('konke_id');
        });
    }
};
