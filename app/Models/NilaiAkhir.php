<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class NilaiAkhir extends Model
{
    protected $table = 'nilai_akhir';

    protected $fillable = [
        'user_id',
        'catatan_guru_pembimbing',
        'catatan_instruktur_iduka',
        'nilai_instruktur_iduka',
        'nilai_guru_pembimbing',
        'nilai_akhir',
        'predikat'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
