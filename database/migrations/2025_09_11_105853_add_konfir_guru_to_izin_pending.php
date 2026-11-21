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
        Schema::table('izin_pending', function (Blueprint $table) {
            $table->enum('status_konfirmasi_guru', ['pending', 'approved', 'rejected'])->default('pending');
            $table->text('catatan_guru')->nullable();
            $table->timestamp('waktu_konfirmasi_guru')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('izin_pending', function (Blueprint $table) {
            //
        });
    }
};
