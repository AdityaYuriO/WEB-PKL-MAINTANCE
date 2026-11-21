<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SuratBalasanHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'pengajuan_pkl_id',
        'downloaded_by',
          'status_surat',
    ];

    public function cetakUsulan()
    {
        return $this->belongsTo(CetakUsulan::class);
    }

    public function pengajuanPkl()
    {
        return $this->belongsTo(PengajuanPkl::class, 'pengajuan_pkl_id');
    }
    
}