<?php 

namespace App\Http\Controllers;

use App\Models\PembimbingIduka;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class PembimbingIdukaController extends Controller
{
    public function create()
    {
        // Ambil data pembimbing yang sudah ada, jika belum ada buat objek baru
        $pembimbingIduka = Auth::user()->iduka->pembimbingIduka ?? new PembimbingIduka();

        return view('pembimbing.form', compact('pembimbingIduka'));
    }

    public function store(Request $request)
    {
        $iduka = Auth::user()->iduka; // Ambil Iduka dari user yang login

        if (!$iduka) {
            return redirect()->back()->withErrors(['message' => 'Anda tidak memiliki akses untuk menambahkan pembimbing.']);
        }

        $pembimbingIduka = $iduka->pembimbingIduka; // Cek apakah pembimbing sudah ada

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:pembimbing_idukas,nip,' . ($pembimbingIduka->id ?? 'null') . ',id',
            'no_hp' => 'required|numeric|unique:pembimbing_idukas,no_hp,' . ($pembimbingIduka->id ?? 'null') . ',id',
            'password' => $pembimbingIduka ? 'nullable|string|min:6' : 'required|string|min:6',
        ]);

        DB::transaction(function () use ($request, $iduka, $pembimbingIduka) {
            if (!$pembimbingIduka) {
                // Buat akun user baru jika pembimbing belum ada
                $userPembimbing = User::create([
                    'name' => $request->name,
                    'nip' => $request->nip,
                    'password' => Hash::make($request->password),
                    'role' => 'ppkl',
                ]);

                // Simpan data baru di pembimbing_idukas
                PembimbingIduka::create([
                    'iduka_id' => $iduka->id,
                    'user_id' => $userPembimbing->id,
                    'name' => $request->name,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'password' => Hash::make($request->password),
                ]);
            } else {
                // Update data yang sudah ada
                $pembimbingIduka->update([
                    'name' => $request->name,
                    'nip' => $request->nip,
                    'no_hp' => $request->no_hp,
                    'password' => $request->password ? Hash::make($request->password) : $pembimbingIduka->password,
                ]);

                // Update juga di tabel users
                if ($pembimbingIduka->user) {
                    $pembimbingIduka->user->update([
                        'name' => $request->name,
                        'nip' => $request->nip,
                        'password' => $request->password ? Hash::make($request->password) : $pembimbingIduka->user->password,
                    ]);
                }
            }
        });

        return redirect()->route('pembimbing.create')->with('success', 'Data pembimbing berhasil disimpan.');
    }
}
