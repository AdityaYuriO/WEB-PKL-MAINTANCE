<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Kelas;
use App\Models\Konke;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class UsersController extends Controller
{
    public function index()
    {
        $kelas = Kelas::all();
        $konke = Konke::all();
        return view('user.siswa', compact('kelas', 'konke'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip',
            'kelas_id' => 'required|exists:kelas,id',
            'konke_id' => 'required|exists:konkes,id',
            'tahun_ajaran' => 'required|string|max:9',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        // Buat user baru
        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'kelas_id' => $request->kelas_id,   // foreign key ke tabel kelas
            'konke_id' => $request->konke_id,   // foreign key ke tabel konkes
            'email' => $request->email,
            'tahun_ajaran' => $request->tahun_ajaran,
            'password' => Hash::make($request->password),
            'role' => 'siswa'
        ]);

        return redirect()->route('data.siswa')->with('success', 'Siswa berhasil ditambahkan.');
    }

    public function guruIndex() {
        $konke = Konke::all();
        return view('user.guru',compact('konke'));
    }

    public function guruStore(Request $request)
{
    // Validasi input
    $request->validate([
        'nama'          => 'required|string|max:255',
        'nik'           => 'required|string|unique:gurus,nik',
        'nip'           => 'nullable|string|max:20|unique:users,nip|unique:gurus,nip',
        'email'         => 'required|email|max:255|unique:gurus,email|unique:users,email',
        'password'      => 'required|min:8',
        'konke_id'      => 'nullable|exists:konkes,id',
        'role'          => 'required|in:guru,kaprog,hubin,psekolah',
        'tempat_lahir'  => 'required|string|max:255',
        'tanggal_lahir' => 'required|date',
        'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
        'alamat'        => 'required|string',
        'no_hp'         => 'required|string|max:20',
    ]);

    DB::transaction(function () use ($request) {
        // Simpan data ke tabel users
        $user = User::create([
            'name'      => $request->nama,
            'email'     => $request->email,
            'nip'       => $request->nip, // nip/nupkt disimpan ke users
            'password'  => Hash::make($request->password),
            'role'      => $request->role,
            'konke_id'  => $request->konke_id,
        ]);

        // Simpan data ke tabel gurus
        Guru::create([
            'nama'          => $request->nama,
            'nik'           => $request->nik,
            'nip'           => $request->nip,
            'email'         => $request->email,
            'tempat_lahir'  => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat'        => $request->alamat,
            'no_hp'         => $request->no_hp,
            'password'      => Hash::make($request->password),
            'konke_id'      => $request->konke_id,
            'user_id'       => $user->id,
        ]);
    });

    return redirect()->route('guru.index')->with('success', 'Data Guru berhasil ditambahkan!');
}


}
