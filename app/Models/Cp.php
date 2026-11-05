<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Cp extends Model
{
    use HasFactory;

    protected $table = 'cps'; // Pastikan sesuai nama tabel di database
    protected $fillable = ['konke_id', 'cp'];

    // Relasi ke ATP (One to Many)
    public function atp()
    {
        return $this->hasMany(Atp::class, 'cp_id', 'id');
    }

    // Relasi ke Konsentrasi Keahlian (Many to One)
    public function konke()
    {
        return $this->belongsTo(Konke::class, 'konke_id', 'id');
    }
}

