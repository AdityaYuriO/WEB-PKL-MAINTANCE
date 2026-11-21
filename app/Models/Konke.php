<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Konke extends Model
{
    use HasFactory;
    protected $table = 'konkes';

    protected $fillable = ['name_konke', 'proker_id'];

    public function proker()
    {
        return $this->belongsTo(Proker::class);
    }
    public function konke()
    {
        return $this->belongsTo(Konke::class);
    }

    public function gurus()
    {
        return $this->hasMany(Guru::class, 'konke_id', 'id');
    }
    public function dataPribadis()
    {
        return $this->hasMany(DataPribadi::class, 'konke_id');
    }
    public function cp()
    {
        return $this->hasMany(Cp::class, 'konke_id');
    }
    public function idukaAtps()
    {
        return $this->hasMany(IdukaAtp::class, 'konke_id');
    }

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'konke_id');
    }
}
