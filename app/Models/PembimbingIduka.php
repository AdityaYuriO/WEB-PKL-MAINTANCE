<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class PembimbingIduka extends Authenticatable
{
    use HasFactory;

    protected $fillable = ['iduka_id', 'name', 'nip', 'no_hp', 'password'];

    protected $hidden = ['password'];

    public function iduka()
    {
        return $this->belongsTo(Iduka::class);
    }
}
