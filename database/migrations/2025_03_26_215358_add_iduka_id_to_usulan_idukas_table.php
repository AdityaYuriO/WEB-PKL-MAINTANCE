<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->foreignId('iduka_id')->nullable()->constrained()->onDelete('cascade');
        });
    }

    public function down() {
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->dropForeign(['iduka_id']);
            $table->dropColumn('iduka_id');
        });
    }
};
