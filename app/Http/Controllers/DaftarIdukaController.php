<?php

namespace App\Http\Controllers;

use App\Models\Iduka;
use Illuminate\Http\Request;

class DaftarIdukaController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $query = Iduka::with(['pusat', 'cabangs']);

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                    ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $data = $query->orderBy('nama', 'asc')
            ->paginate(10)
            ->withQueryString();

        // Tambahkan data lengkap untuk modal
        $allIduka = Iduka::orderBy('nama', 'asc')->get();

        return view('hubin.iduka.daftar', compact('data', 'search', 'allIduka'));
    }
    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'email' => 'nullable|email|max:255',
            'latitude' => 'nullable|numeric|between:-90,90',
            'longitude' => 'nullable|numeric|between:-180,180',
            'radius' => 'nullable|integer|min:0',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
            'is_pusat' => 'nullable|boolean',
            'id_pusat' => 'nullable|exists:idukas,id',
        ]);

        $iduka = Iduka::findOrFail($id);
        $iduka->update($request->all());

        return response()->json([
            'success' => true,
            'message' => 'Data IDUKA berhasil diperbarui.'
        ]);
    }

    // Tambahkan method untuk membuat lokasi cabang baru
    public function storeCabang(Request $request)
    {
        try {
            $request->validate([
                'nama' => 'required|string|max:255',
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'radius' => 'required|integer|min:0',
                'jam_masuk' => 'required|date_format:H:i',
                'jam_pulang' => 'required|date_format:H:i',
                'id_pusat' => 'required|exists:idukas,id',
            ]);

            // Cek apakah lokasi pusat benar-benar ada dan merupakan pusat
            $pusat = Iduka::find($request->id_pusat);
            if (!$pusat || $pusat->is_pusat != 1) {
                return response()->json([
                    'success' => false,
                    'message' => 'Lokasi pusat tidak valid atau bukan merupakan lokasi pusat.'
                ], 422);
            }

            // Buat lokasi cabang baru
            $cabang = Iduka::create([
                'nama' => $request->nama,
                'latitude' => $request->latitude,
                'longitude' => $request->longitude,
                'radius' => $request->radius,
                'jam_masuk' => $request->jam_masuk,
                'jam_pulang' => $request->jam_pulang,
                'is_pusat' => 0, // Tandai sebagai cabang
                'id_pusat' => $request->id_pusat,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Lokasi cabang berhasil ditambahkan.',
                'data' => $cabang
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal.',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            \Log::error('Error creating cabang: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
