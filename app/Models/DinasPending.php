<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DinasPending extends Model
{
    use HasFactory;

    protected $table = 'dinas_pending';

    protected $fillable = [
        'user_id',
        'iduka_id',
        'tanggal',
        'jenis_dinas',
        'keterangan',
        'status_konfirmasi',
        'alasan_penolakan'
    ];

    protected $casts = [
        'tanggal' => 'datetime:d M Y',
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
