<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AbsensiGuru extends Model
{
    use HasFactory;

    protected $table = 'absensi_gurus'; // Pastikan nama tabel benar

    protected $fillable = [
        'user_id',
        'latitude',
        'longitude',
        'jarak',
        'status',
        'jam_masuk',
        'jam_pulang',
        'keterangan',
        'alasan',
        'bukti_file',
        'tanggal'
    ];

    protected $casts = [
        'tanggal' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Helper method untuk cek apakah sudah pulang
    public function sudahPulang()
    {
        return !is_null($this->jam_pulang);
    }

    // Helper method untuk cek apakah hadir
    public function isHadir()
    {
        return $this->status === 'hadir';
    }
}
