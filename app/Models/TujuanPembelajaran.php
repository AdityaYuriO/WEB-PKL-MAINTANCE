<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    protected $table = 'tujuan_pembelajaran';

    protected $fillable = [
        'id_konke',
        'tujuan_pembelajaran'
    ];

    public function konke()
    {
        return $this->belongsTo(Konke::class, 'id_konke');
    }

    public function indikatorPenilaians()
    {
        return $this->hasMany(IndikatorPenilaian::class, 'id_tujuan_pembelajaran');
    }

    public function penilaian()
    {
        return $this->hasMany(Penilaian::class, 'id_tujuan_pembelajaran');
    }
}