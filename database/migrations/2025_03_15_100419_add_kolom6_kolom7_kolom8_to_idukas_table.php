<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->enum('kolom6', ['Ya', 'Tidak'])->nullable();
            $table->enum('kolom7', ['Ya', 'Tidak'])->nullable();
            $table->enum('kolom8', ['Ya', 'Tidak'])->nullable();
        });
    }
    
    public function down()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->dropColumn(['kolom6', 'kolom7', 'kolom8']);
        });
    }
    
};
