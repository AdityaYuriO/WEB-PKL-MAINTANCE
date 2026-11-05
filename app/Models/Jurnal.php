<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Jurnal extends Model
{
    use HasFactory;

    protected $table = 'jurnals';

    protected $fillable = [
        'user_id',
        'iduka_id',
        'pembimbing_id',
        'nis',
        'tgl',
        'uraian',
        'jam_mulai',
        'jam_selesai',
        'foto',
        'status',
        'validasi_iduka',
        'validasi_pembimbing',
        'rejected_reason',
        'rejected_at',
        'approved_iduka_at',
        'approved_pembimbing_at',
        'is_pengetahuan_baru',
        'is_dalam_mapel'
    ];

    protected $casts = [
        'tgl' => 'date',
        'approved_iduka_at' => 'datetime',
        'approved_pembimbing_at' => 'datetime',
        'rejected_at' => 'datetime',
        'is_pengetahuan_baru' => 'boolean',
        'is_dalam_mapel' => 'boolean'
    ];

    // Relasi dengan User (siswa)
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    // Relasi dengan siswa melalui NIS
    public function siswa()
    {
        return $this->belongsTo(User::class, 'nis', 'nip');
    }

    // Relasi dengan IDUKA
    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }

    // Relasi dengan Pembimbing (Guru)
    public function pembimbing()
    {
        return $this->belongsTo(Guru::class);
    }

    // Method untuk mengecek apakah jurnal sudah disetujui (oleh siapa saja)
    public function isApproved()
    {
        return $this->validasi_iduka === 'sudah' || $this->validasi_pembimbing === 'sudah';
    }

    // Method untuk mendapatkan siapa yang menyetujui
    public function getApprovedByAttribute()
    {
        if ($this->validasi_iduka === 'sudah' && $this->validasi_pembimbing === 'sudah') {
            return 'Keduanya';
        } elseif ($this->validasi_iduka === 'sudah') {
            return 'IDUKA';
        } elseif ($this->validasi_pembimbing === 'sudah') {
            return 'Pembimbing';
        }
        return null;
    }

    // Method untuk mendapatkan status teks
    public function getStatusTextAttribute()
    {
        if ($this->status === 'rejected') {
            return 'Ditolak';
        }

        if ($this->isApproved()) {
            return 'Disetujui oleh ' . $this->approved_by;
        }

        return 'Menunggu Persetujuan';
    }

    // Method untuk mendapatkan status badge HTML
    public function getStatusBadgeAttribute()
    {
        if ($this->status === 'rejected') {
            return '<span class="badge bg-danger">❌ Ditolak</span>';
        }

        if ($this->isApproved()) {
            return '<span class="badge bg-success">✅ Disetujui</span>';
        }

        return '<span class="badge bg-warning">⏳ Menunggu Persetujuan</span>';
    }

    // Boot method untuk set default values
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($jurnal) {
            if (empty($jurnal->status)) {
                $jurnal->status = 'pending';
            }
            if (empty($jurnal->validasi_iduka)) {
                $jurnal->validasi_iduka = 'belum';
            }
            if (empty($jurnal->validasi_pembimbing)) {
                $jurnal->validasi_pembimbing = 'belum';
            }

            // Set default untuk kolom baru
            if (!isset($jurnal->is_pengetahuan_baru)) {
                $jurnal->is_pengetahuan_baru = false;
            }
            if (!isset($jurnal->is_dalam_mapel)) {
                $jurnal->is_dalam_mapel = false;
            }
        });

        // Event ketika jurnal diupdate
        static::updating(function ($jurnal) {
            // Update status berdasarkan persetujuan
            if ($jurnal->isApproved()) {
                $jurnal->status = 'approved';
            }
        });
    }
}
