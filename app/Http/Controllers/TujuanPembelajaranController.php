<?php

namespace App\Http\Controllers;

use App\Models\TujuanPembelajaran;
use App\Models\Konke;
use Illuminate\Http\Request;

class TujuanPembelajaranController extends Controller
{
    public function index()
    {
        $tujuan = TujuanPembelajaran::with('konke')->get();
        $konkes = Konke::all();

        return view('penilaian.tujuan_pembelajaran.index', compact('tujuan','konkes'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'id_konke' => 'required',
            'tujuan_pembelajaran' => 'required'
        ]);

        TujuanPembelajaran::create($request->all());

        return redirect()->back()->with('success','Data berhasil ditambahkan');
    }

    public function update(Request $request, $id)
    {
        $tujuan = TujuanPembelajaran::findOrFail($id);

        $tujuan->update($request->all());

        return redirect()->back()->with('success','Data berhasil diupdate');
    }

    public function destroy($id)
    {
        TujuanPembelajaran::destroy($id);

        return redirect()->back()->with('success','Data berhasil dihapus');
    }
}
