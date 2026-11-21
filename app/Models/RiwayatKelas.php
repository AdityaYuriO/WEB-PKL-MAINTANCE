<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RiwayatKelas extends Model
{
    use HasFactory;

    protected $table = 'riwayat_kelas';

    protected $fillable = [
        'user_id',
        'kelas_lama_id',
        'kelas_baru_id',
        'tahun_ajaran_id',
        'jenis',
        'dilakukan_oleh',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function kelasLama()
    {
        return $this->belongsTo(Kelas::class, 'kelas_lama_id');
    }

    public function kelasBaru()
    {
        return $this->belongsTo(Kelas::class, 'kelas_baru_id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(tahunAjaran::class, 'tahun_ajaran_id');
    }

    public function pelaku()
    {
        return $this->belongsTo(User::class, 'dilakukan_oleh');
    }
}
