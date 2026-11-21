<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PengajuanPkl extends Model
{
    use HasFactory;
// App\Models\PengajuanPkl.php

protected $table = 'pengajuan_pkl';
protected $fillable = ['siswa_id', 'iduka_id', 'status'];


public function siswa()
{
    return $this->belongsTo(User::class, 'siswa_id');
}

    public function dataPribadi()
    {
        return $this->belongsTo(DataPribadi::class, 'siswa_id', 'user_id');
    }
    
 

    public function iduka()
    {
        return $this->belongsTo(Iduka::class, 'iduka_id', 'id');
    }
    
    public function pembimbingpkl()
{
    return $this->belongsTo(Pembimbing::class, 'user_id', 'user_id');
}

public function pengajuanUsulan()
{
    return $this->belongsTo(PengajuanUsulan::class, 'user_id', 'id');
}

public function historiDownload()
{
    return $this->hasOne(SuratBalasanHistory::class, 'pengajuan_pkl_id');
}

}
