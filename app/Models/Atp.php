<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atp extends Model
{
    use HasFactory;

    protected $table = 'atps'; // Pastikan nama tabel benar di database
    protected $fillable = ['cp_id', 'atp', 'kode_atp'];

    // Relasi ke CP (Many to One)
    public function cp()
    {
        return $this->belongsTo(Cp::class, 'cp_id', 'id');
    }
    public function idukas() {
        return $this->belongsToMany(Iduka::class, 'iduka_atp');
    }
    
}

