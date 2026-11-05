<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    use HasFactory;

    protected $fillable = ['kelas', 'konke_id', 'name_kelas'];

    public function konke()
    {
        return $this->belongsTo(Konke::class, 'konke_id'); // Perbaiki relasi
    }

    public function siswa()
    {
        return $this->hasMany(User::class, 'kelas_id');
    }

}
