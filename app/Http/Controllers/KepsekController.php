<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\User;
use App\Models\Iduka;
use App\Models\Kelas;
use App\Models\Konke;
use App\Models\Kependik;
use App\Models\PengajuanPkl;
use App\Support\KelasJurusan;
use Illuminate\Http\Request;

class KepsekController extends Controller
{
    public function dataIdukaKepsek (){
        $iduka = Iduka::orderBy('rekomendasi', 'desc')  // Urutkan berdasarkan rekomendasi (1 di atas)
        ->orderBy('created_at', 'desc') // Jika ada yang sama, urutkan berdasarkan tanggal dibuat
        ->paginate(10);
        
        return view('iduka.dataiduka.dataiduka', compact('iduka'));
    }

    public function dataSiswaKepsek(Request $request){
        $query = User::where('role', 'siswa')
            ->with(['kelas', 'konke'])
            ->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . trim($request->input('search')) . '%');
        }
        $siswa = $query->paginate(10)->withQueryString();
        $konke = Konke::all();
        $kelas = Kelas::all();
        $kelasByKonkeId = KelasJurusan::optionsByKonkeId($konke);

        return view('siswa.datasiswa.datasiswa', compact('siswa', 'konke', 'kelas', 'kelasByKonkeId'));
    }

    public function dataGuruKepsek(){
        $user = User::where('role', 'guru')->with('konke')->get();
        $guru = Guru::orderBy('created_at', 'desc')->paginate(10); // Urutkan berdasarkan created_at descending
        $konkes = Konke::all(); // Ambil data konsentrasi keahlian
        return view('hubin.dataguru.dataguru', compact('guru', 'konkes', 'user')); 
    }

    public function dataTKKepsek(Request $request){
        $query = Kependik::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . trim($request->input('search')) . '%');
        }
        $kependik = $query->paginate(10)->withQueryString();
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
