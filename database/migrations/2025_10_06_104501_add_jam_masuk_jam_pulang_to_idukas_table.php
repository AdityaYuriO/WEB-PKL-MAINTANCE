<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->time('jam_masuk')->nullable()->after('radius');
            $table->time('jam_pulang')->nullable()->after('jam_masuk');
        });
    }

    public function down()
    {
        Schema::table('idukas', function (Blueprint $table) {
            $table->dropColumn(['jam_masuk', 'jam_pulang']);
        });
    }
};
