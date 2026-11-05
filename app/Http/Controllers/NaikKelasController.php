<?php

namespace App\Http\Controllers;

use App\Models\DataPribadi;
use App\Models\Kelas;
use App\Models\RiwayatKelas;
use App\Models\tahunAjaran;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class NaikKelasController extends Controller
{
    /**
     * Cari kelas XII yang namanya cocok dengan kelas XI ini, dengan mencocokkan
     * "sisa nama" setelah tingkatnya dihapus. Contoh: "XI RPL 1" -> "RPL 1" -> cari
     * kelas XII yang sisa namanya juga "RPL 1" -> ketemu "XII RPL 1".
     *
     * Sengaja TIDAK mencocokkan lewat konke_id, karena di data kelas GIM dan RPL
     * kebetulan berbagi konke_id yang sama, jadi konke_id saja tidak cukup unik.
     */
    private function cariKelasTujuan($namaKelasXI, $daftarKelasXII)
    {
        $sisaNama = trim(preg_replace('/^XI\s+/', '', $namaKelasXI));

        return $daftarKelasXII->first(function ($xii) use ($sisaNama) {
            $sisaNamaXii = trim(preg_replace('/^XII\s+/', '', $xii->name_kelas));
            return strcasecmp($sisaNamaXii, $sisaNama) === 0;
        });
    }

    /**
     * Halaman preview: daftar kelas XI (akan naik ke XII) dan kelas XII (akan lulus/alumni),
     * lengkap dengan jumlah siswa aktif dan target kenaikan yang dicocokkan otomatis dari nama kelas.
     */
    public function index()
    {
        $kelasXI = Kelas::where('kelas', 'XI')
            ->withCount(['siswa' => function ($q) {
                $q->where('role', 'siswa')->where('status', 'aktif');
            }])
            ->with('konke')
            ->orderBy('name_kelas')
            ->get();

        $kelasXII = Kelas::where('kelas', 'XII')
            ->withCount(['siswa' => function ($q) {
                $q->where('role', 'siswa')->where('status', 'aktif');
            }])
            ->with('konke')
            ->orderBy('name_kelas')
            ->get();

        // Cocokkan tiap kelas XI ke kelas XII tujuan secara otomatis berdasarkan nama
        $pemetaan = $kelasXI->map(function ($xi) use ($kelasXII) {
            return [
                'kelas' => $xi,
                'tujuan' => $this->cariKelasTujuan($xi->name_kelas, $kelasXII),
            ];
        });

        $adaYangTidakKetemu = $pemetaan->contains(fn($p) => $p['tujuan'] === null);

        $tahunAjaran = tahunAjaran::orderBy('tahun_ajaran', 'desc')->get();

        $jumlahCalonAlumni = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->whereHas('kelas', fn($q) => $q->where('kelas', 'XII'))
            ->count();

        $jumlahCalonNaik = User::where('role', 'siswa')
            ->where('status', 'aktif')
            ->whereHas('kelas', fn($q) => $q->where('kelas', 'XI'))
            ->count();

        return view('hubin.naikKelas.index', compact(
            'pemetaan',
            'adaYangTidakKetemu',
            'kelasXII',
            'tahunAjaran',
            'jumlahCalonAlumni',
            'jumlahCalonNaik'
        ));
    }

    /**
     * Proses naik kelas:
     *  1. Semua siswa aktif di kelas XII -> jadi alumni (di-snapshot dulu sebelum kelas XI dinaikkan,
     *     supaya siswa XI yang baru naik ke XII tidak ikut ke-lulus-kan di proses yang sama).
     *  2. Semua siswa aktif di kelas XI -> pindah ke kelas XII, tujuannya dihitung ULANG di sini
     *     (bukan dari input form) supaya tidak bisa diutak-atik dari luar dan selalu konsisten
     *     dengan yang ditampilkan di halaman preview.
     * Semua dibungkus dalam satu transaction supaya tidak ada data setengah jalan kalau gagal.
     */
    public function proses(Request $request)
    {
        $request->validate([
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
        ]);

        $tahunAjaranId = $request->tahun_ajaran_id;
        $tahunAjaranBaru = tahunAjaran::findOrFail($tahunAjaranId);
        $pelakuId = Auth::id();

        // Hitung ulang mapping otomatis di sini, sumber kebenaran satu-satunya
        $kelasXI = Kelas::where('kelas', 'XI')->get();
        $kelasXII = Kelas::where('kelas', 'XII')->get();

        $mapping = []; // kelas_lama_id => kelas_baru_id
        $tidakKetemu = [];

        foreach ($kelasXI as $xi) {
            $tujuan = $this->cariKelasTujuan($xi->name_kelas, $kelasXII);
            if (!$tujuan) {
                $tidakKetemu[] = $xi->name_kelas;
                continue;
            }
            $mapping[$xi->id] = $tujuan->id;
        }

        if (!empty($tidakKetemu)) {
            return redirect()->back()->with(
                'error',
                'Proses dibatalkan, kelas berikut belum punya pasangan kelas XII dengan nama yang cocok: ' .
                implode(', ', $tidakKetemu) . '. Buat dulu kelas XII-nya lewat menu Data Kelas.'
            );
        }

        try {
            DB::transaction(function () use ($mapping, $tahunAjaranId, $tahunAjaranBaru, $pelakuId) {

                // ===== 1) SNAPSHOT & LULUSKAN SISWA KELAS XII =====
                $calonAlumni = User::where('role', 'siswa')
                    ->where('status', 'aktif')
                    ->whereHas('kelas', fn($q) => $q->where('kelas', 'XII'))
                    ->get(['id', 'kelas_id']);

                foreach ($calonAlumni as $siswa) {
                    User::where('id', $siswa->id)->update([
                        'status' => 'alumni',
                        'tahun_lulus' => $tahunAjaranBaru->tahun_ajaran,
                        'kelas_id' => null, // kelas dikosongkan setelah jadi alumni
                    ]);

                    DataPribadi::where('user_id', $siswa->id)->update([
                        'kelas_id' => null,
                    ]);

                    RiwayatKelas::create([
                        'user_id' => $siswa->id,
                        'kelas_lama_id' => $siswa->kelas_id, // tetap simpan kelas terakhir sebelum dikosongkan, untuk histori
                        'kelas_baru_id' => null,
                        'tahun_ajaran_id' => $tahunAjaranId,
                        'jenis' => 'lulus',
                        'dilakukan_oleh' => $pelakuId,
                    ]);
                }

                // ===== 2) NAIKKAN SISWA KELAS XI SESUAI MAPPING =====
                foreach ($mapping as $kelasLamaId => $kelasBaruId) {
                    $siswaXI = User::where('role', 'siswa')
                        ->where('status', 'aktif')
                        ->where('kelas_id', $kelasLamaId)
                        ->get(['id']);

                    foreach ($siswaXI as $siswa) {
                        User::where('id', $siswa->id)->update([
                            'kelas_id' => $kelasBaruId,
                        ]);

                        // Samakan juga data_pribadis supaya tidak beda dengan users
                        DataPribadi::where('user_id', $siswa->id)->update([
                            'kelas_id' => $kelasBaruId,
                            'tahun_ajaran_id' => $tahunAjaranId,
                        ]);

                        RiwayatKelas::create([
                            'user_id' => $siswa->id,
                            'kelas_lama_id' => $kelasLamaId,
                            'kelas_baru_id' => $kelasBaruId,
                            'tahun_ajaran_id' => $tahunAjaranId,
                            'jenis' => 'naik',
                            'dilakukan_oleh' => $pelakuId,
                        ]);
                    }
                }
            });
        } catch (\Throwable $e) {
            \Log::error('Gagal proses naik kelas: ' . $e->getMessage());

            return redirect()->back()->with(
                'error',
                'Proses naik kelas gagal dan sudah dibatalkan (tidak ada data yang berubah). ' .
                'Detail: ' . $e->getMessage()
            );
        }

        return redirect()->back()->with('success', 'Proses naik kelas & kelulusan berhasil dilakukan.');
    }
}
