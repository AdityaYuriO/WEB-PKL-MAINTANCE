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
            $table->boolean('hidden')->default(false)->after('rekomendasi');
          
        });
    }
    
    public function down()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->dropColumn('hidden');
        });
    }
};
