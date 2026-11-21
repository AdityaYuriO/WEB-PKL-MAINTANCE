<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up() {
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->dropUnique('usulan_idukas_email_unique'); // Hapus unique constraint
        });
    }

    public function down() {
        Schema::table('usulan_idukas', function (Blueprint $table) {
            $table->unique('email'); // Tambahkan kembali unique jika rollback
        });
    }
};

