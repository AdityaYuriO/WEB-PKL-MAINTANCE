<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Institusi extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'name',
        'alamat',
        'bidang',
        'no_hp',
        'name_pimpinan',
        'no_induk',
        'jabatan',
        'no_hp_pimpinan',
        'jabatan',
        'name_pembimbing',
        'no_induk_pembimbing',
        'no_hp_pembimbing',
        'sertifikat',
        'sop',
        'k3lh'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function iduka() 
    {
        return $this->hasMany(Iduka::class, 'user_id');
    }

    public function pembimbing() 
    {
        return $this->hasMany(Pembimbing::class, 'user_id');    
    }
}
