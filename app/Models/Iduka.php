<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Iduka extends Model
{
    use HasFactory;

    protected $table = "idukas";

    protected $fillable = [
        'user_id',
        'nama',
        'nama_pimpinan',
        'nip_pimpinan',
        'no_hp_pimpinan',
        'jabatan',
        'alamat',
        'kode_pos',
        'telepon',
        'email',
        'password',
        'bidang_industri',
        'kerjasama',
        'kuota_pkl',
        'kerjasama_lainnya',
        'rekomendasi',
        'hidden',
        'kolom6',
        'kolom7',
        'kolom8',
        'tanggal_awal',
        'tanggal_akhir',
        'foto',
        'mulai_kerjasama',
        'akhir_kerjasama',
        'latitude',
        'longitude',
        'radius',
        'jam_masuk',
        'jam_pulang',
        'is_pusat',
        'id_pusat'
    ];

    protected $casts = [
        'jam_masuk' => 'datetime:H:i',
        'jam_pulang' => 'datetime:H:i',
        'latitude' => 'float',
        'longitude' => 'float',
        'radius' => 'float',
        'is_pusat' => 'boolean',
        'tanggal_awal' => 'date',
        'tanggal_akhir' => 'date',
        'mulai_kerjasama' => 'date',
        'akhir_kerjasama' => 'date',
    ];

    // Relasi ke diri sendiri (untuk pusat-cabang)
    public function pusat()
    {
        return $this->belongsTo(Iduka::class, 'id_pusat');
    }

    public function cabangs()
    {
        return $this->hasMany(Iduka::class, 'id_pusat');
    }

    // Relasi ke user yang memiliki IDUKA ini
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function siswa()
    {
        return $this->hasMany(User::class, 'iduka_id', 'id')
            ->where('role', 'siswa');
    }

    // Relasi ke semua users yang terkait dengan IDUKA ini
    public function users()
    {
        return $this->hasMany(User::class, 'iduka_diterima_id', 'id');
    }

    // Relasi ke pembimbing di IDUKA ini
    public function pembimbing()
    {
        return $this->hasMany(Pembimbing::class, 'iduka_id', 'id');
    }

    // Relasi ke absensi di IDUKA ini
    public function absensis()
    {
        return $this->hasMany(Absensi::class, 'iduka_id', 'id');
    }

    // Relasi ke absensi pending di IDUKA ini
    public function absensisPending()
    {
        return $this->hasMany(AbsensiPending::class, 'iduka_id', 'id');
    }

    // Relasi ke usulan IDUKA
    public function usulan()
    {
        return $this->hasOne(UsulanIduka::class, 'iduka_id', 'id');
    }

    // Relasi ke ATP IDUKA
    public function atps()
    {
        return $this->hasMany(IdukaAtp::class, 'iduka_id', 'id');
    }

    // Relasi ke Kompetensi Keahlian (Konkes)
    public function konkes()
    {
        return $this->belongsToMany(Konke::class, 'iduka_atps', 'iduka_id', 'konke_id');
    }

    // Relasi ke CP (Capaian Pembelajaran)
    public function cp()
    {
        return $this->belongsTo(Cp::class);
    }

    public function atp()
    {
        return $this->belongsTo(Atp::class);
    }

    // Relasi ke pengajuan usulan
    public function pengajuanUsulans()
    {
        return $this->hasMany(PengajuanUsulan::class, 'iduka_id', 'id');
    }

    // Relasi ke lokasi IDUKA
    public function lokasi()
    {
        return $this->hasMany(LokasiIduka::class, 'iduka_id', 'id');
    }



}
