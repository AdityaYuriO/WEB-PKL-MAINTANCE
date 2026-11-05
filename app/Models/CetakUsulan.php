<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CetakUsulan extends Model
{
    use HasFactory;
    protected $table = 'cetak_usulans';
    protected $fillable = [
        'siswa_id',
        'iduka_id',
        'status',
        'dikirim',
    ];

    public function dataPribadi()
    {
        return $this->belongsTo(DataPribadi::class, 'siswa_id', 'user_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }



    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id', 'id');
    }

    public function pembimbingpkl()
{
    return $this->belongsTo(Pembimbing::class, 'user_id', 'user_id');
}
public function siswa()
{
    return $this->belongsTo(DataPribadi::class);
}

public function suratPengantar()
{
    return $this->belongsTo(SuratPengantar::class);
}



}
