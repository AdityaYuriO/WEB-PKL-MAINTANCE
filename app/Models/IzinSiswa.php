<?php

// ============= MODEL: IzinSiswa =============
// File: app/Models/IzinSiswa.php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class IzinSiswa extends Model
{
    use HasFactory;

    protected $table = 'izin_siswa';

    protected $fillable = [
        'user_id',
        'iduka_id',
        'tanggal_izin',
        'jenis_izin',
        'alasan',
        'file_pendukung',
        'status',
        'catatan_iduka',
        'dikonfirmasi_oleh',
        'dikonfirmasi_pada',
    ];

    protected $casts = [
        'tanggal_izin' => 'date',
        'dikonfirmasi_pada' => 'datetime',
    ];

    // Relasi ke User (Siswa)
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke IDUKA
    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }

    // Relasi ke User yang mengkonfirmasi (IDUKA)
    public function dikonfirmasiOleh()
    {
        return $this->belongsTo(User::class, 'dikonfirmasi_oleh');
    }

    // Scope untuk izin pending
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    // Scope untuk izin hari ini
    public function scopeHariIni($query)
    {
        return $query->whereDate('tanggal_izin', Carbon::today());
    }

    // Accessor untuk status label
    public function getStatusLabelAttribute()
    {
        return match($this->status) {
            'pending' => 'Menunggu',
            'disetujui' => 'Disetujui',
            'ditolak' => 'Ditolak',
            default => 'Tidak Diketahui'
        };
    }

    // Accessor untuk jenis izin label
    public function getJenisIzinLabelAttribute()
    {
        return match($this->jenis_izin) {
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'cuti' => 'Cuti',
            'lainnya' => 'Lainnya',
            default => 'Tidak Diketahui'
        };
    }
}