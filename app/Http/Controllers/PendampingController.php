<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Iduka;
use App\Models\Kelas;
use App\Models\Kependik;
use App\Models\Konke;
use App\Models\User;
use App\Support\KelasJurusan;
use Illuminate\Http\Request;

class PendampingController extends Controller
{
    public function dataIdukaPendamping (){
        $iduka = Iduka::paginate(10);
        return view('iduka.dataiduka.dataiduka', compact('iduka'));
    }

    public function dataSiswaPendamping(Request $request){
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

    public function dataGuruPendamping(Request $request){
        $user = User::where('role', 'guru')->with('konke')->get();
        $guruQuery = Guru::with('konke')->orderBy('created_at', 'desc');
        if ($request->filled('search')) {
            $guruQuery->where('nama', 'like', '%' . trim($request->input('search')) . '%');
        }
        $guru = $guruQuery->paginate(10)->withQueryString();
        $konkes = Konke::all(); // Ambil data konsentrasi keahlian
        return view('hubin.dataguru.dataguru', compact('guru', 'konkes', 'user')); 
    }

    public function dataTKPembimbing(Request $request){
        $query = Kependik::query();
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . trim($request->input('search')) . '%');
        }
        $kependik = $query->paginate(10)->withQueryString();
        return view('tk.dataTk', compact('kependik'));
    }
}

