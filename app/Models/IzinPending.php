<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class IzinPending extends Model
{
    protected $table = 'izin_pending';
    
    protected $fillable = [
        'user_id', 
        'iduka_id', 
        'tanggal', 
        'jenis_izin', 
        'keterangan', 
        'status_konfirmasi',
        'status_konfirmasi_guru',
        'catatan_guru',
        'waktu_konfirmasi_guru',
        'alasan_penolakan'
    ];

    protected $casts = [
        'waktu_konfirmasi_guru' => 'datetime',
    ];
    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
    
    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }
}