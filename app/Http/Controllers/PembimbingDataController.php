<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\Konke;
use App\Models\User;

class PembimbingDataController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $error = null;

        if ($user->role === 'kaprog') {
            $konkeId = $user->konke_id
                ?? Guru::where('user_id', $user->id)->value('konke_id');
            $konke = $konkeId ? Konke::find($konkeId) : null;

            if (!$konke) {
                $siswas = collect();
                $title = 'Siswa di Jurusan';
                $error = 'Jurusan akun Kaprog belum terhubung. Hubungkan akun ke jurusan terlebih dahulu agar daftar siswa dapat ditampilkan.';
            } else {
                $siswas = User::where('role', 'siswa')
                    ->where('status', 'aktif')
                    ->whereHas('kelas', function ($query) use ($konkeId) {
                        $query->where('konke_id', $konkeId);
                    })
                    ->with(['kelas', 'idukaDiterima'])
                    ->get();
                $title = 'Siswa Jurusan ' . $konke->name_konke;
            }

            return view('guru.siswa-dibimbing.index', [
                'title' => $title,
                'siswas' => $siswas,
                'error' => $error,
            ]);
        }

        $guru = Guru::where('user_id', $user->id)->first();
        if (!$guru) {
            return view('guru.siswa-dibimbing.index', [
                'title' => 'Siswa Bimbingan',
                'siswas' => collect(),
                'error' => 'Akun guru ini belum terhubung ke data pembimbing.',
            ]);
        }

        $siswas = $guru->siswas()
            ->where('status', 'aktif')
            ->with(['kelas', 'idukaDiterima'])
            ->get();

        return view('guru.siswa-dibimbing.index', [
            'title' => 'Siswa Bimbingan ' . $guru->nama,
            'siswas' => $siswas,
            'error' => $error,
        ]);
    }

}
