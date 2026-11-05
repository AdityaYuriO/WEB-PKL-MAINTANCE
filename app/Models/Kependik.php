<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kependik extends Model
{
    use HasFactory;

    protected $table = 'kependik';

    protected $fillable = [
        'nama',
        'nik',
        'nip_nuptk',
        'tempat_lahir',
        'tanggal_lahir',
        'jenis_kelamin',
        'alamat',
        'email',
        'no_hp',
        'password',
    ];

    protected $hidden = [
        'password',
    ];

    public function user()
    {
        return $this->hasOne(User::class, 'email', 'email');
    }
}
