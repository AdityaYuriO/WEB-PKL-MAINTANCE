<?php

namespace App\Http\Controllers;

use App\Models\IndikatorPenilaian;
use App\Models\TujuanPembelajaran;
use Illuminate\Http\Request;

class IndikatorPenilaianController extends Controller
{
    public function index()
    {
        $indikator = IndikatorPenilaian::with('tujuanPembelajaran')->get();
        $tujuan = TujuanPembelajaran::all();

        return view('penilaian.indikator.index', compact('indikator','tujuan'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_tujuan_pembelajaran' => 'required',
            'indikator' => 'required'
        ]);

        IndikatorPenilaian::create([
            'id_tujuan_pembelajaran' => $request->id_tujuan_pembelajaran,
            'indikator' => $request->indikator
        ]);

        return redirect()->route('indikator.index')
        ->with('success','Indikator berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_tujuan_pembelajaran' => 'required',
            'indikator' => 'required'
        ]);

        $indikator = IndikatorPenilaian::findOrFail($id);

        $indikator->update([
            'id_tujuan_pembelajaran' => $request->id_tujuan_pembelajaran,
            'indikator' => $request->indikator
        ]);

        return redirect()->route('indikator.index')
        ->with('success','Indikator berhasil diupdate');
    }

    public function destroy($id)
    {
        $indikator = IndikatorPenilaian::findOrFail($id);
        $indikator->delete();

        return redirect()->route('indikator.index')
        ->with('success','Indikator berhasil dihapus');
    }
}