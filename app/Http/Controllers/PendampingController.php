<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Iduka;
use App\Models\Kelas;
use App\Models\Kependik;
use App\Models\Konke;
use App\Models\User;
use Illuminate\Http\Request;

class PendampingController extends Controller
{
    public function dataIdukaPendamping (){
        $iduka = Iduka::paginate(10);
        return view('iduka.dataiduka.dataiduka', compact('iduka'));
    }

    public function dataSiswaPendamping(){
        $siswa = User::where('role', 'siswa')
                ->with(['kelas', 'konke'])
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at descending
                ->paginate(10);
        $konke = Konke::all();
        $kelas = Kelas::all();

        return view('siswa.datasiswa.datasiswa', compact('siswa', 'konke', 'kelas'));    
    }

    public function dataGuruPendamping(){
        $user = User::where('role', 'guru')->with('konke')->get();
        $guru = Guru::orderBy('created_at', 'desc')->paginate(10); // Urutkan berdasarkan created_at descending
        $konkes = Konke::all(); // Ambil data konsentrasi keahlian
        return view('hubin.dataguru.dataguru', compact('guru', 'konkes', 'user')); 
    }

    public function dataTKPembimbing(){
        $kependik = Kependik::query()->paginate(10); // Gunakan query builder agar tetap LengthAwarePaginator
        return view('tk.dataTk', compact('kependik'));
    }
}

