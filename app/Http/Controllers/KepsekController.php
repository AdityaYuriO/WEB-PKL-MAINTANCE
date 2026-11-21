<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Iduka;
use App\Models\Kelas;
use App\Models\Konke;
use App\Models\Kependik;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;

class KepsekController extends Controller
{
    public function dataIdukaKepsek (){
        $iduka = Iduka::orderBy('rekomendasi', 'desc')  // Urutkan berdasarkan rekomendasi (1 di atas)
        ->orderBy('created_at', 'desc') // Jika ada yang sama, urutkan berdasarkan tanggal dibuat
        ->paginate(10);
        
        return view('iduka.dataiduka.dataiduka', compact('iduka'));
    }

    public function dataSiswaKepsek(){
        $siswa = User::where('role', 'siswa')
                ->with(['kelas', 'konke'])
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at descending
                ->paginate(10);
        $konke = Konke::all();
        $kelas = Kelas::all();

        return view('siswa.datasiswa.datasiswa', compact('siswa', 'konke', 'kelas'));    
    }

    public function dataGuruKepsek(){
        $user = User::where('role', 'guru')->with('konke')->get();
        $guru = Guru::orderBy('created_at', 'desc')->paginate(10); // Urutkan berdasarkan created_at descending
        $konkes = Konke::all(); // Ambil data konsentrasi keahlian
        return view('hubin.dataguru.dataguru', compact('guru', 'konkes', 'user')); 
    }

    public function dataTKKepsek(){
        $kependik = Kependik::query()->paginate(10); // Gunakan query builder agar tetap LengthAwarePaginator
        return view('tk.dataTk', compact('kependik'));
    }

    public function show($id)
    {
        $iduka = Iduka::where('id', $id)->first();

        return view('iduka.dataiduka.detailDataIduka', compact('iduka'));
    }

     public function historiPengajuan()
    {
        // Ambil semua data pengajuan yang sudah diproses (status bukan 'proses')
        $historiPengajuan = PengajuanPkl::with('iduka', 'siswa')
            ->where('status', 'diterima')
            ->orderBy('updated_at', 'desc')
            ->get()
            ->groupBy('iduka_id');

        return view('kepsek.reviewPengajuanSiswa', compact('historiPengajuan'));
    }
 
}
