<?php

namespace App\Http\Controllers;

use App\Models\Iduka;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\LaporanIdukaExport;
use App\Exports\LaporanIdukaAllExport;

class LaporanIduka extends Controller
{
    /**
     * Tampilkan daftar IDUKA dengan pagination (untuk tampilan web).
     */
    public function index(Request $request)
    {
        $query = Iduka::with(['siswa.kelas']);

        // Filter nama IDUKA
        if ($request->filled('nama_iduka')) {
            $query->where('nama', 'like', '%' . $request->nama_iduka . '%');
        }

        // Filter berdasarkan alamat
        if ($request->filled('alamat')) {
            $query->where('alamat', 'like', '%' . $request->alamat . '%');
        }

        // Filter berdasarkan bidang industri
        if ($request->filled('bidang_industri')) {
            $query->where('bidang_industri', 'like', '%' . $request->bidang_industri . '%');
        }

        $idukas = $query->paginate(10);

        return view('laporan_iduka.index', compact('idukas'));
    }

    /**
     * Tampilkan daftar siswa per IDUKA.
     */
    public function showSiswa($id)
    {
        $iduka = Iduka::with(['siswa.kelas'])->findOrFail($id);
        return view('laporan_iduka.siswa', compact('iduka'));
    }

    /**
     * Export siswa per IDUKA ke Excel.
     */
    public function exportExcel($id)
    {
        $iduka = Iduka::with(['siswa' => function ($query) {
                $query->where('status', 'aktif'); // Filter siswa yang statusnya aktif
            }, 'siswa.kelas'])
            ->findOrFail($id);
        return Excel::download(
            new LaporanIdukaExport($iduka),
            'laporan_iduka_' . $iduka->nama . '.xlsx'
        );
    }

    /**
     * Export semua IDUKA + siswa ke Excel.
     */
    public function exportAll()
    {
        return Excel::download(
            new LaporanIdukaAllExport,
            'laporan_iduka.xlsx'
        );
    }
}
