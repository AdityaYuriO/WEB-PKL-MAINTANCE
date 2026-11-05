<?php

namespace App\Http\Controllers;

use App\Exports\CetakUsulanExport;
use Illuminate\Support\Collection;
use Illuminate\Http\Request;
use App\Models\CetakUsulan;
use Maatwebsite\Excel\Facades\Excel;

class DaftarCetakController extends Controller
{
    public function index()
    {

        $cetakUsulans = CetakUsulan::with([
            'dataPribadi.kelas',
            'iduka',
            'siswa',
            'pembimbingpkl',
            'suratPengantar'
        ])
        ->where('status', 'sudah')
        ->get()
        ->sortBy(function ($item) {

            $kelasNama = ($item->dataPribadi->kelas->kelas ?? '') . ($item->dataPribadi->kelas->name_kelas ?? '');
            $nama = $item->dataPribadi->name ?? '';
            return $kelasNama . $nama;
        })
        ->groupBy(function ($item) {

            return ($item->dataPribadi->kelas->name_kelas ?? '-');
        });

        return view('hubin.daftarcetak', compact('cetakUsulans'));
    }
    public function downloadExcel()
{
    return Excel::download(new CetakUsulanExport, 'daftar_cetak_usulan.xlsx');
}
}
