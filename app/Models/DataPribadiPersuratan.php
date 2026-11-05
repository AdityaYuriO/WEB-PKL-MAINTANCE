<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DataPribadiPersuratan extends Model
{
    use HasFactory;

    protected $table = 'data_pribadi_persuratans'; // Nama tabel di database                      

    protected $fillable = [
        'user_id',
        'name',
        'nip',
        'alamat',
        'no_hp',
        'jk',
        'agama',
        'tempat_lahir',
        'tgl_lahir',
        'email',
        'password'
    ];

    public function user()
    {
        return $this->belongsTo(User::class,  'user_id', 'id');

    }

    public function dataPersuratan()
    {
        return $this->hasOne(DataPribadiPersuratan::class, 'user_id', 'id');

    }
}    
