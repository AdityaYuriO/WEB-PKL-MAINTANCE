<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratPengantar extends Model
{
    use HasFactory;
    protected $table = 'surat_pengantars';
    protected $fillable = [
       'nomor', 'perihal', 'tempat', 'tanggalbuat', 'deskripsi', 
        'nama_instansi', 'nama_kepsek', 'tanggal_awal', 'tanggal_akhir',
    ];
    
}
