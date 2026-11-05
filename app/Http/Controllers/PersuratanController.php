<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Iduka;
use App\Models\IdukaAtp;
use App\Models\CetakUsulan;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use App\Models\SuratBalasanHistory;
use Illuminate\Support\Facades\DB;

class PersuratanController extends Controller
{
    public function index()
    {
        return view('persuratan.suratPengajuan.suratPengajuan');
    }
    // DETAIL SISWA DI PERSURATAN
    public function show($id)
    {
        $pengajuan = CetakUsulan::with([
            'dataPribadi.kelas',
            'iduka.user.pembimbingpkl', // Mengambil pembimbing lewat user di iduka
            'iduka.konkes',
            'iduka.cp',
            'iduka.atps'
        ])->findOrFail($id);

        return view('persuratan.suratPengajuan.detailSuratPengajuan', compact('pengajuan'));
    }



    public function idukaBaru()
    {
        return view('persuratan.PengajuanIdukaBaru.idukaBaru');
    }

    public function showidukaBaru()
    {
        return view('persuratan.PengajuanIdukaBaru.detailidukaBaru');
    }

    public function downloadPdf($id)
    {
        $pengajuan = CetakUsulan::with(['dataPribadi.kelas', 'iduka.user.pembimbingpkl', 'iduka.konkes', 'iduka.cp', 'iduka.atps'])
            ->findOrFail($id);

        $pdf = Pdf::loadView('persuratan.suratPengajuan.surat-pengajuan', compact('pengajuan'));

        return $pdf->download('Surat_Pengajuan_' . $pengajuan->dataPribadi->nama . '.pdf');
    }

    public function create()
    {
        return view('persuratan.data_pribadi.form');
    }

    public function reviewPengajuan()
    {
        $pengajuanUsulans = CetakUsulan::with(['dataPribadi.kelas', 'iduka'])
            ->where('status', 'proses')
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('iduka_id');

        return view('persuratan.review', compact('pengajuanUsulans'));
    }



    public function detailUsulan($iduka_id)
    {
        $pengajuanUsulans = CetakUsulan::with(['dataPribadi.kelas', 'iduka'])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'proses')
            ->get();

        return view('persuratan.detail', compact('pengajuanUsulans', 'iduka_id'));
    }



    public function prosesPengajuan($id)
    {
        $pengajuan = CetakUsulan::findOrFail($id);
        $pengajuan->status = 'sudah';
        $pengajuan->save();

        return response()->json(['success' => true, 'message' => 'Data berhasil di kirim ke Kaprog.']);
    }

    //mengubah semua status siswa jadi sudah
    public function kirimSemua($iduka_id)
    {
        $pengajuanList = CetakUsulan::where('iduka_id', $iduka_id)->where('status', 'proses')->get();

        foreach ($pengajuanList as $pengajuan) {
            $pengajuan->status = 'sudah';
            $pengajuan->save();
        }

        return response()->json([
            'success' => true,
            'message' => 'Data Berhasil di kirim ke Kaprog.'
        ]);
    }

    public function suratPengantar()
    {
        return view('surat_pengantar.surat_pengantarPDF');
    }

    public function historykirim()
    {
        $dataDikirim = CetakUsulan::with('iduka', 'siswa')
            ->where('status', 'sudah')
            ->orderByDesc('created_at') // Urut berdasarkan tanggal terbaru
            ->get();

        return view('persuratan.historykirim', compact('dataDikirim'));
    }


    public function downloadKelompokPdf($iduka_id)
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

    // Menampilkan semua surat balasan berdasarkan PengajuanPkl (yang BELUM di-download)
    public function suratBalasan()
    {
        $pengajuanUsulans = PengajuanPkl::with(['dataPribadi.kelas', 'iduka'])
            ->whereIn('status', ['diterima', 'ditolak'])
            ->whereDoesntHave('historiDownload') // hanya yang BELUM ada histori download
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy('iduka_id');

        return view('persuratan.suratBalasan.suratbalasan', compact('pengajuanUsulans'));
    }

    // Menampilkan detail balasan untuk satu IDUKA (yang BELUM di-download)
    public function detailBalasan($iduka_id)
    {
        $iduka = Iduka::findOrFail($iduka_id);

        $pengajuans = PengajuanPkl::with(['dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->whereIn('status', ['diterima', 'ditolak'])
            ->whereDoesntHave('historiDownload') // hanya yang BELUM ada histori download
            ->get();

        return view('persuratan.suratBalasan.detailbalasan', compact('iduka', 'pengajuans'));
    }

    // Menampilkan histori surat balasan (yang SUDAH di-download)
    public function historyBalasan()
    {
        $histori = SuratBalasanHistory::with(['pengajuanPkl.dataPribadi', 'pengajuanPkl.iduka'])
            ->whereHas('pengajuanPkl', function ($query) {
                $query->whereIn('status', ['diterima', 'ditolak']);
            })
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($item) {
                return $item->pengajuanPkl->iduka_id;
            });

        return view('persuratan.suratBalasan.history', compact('histori'));
    }

    // Download surat balasan
    // Pastikan di atas file controllermu ada ini

    public function downloadSuratBalasan($id)
    {
        $pengajuan = PengajuanPkl::with(['dataPribadi', 'iduka'])
            ->findOrFail($id);

        if (!$pengajuan->dataPribadi || !$pengajuan->iduka) {
            return redirect()->back()->with('error', 'Data siswa atau IDUKA tidak ditemukan');
        }

        $historyExists = SuratBalasanHistory::where('pengajuan_pkl_id', $pengajuan->id)
            ->where('downloaded_by', auth()->user()->name)
            ->exists();

        if (!$historyExists) {
            SuratBalasanHistory::create([
                'pengajuan_pkl_id' => $pengajuan->id,
                'downloaded_by' => auth()->user()->name
            ]);
            session()->flash('success', 'Histori download berhasil disimpan.');
        } else {
            session()->flash('info', 'Histori download sudah ada.');
        }

        $tanggalHariIni = Carbon::now()->translatedFormat('d F Y'); // Contoh: 28 April 2025

        $pdf = Pdf::loadView('persuratan.suratBalasan.suratbalasan-pdf', [
            'pengajuan' => $pengajuan,
            'siswa' => $pengajuan->dataPribadi,
            'iduka' => $pengajuan->iduka,
            'tanggalHariIni' => $tanggalHariIni
        ]);

        return $pdf->download('Surat_Balasan_' . $pengajuan->dataPribadi->nama . '.pdf');
    }


    // Update status surat balasan
    public function updateStatusSurat(Request $request)
    {
        $request->validate([
            'history_id' => 'required|exists:surat_balasan_histories,id'
        ]);

        $history = SuratBalasanHistory::find($request->history_id);
        $history->status_surat = 'sudah';
        $history->save();

        return response()->json([
            'success' => true,
            'message' => 'Status surat berhasil diubah'
        ]);
    }

    public function massDownload(Request $request)
    {
        $request->validate([
            'pengajuan_ids' => 'required|array|min:1',
        ]);

        $ids = $request->pengajuan_ids;

        $pengajuans = PengajuanPkl::with(['dataPribadi', 'iduka'])->whereIn('id', $ids)->get();

        $tanggalHariIni = Carbon::now()->translatedFormat('d F Y');

        foreach ($pengajuans as $pengajuan) {
            // Cek dan simpan histori
            $historyExists = SuratBalasanHistory::where('pengajuan_pkl_id', $pengajuan->id)
                ->where('downloaded_by', auth()->user()->name)
                ->exists();

            if (!$historyExists) {
                SuratBalasanHistory::create([
                    'pengajuan_pkl_id' => $pengajuan->id,
                    'downloaded_by' => auth()->user()->name
                ]);
            }
        }

        // Generate PDF gabungan
        $pdf = PDF::loadView('persuratan.suratBalasan.suratbalasan-pdf-massal', [
            'pengajuans' => $pengajuans,
            'tanggalHariIni' => $tanggalHariIni
        ]);

        return $pdf->download('Surat_Balasan_Massal.pdf');
    }

    // Tampilkan daftar pengajuan pindah PKL yang sudah dikonfirmasi kaprog
    public function pindahPklIndex()
    {
        $pindah = DB::table('pindah_pkl')
            ->join('users', 'pindah_pkl.siswa_id', '=', 'users.id')
            ->join('idukas', 'pindah_pkl.iduka_id', '=', 'idukas.id')
            ->select(
                'pindah_pkl.id',
                'pindah_pkl.status',
                'pindah_pkl.created_at',
                'pindah_pkl.updated_at',
                'users.name as nama_siswa',
                'users.kelas_id',
                'idukas.nama as nama_iduka'
            )
            ->where('pindah_pkl.status', 'menunggu_surat') // status setelah kaprog konfirmasi
            ->get();

        return view('persuratan.pindahpkl.index', compact('pindah'));
    }

    // Konfirmasi pengajuan pindah PKL oleh persuratan
    public function konfirmasiPindahPkl(Request $request, $id)
{
    $request->validate([
        'status' => 'required|in:diterima,ditolak',
    ]);

    $pindah = DB::table('pindah_pkl')->where('id', $id)->first();

    if (!$pindah) {
        return redirect()->back()->with('error', 'Data pengajuan tidak ditemukan.');
    }

    if ($request->status === 'ditolak') {
        DB::table('pindah_pkl')->where('id', $id)->update([
            'status' => 'ditolak_persuratan', // status ditolak persuratan
            'updated_at' => now(),
        ]);

        return redirect()->back()->with('success', 'Pengajuan pindah telah ditolak.');
    }

    if ($request->status === 'diterima') {
        DB::table('pindah_pkl')->where('id', $id)->update([
            'status' => 'siap_kirim', // status siap dikirim ke iduka
            'updated_at' => now(),
        ]);
    }

    return redirect()->back()->with('success', 'Pengajuan pindah berhasil dikonfirmasi.');
}

public function pindahPklSelesai()
{
    // Mengambil data pindah PKL yang sudah selesai
    $pindah = DB::table('pindah_pkl')
        ->join('users', 'pindah_pkl.siswa_id', '=', 'users.id')
        ->join('idukas', 'pindah_pkl.iduka_id', '=', 'idukas.id')
        ->select(
            'pindah_pkl.id',
            'pindah_pkl.status',
            'pindah_pkl.created_at',
            'pindah_pkl.updated_at',
            'users.name as nama_siswa',
            'users.kelas_id',
            'idukas.nama as nama_iduka',
            'idukas.id as iduka_id'
        )
        ->whereIn('pindah_pkl.status', ['siap_kirim', 'ditolak_persuratan', 'diterima_iduka'])
        ->get();

    // Kelompokkan data berdasarkan IDUKA dan status
    $groupedPindah = $pindah->groupBy(function ($item) {
        return $item->iduka_id . '_' . $item->status;
    });

    // Buat koleksi baru untuk ditampilkan di view
    $result = collect();
    foreach ($groupedPindah as $group) {
        $firstItem = $group->first();
        $firstItem->jumlah_siswa = $group->count();
        $firstItem->daftar_siswa = $group->pluck('nama_siswa')->implode(', ');
        $result->push($firstItem);
    }

    return view('persuratan.pindahpkl.selesai', ['pindah' => $result]);
}
}
