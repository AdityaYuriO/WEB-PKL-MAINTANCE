<?php

namespace App\Http\Controllers;

use App\Models\DataPribadi;
use App\Models\PengajuanUsulan;
use App\Models\UsulanIduka;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DataController extends Controller
{
    public function proker()
    {
        return view('data.proker.dataproker');
    }

    public function konke()
    {
        return view('data.konke.dataKonke');
    }

    public function kelas()
    {
        return view('data.kelas.dataKelas');
    }

    public function dataUsulan()
    {
        return view('data.usulan.formUsulan');
    }

    public function detailUsulan()
    {
        $user = auth()->user();

        $usulanIduka = UsulanIduka::where('user_id', $user->id)->latest()->first();
        $pengajuanUsulan = PengajuanUsulan::with('iduka')->where('user_id', $user->id)->latest()->first();
        $dataPribadi = DataPribadi::with(['kelas', 'konkes'])->where('user_id', $user->id)->first();
        
        return view('data.usulan.detailUsulan', compact('dataPribadi', 'usulanIduka', 'pengajuanUsulan'));
    }


    public function usulanPdf()
{
    $user = Auth::user();
    $dataPribadi = DataPribadi::where('user_id', $user->id)->first();
    $usulanIduka = UsulanIduka::where('user_id', $user->id)->latest()->first();

    // Debug: Cek apakah data tersedia sebelum diproses
    if (!$dataPribadi) {
        return "Data pribadi tidak ditemukan!";
    }

    if (!$usulanIduka) {
        return "Usulan IDUKA tidak ditemukan!";
    }

    $pdf = Pdf::loadView('data.usulan.suratUsulanPDF', compact('dataPribadi', 'usulanIduka'));
    return $pdf->download('surat_usulan_iduka_baru.pdf');
}


    public function detailPengajuan()
    {
        return view('data.usulan.detailPengajuan');
    }
}
