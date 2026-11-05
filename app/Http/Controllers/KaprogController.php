<?php

namespace App\Http\Controllers;

use App\Exports\DataAbsenKaprog;
use App\Models\Absensi;
use App\Models\Cp;
use App\Models\Atp;
use App\Models\CetakUsulan;
use App\Models\User;
use App\Models\Iduka;
use App\Models\IdukaHoliday;
use App\Models\Kelas;
use App\Models\PengajuanPkl;
use App\Models\UsulanIduka;
use Illuminate\Http\Request;
use App\Models\PengajuanUsulan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Facades\Excel;

class KaprogController extends Controller
{

    public function reviewUsulan()
    {
        $user = Auth::user();

        $kaprog = DB::table('gurus')->where('user_id', $user->id)->first();
        if (!$kaprog) {
            return redirect()->back()->with('error', 'Data Kaprog tidak ditemukan.');
        }

        // Menggunakan paginate untuk mendapatkan hasil yang terpisah ke dalam halaman
        $usulanIdukas = UsulanIduka::with(['user.dataPribadi.kelas'])
            ->whereIn('status', ['proses', 'menunggu'])
            ->where('konke_id', $kaprog->konke_id)
            ->paginate(10);  // Gunakan paginate

        $groupedIduka = $usulanIdukas->groupBy('email');

        // Ambil semua data pengajuan PKL (tanpa paginate), untuk menghitung jumlah total siswa per IDUKA
        $pengajuanUsulanSemua = PengajuanUsulan::with('iduka')
            ->whereIn('status', ['proses', 'menunggu'])
            ->where('konke_id', $kaprog->konke_id)
            ->get()
            ->groupBy('iduka_id');

        // Ambil data paginate hanya untuk tampilan daftar
        $pengajuanUsulans = PengajuanUsulan::with(['user.dataPribadi.kelas', 'iduka'])
            ->whereIn('status', ['proses', 'menunggu'])
            ->where('konke_id', $kaprog->konke_id)
            ->paginate(10);

        return view('kaprog.review.reviewusulan', compact('usulanIdukas', 'pengajuanUsulans', 'groupedIduka', 'pengajuanUsulanSemua'));
    }
    public function detailPengajuanSiswa($pengajuanId)
    {
        $usulan = PengajuanUsulan::with(['user.dataPribadi.kelas', 'user.dataPribadi.konkes', 'iduka'])->findOrFail($pengajuanId);
        return view('kaprog.review.detailpengajuansiswa', compact('usulan'));
    }

    public function show($id)
    {
        $usulan = UsulanIduka::with(['user.dataPribadi.konkes', 'user.dataPribadi.kelas', 'iduka'])
            ->findOrFail($id);

        return view('kaprog.review.
        lanpkl', compact('usulan'));
    }


    // public function showUsulan($id)
    // {
    //     $usulan = PengajuanUsulan::with(['user.dataPribadi.konkes', 'user.dataPribadi.kelas', 'iduka'])
    //         ->findOrFail($id);

    //     return view('kaprog.review.detailusulanpkl', compact('usulan'));
    // }

    public function prosesPengajuan($id, Request $request)
    {
        // Validasi iduka_id
        $request->validate([
            'iduka_id' => 'required|exists:idukas,id',
        ]);

        // Ambil data cetak_usulan berdasarkan ID
        $cetak = CetakUsulan::findOrFail($id);

        // Cek duplikat: pastikan siswa + iduka belum pernah di-push ke pengajuan_pkl
        $duplikat = PengajuanPkl::where('siswa_id', $cetak->data_pribadi_id)
            ->where('iduka_id', $request->iduka_id)
            ->exists();

        if ($duplikat) {
            return redirect()->back()->with('info', 'Data sudah dikirim sebelumnya.');
        }

        // Simpan ke tabel pengajuan_pkl
        PengajuanPkl::create([
            'siswa_id' => $cetak->siswa_id, // pastikan ini sesuai dengan struktur
            'iduka_id' => $request->iduka_id,
            'status' => 'proses', // default status ketika baru dibuat
        ]);

        // Update kolom dikirim di cetak_usulans
        $cetak->update([
            'dikirim' => 'sudah',
        ]);

        return redirect()->back()->with('success', 'Pengajuan berhasil dikirim.');
    }

    public function diterima($id, Request $request)
    {
        $usulan = UsulanIduka::findOrFail($id);


        // Buat akun user untuk IDUKA terlebih dahulu
        $user = User::create([
            'name' => $usulan->nama,
            'nip' => $usulan->email,
            'password' => $usulan->password, // sudah di-hash dari awal
            'role' => 'iduka',
        ]);


        // Buat data di tabel idukas dan arahkan ke user_id dari akun IDUKA
        $iduka = Iduka::create([
            'user_id' => $user->id, // <- ini sekarang menunjuk ke user ID dari akun IDUKA
            'nama' => $usulan->nama,
            'nama_pimpinan' => $usulan->nama_pimpinan,
            'nip_pimpinan' => $usulan->nip_pimpinan,
            'jabatan' => $usulan->jabatan,
            'alamat' => $usulan->alamat,
            'kode_pos' => $usulan->kode_pos,
            'telepon' => $usulan->telepon,
            'email' => $usulan->email,
            'bidang_industri' => $usulan->bidang_industri,
            'kerjasama' => $usulan->kerjasama,
            'password' => $usulan->password,
            'kuota_pkl' => $usulan->kuota_pkl ?? 0,
        ]);



        // Tambahkan iduka_id ke user agar relasi lengkap (jika pakai relasi ke iduka)
        $user->update(['iduka_id' => $iduka->id]);

        // Update status usulan
        $usulan->update(['status' => 'diterima', 'iduka_id' => $iduka->id]);

        return redirect()->route('review.usulan')->with('success', 'Usulan IDUKA diterima dan akun pengguna berhasil dibuat.');
    }

    public function ditolak($id)
    {
        $usulan = UsulanIduka::findOrFail($id);
        $usulan->update(['status' => 'ditolak']);

        return redirect()->route('review.usulan')->with('error', 'Usulan IDUKA ditolak.');
    }

    public function diterimaUsulan(Request $request, $id)
    {
        $request->validate(['status' => 'required|in:diterima,ditolak']);

        $usulan = PengajuanUsulan::findOrFail($id);

        $usulan->update(['status' => $request->status]);


        $msg = $request->status === 'diterima' ? 'Pengajuan PKL diterima.' : 'Pengajuan PKL ditolak.';
        $type = $request->status === 'diterima' ? 'success' : 'error';

        return redirect()->route('review.usulan', $usulan->iduka_id)

            ->with($type, $msg);
    }

    public function historyDiterima()
    {
        $kaprog = DB::table('gurus')->where('user_id', Auth::id())->first();
        if (!$kaprog) {
            return redirect()->back()->with('error', 'Data Kaprog tidak ditemukan.');
        }

        // Ambil data dari UsulanIduka
        $usulanDiterima = UsulanIduka::with(['user.dataPribadi.kelas'])
                    ->where('status', 'diterima')
                    ->where('konke_id', $kaprog->konke_id)
                    // Tambahkan filter ini untuk memastikan siswanya berstatus aktif
                    ->whereHas('user', function ($query) {
                        $query->where('status', 'aktif')
                            ->where('role', 'siswa');
                    })
                    ->orderByRaw("CASE WHEN surat_izin = 'belum' THEN 0 ELSE 1 END")
                    ->latest()
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'usulan';
                        return $item;
                    });

        // Ambil data dari PengajuanUsulan
        $usulanDiterimaPkl = PengajuanUsulan::with(['user.kelas', 'iduka']) // Sesuaikan relasi 'user.kelas' jika di model pakai 'kelas' langsung
                    ->where('status', 'diterima')
                    ->where('konke_id', $kaprog->konke_id)
                    // Tambahkan filter ini untuk memastikan siswanya berstatus aktif
                    ->whereHas('user', function ($query) {
                        $query->where('status', 'aktif')
                            ->where('role', 'siswa');
                    })
                    ->orderByRaw("CASE WHEN surat_izin = 'belum' THEN 0 ELSE 1 END")
                    ->latest()
                    ->get()
                    ->map(function ($item) {
                        $item->tipe = 'pkl';
                        return $item;
                    });

        // Gabungkan dan urutkan berdasarkan surat_izin dulu, lalu tanggal
        $combined = $usulanDiterima->concat($usulanDiterimaPkl)
            ->sortBy([
                fn($a, $b) => strcmp($a->surat_izin, $b->surat_izin), // surat_izin 'belum' dulu
                fn($a, $b) => $b->created_at <=> $a->created_at       // terbaru dulu
            ])
            ->values();

        // Paginate manual
        $perPage = 10;
        $currentPage = LengthAwarePaginator::resolveCurrentPage();
        $currentItems = $combined->slice(($currentPage - 1) * $perPage, $perPage)->values();

        $paginated = new LengthAwarePaginator(
            $currentItems,
            $combined->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('kaprog.review.historyditerima', compact('paginated'));
    }

    public function historyDitolak()
    {
        $kaprog = DB::table('gurus')->where('user_id', Auth::id())->first();
        if (!$kaprog) {
            return redirect()->back()->with('error', 'Data Kaprog tidak ditemukan.');
        }

        // Ambil data usulan dari UsulanIduka
        $usulanDitolak = UsulanIduka::with(['user.dataPribadi.kelas'])
            ->where('status', 'ditolak')
            ->whereHas('user', function ($query) {
                $query->where('status', 'aktif')
                ->where('role', 'siswa');
            })
            ->where('konke_id', $kaprog->konke_id)
            ->get();

        // Ambil data usulan dari PengajuanUsulan
        $usulanDitolakPkl = PengajuanUsulan::with(['user.dataPribadi.kelas', 'iduka'])
            ->where('status', 'ditolak')
            ->whereHas('user', function ($query) {
                $query->where('status', 'aktif')
                ->where('role', 'siswa');
            })
            ->where('konke_id', $kaprog->konke_id)
            ->get();

        // Gabungkan semua data dan urutkan berdasarkan tanggal terbaru
        $combined = $usulanDitolak->concat($usulanDitolakPkl)->sortByDesc('created_at')->values();

        // Manual pagination
        $perPage = 10;
        $currentPage = request()->get('page', 1);
        $paginated = new LengthAwarePaginator(
            $combined->forPage($currentPage, $perPage),
            $combined->count(),
            $perPage,
            $currentPage,
            ['path' => request()->url(), 'query' => request()->query()]
        );

        return view('kaprog.review.historyditolak', compact('paginated'));
    }



    // public function showDetailUsulan($id)
    // {
    //     $usulan = UsulanIduka::with('user.dataPribadi.kelas', 'user.dataPribadi.konkes')
    //                 ->findOrFail($id);

    //     return view('kaprog.review.detailusulan', compact('usulan'));
    // }




    public function showDetailPengajuanIduka($iduka_id)
    {
        $iduka = Iduka::findOrFail($iduka_id);
        $pengajuans = PengajuanUsulan::where('iduka_id', $iduka_id)
            ->where('status', 'proses') // hanya yang masih dalam proses
            ->with(['user.dataPribadi.kelas']) // eager load user, data pribadi, dan kelas
            ->get();




        return view('kaprog.review.detailpengajuaniduka', compact('iduka', 'pengajuans'));
    }

    //fungsi mengirim ke tabel pengajuan jika siswa sudah mengirim surat
    public function updateSuratIzin(Request $request, $id): JsonResponse
    {
        Log::info('Update Surat Izin', [
            'request' => $request->all(),
            'usulan_id' => $id,
            'tipe' => $request->input('tipe'),
        ]);

        $tipe = $request->input('tipe');

        if ($tipe === 'usulan') {
            $usulan = UsulanIduka::with('user')->find($id);
            if (!$usulan) {
                return response()->json(['success' => false, 'message' => 'Data usulan tidak ditemukan.']);
            }

            if ($usulan->surat_izin == 'belum') {
                $usulan->update(['surat_izin' => 'sudah']);

                CetakUsulan::create([
                    'siswa_id' => $usulan->user_id,
                    'iduka_id' => $usulan->iduka_id,
                    'status' => 'proses',
                ]);

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Surat izin sudah diubah sebelumnya.']);
            }
        }

        if ($tipe === 'pkl') {
            $usulan = PengajuanUsulan::with('user')->find($id);
            if (!$usulan) {
                return response()->json(['success' => false, 'message' => 'Data PKL tidak ditemukan.']);
            }

            if ($usulan->surat_izin == 'belum') {
                $usulan->update(['surat_izin' => 'sudah']);

                CetakUsulan::create([
                    'siswa_id' => $usulan->user_id,
                    'iduka_id' => $usulan->iduka_id,
                    'status' => 'proses',
                ]);

                return response()->json(['success' => true]);
            } else {
                return response()->json(['success' => false, 'message' => 'Surat izin PKL sudah diperbarui sebelumnya.']);
            }
        }

        return response()->json(['success' => false, 'message' => 'Tipe permintaan tidak valid.']);
    }

    //mengirim ke iduka
    public function reviewPengajuan()
    {
        $userKonkeId = auth()->user()->konke_id; // ambil konke_id dari Kaprog yang login

        $pengajuanUsulans = CetakUsulan::with(['dataPribadi.kelas', 'user', 'iduka'])
            ->whereIn('dikirim', ['belum', 'menunggu'])
            ->whereHas('user', function ($query) use ($userKonkeId) {
                $query->where('konke_id', $userKonkeId);
            })
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('kaprog.review.reviewpengajuan', compact('pengajuanUsulans'));
    }


    public function detailUsulanPkl($iduka_id)
    {
        $userKonkeId = auth()->user()->konke_id;

        $iduka = Iduka::findOrFail($iduka_id);

        $pengajuans = CetakUsulan::with(['dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->whereIn('dikirim', ['belum', 'menunggu'])
            ->whereHas('user', function ($query) use ($userKonkeId) {
                $query->where('konke_id', $userKonkeId);
            })
            ->get();

        return view('kaprog.review.reviewdetail', compact('iduka', 'pengajuans'));
    }





    public function detailusulan($iduka_id)
    {
        $pengajuanUsulans = PengajuanUsulan::with(['dataPribadi.kelas'])
            ->where('iduka_id', $iduka_id)
            ->whereIn('status', ['proses', 'menunggu'])
            ->get();

        return view('kaprog.review.detailusulan', compact('pengajuanUsulans'));
    }

    public function kirimSemua($iduka_id)
    {
        // Cek jika ada siswa yang mengajukan pembatalan
        $adaPembatalan = CetakUsulan::where('iduka_id', $iduka_id)
            ->where('dikirim', 'menunggu')
            ->exists();

        if ($adaPembatalan) {
            return redirect()->back()->with('error', 'Tidak dapat mengirim karena ada siswa yang sedang mengajukan pembatalan.');
        }
        // Ambil semua pengajuan untuk IDUKA tersebut yang statusnya sudah 'sudah'
        $cetak = CetakUsulan::where('iduka_id', $iduka_id)
            ->where('status', 'sudah')
            ->get();

        $jumlahTerkirim = 0;

        foreach ($cetak as $pengajuan) {

            $siswaId = $pengajuan->siswa_id;


            // Cek apakah sudah ada di pengajuan_pkl
            $sudahAda = PengajuanPkl::where('siswa_id', $siswaId)
                ->where('iduka_id', $iduka_id)
                ->exists();

            if (!$sudahAda) {
                // Tambahkan ke tabel pengajuan_pkl
                PengajuanPkl::create([
                    'siswa_id' => $siswaId,
                    'iduka_id' => $iduka_id,
                    'status' => 'proses',
                ]);
                // Update status dikirim di cetak_usulans menjadi 'sudah'
                $pengajuan->update([
                    'dikirim' => 'sudah'
                ]);
                $jumlahTerkirim++;
            }
        }

        if ($jumlahTerkirim > 0) {
            return redirect()->back()->with('success', "$jumlahTerkirim pengajuan berhasil dikirim.");
        } else {
            return redirect()->back()->with('info', "Semua pengajuan sudah pernah dikirim.");
        }
    }

    public function historiPengajuan()
    {
        // Ambil semua data pengajuan yang sudah diproses (status bukan 'proses')
        $historiPengajuan = PengajuanPkl::with('iduka', 'siswa')
                    ->where('status', 'diterima')
                    // Tambahkan filter ini untuk menyaring siswa yang statusnya aktif
                    ->whereHas('siswa', function ($query) {
                        $query->where('status', 'aktif');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->get()
                    ->groupBy('iduka_id');

        return view('kaprog.review.histori', compact('historiPengajuan'));
    }

    public function persetujuanPembatalan(Request $request)
    {
        $id = $request->input('id');
        $status = $request->input('status'); // 'batal' atau 'proses'

        // Coba update di pengajuan_usulans dulu
        $pengajuan = PengajuanUsulan::find($id);

        if ($pengajuan) {
            $pengajuan->status = $status;
            $pengajuan->save();
        } else {
            // Kalau tidak ada, coba cari di usulan_idukas
            $usulan = UsulanIduka::find($id);
            if ($usulan) {
                $usulan->status = $status;
                $usulan->save();
            } else {
                return response()->json(['message' => 'Data tidak ditemukan'], 404);
            }
        }


        return response()->json(['message' => 'Status berhasil diperbarui', 'status' => $status]);
    }

    public function terimaPembatalan($id)
    {
        $cetak = CetakUsulan::findOrFail($id);
        $cetak->dikirim = 'batal';
        $cetak->save();

        $pengajuan = PengajuanUsulan::where('user_id', $cetak->siswa_id)
            ->where('iduka_id', $cetak->iduka_id)
            ->first();

        if ($pengajuan) {
            $pengajuan->status = 'batal';
            $pengajuan->save();
        } else {
            $usulan = UsulanIduka::where('user_id', $cetak->siswa_id)
                ->where('iduka_id', $cetak->iduka_id)
                ->first();

            if ($usulan) {
                $usulan->status = 'batal';
                $usulan->save();
            }
        }

        return back()->with('success', 'Pembatalan berhasil diterima.');
    }

    public function tolakPembatalan($id)
    {
        $cetak = CetakUsulan::findOrFail($id);
        $cetak->dikirim = 'belum';
        $cetak->save();

        $pengajuan = PengajuanUsulan::where('user_id', $cetak->siswa_id)
            ->where('iduka_id', $cetak->iduka_id)
            ->first();

        if ($pengajuan) {
            $pengajuan->status = 'diterima';
            $pengajuan->save();
        } else {
            $usulan = UsulanIduka::where('user_id', $cetak->siswa_id)
                ->where('iduka_id', $cetak->iduka_id)
                ->first();

            if ($usulan) {
                $usulan->status = 'diterima';
                $usulan->save();
            }
        }

        return back()->with('success', 'Pembatalan berhasil ditolak.');
    }


    public function toggleVisibility(Iduka $iduka)
    {
        $iduka->update([
            'hidden' => !$iduka->hidden
        ]);

        return back()->with('success', 'Status tampil berhasil diubah!');
    }

    public function dataAbsen()
{
    $kaprog = auth()->user();
    $konkeId = $kaprog->konke_id;
    $today = Carbon::today();

    // Total siswa PKL per jurusan kaprog
    $totalSiswaPKL = User::whereHas('kelas', function ($q) use ($konkeId) {
        $q->where('konke_id', $konkeId);
    })
        ->where('role', 'siswa')
        ->count();

    // Ambil semua ID siswa di jurusan kaprog
    $allSiswaIds = User::whereHas('kelas', function ($q) use ($konkeId) {
        $q->where('konke_id', $konkeId);
    })
        ->where('role', 'siswa')
        ->pluck('id')
        ->toArray();

    // Hadir hari ini
    $hadirHariIni = Absensi::whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->where(function ($q) {
            $q->where('status', 'hadir')
                ->orWhere('status', 'tepat_waktu')
                ->orWhere('status', 'terlambat')
                ->orWhereNotNull('jam_masuk');
        })
        ->distinct('user_id')
        ->count('user_id');

    // Ambil semua user_id yang sudah melakukan aktivitas hari ini dari semua tabel
    $activeUserIds = DB::table('absensi')
        ->whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->select('user_id')
        ->union(
            DB::table('absensi_pending')
                ->whereDate('tanggal', $today)
                ->whereIn('user_id', $allSiswaIds)
                ->select('user_id')
        )
        ->union(
            DB::table('izin_pending')
                ->whereDate('tanggal', $today)
                ->whereIn('user_id', $allSiswaIds)
                ->select('user_id')
        )
        ->union(
            DB::table('dinas_pending')
                ->whereDate('tanggal', $today)
                ->whereIn('user_id', $allSiswaIds)
                ->select('user_id')
        )
        ->pluck('user_id')
        ->toArray();

    // Hitung siswa yang tidak hadir = total siswa - siswa yang sudah aktivitas
    $tidakHadir = $totalSiswaPKL - count($activeUserIds);

    // Hitung siswa yang belum dikonfirmasi (absensi, izin, atau dinas pending)
    $idsSiswaPending = DB::table('absensi_pending as ap')
        ->join('users as u', 'ap.user_id', '=', 'u.id')
        ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
        ->whereDate('ap.tanggal', $today)
        ->where('ap.status_konfirmasi', 'pending')
        ->where('k.konke_id', $konkeId)
        ->pluck('u.id')
        ->toArray();

    $idsSiswaIzin = DB::table('izin_pending as ip')
        ->join('users as u', 'ip.user_id', '=', 'u.id')
        ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
        ->whereDate('ip.tanggal', $today)
        ->where('ip.status_konfirmasi', 'pending')
        ->where('k.konke_id', $konkeId)
        ->pluck('u.id')
        ->toArray();

    $idsSiswaDinas = DB::table('dinas_pending as dp')
        ->join('users as u', 'dp.user_id', '=', 'u.id')
        ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
        ->whereDate('dp.tanggal', $today)
        ->where('dp.status_konfirmasi', 'pending')
        ->where('k.konke_id', $konkeId)
        ->pluck('u.id')
        ->toArray();

    // Gabungkan semua ID siswa yang pending dan hapus duplikat
    $allPendingIds = array_unique(array_merge($idsSiswaPending, $idsSiswaIzin, $idsSiswaDinas));
    $belumDikonfirmasi = count($allPendingIds);

    // Tingkat kehadiran (%)
    $totalAbsenHariIni = max($totalSiswaPKL, 1);
    $tingkatKehadiran = round(($hadirHariIni / $totalAbsenHariIni) * 100, 2);

    // Ambil semua kelas dari jurusan kaprog
    $kelasList = Kelas::with([
        'siswa' => function ($q) {
            $q->where('role', 'siswa');
        }
    ])
        ->withCount([
            'siswa' => function ($q) {
                $q->where('role', 'siswa');
            }
        ])
        ->where('konke_id', $konkeId)
        ->orderBy('kelas', 'asc')
        ->orderBy('name_kelas', 'asc')
        ->get();

    // Ambil absensi hari ini untuk jurusan kaprog saja
    $absensiHariIni = Absensi::with('user')
        ->whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->get();

    // Ambil data pending untuk analisis per kelas
    $absensiPendingData = DB::table('absensi_pending')
        ->whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->get();

    $izinPendingData = DB::table('izin_pending')
        ->whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->get();

    $dinasPendingData = DB::table('dinas_pending')
        ->whereDate('tanggal', $today)
        ->whereIn('user_id', $allSiswaIds)
        ->get();

    // Analisis per kelas
    $kelasAnalisis = $kelasList->map(function ($kelas) use ($absensiHariIni, $absensiPendingData, $izinPendingData, $dinasPendingData) {
        $totalSiswa = $kelas->siswa_count;
        $siswaIdsInKelas = $kelas->siswa->pluck('id')->toArray();

        // Hitung jumlah siswa yang sudah aktivitas (baik sudah absen maupun pending)
        $activeUserIdsInKelas = $absensiHariIni
            ->whereIn('user_id', $siswaIdsInKelas)
            ->pluck('user_id')
            ->merge($absensiPendingData->whereIn('user_id', $siswaIdsInKelas)->pluck('user_id'))
            ->merge($izinPendingData->whereIn('user_id', $siswaIdsInKelas)->pluck('user_id'))
            ->merge($dinasPendingData->whereIn('user_id', $siswaIdsInKelas)->pluck('user_id'))
            ->unique()
            ->toArray();

        $sudahAbsen = count($activeUserIdsInKelas);
        $belumAbsen = $totalSiswa - $sudahAbsen;

        // Hitung yang belum divalidasi (pending)
        $belumDivalidasi = $absensiPendingData->whereIn('user_id', $siswaIdsInKelas)->count() +
                          $izinPendingData->whereIn('user_id', $siswaIdsInKelas)->count() +
                          $dinasPendingData->whereIn('user_id', $siswaIdsInKelas)->count();

        // Hitung masing-masing kategori
        $hadirCount = 0;
        $ijinCount = 0;
        $sakitCount = 0;
        $dinasCount = 0;

        // Proses absensi yang sudah dikonfirmasi
        foreach ($absensiHariIni->whereIn('user_id', $siswaIdsInKelas) as $absensi) {
            if (in_array($absensi->status, ['hadir', 'tepat_waktu', 'terlambat'])) {
                $hadirCount++;
            } elseif ($absensi->status == 'izin') {
                $ijinCount++;
            } elseif ($absensi->status == 'sakit') {
                $sakitCount++;
            } elseif ($absensi->status_dinas == 'disetujui') {
                $dinasCount++;
            }
        }

        // Hitung persentase kehadiran
        $persentase = $totalSiswa > 0 ? round(($hadirCount / $totalSiswa) * 100, 2) : 0;

        return [
            'kelas' => $kelas->name_kelas,
            'total_siswa' => $totalSiswa,
            'sudah_absen' => $sudahAbsen,
            'belum_absen' => $belumAbsen,
            'belum_divalidasi' => $belumDivalidasi,
            'hadir_count' => $hadirCount,
            'ijin_count' => $ijinCount,
            'sakit_count' => $sakitCount,
            'dinas_count' => $dinasCount,
            'persentase' => $persentase,
        ];
    });

    // Data chart distribusi
    $kelasLabels = $kelasAnalisis->pluck('kelas');
    $kelasValues = $kelasAnalisis->pluck('total_siswa');

    // Data detail absensi per kelas
    $detailAbsensiPerKelas = $kelasList->map(function ($kelas) use ($absensiHariIni, $absensiPendingData, $izinPendingData, $dinasPendingData) {
        $siswaList = $kelas->siswa;

        $detailSiswa = $siswaList->map(function ($siswa) use ($absensiHariIni, $absensiPendingData, $izinPendingData, $dinasPendingData) {
            $absensi = $absensiHariIni->firstWhere('user_id', $siswa->id);

            // Cek di tabel pending
            $absensiPending = $absensiPendingData->firstWhere('user_id', $siswa->id);
            $izinPending = $izinPendingData->firstWhere('user_id', $siswa->id);
            $dinasPending = $dinasPendingData->firstWhere('user_id', $siswa->id);

            // Tentukan status dengan benar
            $status = 'belum_absen';
            $keterangan = '-';

            if ($absensi) {
                // Cek status izin/sakit/alfa terlebih dahulu
                if (in_array($absensi->status, ['izin', 'sakit', 'alfa'])) {
                    $status = $absensi->status;
                    $keterangan = $absensi->keterangan_izin ?? $absensi->keterangan ?? '-';
                }
                // Cek jika ada jam masuk
                elseif ($absensi->jam_masuk) {
                    if ($absensi->status == 'terlambat') {
                        $status = 'terlambat';
                    } else {
                        $status = 'hadir';
                    }
                    $keterangan = $absensi->keterangan ?? '-';
                }
                // Cek jika status dinas disetujui
                elseif ($absensi->status_dinas === 'disetujui') {
                    $status = 'dinas';
                    $keterangan = $absensi->keterangan_dinas ?? '-';
                }
            } elseif ($absensiPending) {
                $status = 'pending';
                $keterangan = 'Absensi pending konfirmasi';
            } elseif ($izinPending) {
                $status = 'pending';
                $keterangan = 'Izin pending konfirmasi: ' . ($izinPending->keterangan ?? '');
            } elseif ($dinasPending) {
                $status = 'pending';
                $keterangan = 'Dinas pending konfirmasi: ' . ($dinasPending->keterangan ?? '');
            }

            return [
                'id' => $siswa->id,
                'nama' => $siswa->name,
                'nis' => $siswa->nis,
                'status' => $status,
                'jam_masuk' => $absensi ? $absensi->jam_masuk : null,
                'jam_pulang' => $absensi ? $absensi->jam_pulang : null,
                'keterangan' => $keterangan,
            ];
        });

        return [
            'kelas' => $kelas->name_kelas,
            'siswa' => $detailSiswa,
        ];
    });

    // Ambil data jurusan untuk analisis kehadiran per jurusan
    $jurusanData = $this->getKehadiranJurusan($konkeId);

    // Cari hari libur untuk hari ini (hanya untuk IDUKA yang relevan dengan Kaprog ini)
    try {
        $today = Carbon::today();

        // ambil iduka_id yang ada di daftar siswa jurusan ini
        $idukaIds = User::whereIn('id', $allSiswaIds)
            ->pluck('iduka_id')
            ->unique()
            ->filter()
            ->toArray();

        $holidays = collect();
        if (!empty($idukaIds)) {
            $holidays = IdukaHoliday::whereIn('iduka_id', $idukaIds)
                ->where(function ($q) use ($today) {
                    $q->whereRaw("DATE(date) = ?", [$today->toDateString()])
                      ->orWhere(function ($q2) use ($today) {
                          $q2->where('recurring', true)
                             ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$today->format('m-d')]);
                      });
                })->get();
        }

        $holidaysByIduka = [];
        if ($holidays->isNotEmpty()) {
            $idukas = Iduka::whereIn('id', $holidays->pluck('iduka_id')->unique()->toArray())->get()->keyBy('id');

            foreach ($holidays->groupBy('iduka_id') as $idukaId => $group) {
                $names = $group->map(function ($h) {
                    return $h->name ? $h->name : 'Hari Libur';
                })->toArray();

                $holidaysByIduka[] = [
                    'iduka_id' => $idukaId,
                    'iduka_nama' => isset($idukas[$idukaId]) ? $idukas[$idukaId]->nama : 'IDUKA #' . $idukaId,
                    'holidays' => $names
                ];
            }
        }
    } catch (\Exception $e) {
        \Log::error('Error fetching iduka holidays for kaprog: ' . $e->getMessage());
        $holidaysByIduka = [];
    }

    return view('kaprog.absensi.index', compact(
        'totalSiswaPKL',
        'hadirHariIni',
        'tidakHadir',
        'belumDikonfirmasi',
        'tingkatKehadiran',
        'kelasLabels',
        'kelasValues',
        'kelasAnalisis',
        'detailAbsensiPerKelas',
        'jurusanData',
        'holidaysByIduka'
    ));
}

    // PERBAIKAN: Tambahkan method untuk mendapatkan data kehadiran per jurusan
    private function getKehadiranJurusan($konkeId)
    {
        $jurusanData = DB::table('konkes')
            ->leftJoin('users', 'users.konke_id', '=', 'konkes.id')
            ->leftJoin('absensi', function ($join) {
                $join->on('absensi.user_id', '=', 'users.id')
                    ->whereDate('absensi.tanggal', Carbon::today());
            })
            ->select(
                'konkes.id',
                'konkes.name_konke as jurusan',
                DB::raw('COUNT(DISTINCT users.id) as total_siswa'),
                DB::raw('COUNT(absensi.id) as total_hadir_today')
            )
            ->where('users.role', 'siswa')
            ->where('konkes.id', $konkeId)
            ->groupBy('konkes.id', 'konkes.name_konke')
            ->get();

        $hasil = $jurusanData->map(function ($item) {
            $total_siswa = (int) $item->total_siswa;
            $total_hadir = (int) $item->total_hadir_today;
            $persentase = $total_siswa > 0 ? round(($total_hadir / $total_siswa) * 100) : 0;

            return [
                'jurusan' => $item->jurusan,
                'total_siswa' => $total_siswa,
                'persentase' => $persentase,
            ];
        });

        return $hasil;
    }

    public function export(Request $request)
    {
        $tanggal = $request->input('tanggal', now()->toDateString());
        $konkeId = auth()->user()->konke_id; // pastikan field ini ada

        return Excel::download(new DataAbsenKaprog($tanggal, $konkeId), "absensi_{$tanggal}.xlsx");
    }

    /**
     * 🔹 Helper untuk ambil singkatan jurusan
     */
    private function getSingkatan($jurusan)
    {
        $map = [
            'Rekayasa Perangkat Lunak' => 'RPL',
            'Manajemen Perkantoran dan Lembaga Bisnis' => 'MPLB',
            'Teknik Komputer dan Jaringan' => 'TKJ',
            'Akuntansi dan Keuangan Lembaga' => 'AKL',
            'Desain Pemodelan dan Informasi Bangunan' => 'DPIB',
            'Teknik Kendaraan Ringan' => 'TKR',
            // tambahkan sesuai kebutuhan
        ];

        return $map[$jurusan] ?? $jurusan;
    }

    // Tambahkan method ini di KaprogController.php

    public function getSiswaBelumDikonfirmasi()
    {
        try {
            $kaprog = auth()->user();
            $konkeId = $kaprog->konke_id;

            \Log::info('Memulai getSiswaBelumDikonfirmasi untuk konke_id: ' . $konkeId);

            // Ambil data dari absensi_pending
            $absensiPending = DB::table('absensi_pending as ap')
                ->join('users as u', 'ap.user_id', '=', 'u.id')
                ->leftJoin('idukas as i', 'u.iduka_id', '=', 'i.id')
                ->leftJoin('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
                ->whereDate('ap.tanggal', Carbon::today())
                ->where('ap.status_konfirmasi', 'pending')
                ->where('k.konke_id', $konkeId)
                ->select(
                    'u.id',
                    'u.name',
                    'u.email',
                    'u.iduka_id',
                    'u.pembimbing_id',
                    'ap.jam',
                    DB::raw('COALESCE(i.nama, "-") as iduka_nama'),
                    DB::raw('COALESCE(g.nama, "-") as pembimbing_nama'),
                    DB::raw('"Absensi" as jenis'),
                    DB::raw('"" as keterangan')
                )
                ->get();

            // Ambil data dari izin_pending
            $izinPending = DB::table('izin_pending as ip')
                ->join('users as u', 'ip.user_id', '=', 'u.id')
                ->leftJoin('idukas as i', 'u.iduka_id', '=', 'i.id')
                ->leftJoin('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
                ->whereDate('ip.tanggal', Carbon::today())
                ->where('ip.status_konfirmasi', 'pending')
                ->where('k.konke_id', $konkeId)
                ->select(
                    'u.id',
                    'u.name',
                    'u.email',
                    'u.iduka_id',
                    'u.pembimbing_id',
                    DB::raw('"" as jam'),
                    DB::raw('COALESCE(i.nama, "-") as iduka_nama'),
                    DB::raw('COALESCE(g.nama, "-") as pembimbing_nama'),
                    DB::raw('"Izin" as jenis'),
                    'ip.keterangan'
                )
                ->get();

            // Ambil data dari dinas_pending
            $dinasPending = DB::table('dinas_pending as dp')
                ->join('users as u', 'dp.user_id', '=', 'u.id')
                ->leftJoin('idukas as i', 'u.iduka_id', '=', 'i.id')
                ->leftJoin('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
                ->whereDate('dp.tanggal', Carbon::today())
                ->where('dp.status_konfirmasi', 'pending')
                ->where('k.konke_id', $konkeId)
                ->select(
                    'u.id',
                    'u.name',
                    'u.email',
                    'u.iduka_id',
                    'u.pembimbing_id',
                    DB::raw('"" as jam'),
                    DB::raw('COALESCE(i.nama, "-") as iduka_nama'),
                    DB::raw('COALESCE(g.nama, "-") as pembimbing_nama'),
                    DB::raw('"Dinas" as jenis'),
                    'dp.keterangan'
                )
                ->get();

            // Gabungkan semua data
            $allPending = $absensiPending->concat($izinPending)->concat($dinasPending);

            $data = [];
            foreach ($allPending as $index => $siswa) {
                $data[] = [
                    'no' => $index + 1,
                    'name' => $siswa->name ?? '-',
                    'email' => $siswa->email ?? '-',
                    'iduka' => $siswa->iduka_nama ?? '-',
                    'pembimbing' => $siswa->pembimbing_nama ?? '-',
                    'jenis' => $siswa->jenis ?? '-',
                    'keterangan' => $siswa->keterangan ?? '-',
                    'waktu_absen' => $siswa->jam ? substr($siswa->jam, 0, 5) : '-',
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error di getSiswaBelumDikonfirmasi: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function getSiswaBelumAbsen()
{
    try {
        \Log::info('Memulai getSiswaBelumAbsen');

        $kaprog = auth()->user();
        $konkeId = $kaprog->konke_id;
        $today = Carbon::today();

        // Ambil semua ID siswa di jurusan kaprog
        $allSiswaIds = User::whereHas('kelas', function ($q) use ($konkeId) {
            $q->where('konke_id', $konkeId);
        })
            ->where('role', 'siswa')
            ->pluck('id')
            ->toArray();

        \Log::info('Total siswa PKL: ' . count($allSiswaIds));

        // Ambil semua user_id yang sudah melakukan aktivitas hari ini dari semua tabel
        $activeUserIds = DB::table('absensi')
            ->whereDate('tanggal', $today)
            ->whereIn('user_id', $allSiswaIds)
            ->select('user_id')
            ->union(
                DB::table('absensi_pending')
                    ->whereDate('tanggal', $today)
                    ->whereIn('user_id', $allSiswaIds)
                    ->select('user_id')
            )
            ->union(
                DB::table('izin_pending')
                    ->whereDate('tanggal', $today)
                    ->whereIn('user_id', $allSiswaIds)
                    ->select('user_id')
            )
            ->union(
                DB::table('dinas_pending')
                    ->whereDate('tanggal', $today)
                    ->whereIn('user_id', $allSiswaIds)
                    ->select('user_id')
            )
            ->pluck('user_id')
            ->toArray();

        \Log::info('Total ID siswa yang sudah aktivitas: ' . count($activeUserIds));

        // Query utama: ambil siswa yang tidak ada di daftar user_id yang sudah aktivitas
        $siswaBelumAbsen = User::where('role', 'siswa')
            ->whereIn('users.id', $allSiswaIds) // PERBAIKAN: Tambahkan prefix tabel
            ->whereNotIn('users.id', $activeUserIds) // PERBAIKAN: Tambahkan prefix tabel
            ->leftJoin('idukas', 'users.iduka_id', '=', 'idukas.id')
            ->leftJoin('gurus', 'users.pembimbing_id', '=', 'gurus.id')
            ->select(
                'users.id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(idukas.nama, "-") as iduka_nama'),
                DB::raw('COALESCE(gurus.nama, gurus.nama, "-") as pembimbing_nama')
            )
            ->get();

        \Log::info('Jumlah siswa belum absen: ' . $siswaBelumAbsen->count());

        // Format data
        $data = $siswaBelumAbsen->map(function ($siswa, $index) {
            return [
                'no' => $index + 1,
                'name' => $siswa->name ?? '-',
                'email' => $siswa->email ?? '-',
                'iduka' => $siswa->iduka_nama ?? '-',
                'pembimbing' => $siswa->pembimbing_nama ?? '-',
            ];
        });

        return response()->json($data);
    } catch (\Exception $e) {
        \Log::error('Error di getSiswaBelumAbsen: ' . $e->getMessage());
        \Log::error('Trace: ' . $e->getTraceAsString());
        return response()->json(['error' => $e->getMessage()], 500);
    }
}
}
