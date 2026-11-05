<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPribadi extends Model
{
    use HasFactory;

    protected $table = 'data_pribadis';
    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'konke_id',
        'kelas_id',
        'alamat_siswa',
        'no_hp',
        'jk',
        'agama',
        'tempat_lhr',
        'tgl_lahir',
        'tahun_ajaran_id',
        'email',

        'name_ayh',
        'nik_ayh',
        'tempat_lhr_ayh',
        'tgl_lahir_ayh',
        'pekerjaan_ayh',

        'name_ibu',
        'nik_ibu',
        'tempat_lhr_ibu',
        'tgl_lahir_ibu',
        'pekerjaan_ibu',
        'ttd_ortu',
        'ttd_ortu_nama',

        'email_ortu',
        'no_tlp',

    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function siswa()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function konkes()
    {
        return $this->belongsTo(Konke::class, 'konke_id'); // Perbaiki relasi
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'kelas_id', 'id');
    }

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'tahun_ajaran_id');
    }
}
