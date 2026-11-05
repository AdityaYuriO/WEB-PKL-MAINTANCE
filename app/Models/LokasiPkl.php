<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LokasiPkl extends Model
{

    protected $table = 'lokasi_pkl';
    protected $fillable = [
        'nama_lokasi', 'alamat', 'pembimbing', 'telepon'
    ];

    public function siswa()
    {
        return $this->hasMany(User::class, 'iduka_id'); // pastikan kolom foreign key sesuai
    }
}
