<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pembimbing extends Model
{
    protected $table = 'pembimbingpkl';
    protected $fillable = ['user_id', 'name', 'nip', 'no_hp', 'password'];

    public function user()
{
    return $this->belongsTo(User::class, 'user_id', 'id');
}


    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'user_id', 'id');
    }
}
