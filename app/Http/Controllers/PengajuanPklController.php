<?php
//anjay
namespace App\Http\Controllers;

use App\Models\CetakUsulan;
use App\Models\Iduka;
use App\Models\PengajuanPkl;
use App\Models\PengajuanUsulan;
use App\Models\UsulanIduka;
use App\Models\DataPribadi;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class PengajuanPklController extends Controller
{
    // Siswa mengajukan PKL
    public function ajukan(Request $request, $iduka_id)
    {
        $siswa = auth()->user();

        // Cek apakah siswa sudah pernah diterima di IDUKA manapun
        if (PengajuanPkl::where('siswa_id', $siswa->id)->where('status', 'diterima')->exists()) {
            return back()->with('error', 'Anda sudah diterima PKL di IDUKA, tidak dapat mengajukan ulang.');
        }

        // Cek apakah siswa sudah mengajukan ke IDUKA ini sebelumnya
        if (PengajuanPkl::where('siswa_id', $siswa->id)->where('iduka_id', $iduka_id)->exists()) {
            return back()->with('error', 'Anda sudah mengajukan PKL ke IDUKA ini.');
        }

        // Simpan pengajuan baru
        PengajuanPkl::create([
            'siswa_id' => $siswa->id,
            'iduka_id' => $iduka_id,
            'status' => 'proses',
        ]);


        return redirect()->route('siswa.dashboard')->with('success', 'Pengajuan PKL berhasil diajukan.');
    }


    // IDUKA menerima atau menolak pengajuan
    public function verifikasi(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:diterima,ditolak']);

        $pengajuan = PengajuanPkl::findOrFail($id);
        $pengajuan->update(['status' => $request->status]);

        return back()->with('success', 'Pengajuan berhasil diperbarui.');
    }

    // Persuratan melihat semua pengajuan
    public function index()
    {
        $pengajuan = PengajuanPkl::with(['siswa', 'iduka'])->get();
        return view('pengajuan.index', compact('pengajuan'));
    }

    // Download Capaian Pembelajaran (CP)
    public function downloadCp($iduka_id)
    {
        $iduka = Iduka::with('cp')->findOrFail($iduka_id);

        // Asumsikan CP berupa file dalam database
        if ($iduka->cp && file_exists(storage_path('app/' . $iduka->cp->file))) {
            return response()->download(storage_path('app/' . $iduka->cp->file));
        }

        return back()->with('error', 'File CP tidak ditemukan.');
    }

    public function reviewPengajuan()
    {
        $iduka_id = auth()->user()->iduka_id;
        $iduka = Iduka::findOrFail($iduka_id);
        //$sisa_kuota = $iduka->kuota_pkl - PengajuanPkl::where('iduka_id', $iduka_id)
            //->where('status', 'diterima')
            //->count();
        $sisa_kuota = $iduka->kuota_pkl - $iduka->kuota_terisi;

        // Ambil hanya pengajuan yang statusnya "proses"
        $pengajuans = PengajuanPkl::with(['dataPribadi.user', 'dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'proses')
            ->get();


        return view('pengajuan.review', compact('pengajuans', 'iduka', 'sisa_kuota', 'iduka_id'));
    }

    public function showPengajuan($id)
    {
        $pengajuan = PengajuanPkl::with(['dataPribadi.kelas', 'iduka'])->findOrFail($id);
        return view('pengajuan.detail', compact('pengajuan'));
    }


    public function reviewPengajuanDiterima()
    {
        $iduka_id = auth()->user()->iduka_id;

        $pengajuans = PengajuanPkl::with(['dataPribadi.user', 'dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'diterima') // Hanya ambil yang diterima
            ->get();

        return view('pengajuan.historyditerima', compact('pengajuans'));
    }

    public function reviewPengajuanDitolak()
    {
        $iduka_id = auth()->user()->iduka_id;

        $pengajuans = PengajuanPkl::with(['dataPribadi.user', 'dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->where('status', 'ditolak') // Hanya ambil yang ditolak
            ->get();

        return view('pengajuan.historyditolak', compact('pengajuans'));
    }

    public function terima($id)
{
    $pengajuan = PengajuanPkl::findOrFail($id);

    // Pastikan hanya memproses jika belum diterima sebelumnya
    if ($pengajuan->status !== 'diterima') {
        $iduka = Iduka::findOrFail($pengajuan->iduka_id);

        // Cek apakah kuota tersedia
	$sisa_kuota = $iduka->kuota_pkl  - $iduka->kuota_terisi;
        if ($sisa_kuota > 0) {
            // Kurangi kuota IDUKA
            //$iduka->decrement('kuota_pkl');
           
            $tahunAjaran = '2';

            $terisi = $pengajuan
                ::where('status', 'diterima')
                ->whereHas('DataPribadi', function ($query) use ($tahunAjaran) {
                    $query->where('tahun_ajaran_id', $tahunAjaran);
                })
                ->count();

            $sisa = $iduka->kuota_pkl - $terisi;
            $iduka->increment('kuota_terisi');

            // Ubah status pengajuan menjadi diterima
            $pengajuan->update(['status' => 'diterima']);

            // ? Ambil data user terkait siswa
            $user = \App\Models\User::find($pengajuan->siswa_id);
            if ($user) {
                $idukaLama = $user->iduka_id; // simpan iduka lama sebelum update

                // ? Update iduka_id siswa di tabel users
                $user->update(['iduka_id' => $pengajuan->iduka_id]);
                
            }

            // Update status di pengajuan_usulans atau usulan_idukas
            $updated = false;

            $usulan = \App\Models\PengajuanUsulan::where('user_id', $pengajuan->siswa_id)
                ->where('iduka_id', $pengajuan->iduka_id)
                ->first();

            if ($usulan) {
                $usulan->status = 'diterima';
                $usulan->save();
                $updated = true;
                Log::info('Status PengajuanUsulan updated', ['id' => $usulan->id, 'status' => $usulan->status]);
            } else {
                $usulanIduka = \App\Models\UsulanIduka::where('user_id', $pengajuan->siswa_id)
                    ->latest()
                    ->first();

                if ($usulanIduka) {
                    $usulanIduka->status = 'diterima';
                    $usulanIduka->save();
                    $updated = true;
                    Log::info('Status UsulanIduka updated', ['id' => $usulanIduka->id, 'status' => $usulanIduka->status]);
                }
            }

            return redirect()->route('pengajuan.review')
                ->with('success', 'Pengajuan PKL diterima, kuota dikurangi, data siswa & history PKL diperbarui.');
        } else {
            // Jika kuota kosong
            return redirect()->route('iduka.pribadi')
                ->with('error', 'IDUKA belum mengisi kuota PKL.');
        }
    }

    return redirect()->route('pengajuan.review')
        ->with('info', 'Pengajuan sudah diterima sebelumnya.');
}

    public function tolak($id)
    {
        $pengajuan = PengajuanPkl::findOrFail($id);

        if ($pengajuan->status !== 'ditolak') {
            $pengajuan->update(['status' => 'ditolak']);

            // Cek dan update status di pengajuan_usulans atau usulan_idukas
            $updated = false;

            $usulan = \App\Models\PengajuanUsulan::where('user_id', $pengajuan->siswa_id)
                ->where('iduka_id', $pengajuan->iduka_id)
                ->first();

            if ($usulan) {
                $usulan->status = 'ditolak';
                $usulan->save();
                $updated = true;
            } else {
                $usulanIduka = \App\Models\UsulanIduka::where('user_id', $pengajuan->siswa_id)
                    ->where('iduka_id', $pengajuan->iduka_id)
                    ->first();

                if ($usulanIduka) {
                    $usulanIduka->status = 'ditolak';
                    $usulanIduka->save();
                    $updated = true;
                }
            }

            return redirect()->route('pengajuan.review')->with('success', 'Pengajuan PKL telah ditolak.');
        }

        return redirect()->route('pengajuan.review')->with('info', 'Pengajuan sudah ditolak sebelumnya.');
    }

    public function ajukanPembatalan($id)
    {
        Log::info("Mulai ajukan pembatalan untuk ID: $id");

        // Cek di pengajuan_usulans terlebih dahulu
        $pengajuan = PengajuanUsulan::find($id);
        $tipe = null;

        // Jika data ditemukan di pengajuan_usulans dan statusnya bisa dibatalkan
        if ($pengajuan && in_array($pengajuan->status, ['proses', 'diterima'])) {
            $tipe = 'pengajuan';
            Log::info("Data aktif ditemukan di: pengajuan_usulans. Status: " . $pengajuan->status);
        } else {
            // Jika data tidak ada di pengajuan_usulans, cek di usulan_idukas
            $pengajuan = UsulanIduka::find($id);
            if ($pengajuan && in_array($pengajuan->status, ['proses', 'diterima'])) {
                $tipe = 'usulan';
                Log::info("Data aktif ditemukan di: usulan_idukas. Status: " . $pengajuan->status);
            } else {
                Log::warning("Data tidak valid ditemukan di kedua tabel. ID: $id");
                return redirect()->back()->with('error', 'Data pengajuan tidak valid atau tidak ditemukan.');
            }
        }

        // Update status pengajuan menjadi 'menunggu'
        $pengajuan->status = 'menunggu';
        $pengajuan->save();
        Log::info("Status pengajuan di tabel $tipe diubah menjadi 'menunggu'.");

        // Periksa apakah data siswa ada di cetak_usulans
        $cetak = CetakUsulan::where('siswa_id', $pengajuan->user_id)
            ->where('iduka_id', $pengajuan->iduka_id)
            ->first();

        // Jika siswa ditemukan di cetak_usulans, ubah status 'dikirim' menjadi 'menunggu'
        if ($cetak) {
            $cetak->dikirim = 'menunggu';
            $cetak->save();
            Log::info("Status 'dikirim' di cetak_usulans berhasil diubah menjadi 'menunggu'.");
        } else {
            // Jika siswa tidak ditemukan di cetak_usulans, tidak ada perubahan di tabel cetak_usulans
            Log::info("Siswa tidak ditemukan di tabel cetak_usulans, hanya status di pengajuan/usulan yang diubah.");
        }

        return redirect()->back()->with('success', 'Permintaan pembatalan berhasil diajukan.');
    }





    public function listPembatalan()
    {
        $pengajuans = PengajuanUsulan::where('status', 'menunggu pembatalan')->with('dataPribadi')->get();
        return view('kaprog.pengajuan.pembatalan', compact('pengajuans'));
    }

    public function setujuiPembatalan($id)
    {
        $pengajuan = PengajuanUsulan::findOrFail($id);
        $pengajuan->status = 'batal';
        $pengajuan->save();

        DB::table('cetak_usulans')->where('pengajuan_id', $id)->update(['kirim' => 'batal']);

        return redirect()->back()->with('success', 'Pengajuan berhasil dibatalkan.');
    }


    public function tolakPembatalan($id)
    {
        $pengajuan = PengajuanUsulan::findOrFail($id);
        $pengajuan->status = 'diterima'; // atau kembalikan ke sebelumnya
        $pengajuan->save();

        DB::table('cetak_usulans')->where('pengajuan_id', $id)->update(['kirim' => 'sudah']);

        return redirect()->back()->with('info', 'Pembatalan ditolak, status dikembalikan.');
    }
}
