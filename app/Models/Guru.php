<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama',
        'nik',
        'nip',
        'email',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'no_hp',
        'password',
        'konke_id',
        'user_id',
    ];

    protected $hidden = [
        'password',
    ];

    // Relasi ke User
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi ke Konke
    public function konke()
    {
        return $this->belongsTo(Konke::class, 'konke_id', 'id');
    }

    // Relasi ke Siswa (sebagai pembimbing)
    public function siswas()
    {
        return $this->hasMany(User::class, 'pembimbing_id');
    }

    // Relasi ke AbsensiPending
    public function absensiPending()
    {
        return $this->hasMany(AbsensiPending::class, 'pembimbing_id');
    }

    // Relasi ke Monitoring
    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'guru_id');
    }
}
