<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LokasiIduka extends Model
{
    use HasFactory;

    protected $fillable = [
        'iduka_id',
        'nama_lokasi',
        'latitude',
        'longitude',
        'radius',
    ];

    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }
}
