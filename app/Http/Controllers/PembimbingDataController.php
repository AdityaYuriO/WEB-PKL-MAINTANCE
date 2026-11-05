<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Guru;
use App\Models\User;

class PembimbingDataController extends Controller
{
    public function index()
    {
        $guru = auth()->user()->guru;

        if (!$guru) {
            return view('guru.siswa-dibimbing.index', [
                'guru' => null,
                'siswas' => collect(),
            ])->with('error', 'Akun guru ini belum punya data pembimbing.');
        }

        $siswas = $guru->siswas()
        ->where('status', 'aktif')
        ->with(['kelas', 'idukaDiterima'])->get();

        return view('guru.siswa-dibimbing.index', compact('guru', 'siswas'));
    }


}
