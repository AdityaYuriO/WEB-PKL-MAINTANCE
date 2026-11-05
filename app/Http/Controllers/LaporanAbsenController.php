v<?php

namespace App\Http\Controllers;

use App\Exports\LaporanAbsensiExport;
use App\Models\Absensi;
use App\Models\Konke;
use App\Models\Iduka;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class LaporanAbsenController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil data jurusan dan IDUKA berdasarkan role
        $jurusans = $this->getJurusansByRole($user);
        $idukas = $this->getIdukasByRole($user);

        return view('laporan.absensi', [
            'jurusans' => $jurusans,
            'idukas' => $idukas,
        ]);
    }

    public function getLaporan(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $jurusan = $request->get('jurusan');
            $iduka = $request->get('iduka');
            $status = $request->get('status');

            // Validasi tanggal
            if (!$startDate || !$endDate) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tanggal mulai dan tanggal selesai harus diisi',
                    'data' => []
                ], 400);
            }

            // Parse tanggal
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            // Build query berdasarkan role
            $query = $this->buildQueryByRole($user, $startDate, $endDate);

            // Apply filters
            if ($jurusan) {
                $query->whereHas('user.kelas', function($q) use ($jurusan) {
                    $q->where('konke_id', $jurusan);
                });
            }

            if ($iduka) {
                $query->where('iduka_id', $iduka);
            }

            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            // Get data dengan eager load relations
            $absensis = $query->with(['user', 'iduka'])
                ->orderBy('tanggal', 'desc')
                ->get();

            // Format data untuk DataTables
            $data = $absensis->map(function($absensi) {
                return [
                    'tanggal' => $absensi->tanggal ? $absensi->tanggal->format('d-m-Y') : '-',
                    'nama_siswa' => $absensi->user->name ?? '-',
                    'email' => $absensi->user->email ?? '-',
                    'jurusan' => $absensi->user->konke->name_konke ?? $absensi->user->kelas->konke->name_konke ?? '-',
                    'iduka' => $absensi->iduka->nama ?? '-',
                    'status' => $this->formatStatus($absensi->status),
                    'jam' => $this->formatJam($absensi),
                    'keterangan' => $absensi->keterangan ?? '-',
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
                'data' => []
            ], 500);
        }
    }

    public function exportExcel(Request $request)
    {
        try {
            $user = Auth::user();
            $startDate = $request->get('start_date');
            $endDate = $request->get('end_date');
            $jurusan = $request->get('jurusan');
            $iduka = $request->get('iduka');
            $status = $request->get('status');

            // Validasi tanggal
            if (!$startDate || !$endDate) {
                return back()->with('error', 'Tanggal mulai dan tanggal selesai harus diisi');
            }

            // Parse tanggal
            $startDate = Carbon::createFromFormat('Y-m-d', $startDate)->startOfDay();
            $endDate = Carbon::createFromFormat('Y-m-d', $endDate)->endOfDay();

            // Build query berdasarkan role
            $query = $this->buildQueryByRole($user, $startDate, $endDate);

            // Apply filters
            if ($jurusan) {
                $query->whereHas('user.kelas', function($q) use ($jurusan) {
                    $q->where('konke_id', $jurusan);
                });
            }

            if ($iduka) {
                $query->where('iduka_id', $iduka);
            }

            if ($status && $status !== 'all') {
                $query->where('status', $status);
            }

            // Get data
            $absensis = $query->with(['user', 'iduka'])
                ->orderBy('tanggal', 'desc')
                ->get();

            // Siapkan data untuk export
            $exportData = [
                'start_date' => $startDate->format('d-m-Y'),
                'end_date' => $endDate->format('d-m-Y'),
                'data' => $absensis->map(function($absensi) {
                    return [
                        'tanggal' => $absensi->tanggal ? $absensi->tanggal->format('d-m-Y') : '-',
                        'nama_siswa' => $absensi->user->name ?? '-',
                        'email' => $absensi->user->email ?? '-',
                        'jurusan' => $absensi->user->konke->name_konke ?? $absensi->user->kelas->konke->name_konke ?? '-',
                        'iduka' => $absensi->iduka->nama ?? '-',
                        'status' => $this->formatStatus($absensi->status),
                        'jam' => $this->formatJam($absensi),
                        'keterangan' => $absensi->keterangan ?? '-',
                    ];
                })->toArray(),
            ];

            // Simpan data ke session untuk diakses oleh export class
            session(['laporan_absensi_export' => $exportData]);

            $fileName = 'Laporan_Absensi_' . $startDate->format('d-m-Y') . '_' . $endDate->format('d-m-Y') . '.xlsx';
            return Excel::download(new LaporanAbsensiExport($exportData), $fileName);

        } catch (\Exception $e) {
            return back()->with('error', 'Terjadi kesalahan saat export: ' . $e->getMessage());
        }
    }

    /**
     * Build query based on user role
     */
    private function buildQueryByRole($user, $startDate, $endDate)
    {
        $query = Absensi::whereBetween('tanggal', [$startDate, $endDate]);

        // Filter berdasarkan role
        $role = $user->role ?? '';

        if ($role === 'hubin') {
            // Hubin: Lihat semua siswa - no filter
        } elseif (in_array($role, ['guru', 'pembimbing'])) {
            // Guru/Pembimbing: Lihat siswa yang dibimbing (via pembimbing_id)
            if ($user->guru) {
                $guruId = $user->guru->id ?? null;
                if ($guruId) {
                    $query->whereHas('user', function($q) use ($guruId) {
                        $q->where('pembimbing_id', $guruId);
                    });
                }
            }
        } elseif ($role === 'kepsek') {
            // Kepsek: Lihat semua siswa - no filter
        } elseif ($role === 'akprog') {
            // Akprog: Lihat siswa dari program keahlian (konke_id)
            if ($user->konke_id) {
                $query->whereHas('user', function($q) use ($user) {
                    $q->where('konke_id', $user->konke_id);
                });
            }
        } elseif (in_array($role, ['siswa', 'student'])) {
            // Siswa: hanya lihat data sendiri
            $query->where('user_id', $user->id);
        }

        return $query;
    }    /**
     * Get jurusan based on user role
     */
    private function getJurusansByRole($user)
    {
        $role = $user->role ?? '';

        if (in_array($role, ['hubin', 'kepsek'])) {
            return Konke::all();
        } elseif (in_array($role, ['guru', 'pembimbing'])) {
            // Guru: hanya jurusan dari kelas yang dibimbing
            if ($user->guru && $user->guru->kelas_id) {
                $kelas = $user->guru->kelas;
                if ($kelas && $kelas->konke_id) {
                    return Konke::where('id', $kelas->konke_id)->get();
                }
            }
        } elseif ($role === 'akprog' && $user->konke_id) {
            // Akprog: jurusan yang dikelola
            return Konke::where('id', $user->konke_id)->get();
        }

        return collect([]);
    }

    /**
     * Get IDUKA based on user role
     */
    private function getIdukasByRole($user)
    {
        $role = $user->role ?? '';

        if (in_array($role, ['hubin', 'kepsek'])) {
            return Iduka::all();
        } elseif (in_array($role, ['guru', 'pembimbing'])) {
            // Guru: IDUKA dari siswa yang dibimbing (perlu eager load)
            // Simplified: return all for now
            return Iduka::all();
        } elseif ($role === 'akprog') {
            // Akprog: IDUKA dari siswa program keahlian
            return Iduka::whereHas('absensi.user', function($q) use ($user) {
                $q->where('konke_id', $user->konke_id);
            })->distinct()->get();
        }

        return collect([]);
    }

    /**
     * Format status untuk tampilan
     */
    private function formatStatus($status)
    {
        $statusMap = [
            'hadir' => 'Hadir',
            'pending' => 'Pending',
            'tidak_hadir' => 'Tidak Hadir',
            'izin' => 'Izin',
            'dinas' => 'Dinas',
        ];

        return $statusMap[$status] ?? ucfirst($status);
    }

    /**
     * Format jam untuk tampilan
     */
    private function formatJam($absensi)
    {
        if ($absensi->jam_masuk && $absensi->jam_pulang) {
            return $absensi->jam_masuk->format('H:i') . ' - ' . $absensi->jam_pulang->format('H:i');
        } elseif ($absensi->jam_masuk) {
            return $absensi->jam_masuk->format('H:i');
        }
        return '-';
    }
}
