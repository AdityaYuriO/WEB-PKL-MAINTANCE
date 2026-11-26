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
        'iduka_id',
        'pembimbing_id',
        'kelas_id',
        'konke_id',
        'tahun_ajaran',
        'lokasi_pkl_id',
        'profile_photo'
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

    /**
     * Memeriksa apakah data pribadi siswa sudah terisi lengkap di database.
     */
    public function isDataPribadiLengkap(): bool
    {
        $data = $this->dataPribadi;
        if (!$data) {
            return false;
        }

        // Field wajib berdasarkan validasi DataPribadiController::store()
        $requiredFields = [
            'alamat_siswa',
            'no_hp',
            'jk',
            'agama',
            'tempat_lhr',
            'name_ayh',
            'nik_ayh',
            'tempat_lhr_ayh',
            'name_ibu',
            'nik_ibu',
            'tempat_lhr_ibu',
            'no_tlp',
        ];

        foreach ($requiredFields as $field) {
            $val = $data->{$field};
            if (is_null($val) || trim((string)$val) === '') {
                return false;
            }
        }

        return true;
    }

    /**
     * Mendapatkan daftar label field data pribadi yang belum lengkap.
     */
    public function getMissingDataPribadiFields(): array
    {
        $data = $this->dataPribadi;
        if (!$data) {
            return ['Data Diri & Orang Tua'];
        }

        $fieldLabels = [
            'alamat_siswa' => 'Alamat',
            'no_hp' => 'No. HP Siswa',
            'jk' => 'Jenis Kelamin',
            'agama' => 'Agama',
            'tempat_lhr' => 'Tempat Lahir Siswa',
            'name_ayh' => 'Nama Ayah',
            'nik_ayh' => 'NIK Ayah',
            'tempat_lhr_ayh' => 'Tempat Lahir Ayah',
            'name_ibu' => 'Nama Ibu',
            'nik_ibu' => 'NIK Ibu',
            'tempat_lhr_ibu' => 'Tempat Lahir Ibu',
            'no_tlp' => 'No. Telepon Orang Tua',
        ];

        $missing = [];
        foreach ($fieldLabels as $field => $label) {
            $val = $data->{$field};
            if (is_null($val) || trim((string)$val) === '') {
                $missing[] = $label;
            }
        }

        return $missing;
    }
}
