<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Penilaian extends Model
{
    protected $table = 'penilaian';

    protected $fillable = [
        'users_id',
        'id_tujuan_pembelajaran',
        'ketercapaian_indikator',
        'jenis_penilaian',
        'nilai',
        'deskripsi',
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'users_id');
    }

    public function tujuanPembelajaran()
    {
        return $this->belongsTo(TujuanPembelajaran::class, 'id_tujuan_pembelajaran');
    }
}