<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Enum status di database produksi saat ini cuma:
     *   menunggu, menunggu_surat, diterima_iduka, ditolak_iduka, siap_kirim
     *
     * Padahal kode (PindahPklController & PersuratanController) juga menulis
     * 'ditolak' dan 'ditolak_persuratan' — dua nilai ini hilang dari enum,
     * sehingga tombol "Tolak" di role kaprog & persuratan gagal (data truncated)
     * setiap kali dipakai. Migration ini mengembalikan kedua nilai tsb.
     */
    public function up(): void
    {
        DB::statement("
            ALTER TABLE pindah_pkl
            MODIFY COLUMN status ENUM(
                'menunggu',
                'menunggu_surat',
                'siap_kirim',
                'ditolak_persuratan',
                'ditolak',
                'diterima_iduka',
                'ditolak_iduka'
            ) NOT NULL DEFAULT 'menunggu'
        ");
    }

    public function down(): void
    {
        // Kembalikan ke enum yang sekarang aktif di produksi (tanpa ditolak/ditolak_persuratan)
        DB::statement("
            ALTER TABLE pindah_pkl
            MODIFY COLUMN status ENUM(
                'menunggu',
                'menunggu_surat',
                'diterima_iduka',
                'ditolak_iduka',
                'siap_kirim'
            ) DEFAULT 'menunggu'
        ");
    }
};
