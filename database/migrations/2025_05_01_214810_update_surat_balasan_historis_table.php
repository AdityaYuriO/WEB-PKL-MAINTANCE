<?php 

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('surat_balasan_histories', function (Blueprint $table) {
            // Hapus foreign key lama jika ada
            if (Schema::hasColumn('surat_balasan_histories', 'cetak_usulan_id')) {
                // Cek dan drop foreign key secara aman (hindari error jika sudah dihapus sebelumnya)
                DB::statement('ALTER TABLE surat_balasan_histories DROP FOREIGN KEY surat_balasan_histories_cetak_usulan_id_foreign');
                $table->dropColumn('cetak_usulan_id');
            }

            // Tambahkan kolom baru jika belum ada
            if (!Schema::hasColumn('surat_balasan_histories', 'pengajuan_pkl_id')) {
                $table->foreignId('pengajuan_pkl_id')->after('id')->constrained('pengajuan_pkls')->onDelete('cascade');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('surat_balasan_histories', function (Blueprint $table) {
            // Hapus kolom pengajuan_pkl_id jika ada
            if (Schema::hasColumn('surat_balasan_histories', 'pengajuan_pkl_id')) {
                DB::statement('ALTER TABLE surat_balasan_histories DROP FOREIGN KEY surat_balasan_histories_pengajuan_pkl_id_foreign');
                $table->dropColumn('pengajuan_pkl_id');
            }

            // Tambahkan kembali kolom cetak_usulan_id jika belum ada
            if (!Schema::hasColumn('surat_balasan_histories', 'cetak_usulan_id')) {
                $table->foreignId('cetak_usulan_id')->constrained()->onDelete('cascade');
            }
        });
    }
};
