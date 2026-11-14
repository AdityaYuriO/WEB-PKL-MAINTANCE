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
        $columns = [];
        if (!Schema::hasColumn('absensi_pending', 'status_konfirmasi_guru')) {
            $columns[] = 'status_konfirmasi_guru';
        }
        if (!Schema::hasColumn('absensi_pending', 'catatan_guru')) {
            $columns[] = 'catatan_guru';
        }
        if (!Schema::hasColumn('absensi_pending', 'waktu_konfirmasi_guru')) {
            $columns[] = 'waktu_konfirmasi_guru';
        }

        if ($columns) {
            Schema::table('absensi_pending', function (Blueprint $table) use ($columns) {
                if (in_array('status_konfirmasi_guru', $columns, true)) {
                    $table->enum('status_konfirmasi_guru', ['pending', 'approved', 'rejected'])->default('pending');
                }
                if (in_array('catatan_guru', $columns, true)) {
                    $table->text('catatan_guru')->nullable();
                }
                if (in_array('waktu_konfirmasi_guru', $columns, true)) {
                    $table->timestamp('waktu_konfirmasi_guru')->nullable();
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('absensi_pending', function (Blueprint $table) {
            //
        });
    }
};
