<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IdukaAtp extends Model
{
    use HasFactory;
    protected $fillable = ['iduka_id', 'cp_id', 'atp_id','is_selected','konke_id'];

    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }

    public function cp()
    {
        return $this->belongsTo(Cp::class, 'cp_id');
    }

    public function atp()
    {
        return $this->belongsTo(Atp::class, 'atp_id');
    }
    public function konke()
    {
        return $this->belongsTo(Konke::class, 'konke_id');
    }
}
