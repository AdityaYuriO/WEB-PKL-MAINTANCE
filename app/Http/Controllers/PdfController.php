<?php

namespace App\Http\Controllers;

use App\Models\CetakUsulan;
use App\Models\DataPribadi;
use App\Models\Iduka;
use App\Models\UsulanIduka;
use App\Models\tahunAjaran;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class PdfController extends Controller
{
   public function usulanPdf()
{
    $user = Auth::user();
    $dataPribadi = DataPribadi::where('user_id', $user->id)
    ->first();

    // Ambil usulan_iduka terbaru yang memiliki iduka_id
    $usulanTerakhir = UsulanIduka::where('user_id', $user->id)
                        ->whereNotNull('iduka_id')
                        ->latest()
                        ->first();

    if ($usulanTerakhir) {
        $usulanIduka = DB::table('idukas')->where('id', $usulanTerakhir->iduka_id)->first();
    } else {
        // Jika tidak ditemukan di usulan_idukas, cari di pengajuan_usulans yang valid
        $pengajuan = DB::table('pengajuan_usulans')
                        ->where('user_id', $user->id)
                        ->whereNotNull('iduka_id')
                        ->latest()
                        ->first();

        $usulanIduka = $pengajuan ? DB::table('idukas')->where('id', $pengajuan->iduka_id)->first() : null;
    }

    if (!$usulanIduka) {
        return back()->with('error', 'Data IDUKA tidak ditemukan.');
    }

    // Ambil kaprog
    $kaprog = \App\Models\User::with('konke')
        ->where('role', 'kaprog')
        ->where('konke_id', $dataPribadi->konke_id)
        ->first();

    if (!$kaprog) {
        return back()->with('error', 'Kaprog tidak ditemukan.');
    }

    $singkatan = [
        'Pengembangan Perangkat Lunak dan Gim' => 'PPLG',
        'Manajemen  Perkantoran dan  Layanan Bisnis' => 'MPLB',
        'Desain Pemodelan  dan Informasi  Bangunan' => 'DPIB',
        'Akuntansi dan  Keuangan  Lembaga' => 'AKL',
        'Teknik Jaringan Komputer dan Telekomunikasi' => 'TJKT',
        'Teknik Otomotif' => 'TO',
        'Seni Pertunjukan' => 'SP',
    ];

    $namaJurusan = strtoupper($kaprog->konke->name_konke ?? 'Tidak diketahui');
    $singkatanJurusan = $singkatan[$namaJurusan] ?? $namaJurusan;

    $pdf = Pdf::loadView('data.usulan.suratUsulanPDF', compact('dataPribadi', 'usulanIduka', 'kaprog', 'singkatanJurusan'));
    return $pdf->download('surat_usulan_iduka_baru.pdf');
}


    public function siswaUsulanPdf()
    {
        $user = Auth::user();

        // Ambil data siswa yang sedang login
        $dataPribadi = DataPribadi::with(['kelas', 'konkes'])->where('user_id', $user->id)->first();

        // Ambil data usulan IDUKA terbaru berdasarkan user
        $usulanIduka = UsulanIduka::where('user_id', $user->id)->latest()->first();

        // Validasi jika data tidak ditemukan
        if (!$dataPribadi || !$usulanIduka) {
            return redirect()->back()->with('error', 'Data tidak ditemukan!');
        }

        // Generate PDF menggunakan template suratUsulanSiswaPDF.blade.php
        $pdf = Pdf::loadView('data.usulan.siswaUsulanPdf', compact('dataPribadi', 'usulanIduka'));

        return $pdf->download('siswa_usulan_iduka.pdf');
    }

    public function unduhDetailIdukaPDF($id)
    {
        $dataIduka = Iduka::findOrFail($id);
        $pdf = Pdf::loadView('iduka.dataiduka.detailDataIdukaPDF', compact('dataIduka'));
        return $pdf->download('data-iduka-' . $dataIduka->nama . '.pdf');
    }
}
