<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Pembimbing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class PembimbingController extends Controller
{
    /**
     * Menampilkan form tambah/edit pembimbing
     */
    public function create()
    {
        // Ambil pembimbing yang terhubung dengan user yang login
        $pembimbing = Auth::user()->pembimbing;
        $iduka = User::with('iduka')->where('id', Auth::id())->first();

        // Jika tidak ada pembimbing, buat objek kosong agar tidak error di form
        if (!$pembimbing) {
            $pembimbing = new Pembimbing();
        }

        return view('iduka.pembimbing.form', compact('pembimbing', 'iduka'));
    }

    /**
     * Menyimpan data pembimbing baru atau mengupdate yang sudah ada
     */
    public function store(Request $request)
    {
        // Ambil iduka yang sedang login
        $user = Auth::user();
    
        // Periksa apakah iduka sudah memiliki pembimbing
        if ($user->pembimbing) {
            return redirect()->back()->with('error', 'Anda hanya dapat menambahkan satu pembimbing.');
        }
    
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip',
            'no_hp' => 'required|string|max:15|unique:pembimbingpkl,no_hp',
            'password' => 'required|string|min:6',
        ]);
    
        // Buat akun User untuk pembimbing
        $pembimbingUser = User::create([
            'name' => $request->name,
            'nip' => $request->nip, 
            'password' => Hash::make($request->password),
            'role' => 'ppkl',
        ]);
    
        // Buat pembimbing dengan user_id dari iduka yang login
        Pembimbing::create([
            'user_id' => $user->id,
            'name' => $request->name,
            'nip' => $request->nip,
            'no_hp' => $request->no_hp,
            'password' => Hash::make($request->password),
        ]);
    
        return redirect()->route('iduka.pembimbing.create')->with('success', 'Pembimbing berhasil ditambahkan.');
    }
    

  

    /**
     * Mengupdate data pembimbing yang sudah ada
     */
    public function update(Request $request, $id)
    {
        // Ambil data pembimbing berdasarkan ID
        $pembimbing = Pembimbing::findOrFail($id);
        $user = $pembimbing->user;

        // Validasi data
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['required', 'string', 'max:255',
                function ($attribute, $value, $fail) use ($user) {
                    if ($value !== $user->nip && User::where('nip', $value)->where('id', '!=', $user->id)->exists()) {
                        $fail('NIP sudah digunakan.');
                    }
                }
            ],
            'no_hp' => ['required', 'string', 'max:15',
                function ($attribute, $value, $fail) use ($pembimbing) {
                    if ($value !== $pembimbing->no_hp && Pembimbing::where('no_hp', $value)->where('id', '!=', $pembimbing->id)->exists()) {
                        $fail('Nomor HP sudah digunakan.');
                    }
                }
            ],
            'password' => 'nullable|string|min:6',
        ]);

        // Update data dalam transaksi database untuk menjaga konsistensi
        DB::transaction(function () use ($request, $pembimbing, $user) {
            // Update data Pembimbing
            $pembimbing->update([
                'name' => $request->name,
                'no_hp' => $request->no_hp,
            ]);

            // Update data User terkait
            $user->update([
                'name' => $request->name,
                'nip' => $request->nip,
                'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
            ]);
        });

        return redirect()->back()->with('success', 'Data Pembimbing berhasil diperbarui.');
    }
}
