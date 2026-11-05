<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryPkl extends Model
{
    use HasFactory;

    protected $table = 'history_pkl';

    protected $fillable = [
        'user_id',
        'iduka_lama_id',
        'iduka_baru_id',
        'tgl_pindah',
    ];

    // Relasi ke IDUKA lama
    public function idukaLama()
    {
        return $this->belongsTo(Idukas::class, 'iduka_lama_id');
    }

    public function idukaBaru()
    {
        return $this->belongsTo(Idukas::class, 'iduka_baru_id');
    }

    // Relasi ke user
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
