<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PindahPkl extends Model
{
    protected $table = 'pindah_pkl';
    protected $fillable = ['siswa_id', 'iduka_id', 'iduka_baru_id', 'konke_id', 'status'];

    // Relasi ke tempat PKL lama
    public function idukaLama()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id');
    }

    public function idukaBaru()
    {
        return $this->belongsTo(Iduka::class, 'iduka_baru_id');
    }

    // Relasi ke history pkl untuk mendapatkan tempat PKL baru
    public function historyPkl()
    {
        return $this->hasOne(HistoryPkl::class, 'user_id', 'siswa_id')
                    ->latest(); // Ambil yang terbaru
    }

    // Accessor untuk mendapatkan nama iduka baru
    public function getIdukaBaruNamaAttribute()
    {
        if ($this->historyPkl && $this->historyPkl->iduka_baru_id) {
            $idukaBaru = Iduka::find($this->historyPkl->iduka_baru_id);
            return $idukaBaru->nama ?? 'Tidak Diketahui';
        }
        return 'Tidak Diketahui';
    }

    // Relasi ke siswa
    public function siswa()
    {
        return $this->belongsTo(User::class, 'siswa_id');
    }
}