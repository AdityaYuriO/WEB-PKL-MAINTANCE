<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class tahunAjaran extends Model
{
    use HasFactory;
    protected $table = 'tahun_ajaran';
    protected $fillable = [
        'tahun_ajaran'
    ];

    public function DataPribadi(){
        return $this->hasMany(DataPribadi::class, 'tahun_ajaran_id');
    }
}

