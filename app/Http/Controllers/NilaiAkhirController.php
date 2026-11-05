<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Penilaian;
use Illuminate\Http\Request;

class NilaiAkhirController extends Controller
{
    // ─────────────────────────────────────────────
    // Helper: hitung predikat dari nilai akhir
    // ─────────────────────────────────────────────
    private function getPredikat(float $nilaiAkhir): string
    {
        if ($nilaiAkhir >= 86) return 'Sangat Baik';
        if ($nilaiAkhir >= 71) return 'Baik';
        if ($nilaiAkhir >= 56) return 'Cukup';
        return 'Kurang';
    }

    // ─────────────────────────────────────────────
    // Helper: susun array data satu siswa
    // ─────────────────────────────────────────────
    private function buildData(User $siswa): ?array
    {
        $nilaiGuru  = Penilaian::where('users_id', $siswa->id)
                        ->where('jenis_penilaian', 'guru_pembimbing')
                        ->avg('nilai');

        $nilaiIduka = Penilaian::where('users_id', $siswa->id)
                        ->where('jenis_penilaian', 'instruktur_iduka')
                        ->avg('nilai');

        // Jika nilai belum ada (null) atau nol, kita set 0 tapi tetap tampilkan
        $nilaiGuruClean  = $nilaiGuru  ?? 0;
        $nilaiIdukaClean = $nilaiIduka ?? 0;
        $nilaiAkhir      = ($nilaiGuruClean + $nilaiIdukaClean) / 2;

        // Jika nilai belum ada sama sekali, default Predikat adalah "Sangat Baik"
        // sesuai instruksi user karena waktu mepet.
        $predikat = ($nilaiAkhir > 0) ? $this->getPredikat($nilaiAkhir) : 'Sangat Baik';

        return [
            'id'          => $siswa->id,
            'nama'        => $siswa->name,
            'nilai_guru'  => round($nilaiGuruClean,  2),
            'nilai_iduka' => round($nilaiIdukaClean, 2),
            'nilai_akhir' => round($nilaiAkhir, 2),
            'predikat'    => $predikat,
        ];
    }

    // ─────────────────────────────────────────────
    // Main index
    // ─────────────────────────────────────────────
    public function index(Request $request)
    {
        $user = auth()->user();
        $role = $user->role;
        $data = [];

        // ── Filter satu siswa (dari query ?siswa=id) ──────────────────
        if ($request->has('siswa')) {
            $siswa = User::where('role', 'siswa')
                         ->where('status', 'aktif')
                         ->where('id', $request->siswa)
                         ->first();

            if ($siswa) {
                $row = $this->buildData($siswa);
                if ($row) $data[] = $row;
            }

            return view('penilaian.nilai_akhir.index', compact('data'));
        }

        // ── Filter semua siswa berdasarkan role ───────────────────────
        if ($role === 'hubin') {
            $siswas = User::where('role', 'siswa')->where('status', 'aktif')->get();

        } elseif ($role === 'kaprog' || $role === 'guru') {
            $guru   = $user->guru;
            $siswas = $guru ? $guru->siswas : collect();

        } elseif ($role === 'iduka') {
            $iduka  = $user->iduka;
            $siswas = $iduka
                ? User::where('role', 'siswa')
                        ->where('status', 'aktif')
                       ->where('iduka_id', $iduka->id)
                       ->get()
                : collect();

        } else {
            $siswas = collect();
        }

        foreach ($siswas as $s) {
            $row = $this->buildData($s);
            if ($row) $data[] = $row;
        }

        return view('penilaian.nilai_akhir.index', compact('data'));
    }
}
