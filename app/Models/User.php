<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'role',
        'nip',
        'iduka_id',
        'pembimbing_id',
        'kelas_id',
        'konke_id',
        'lokasi_pkl_id',
        'profile_photo',
        'status',
        'tahun_lulus',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
    ];

    // Relasi ke tabel gurus (One to One)
    public function guru()
    {
        return $this->hasOne(Guru::class, 'user_id');
    }

    // Relasi ke tabel konkes (Many to One)
    public function konke()
    {
        return $this->belongsTo(Konke::class, 'konke_id');
    }

    // Relasi ke Kelas (Many to One)
    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id');
    }

    // Relasi ke Iduka (Many to One) - PERBAIKAN
    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id', 'id');
    }

    // Relasi ke Kependik (One to One, by email)
    public function kependik()
    {
        return $this->hasOne(Kependik::class, 'email', 'email');
    }

    // Relasi ke Data Pribadi (One to One)
    public function dataPribadi()
    {
        return $this->hasOne(DataPribadi::class, 'user_id');
    }

    public function dataPersuratan()
    {
        return $this->hasOne(DataPribadiPersuratan::class, 'user_id', 'id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function pembimbingpkl()
    {
        return $this->hasOne(Pembimbing::class, 'user_id', 'id');
    }

    // Relasi ke Absensi (One to Many)
    public function absensi()
    {
        return $this->hasMany(Absensi::class, 'user_id', 'id');
    }

    // Relasi ke AbsensiPending (One to Many)
    public function absensiPending()
    {
        return $this->hasMany(AbsensiPending::class, 'user_id', 'id');
    }

    public function lokasiPkl()
    {
        return $this->belongsTo(LokasiPkl::class, 'lokasi_pkl_id');
    }

    public function idukaDiterima()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id', 'id');
    }

    // Relasi ke Guru sebagai pembimbing (Many to One) - PERBAIKAN
    public function pembimbing()
    {
        return $this->belongsTo(Guru::class, 'pembimbing_id', 'id');
    }

    public function monitoring()
    {
        return $this->hasMany(Monitoring::class, 'guru_id');
    }

    public function riwayatKelas()
    {
        return $this->hasMany(RiwayatKelas::class, 'user_id');
    }

    // Scope: hanya siswa/akun yang masih aktif (belum jadi alumni)
    public function scopeAktif($query)
    {
        return $query->where('status', 'aktif');
    }

    // Scope: hanya alumni
    public function scopeAlumni($query)
    {
        return $query->where('status', 'alumni');
    }
}
