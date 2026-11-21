<?php

// app/Http/Controllers/LokasiController.php
namespace App\Http\Controllers;

use App\Models\Iduka;
use Illuminate\Http\Request;

class LokasiController extends Controller
{
    // Menampilkan semua lokasi (pusat dan cabang)
    public function index()
    {
        $lokasiPusat = Iduka::where('is_pusat', 1)->get();
        $lokasiCabang = Iduka::where('is_pusat', 0)->with('pusat')->get();

        return view('lokasi.index', compact('lokasiPusat', 'lokasiCabang'));
    }

    // Menambah lokasi cabang baru
    public function storeCabang(Request $request)
    {
        $validated = $request->validate([
            'id_pusat' => 'required|exists:idukas,id',
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        $pusat = Iduka::find($validated['id_pusat']);

        $cabang = new Iduka();
        $cabang->fill($validated);
        $cabang->is_pusat = 0; // Tandai sebagai cabang
        $cabang->save();

        return redirect()->route('lokasi.index')->with('success', 'Lokasi cabang berhasil ditambahkan');
    }

    // Update lokasi (baik pusat maupun cabang)
    public function update(Request $request, $id)
    {
        $lokasi = Iduka::findOrFail($id);

        $validated = $request->validate([
            'nama' => 'required|string|max:255',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'radius' => 'required|integer|min:1',
            'jam_masuk' => 'required|date_format:H:i',
            'jam_pulang' => 'required|date_format:H:i|after:jam_masuk',
        ]);

        $lokasi->update($validated);

        return redirect()->route('lokasi.index')->with('success', 'Lokasi berhasil diperbarui');
    }
}
