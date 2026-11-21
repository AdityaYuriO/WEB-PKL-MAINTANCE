<?php

namespace App\Http\Controllers;

use App\Exports\CetakUsulanExport;
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
            'user'
        ])
        ->where('status', 'sudah')
        ->whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })
        ->get()
        ->sortBy(function ($item) {
            $kelasNama = optional($item->dataPribadi->kelas)->kelas . optional($item->dataPribadi->kelas)->name_kelas;
            $nama = optional($item->dataPribadi)->name ?? '';
            return $kelasNama . $nama;
        })
        ->groupBy(function ($item) {
            return optional($item->dataPribadi->kelas)->name_kelas ?? '-';
        });

        return view('hubin.daftarcetak', compact('cetakUsulans'));
    }

    public function downloadExcel()
    {
        return Excel::download(new CetakUsulanExport, 'daftar_cetak_usulan.xlsx');
    }
}