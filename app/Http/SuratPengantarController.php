<?php

namespace App\Http\Controllers;

use App\Models\CetakUsulan;
use App\Models\SuratPengantar;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;

class SuratPengantarController extends Controller
{
    public function index()
    {
        $data = SuratPengantar::first(); // hanya ambil data pertama
        return view('pusatbantuan.index', compact('data'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nomor' => 'required',
            'perihal' => 'required',
            'tempat' => 'required',
            'tanggalbuat' => 'required|date',
            'deskripsi' => 'required',
            'nama_instansi' => 'required',
            'nama_kepsek' => 'required',
            'tanggal_awal' => 'nullable|date',
            'tanggal_akhir' => 'nullable|date',
        ]);

        $surat = SuratPengantar::first();

        if ($surat) {
            $surat->update($request->all());
        } else {
            SuratPengantar::create($request->all());
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
    }

    public function suratPengantarPDF( $iduka_id)
    {
        // Ambil semua pengajuan berdasarkan IDUKA
        $pengajuans = CetakUsulan::with(['dataPribadi.kelas', 'iduka.konkes'])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'proses')
            ->get();

        if ($pengajuans->isEmpty()) {
            return back()->with('error', 'Tidak ada data pengajuan untuk IDUKA ini.');
        }

        $iduka = $pengajuans->first()->iduka;

        $pdf = Pdf::loadView('persuratan.suratPengajuan.surat-pengajuan-kelompok', compact('pengajuans', 'iduka'));

        return $pdf->download('Surat_Pengajuan_Kelompok_' . $iduka->nama . '.pdf');
    }

    public function semuasurat($iduka_id)
    {
        $suratPengantar = SuratPengantar::first();

        // Ambil semua pengajuan untuk IDUKA ini
        $pengajuans = CetakUsulan::with([
            'dataPribadi.kelas',
            'dataPribadi.konkes',
            'iduka.user.pembimbingpkl',
            'iduka.konkes',
            'iduka.cp',
            'iduka.atps',
        ])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'proses')
            ->get();

        // Jika kosong, redirect balik atau abort
        if ($pengajuans->isEmpty()) {
            return redirect()->back()->with('error', 'Tidak ada pengajuan untuk IDUKA ini.');
        }
        // Ambil nama IDUKA dari pengajuan pertama
        $iduka = optional($pengajuans->first())->iduka;

        $data = [
            'pengajuans' => $pengajuans,
            'tanggal' => now()->translatedFormat('d F Y'), // Format tanggal lokal
            'suratPengantar' => $suratPengantar,
            'iduka' => $iduka, // Mengirimkan IDUKA ke view
        ];

        $pdf = Pdf::loadView('surat_pengantar.surat_pengantarsemua', $data)
            ->setPaper('F4', 'portrait');

        return $pdf->download('surat-pengantar.pdf');
    }

    public function cetakPilihan(Request $request)
{
    $ids = $request->input('ids');
    $iduka_id = $request->input('iduka_id');
    $suratPengantar = SuratPengantar::first();

    if (empty($ids)) {
        return redirect()->back()->with('error', 'Tidak ada siswa yang dipilih.');
    }

    $pengajuans = CetakUsulan::with([
        'dataPribadi.kelas',
        'dataPribadi.konkes',
        'iduka.user.pembimbingpkl',
        'iduka.konkes',
        'iduka.cp',
        'iduka.atps',
    ])
    ->where('iduka_id', $iduka_id)
    ->whereIn('id', $ids)
    ->get();

    if ($pengajuans->isEmpty()) {
        return redirect()->back()->with('error', 'Tidak ada pengajuan sesuai pilihan.');
    }

    $iduka = optional($pengajuans->first())->iduka;

    $data = [
        'pengajuans' => $pengajuans,
        'tanggal' => now()->translatedFormat('d F Y'),
        'suratPengantar' => $suratPengantar,
        'iduka' => $iduka,
    ];

    $pdf = Pdf::loadView('surat_pengantar.surat_pengantarsemua', $data)
        ->setPaper('F4', 'portrait');

    return $pdf->download('surat-pengantar-pilihan.pdf');
}

}
