<?php

namespace App\Http\Controllers;

use App\Exports\JurusanKehadiranExport;
use App\Models\Absensi;
use App\Models\AbsensiPending;
use App\Models\DinasPending;
use App\Models\IzinPending;
use App\Models\User;
use App\Models\Iduka;
use App\Models\IdukaHoliday;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DataAbsensiController extends Controller
{
    public function index()
    {
        // Hitung total siswa PKL (siswa dengan role siswa dan memiliki iduka_id)
        $totalSiswaPKL = User::where('role', 'siswa')
            ->whereNotNull('iduka_id')
            ->count();

        // Kehadiran hari ini (sudah dikonfirmasi)
        $hadirHariIni = Absensi::whereDate('tanggal', Carbon::today())
            ->distinct('user_id')
            ->count('user_id');

        // Siswa yang sudah absensi tapi masih pending (belum dikonfirmasi)
        // Termasuk absensi biasa, izin, dan dinas yang masih pending
        $belumDikonfirmasi = AbsensiPending::whereDate('tanggal', Carbon::today())
            ->distinct('user_id')
            ->count('user_id') +
            IzinPending::whereDate('tanggal', Carbon::today())
                ->where('status_konfirmasi', 'pending')
                ->distinct('user_id')
                ->count('user_id') +
            DinasPending::whereDate('tanggal', Carbon::today())
                ->where('status_konfirmasi', 'pending')
                ->distinct('user_id')
                ->count('user_id');

        // Siswa yang benar-benar tidak absen (tidak hadir)
        $tidakHadir = $totalSiswaPKL - $hadirHariIni - $belumDikonfirmasi;

        $tingkatKehadiran = $totalSiswaPKL > 0
            ? round(($hadirHariIni / $totalSiswaPKL) * 100, 2)
            : 0;

        // Data 7 hari terakhir
        $mingguan = [];
        for ($i = 6; $i >= 0; $i--) {
            $tanggal = Carbon::today()->subDays($i)->toDateString();

            $hadir = Absensi::whereDate('tanggal', $tanggal)
                ->distinct('user_id')
                ->count('user_id');

            $mingguan[] = [
                'tanggal' => Carbon::parse($tanggal)->translatedFormat('l'),
                'hadir' => $hadir,
            ];
        }

        $jurusanData = $this->getKehadiranJurusan();

        // Cari hari libur untuk hari ini (per IDUKA)
        try {
            $today = \Carbon\Carbon::today();

            // Cek apakah hari ini Minggu
            if ($today->isSunday()) {
                // Ambil semua IDUKA yang aktif
                $allIdukas = Iduka::all();

                $holidaysByIduka = $allIdukas->map(function($iduka) {
                    return [
                        'iduka_id' => $iduka->id,
                        'iduka_nama' => $iduka->nama,
                        'holidays' => ['Hari Minggu (Libur Umum)']
                    ];
                })->values()->toArray();
            } else {
                // Cek hari libur khusus dari database
                $holidays = IdukaHoliday::whereRaw("DATE(date) = ?", [$today->toDateString()])
                    ->orWhere(function ($q) use ($today) {
                        $q->where('recurring', true)
                            ->whereRaw("DATE_FORMAT(date, '%m-%d') = ?", [$today->format('m-d')]);
                    })->get();

                $holidaysByIduka = [];
                if ($holidays->isNotEmpty()) {
                    $idukaIds = $holidays->pluck('iduka_id')->unique()->toArray();
                    $idukas = Iduka::whereIn('id', $idukaIds)->get()->keyBy('id');

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
            }
        } catch (\Exception $e) {
            // Jangan ganggu halaman jika ada error; log dan teruskan tanpa data
            \Log::error('Error fetching iduka holidays: ' . $e->getMessage());
            $holidaysByIduka = [];
        }

        // Top 5 siswa paling rajin (berdasarkan total kehadiran)
        $top5Siswa = DB::table('absensi')
            ->join('users', 'absensi.user_id', '=', 'users.id')
            ->leftJoin('idukas', 'users.iduka_id', '=', 'idukas.id')
            ->select(
                'users.id as user_id',
                'users.name',
                'users.email',
                DB::raw('COALESCE(idukas.nama, "-") as iduka_nama'),
                DB::raw('COUNT(absensi.id) as total_hadir')
            )
            ->groupBy('users.id', 'users.name', 'users.email', 'idukas.nama')
            ->orderByDesc('total_hadir')
            ->take(5)
            ->get();

        return view('data.absensi-siswa.index', compact(
            'totalSiswaPKL',
            'hadirHariIni',
            'belumDikonfirmasi',
            'tidakHadir',
            'tingkatKehadiran',
            'mingguan',
            'jurusanData',
            'holidaysByIduka',
            'top5Siswa'
        ));
    }

    public function chartData(Request $request)
    {
        $filter = $request->get('filter', '7');
        $labels = [];
        $values = [];

        if ($filter === 'today') {
            $tanggal = Carbon::today()->toDateString();
            $hadir = Absensi::whereDate('tanggal', $tanggal)
                ->distinct('user_id')
                ->count('user_id');

            $labels = [Carbon::parse($tanggal)->translatedFormat('d M Y')];
            $values = [$hadir];
        } else {
            $days = (int) $filter;
            for ($i = $days - 1; $i >= 0; $i--) {
                $tanggal = Carbon::today()->subDays($i)->toDateString();

                $hadir = Absensi::whereDate('tanggal', $tanggal)
                    ->distinct('user_id')
                    ->count('user_id');

                $labels[] = Carbon::parse($tanggal)->translatedFormat('d M');
                $values[] = $hadir;
            }
        }

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    // PERBAIKAN: Menggunakan kolom yang benar
    public function getJurusanChart()
    {
        $data = DB::table('users')
            ->join('konkes', 'users.konke_id', '=', 'konkes.id')
            ->select('konkes.name_konke as jurusan', DB::raw('COUNT(users.id) as total'))
            ->where('users.role', 'siswa')
            ->groupBy('konkes.name_konke')
            ->get();

        $labels = $data->pluck('jurusan');
        $values = $data->pluck('total');

        return response()->json([
            'labels' => $labels,
            'values' => $values
        ]);
    }

    public function getKehadiranJurusan()
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
                DB::raw('COUNT(DISTINCT absensi.user_id) as total_hadir_today') // Perbaikan di sini
            )
            ->where('users.role', 'siswa')
            ->groupBy('konkes.id', 'konkes.name_konke')
            ->get();

        $hasil = $jurusanData->map(function ($item) {
            $total_siswa = (int) $item->total_siswa;
            $total_hadir = (int) $item->total_hadir_today;
            $persentase = $total_siswa > 0 ? round(($total_hadir / $total_siswa) * 100) : 0;

            return [
                'jurusan' => $item->jurusan,
                'total_siswa' => $total_siswa,
                'jumlah_hadir' => $total_hadir, // Tambahkan ini
                'persentase' => $persentase,
            ];
        });

        return $hasil;
    }

    public function exportJurusan()
    {
        return Excel::download(new JurusanKehadiranExport(), 'kehadiran_jurusan.xlsx');
    }

    public function getSiswaBelumDikonfirmasi()
    {
        try {
            // Ambil data dari absensi_pending
            $absensiPending = DB::table('absensi_pending as ap')
                ->join('users as u', 'ap.user_id', '=', 'u.id')
                ->leftJoin('idukas as i', 'u.iduka_id', '=', 'i.id')
                ->leftJoin('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->whereDate('ap.tanggal', Carbon::today())
                ->where('ap.status_konfirmasi', 'pending')
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
                ->whereDate('ip.tanggal', Carbon::today())
                ->where('ip.status_konfirmasi', 'pending')
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
                ->whereDate('dp.tanggal', Carbon::today())
                ->where('dp.status_konfirmasi', 'pending')
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

    // Method untuk mendapatkan siswa yang benar-benar tidak absen
    public function getSiswaBelumAbsen()
    {
        try {
            \Log::info('Memulai getSiswaBelumAbsen');

            // Ambil semua siswa PKL
            $allSiswa = User::where('role', 'siswa')
                ->whereNotNull('iduka_id')
                ->get();

            \Log::info('Total siswa PKL: ' . $allSiswa->count());

            // Ambil ID siswa yang sudah ada di absensi (sudah konfirmasi)
            $idsSiswaHadir = Absensi::whereDate('tanggal', Carbon::today())
                ->pluck('user_id')
                ->toArray();

            // Ambil ID siswa yang ada di absensi_pending (belum konfirmasi)
            $idsSiswaPending = AbsensiPending::whereDate('tanggal', Carbon::today())
                ->pluck('user_id')
                ->toArray();

            // Ambil ID siswa yang ada di izin_pending
            $idsSiswaIzin = IzinPending::whereDate('tanggal', Carbon::today())
                ->pluck('user_id')
                ->toArray();

            // Ambil ID siswa yang ada di dinas_pending
            $idsSiswaDinas = DinasPending::whereDate('tanggal', Carbon::today())
                ->pluck('user_id')
                ->toArray();

            // Gabungkan semua ID siswa yang sudah melakukan aktivitas
            $allIds = array_merge($idsSiswaHadir, $idsSiswaPending, $idsSiswaIzin, $idsSiswaDinas);
            \Log::info('Total ID siswa yang sudah aktivitas: ' . count($allIds));

            // Filter siswa yang belum melakukan aktivitas sama sekali
            $siswaBelumAbsen = $allSiswa->filter(function ($siswa) use ($allIds) {
                return !in_array($siswa->id, $allIds);
            });

            \Log::info('Jumlah siswa belum absen: ' . $siswaBelumAbsen->count());

            // Format data dengan query langsung untuk menghindari error relasi
            $data = [];
            $index = 1;

            foreach ($siswaBelumAbsen as $siswa) {
                // Ambil data iduka dan pembimbing secara manual
                $iduka = DB::table('idukas')->where('id', $siswa->iduka_id)->first();
                $pembimbing = DB::table('gurus')->where('id', $siswa->pembimbing_id)->first();

                $data[] = [
                    'no' => $index++,
                    'name' => $siswa->name ?? '-',
                    'email' => $siswa->email ?? '-',
                    'iduka' => $iduka->nama ?? '-', // Menggunakan kolom 'nama' bukan 'nama_perusahaan'
                    'pembimbing' => $pembimbing->nama ?? $pembimbing->name ?? '-',
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error di getSiswaBelumAbsen: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    public function getPembimbingBelumKonfirmasi()
    {
        try {
            \Log::info('Memulai getPembimbingBelumKonfirmasi');

            // Ambil semua data pending yang perlu dikonfirmasi oleh guru
            $absensiPending = DB::table('absensi_pending as ap')
                ->join('users as u', 'ap.user_id', '=', 'u.id')
                ->join('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->leftJoin('users as guru_user', 'g.user_id', '=', 'guru_user.id')
                ->whereDate('ap.tanggal', Carbon::today())
                ->where('ap.status_konfirmasi', 'pending')
                ->select(
                    'g.id as guru_id',
                    'guru_user.name as guru_name',
                    'guru_user.email as guru_email',
                    DB::raw('COUNT(ap.id) as jumlah_pending'),
                    DB::raw('"Absensi" as jenis')
                )
                ->groupBy('g.id', 'guru_user.name', 'guru_user.email')
                ->get();

            // Ambil data izin pending
            $izinPending = DB::table('izin_pending as ip')
                ->join('users as u', 'ip.user_id', '=', 'u.id')
                ->join('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->leftJoin('users as guru_user', 'g.user_id', '=', 'guru_user.id')
                ->whereDate('ip.tanggal', Carbon::today())
                ->where('ip.status_konfirmasi', 'pending')
                ->select(
                    'g.id as guru_id',
                    'guru_user.name as guru_name',
                    'guru_user.email as guru_email',
                    DB::raw('COUNT(ip.id) as jumlah_pending'),
                    DB::raw('"Izin" as jenis')
                )
                ->groupBy('g.id', 'guru_user.name', 'guru_user.email')
                ->get();

            // Ambil data dinas pending
            $dinasPending = DB::table('dinas_pending as dp')
                ->join('users as u', 'dp.user_id', '=', 'u.id')
                ->join('gurus as g', 'u.pembimbing_id', '=', 'g.id')
                ->leftJoin('users as guru_user', 'g.user_id', '=', 'guru_user.id')
                ->whereDate('dp.tanggal', Carbon::today())
                ->where('dp.status_konfirmasi', 'pending')
                ->select(
                    'g.id as guru_id',
                    'guru_user.name as guru_name',
                    'guru_user.email as guru_email',
                    DB::raw('COUNT(dp.id) as jumlah_pending'),
                    DB::raw('"Dinas" as jenis')
                )
                ->groupBy('g.id', 'guru_user.name', 'guru_user.email')
                ->get();

            // Gabungkan semua data
            $allPending = $absensiPending->concat($izinPending)->concat($dinasPending);

            // Kelompokkan berdasarkan guru
            $groupedData = [];
            foreach ($allPending as $item) {
                $guruId = $item->guru_id;
                if (!isset($groupedData[$guruId])) {
                    $groupedData[$guruId] = [
                        'guru_id' => $guruId,
                        'guru_name' => $item->guru_name,
                        'guru_email' => $item->guru_email,
                        'total_pending' => 0,
                        'detail' => []
                    ];
                }

                $groupedData[$guruId]['total_pending'] += $item->jumlah_pending;
                $groupedData[$guruId]['detail'][] = [
                    'jenis' => $item->jenis,
                    'jumlah' => $item->jumlah_pending
                ];
            }

            // Format data untuk response
            $data = [];
            $index = 1;
            foreach ($groupedData as $guruId => $guruData) {
                $detailText = [];
                foreach ($guruData['detail'] as $detail) {
                    $detailText[] = "{$detail['jumlah']} {$detail['jenis']}";
                }

                $data[] = [
                    'no' => $index++,
                    'guru_id' => $guruData['guru_id'],
                    'guru_name' => $guruData['guru_name'],
                    'guru_email' => $guruData['guru_email'],
                    'total_pending' => $guruData['total_pending'],
                    'detail' => implode(', ', $detailText)
                ];
            }

            return response()->json($data);
        } catch (\Exception $e) {
            \Log::error('Error di getPembimbingBelumKonfirmasi: ' . $e->getMessage());
            \Log::error('Trace: ' . $e->getTraceAsString());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
