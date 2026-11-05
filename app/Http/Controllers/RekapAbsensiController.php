<?php

namespace App\Http\Controllers;

use App\Models\IdukaHoliday;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\DataAbsenPerKelas;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class RekapAbsensiController extends Controller
{
    public function filterOptions(Request $request)
    {
        $user = Auth::user();
        if (!$user) {
            $role = 'hubin';
        } else {
            $role = $user->role;
        }

        $guruId = null;
        if ($user && $role === 'guru') {
            $guruId = optional($user->guru)->id ?: $user->id;
        }

        $jurusanQuery = \App\Models\Konke::query();
        $kelasQuery   = \App\Models\Kelas::query();
        $siswaQuery   = \App\Models\User::where('role', 'siswa');

        if (in_array($role, ['hubin', 'kepsek'])) {
            // akses semua
        } elseif ($role === 'kaprog') {
            $jurusanQuery->where('id', $user->konke_id);
            $kelasQuery->where('konke_id', $user->konke_id);
            $siswaQuery->where('konke_id', $user->konke_id);
        } elseif ($role === 'guru') {
            if ($guruId) {
                $jurusanQuery->whereHas('kelas.siswa', function ($q) use ($guruId) {
                    $q->where('pembimbing_id', $guruId);
                });
                $kelasQuery->whereHas('siswa', function ($q) use ($guruId) {
                    $q->where('pembimbing_id', $guruId);
                });
                $siswaQuery->where('pembimbing_id', $guruId);
            } else {
                $siswaQuery->whereRaw('1=0');
            }
        }

        if ($request->filled('konke_id')) {
            $kelasQuery->where('konke_id', $request->konke_id);
            $siswaQuery->where('konke_id', $request->konke_id);
        }
        if ($request->filled('kelas_id')) {
            $siswaQuery->where('kelas_id', $request->kelas_id);
        }

        return response()->json([
            'jurusan' => $jurusanQuery->select('id', 'name_konke')->get(),
            'kelas' => $kelasQuery->select('id', 'name_kelas', 'konke_id')->get(),
            'siswa' => $siswaQuery->select('id', 'name', 'kelas_id', 'konke_id')->get()
        ]);
    }

    public function index(Request $request)
    {
        return view('rekap_absensi.index');
    }

   public function data(Request $request)
    {
        $user = Auth::user();
        $role = $user->role;
        $guruId = null;

        if ($user && $role === 'guru') {
            $guruId = optional($user->guru)->id ?: $user->id;
        }

        // === 1. SETUP TANGGAL ===
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if (!$start || !$end) {
            $start = '2025-10-14';
            $end = '2026-02-14';
        }

        // === 2. QUERY DATA ABSENSI ===
        $query = Absensi::with(['user.dataPribadi', 'user.kelas', 'iduka']);

        // Filter Role - untuk menentukan student query
        $studentQuery = \App\Models\User::where('role', 'siswa');

        if (in_array($role, ['hubin', 'kepsek'])) {
            // akses semua siswa
        } elseif ($role === 'kaprog') {
            $konke = $user->konke_id ?? null;
            if ($konke) {
                $query->whereHas('user', function ($q) use ($konke) {
                    $q->where('konke_id', $konke);
                });
                $studentQuery->where('konke_id', $konke);
            } else {
                $query->whereRaw('1=0');
                $studentQuery->whereRaw('1=0');
            }
        } elseif ($role === 'guru') {
            if ($guruId) {
                $query->whereHas('user', function ($q) use ($guruId) {
                    $q->where('pembimbing_id', $guruId);
                });
                $studentQuery->where('pembimbing_id', $guruId);
            } else {
                $query->whereRaw('1=0');
                $studentQuery->whereRaw('1=0');
            }
        } else {
            return response()->json(['error' => 'Akses tidak diizinkan'], 403);
        }

        // Apply Tanggal
        $query->whereBetween('tanggal', [$start, $end]);

        // Additional filters
        if ($request->filled('konke_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('konke_id', $request->konke_id);
            });
            $studentQuery->where('konke_id', $request->konke_id);
        }
        if ($request->filled('kelas_id')) {
            $query->whereHas('user', function($q) use ($request) {
                $q->where('kelas_id', $request->kelas_id);
            });
            $studentQuery->where('kelas_id', $request->kelas_id);
        }
        if ($request->filled('siswa_id')) {
            $query->where('user_id', $request->siswa_id);
            $studentQuery->where('id', $request->siswa_id);
        }

        // === 3. LOGIKA SAMA UNTUK SEMUA ROLE (REKAP + ALPHA DINAMIS) ===

        // A. Ambil Siswa beserta IDUKA nya (penting untuk cek libur)
        $students = $studentQuery
            ->select(['id', 'name', 'nip as nis', 'kelas_id', 'iduka_id', 'konke_id'])
            ->with('kelas:id,name_kelas')
            ->get();

        // B. Siapkan Data Libur per IDUKA (Optimasi Query)
        // Kita load semua libur sekali saja, lalu kelompokkan per iduka_id
        $idukaIds = $students->pluck('iduka_id')->unique()->filter();
        $holidaysByIduka = collect();

        if ($idukaIds->isNotEmpty()) {
            // Query libur: ambil yang di range tanggal ATAU yang recurring
            $holidaysByIduka = IdukaHoliday::whereIn('iduka_id', $idukaIds)
                ->where(function($q) use ($start, $end) {
                    $q->whereBetween('date', [$start, $end])
                      ->orWhere('recurring', true);
                })
                ->get()
                ->groupBy('iduka_id');
        }

        // C. Hitung Kehadiran (H, S, I) dari Database
        $aggQuery = clone $query;
        $counts = $aggQuery->select([
            'absensi.user_id',
            DB::raw("SUM(CASE WHEN (absensi.status IN ('tepat_waktu','terlambat','hadir') OR (absensi.jenis_dinas IS NOT NULL AND absensi.status_dinas='disetujui')) THEN 1 ELSE 0 END) as H"),
            DB::raw("SUM(CASE WHEN absensi.status='izin' AND absensi.jenis_izin LIKE '%sakit%' THEN 1 ELSE 0 END) as S"),
            DB::raw("SUM(CASE WHEN absensi.status='izin' AND absensi.jenis_izin NOT LIKE '%sakit%' THEN 1 ELSE 0 END) as I"),
        ])->groupBy('absensi.user_id')->get()->keyBy('user_id');

        // D. Looping untuk Hitung Alpha Per Siswa
        $rows = collect();
        $period = CarbonPeriod::create($start, $end);

        foreach ($students as $s) {
            // 1. Ambil data kehadiran
            $cnt = $counts->get($s->id);
            $H = $cnt->H ?? 0;
            $S = $cnt->S ?? 0;
            $I = $cnt->I ?? 0;

            // 2. Hitung Hari Efektif spesifik untuk IDUKA siswa ini
            $effectiveDays = 0;
            $idukaId = $s->iduka_id;

            // Ambil list libur untuk iduka ini
            $liburIdukaIni = $holidaysByIduka->get($idukaId, collect());

            // Loop tanggal
            foreach ($period as $date) {
                // Skip Minggu
                if ($date->isSunday()) continue;

                // Cek apakah tanggal ini libur di IDUKA ini?
                $isHoliday = false;
                $dateStr = $date->format('Y-m-d');
                $mdStr = $date->format('m-d'); // Format Bulan-Tanggal untuk recurring

                foreach ($liburIdukaIni as $libur) {
                    // Cek cocok secara exact date ATAU recurring (bulan-tanggal sama)
                    $liburDate = is_string($libur->date) ? $libur->date : $libur->date->format('Y-m-d');
                    $liburMd = (is_string($libur->date) ? Carbon::parse($libur->date) : $libur->date)->format('m-d');

                    if ($liburDate == $dateStr || ($libur->recurring && $liburMd == $mdStr)) {
                        $isHoliday = true;
                        break;
                    }
                }

                // Jika bukan libur, maka hari efektif
                if (!$isHoliday) {
                    $effectiveDays++;
                }
            }

            // 3. Hitung Alpha
            $A = max(0, $effectiveDays - ($H + $S + $I));

            $rows->push((object)[
                'user' => $s,
                'counts' => (object)[
                    'H' => $H,
                    'S' => $S,
                    'I' => $I,
                    'A' => $A,
                    'effective_days' => $effectiveDays
                ]
            ]);
        }

        return response()->json(['data' => $rows, 'students' => $students]);
    }
    public function exportPerKelas(Request $request)
    {
        $user = Auth::user();
        $role = $user->role ?? null;
        $guruId = null;

        if ($user && $role === 'guru') {
            $guruId = optional($user->guru)->id ?: $user->id;
        }

        // Gunakan tanggal dari request, atau default PKL
        $start = $request->input('start_date');
        $end = $request->input('end_date');

        if (!$start || !$end) {
            $start = '2025-10-14';
            $end = '2026-02-14';
        }

        $konkeId = null;
        $kelasId = $request->input('kelas_id');
        if ($role === 'kaprog') {
            $konkeId = $user->konke_id ?? null;
        }

        $konkeName = null;
        $kelasName = null;

        if ($role === 'guru') {
            $gurName = $user->name ?? 'Guru';
            $konkeName = 'Bimbingan - ' . $gurName;
            $fileName = 'rekap_pkl_' . str_replace(' ', '_', strtolower($gurName)) . '_' . $start . '_' . $end . '.xlsx';
        } else {
            if ($konkeId) {
                $konke = \App\Models\Konke::find($konkeId);
                $konkeName = $konke ? ($konke->singkatan ?? $konke->name_konke) : null;
            }
            if ($kelasId) {
                $kls = \App\Models\Kelas::find($kelasId);
                $kelasName = $kls ? $kls->name_kelas : null;
            }
            $fileName = 'rekap_absensi_' . ($konkeName ?? 'semua') . '_' . ($kelasName ?? 'semua') . '_' . $start . '_' . $end . '.xlsx';
        }

        // Pastikan export class juga menerapkan logika hari efektif yang sama
        $export = new DataAbsenPerKelas($start, $end, $konkeId, $kelasId, $guruId, $konkeName, $kelasName);

        return Excel::download($export, $fileName);
    }
}
