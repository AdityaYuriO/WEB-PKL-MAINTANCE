<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IndikatorPenilaian extends Model
{
    protected $table = 'indikator_penilaian';

    protected $fillable = [
        'id_tujuan_pembelajaran',
        'indikator'
    ];

    public function tujuanPembelajaran()
    {
        return $this->belongsTo(TujuanPembelajaran::class, 'id_tujuan_pembelajaran');
    }
}
