<?php
//cobain
namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Konke;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class GuruController extends Controller
{
    public function dataguru(Request $request)
    {
        $user = User::where('role', 'guru')->with('konke')->get();
        $konkes = Konke::all(); // Ambil data konsentrasi keahlian

        $query = Guru::with('konke')->orderBy('created_at', 'desc');

        // Kalau ada input search
        if ($request->filled('search')) {
            $query->where('nama', 'like', '%' . $request->search . '%');
        }

        $guru = $query->paginate(10)->withQueryString();

        return view('hubin.dataguru.dataguru', compact('guru', 'konkes', 'user'));
    }

    public function create()
    {
        $konkes = Konke::all();
        return view('hubin.dataguru.create', compact('konkes'));
    }

    public function store(Request $request)
    {
        // Validasi input
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|max:20|unique:gurus,nik',
            'nip' => 'required|string|max:20|unique:users,nip',
            'email' => 'required|email|max:255|unique:gurus,email|unique:users,email',
            'password' => 'required|min:8',
            'konke_id' => 'nullable|exists:konkes,id', // Opsional
            'role' => 'required|in:guru,kaprog,hubin,psekolah', // Validasi role
        ]);

        DB::transaction(function () use ($request) {
            // Simpan data ke tabel users terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'email' => $request->email,
                'nip' => $request->nik,
                'password' => Hash::make($request->password),
                'role' => $request->role,
                'konke_id' => $request->konke_id,
            ]);

            // Simpan data ke tabel gurus dengan user_id yang baru dibuat
            Guru::create([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'nip' => $request->nip,
                'email' => $request->email,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'password' => Hash::make($request->password),
                'konke_id' => $request->konke_id,
                'user_id' => $user->id, // Menyimpan user_id ke tabel gurus
            ]);
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil ditambahkan!');
    }


    public function edit(Guru $guru)
    {
        $konkes = Konke::all();  // Fetch all records from the Konke model
        return view('hubin.dataguru.edit', compact('guru', 'konkes'));  // Pass the konkes variable to the view along with guru
    }


    public function update(Request $request, Guru $guru)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nik' => 'required|string|unique:gurus,nik,' . $guru->id,
            'email' => 'required|email|unique:gurus,email,' . $guru->id,
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'no_hp' => 'required|string',
            'konke_id' => 'nullable|exists:konkes,id', // Opsional
        ]);

        DB::transaction(function () use ($request, $guru) {
            // Update data guru
            $guru->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'email' => $request->email,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'no_hp' => $request->no_hp,
                'konke_id' => $request->konke_id,
            ]);

            // Update password jika diisi
            if ($request->filled('password')) {
                $guru->update(['password' => Hash::make($request->password)]);
            }

            // Update data di tabel users (sinkronisasi)
            $user = User::where('email', $guru->email)->first();
            if ($user) {
                $user->update([
                    'name' => $request->nama,
                    'nip' => $request->nik,
                    'email' => $request->email,
                ]);

                if ($request->filled('password')) {
                    $user->update(['password' => Hash::make($request->password)]);
                }
            }
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil diperbarui!');
    }

    public function destroy(Guru $guru)
    {
        DB::transaction(function () use ($guru) {
            // Hapus data dari tabel Users
            User::where('email', $guru->email)->delete();

            // Hapus data dari tabel Guru
            $guru->delete();
        });

        return redirect()->route('guru.index')->with('success', 'Data Guru berhasil dihapus!');
    }

}
