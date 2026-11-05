<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiPending extends Model
{
    use HasFactory;

    protected $table = 'absensi_pending';

    protected $fillable = [
        'user_id',
        'iduka_id',
        'pembimbing_id',
        'tanggal',
        'jenis',
        'jam',
        'latitude',
        'longitude',
        'status',
        'status_konfirmasi',
        'validasi_iduka',
        'validasi_pembimbing',
        'approved_iduka_at',
        'approved_pembimbing_at',
        'alasan_penolakan',
        'keterangan',
    ];

    protected $casts = [
        'tanggal' => 'date',
        'jam' => 'datetime',
        'approved_iduka_at' => 'datetime',
        'approved_pembimbing_at' => 'datetime',
    ];

    // Default values
    protected $attributes = [
        'validasi_iduka' => 'pending',
        'validasi_pembimbing' => 'pending',
        'status_konfirmasi' => 'pending',
        'status' => 'pending'
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke IDUKA
    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id');
    }

    // Relasi ke Guru (Pembimbing)
    public function pembimbing()
    {
        return $this->belongsTo(Guru::class, 'pembimbing_id');
    }
}
