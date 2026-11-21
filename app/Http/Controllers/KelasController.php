<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Konke;
use Illuminate\Http\Request;

class KelasController extends Controller
{
    public function index(Request $request)
    {
        $query = Kelas::query();
    
        // Filter berdasarkan kelas (X, XI, XII)
        if ($request->filled('kelas')) {
            $query->where('kelas', $request->kelas);
        }
    
        // Filter berdasarkan konsentrasi keahlian
        if ($request->filled('konsentrasi')) {
            $query->whereHas('konke', function ($q) use ($request) {
                $q->where('name_konke', 'like', '%' . $request->konsentrasi . '%');
            });
        }
    
        $kelas = $query->orderBy('created_at', 'desc')->paginate(10);
        $konke = Konke::all();
    
        return view('data.kelas.dataKelas', compact('kelas', 'konke'));
    }
    

    public function store(Request $request)
    {
        $request->validate([
            'kelas' => 'required|in:X,XI,XII',
            'konke_id' => 'required|exists:konkes,id',
            'name_kelas' => 'required|string|max:255',
        ]);
    
        // Cek apakah kombinasi kelas dan name_kelas sudah ada
        $exists = Kelas::where('kelas', $request->kelas)
            ->where('name_kelas', $request->name_kelas)
            ->exists();
    
        if ($exists) {
            return redirect()->back()->with('error', 'Gagal menambahkan! kelas sudah ada.');
        }
    
        Kelas::create([
            'kelas' => $request->kelas,
            'konke_id' => $request->konke_id,
            'name_kelas' => $request->name_kelas,
        ]);
    
        return redirect()->back()->with('success', 'Data kelas berhasil ditambahkan.');
    }
    

    public function showSiswa($id)
    {
        $kelas = Kelas::findOrFail($id);
        $kelas = Kelas::all();
        $konke = Konke::all();
        $siswa = User::all();
        $siswa = User::where('kelas_id', $id)->get(); // Sesuaikan dengan struktur tabelmu
        return view('siswa.datasiswa.showSiswa', compact('kelas', 'siswa', 'konke'));
    }


    public function update(Request $request, Kelas $kelas)
    {
        $request->validate([
            'kelas' => 'required|in:X,XI,XII',
            'konke_id' => 'required|exists:konkes,id',
            'name_kelas' => 'required|string|max:255',
        ]);


        $kelas->update([
            'kelas' => $request->kelas,
            'konke_id' => $request->konke_id,
            'name_kelas' => $request->name_kelas
        ]);

        return redirect()->back()->with('success', 'Data kelas berhasil diperbarui.');
    }

    public function destroy(Kelas $kelas)
    {
        $kelas->delete();

        return redirect()->back()->with('success', 'Data kelas berhasil dihapus.');
    }
}
