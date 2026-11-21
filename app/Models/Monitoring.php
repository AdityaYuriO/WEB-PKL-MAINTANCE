<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Monitoring extends Model
{
    use HasFactory;

    protected $table = 'monitoring';

    protected $fillable = [
        'guru_id',
        'iduka_id',
        'saran',
        'perikiraan_siswa_diterima',
        'foto',
        'tgl'
    ];

    protected $casts = [
    'tgl' => 'date',
];

    /**
     * Relationship with Iduka
     */
    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id');
    }

    public function guru()
{
    return $this->belongsTo(Guru::class);
}
public function user()
{
    return $this->belongsTo(\App\Models\User::class);
}

   public function jurusan()
    {
        return $this->belongsTo(Konke::class);
    }



}
