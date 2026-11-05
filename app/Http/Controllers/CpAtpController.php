<?php

namespace App\Http\Controllers;

use App\Models\Cp;
use App\Models\Atp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class CpAtpController extends Controller
{
    // Menampilkan semua CP berdasarkan Kaprog yang sedang login
    public function cp()
    {
        $kaprog = Auth::user()->guru;
    
        if (!$kaprog || !$kaprog->konke_id) {
            abort(403, 'Akses ditolak: Anda tidak memiliki Konsentrasi Keahlian.');
        }
    
        // Ambil semua Konsentrasi Keahlian yang dimiliki Kaprog
        $konke = \App\Models\Konke::where('id', $kaprog->konke_id)->with('cp.atp')->get();
    
        return view('kaprog.cp.cp', compact('konke'));
    }
    

    // Menyimpan CP dan ATP
    public function store(Request $request)
    {
        $request->validate([
            'cp' => 'required|array',
            'cp.*' => 'required|string',
            'atp' => 'required|array',
            'atp.*' => 'required|array',
            'atp.*.*' => 'required|string',
        ]);
    
        $kaprog = Auth::user()->guru;
    
        if (!$kaprog || !$kaprog->konke_id) {
            return redirect()->back()->with('error', 'Akses ditolak: Anda tidak memiliki Konsentrasi Keahlian.');
        }
    
        try {
            foreach ($request->cp as $cpIndex => $cpText) {
                // Hitung nomor CP berdasarkan urutan dalam input
                $cpNumber = $cpIndex + 1;
    
                // Simpan CP
                $cp = Cp::create([
                    'konke_id' => $kaprog->konke_id,
                    'cp' => $cpText,
                ]);
    
                // Simpan ATP berdasarkan CP yang sesuai
                if (isset($request->atp[$cpNumber])) {
                    foreach ($request->atp[$cpNumber] as $atpIndex => $deskripsi_atp) {
                        $atpNumber = "{$cpNumber}." . ($atpIndex + 1);
                        $cp->atp()->create([
                            'atp' => $deskripsi_atp,
                            'kode_atp' => $atpNumber,
                        ]);
                    }
                }
            }
    
            return redirect()->route('cp.index')->with('success', 'CP dan ATP berhasil ditambahkan.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    

    // Update CP dan ATP
    public function update(Request $request, $id)
    {
        $request->validate([
            'cp' => 'required|array',
            'cp.*' => 'required|string',
            'atp' => 'required|array',
            'atp.*' => 'required|array',
            'atp.*.*' => 'required|string',
        ]);

        try {
            $kaprog = Auth::user()->guru;

            if (!$kaprog || !$kaprog->konke_id) {
                return redirect()->back()->with('error', 'Akses ditolak: Anda tidak memiliki Konsentrasi Keahlian.');
            }

            // Ambil semua CP berdasarkan konsentrasi keahlian yang diakses
            $cps = Cp::where('konke_id', $kaprog->konke_id)->get();

            // Hapus semua ATP lama terkait CP yang diedit
            foreach ($cps as $cp) {
                $cp->atp()->delete();
            }

            // Update atau tambah CP dan ATP yang baru
            foreach ($request->cp as $cpIndex => $cpText) {
                // Update CP
                $cp = $cps[$cpIndex] ?? new Cp();
                $cp->konke_id = $kaprog->konke_id;
                $cp->cp = $cpText;
                $cp->save();

                // Tambah ATP
                if (isset($request->atp[$cpIndex])) {
                    foreach ($request->atp[$cpIndex] as $atpIndex => $atpText) {
                        $cp->atp()->create([
                            'atp' => $atpText,
                            'kode_atp' => ($cpIndex + 1) . '.' . ($atpIndex + 1),
                        ]);
                    }
                }
            }

            return redirect()->route('cp.index')->with('success', 'CP dan ATP berhasil diperbarui.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    // Hapus CP dan ATP
    public function destroy($id)
    {
        try {
            $cp = Cp::findOrFail($id);
            $cp->delete();

            return redirect()->route('cp.index')->with('success', 'CP dan ATP berhasil dihapus.');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    public function getCpAtp($konke_id)
    {
        $cps = Cp::where('konke_id', $konke_id)
            ->with('atp') // Pastikan relasi 'atps' ada di model
            ->get();
    
        return response()->json($cps);
    }
}
