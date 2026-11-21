<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\DinasPending;
use App\Models\Iduka;
use App\Models\IdukaHoliday;
use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\AbsensiPending;
use App\Models\IzinPending;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use App\Notifications\ReminderNotification;

class KonfirAbsenSiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        try {
            $user = Auth::user();

            if (!in_array($user->role, ['iduka', 'guru', 'kaprog'])) {
                return redirect()->back()->with('error', 'Akses tidak diizinkan');
            }

            // Log untuk debugging
            Log::info('User mengakses halaman konfirmasi absen', [
                'user_id' => $user->id,
                'user_role' => $user->role,
                'iduka_id' => $user->iduka_id ?? null,
                'session_success' => session('success'),
                'session_error' => session('error')
            ]);

            // === Untuk IDUKA ===
            if ($user->role === 'iduka') {
                $absensiHariIni = Absensi::with(['user', 'iduka'])
                    ->where('iduka_id', $user->iduka_id)
                    ->whereDate('tanggal', Carbon::today())
                    ->get();

                $absensiPending = AbsensiPending::with(['user', 'iduka'])
                    ->where('iduka_id', $user->iduka_id)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                $izinPending = IzinPending::with(['user', 'iduka'])
                    ->where('iduka_id', $user->iduka_id)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                $dinasPending = DinasPending::with(['user', 'iduka'])
                    ->where('iduka_id', $user->iduka_id)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                $siswaList = User::where('role', 'siswa')
                    ->where('iduka_id', $user->iduka_id)
                    ->orderBy('name')
                    ->get();

                // ambil data iduka yang login untuk kordinat
                $iduka = Iduka::find($user->iduka_id);
            }
            // === Untuk Guru/Pembimbing ===
            elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();

                if (!$guru) {
                    Log::warning('Data guru tidak ditemukan', ['user_id' => $user->id]);
                    return redirect()->back()->with('error', 'Data guru tidak ditemukan.');
                }

                // PERBAIKAN: Ambil siswa yang memiliki pembimbing_id sama dengan ID guru di tabel gurus
                $siswaIds = User::where('role', 'siswa')
                    ->where('pembimbing_id', $guru->id)
                    ->pluck('id')
                    ->toArray();

                $absensiHariIni = Absensi::with(['user', 'iduka'])
                    ->whereIn('user_id', $siswaIds)
                    ->whereDate('tanggal', Carbon::today())
                    ->get();

                // PERBAIKAN: Ambil absensi pending yang user_id-nya ada di siswaIds
                $absensiPending = AbsensiPending::with(['user', 'iduka'])
                    ->whereIn('user_id', $siswaIds)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                // PERBAIKAN: Ambil izin pending yang user_id-nya ada di siswaIds
                $izinPending = IzinPending::with(['user', 'iduka'])
                    ->whereIn('user_id', $siswaIds)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                // PERBAIKAN: Ambil dinas pending yang user_id-nya ada di siswaIds
                $dinasPending = DinasPending::with(['user', 'iduka'])
                    ->whereIn('user_id', $siswaIds)
                    ->where('status_konfirmasi', 'pending')
                    ->latest()
                    ->get();

                $siswaList = User::where('role', 'siswa')
                    ->where('pembimbing_id', $guru->id)
                    ->orderBy('name')
                    ->get();

                // guru bisa lihat semua iduka
                $iduka = null;
            }
            // === Untuk Kepala Program (Kaprog) ===
            elseif ($user->role === 'kaprog') {
                // Cari data guru untuk kaprog ini
                $guru = Guru::where('user_id', $user->id)->first();

                // Jika kaprog belum memiliki data di tabel gurus, buatkan otomatis
                if (!$guru) {
                    try {
                        Log::info('Membuat data guru untuk kaprog', ['user_id' => $user->id]);

                        $guru = Guru::create([
                            'user_id' => $user->id,
                            'nama' => $user->name,
                            'nip' => $user->email, // atau bisa diisi dengan data lain
                            'jabatan' => 'Kepala Program',
                            // tambahkan field lain yang diperlukan
                        ]);

                        Log::info('Berhasil membuat data guru untuk kaprog', ['guru_id' => $guru->id]);
                    } catch (\Exception $e) {
                        Log::error('Gagal membuat data guru untuk kaprog', [
                            'error' => $e->getMessage(),
                            'user_id' => $user->id
                        ]);

                        // Jika gagal membuat, gunakan koleksi kosong
                        $absensiHariIni = collect();
                        $absensiPending = collect();
                        $izinPending = collect();
                        $dinasPending = collect();
                        $siswaList = collect();
                        $statistik = [
                            'total_siswa' => 0,
                            'persentase_kehadiran' => 0,
                            'tepat_waktu' => 0,
                            'terlambat' => 0,
                            'izin' => 0,
                            'sakit' => 0,
                            'alpha' => 0,
                        ];
                        $idukas = Iduka::all();
                        $iduka = null;

                        return view('iduka.konfir_absen_siswa.index', compact(
                            'absensiHariIni',
                            'absensiPending',
                            'izinPending',
                            'dinasPending',
                            'siswaList',
                            'statistik',
                            'idukas',
                            'iduka'
                        ));
                    }
                }

                // Ambil siswa yang pembimbing_id-nya sama dengan id guru kaprog
                $siswaIds = User::where('role', 'siswa')
                    ->where('pembimbing_id', $guru->id)
                    ->pluck('id')
                    ->toArray();

                // Jika kaprog tidak memiliki siswa bimbingan, gunakan koleksi kosong
                if (empty($siswaIds)) {
                    Log::info('Kaprog tidak memiliki siswa bimbingan', ['user_id' => $user->id]);
                    $absensiHariIni = collect();
                    $absensiPending = collect();
                    $izinPending = collect();
                    $dinasPending = collect();
                    $siswaList = collect();
                } else {
                    $absensiHariIni = Absensi::with(['user', 'iduka'])
                        ->whereIn('user_id', $siswaIds)
                        ->whereDate('tanggal', Carbon::today())
                        ->get();

                    $absensiPending = AbsensiPending::with(['user', 'iduka'])
                        ->whereIn('user_id', $siswaIds)
                        ->where('status_konfirmasi', 'pending')
                        ->latest()
                        ->get();

                    $izinPending = IzinPending::with(['user', 'iduka'])
                        ->whereIn('user_id', $siswaIds)
                        ->where('status_konfirmasi', 'pending')
                        ->latest()
                        ->get();

                    $dinasPending = DinasPending::with(['user', 'iduka'])
                        ->whereIn('user_id', $siswaIds)
                        ->where('status_konfirmasi', 'pending')
                        ->latest()
                        ->get();

                    $siswaList = User::where('role', 'siswa')
                        ->where('pembimbing_id', $guru->id)
                        ->orderBy('name')
                        ->get();
                }

                // kaprog bisa lihat semua iduka
                $iduka = null;
            }

            // Hitung statistik (fungsi kamu sendiri)
            $statistik = $this->hitungStatistik($user);
            $idukas = Iduka::all(); // untuk dropdown

            return view('iduka.konfir_absen_siswa.index', compact(
                'absensiHariIni',
                'absensiPending',
                'izinPending',
                'dinasPending',
                'siswaList',
                'statistik',
                'idukas',
                'iduka'
            ));
        } catch (\Exception $e) {
            Log::error('Error in KonfirAbsenSiswaController@index: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function konfirmasiDinas(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            Log::info('=== KONFIRMASI DINAS LUAR DIMULAI ===', [
                'dinas_id' => $id,
                'status' => $request->status,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role
            ]);

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:disetujui,ditolak',
                'catatan' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $dinasPending = DinasPending::with('user')->find($id);

            if (!$dinasPending) {
                Log::error('Dinas pending tidak ditemukan', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data dinas tidak ditemukan'
                ], 404);
            }

            $user = Auth::user();

            // Validasi authorization berdasarkan role
            if ($user->role === 'iduka') {
                if (!$dinasPending->iduka_id || $dinasPending->iduka_id != $user->iduka_id) {
                    Log::warning('IDUKA tidak berwenang', [
                        'iduka_id_pending' => $dinasPending->iduka_id,
                        'iduka_id_user' => $user->iduka_id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access - Bukan IDUKA yang berwenang'
                    ], 403);
                }
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    Log::error('Data guru tidak ditemukan', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 404);
                }

                // Cek langsung pembimbing_id di tabel users
                if (!$dinasPending->user || $dinasPending->user->pembimbing_id != $guru->id) {
                    Log::warning('Guru bukan pembimbing siswa ini', [
                        'guru_id' => $guru->id,
                        'siswa_id' => $dinasPending->user_id,
                        'siswa_pembimbing_id' => $dinasPending->user ? $dinasPending->user->pembimbing_id : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    Log::error('Data guru untuk kaprog tidak ditemukan', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pembimbing untuk kaprog tidak ditemukan'
                    ], 404);
                }

                // Cek langsung pembimbing_id di tabel users
                if (!$dinasPending->user || $dinasPending->user->pembimbing_id != $guru->id) {
                    Log::warning('Kaprog bukan pembimbing siswa ini', [
                        'guru_id' => $guru->id,
                        'siswa_id' => $dinasPending->user_id,
                        'siswa_pembimbing_id' => $dinasPending->user ? $dinasPending->user->pembimbing_id : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak diizinkan untuk mengkonfirmasi dinas'
                ], 403);
            }

            // Cek apakah sudah dikonfirmasi sebelumnya
            if ($dinasPending->status_konfirmasi !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Dinas ini sudah dikonfirmasi sebelumnya'
                ], 400);
            }

            // Update status dinas
            $dinasPending->update([
                'status_konfirmasi' => $request->status,
                'alasan_penolakan' => $request->status === 'ditolak' ? ($request->catatan ?? 'Alasan penolakan tidak diisi') : null
            ]);

            // Jika disetujui, buat record absensi dengan status hadir
            if ($request->status === 'disetujui') {
                // Cek apakah sudah ada absensi untuk tanggal tersebut
                $absensiExist = Absensi::where('user_id', $dinasPending->user_id)
                    ->whereDate('tanggal', $dinasPending->tanggal)
                    ->first();

                if ($absensiExist) {
                    // Update absensi yang sudah ada
                    $absensiExist->update([
                        'status' => 'hadir',
                        'jenis_dinas' => $dinasPending->jenis_dinas,
                        'keterangan_dinas' => $dinasPending->keterangan,
                        'status_dinas' => 'disetujui'
                    ]);
                    Log::info('Absensi existing diupdate dengan status dinas', ['absensi_id' => $absensiExist->id]);
                } else {
                    // Buat absensi baru dengan status hadir
                    $absensi = Absensi::create([
                        'user_id' => $dinasPending->user_id,
                        'iduka_id' => $dinasPending->iduka_id,
                        'tanggal' => $dinasPending->tanggal,
                        'status' => 'hadir',
                        'jenis_dinas' => $dinasPending->jenis_dinas,
                        'keterangan_dinas' => $dinasPending->keterangan,
                        'status_dinas' => 'disetujui',
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'latitude_masuk' => null,
                        'longitude_masuk' => null,
                        'latitude_pulang' => null,
                        'longitude_pulang' => null,
                    ]);
                    Log::info('Absensi baru dibuat dengan status dinas', ['absensi_id' => $absensi->id]);
                }

                // Hapus dari pending setelah disetujui
                $dinasPending->delete();
                Log::info('Dinas pending dihapus setelah disetujui', ['dinas_id' => $id]);
            }

            DB::commit();

            // Set session flash data
            session()->flash('success', 'Dinas luar berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak'));

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            // Return JSON response untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Dinas luar berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak'),
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasiDinas: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Konfirmasi izin siswa - FIXED VERSION
     */
    public function konfirmasiIzin(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            Log::info('=== KONFIRMASI IZIN DIMULAI ===', [
                'izin_id' => $id,
                'status' => $request->status,
                'user_id' => Auth::id(),
                'user_role' => Auth::user()->role
            ]);

            $validator = Validator::make($request->all(), [
                'status' => 'required|in:disetujui,ditolak',
                'catatan' => 'nullable|string|max:500'
            ]);

            if ($validator->fails()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal',
                    'errors' => $validator->errors()
                ], 422);
            }

            $izinPending = IzinPending::with('user')->find($id);

            if (!$izinPending) {
                Log::error('Izin pending tidak ditemukan', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data izin tidak ditemukan'
                ], 404);
            }

            $user = Auth::user();

            // Validasi authorization berdasarkan role
            if ($user->role === 'iduka') {
                if (!$izinPending->iduka_id || $izinPending->iduka_id != $user->iduka_id) {
                    Log::warning('IDUKA tidak berwenang mengkonfirmasi izin ini', [
                        'izin_iduka_id' => $izinPending->iduka_id,
                        'user_iduka_id' => $user->iduka_id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access - Bukan IDUKA yang berwenang'
                    ], 403);
                }
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    Log::error('Data guru tidak ditemukan', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 404);
                }

                // Cek langsung pembimbing_id di tabel users
                if (!$izinPending->user || $izinPending->user->pembimbing_id != $guru->id) {
                    Log::warning('Guru bukan pembimbing siswa ini', [
                        'guru_id' => $guru->id,
                        'siswa_id' => $izinPending->user_id,
                        'siswa_pembimbing_id' => $izinPending->user ? $izinPending->user->pembimbing_id : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    Log::error('Data guru untuk kaprog tidak ditemukan', ['user_id' => $user->id]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pembimbing untuk kaprog tidak ditemukan'
                    ], 404);
                }

                // Cek langsung pembimbing_id di tabel users
                if (!$izinPending->user || $izinPending->user->pembimbing_id != $guru->id) {
                    Log::warning('Kaprog bukan pembimbing siswa ini', [
                        'guru_id' => $guru->id,
                        'siswa_id' => $izinPending->user_id,
                        'siswa_pembimbing_id' => $izinPending->user ? $izinPending->user->pembimbing_id : 'null'
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak diizinkan untuk mengkonfirmasi izin'
                ], 403);
            }

            // Cek apakah sudah dikonfirmasi sebelumnya
            if ($izinPending->status_konfirmasi !== 'pending') {
                return response()->json([
                    'success' => false,
                    'message' => 'Izin ini sudah dikonfirmasi sebelumnya'
                ], 400);
            }

            // Update status izin
            $izinPending->update([
                'status_konfirmasi' => $request->status,
                'alasan_penolakan' => $request->status === 'ditolak' ? ($request->catatan ?? 'Alasan penolakan tidak diisi') : null
            ]);

            Log::info('Status izin berhasil diupdate', [
                'izin_id' => $id,
                'status_baru' => $request->status
            ]);

            // Jika disetujui, buat record absensi dengan status izin
            if ($request->status === 'disetujui') {
                // Cek apakah sudah ada absensi untuk tanggal tersebut
                $absensiExist = Absensi::where('user_id', $izinPending->user_id)
                    ->whereDate('tanggal', $izinPending->tanggal)
                    ->first();

                if ($absensiExist) {
                    // Update absensi yang sudah ada
                    $absensiExist->update([
                        'status' => 'izin',
                        'jenis_izin' => $izinPending->jenis_izin,
                        'keterangan_izin' => $izinPending->keterangan,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                    ]);
                    Log::info('Absensi existing diupdate dengan status izin', ['absensi_id' => $absensiExist->id]);
                } else {
                    // Buat absensi baru
                    $absensi = Absensi::create([
                        'user_id' => $izinPending->user_id,
                        'iduka_id' => $izinPending->iduka_id,
                        'tanggal' => $izinPending->tanggal,
                        'status' => 'izin',
                        'jenis_izin' => $izinPending->jenis_izin,
                        'keterangan_izin' => $izinPending->keterangan,
                        'jam_masuk' => null,
                        'jam_pulang' => null,
                        'latitude_masuk' => null,
                        'longitude_masuk' => null,
                        'latitude_pulang' => null,
                        'longitude_pulang' => null,
                    ]);
                    Log::info('Absensi baru dibuat dengan status izin', ['absensi_id' => $absensi->id]);
                }

                // Hapus dari pending setelah disetujui
                $izinPending->delete();
                Log::info('Izin pending dihapus setelah disetujui', ['izin_id' => $id]);
            }
            DB::commit();

            // Set session flash data
            session()->flash('success', 'Izin berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak'));

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            // Return JSON response untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Izin berhasil ' . ($request->status === 'disetujui' ? 'disetujui' : 'ditolak'),
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasiIzin: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Konfirmasi absensi - FIXED VERSION
     */
    public function konfirmasiAbsensi(Request $request, $id)
    {
        try {
            Log::info('=== KONFIRMASI ABSENSI DIMULAI ===', [
                'id' => $id,
                'user_id' => Auth::id(),
                'role' => Auth::user()->role
            ]);

            $absensiPending = AbsensiPending::find($id);

            if (!$absensiPending) {
                Log::warning('Absensi pending tidak ditemukan', ['id' => $id]);
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }

            $user = Auth::user();

            // Validasi authorization berdasarkan role
            if ($user->role === 'iduka') {
                if (!$absensiPending->iduka_id || $absensiPending->iduka_id != $user->iduka_id) {
                    Log::warning('IDUKA tidak berwenang', [
                        'iduka_id_pending' => $absensiPending->iduka_id,
                        'iduka_id_user' => $user->iduka_id
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak berwenang mengkonfirmasi absensi ini'
                    ], 403);
                }
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || !$absensiPending->pembimbing_id || $absensiPending->pembimbing_id != $guru->id) {
                    Log::warning('Guru bukan pembimbing', [
                        'pembimbing_id_pending' => $absensiPending->pembimbing_id,
                        'guru_id' => $guru ? $guru->id : null
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } elseif ($user->role === 'kaprog') {
                // PERBAIKAN: Kaprog hanya bisa mengkonfirmasi absensi untuk siswa yang dibimbingnya
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || !$absensiPending->pembimbing_id || $absensiPending->pembimbing_id != $guru->id) {
                    Log::warning('Kaprog bukan pembimbing', [
                        'pembimbing_id_pending' => $absensiPending->pembimbing_id,
                        'guru_id' => $guru ? $guru->id : null
                    ]);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak diizinkan untuk mengkonfirmasi absensi'
                ], 403);
            }

            DB::beginTransaction();

            // Update status konfirmasi
            $konfirmator = $user->role === 'iduka' ? 'iduka' : ($user->role === 'guru' ? 'guru' : 'kaprog');

            // Jika sudah ada yang konfirmasi sebelumnya
            if ($absensiPending->dikonfirmasi_oleh) {
                // Jika yang konfirmasi sekarang berbeda dengan sebelumnya
                if ($absensiPending->dikonfirmasi_oleh !== $konfirmator) {
                    $absensiPending->dikonfirmasi_oleh = 'keduanya';
                }
            } else {
                $absensiPending->dikonfirmasi_oleh = $konfirmator;
            }

            $absensiPending->waktu_konfirmasi = now();
            $absensiPending->save();

            // Cari atau buat record absensi
            $absensi = Absensi::where('user_id', $absensiPending->user_id)
                ->where('iduka_id', $absensiPending->iduka_id)
                ->whereDate('tanggal', $absensiPending->tanggal)
                ->first();

            if (!$absensi) {
                $absensi = new Absensi();
                $absensi->user_id = $absensiPending->user_id;
                $absensi->iduka_id = $absensiPending->iduka_id;
                $absensi->tanggal = $absensiPending->tanggal;
                $absensi->status = $absensiPending->status ?? 'hadir';
            }

            // Update data berdasarkan jenis absensi
            if ($absensiPending->jenis === 'masuk') {
                $absensi->jam_masuk = $absensiPending->jam;
                $absensi->latitude_masuk = $absensiPending->latitude;
                $absensi->longitude_masuk = $absensiPending->longitude;
            } elseif ($absensiPending->jenis === 'pulang') {
                $absensi->jam_pulang = $absensiPending->jam;
                $absensi->latitude_pulang = $absensiPending->latitude;
                $absensi->longitude_pulang = $absensiPending->longitude;
            }

            // Simpan absensi
            $absensi->save();

            // Hapus data pending
            $absensiPending->delete();

            DB::commit();

            // Set session flash data
            session()->flash('success', 'Absensi berhasil dikonfirmasi');

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            // Return JSON response untuk AJAX
            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dikonfirmasi',
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasiAbsensi: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString(),
                'file' => $e->getFile(),
                'line' => $e->getLine()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * FINAL FIXED - Konfirmasi banyak absensi sekaligus
     */
    public function konfirmasiBanyakAbsen(Request $request)
    {
        try {
            Log::info('=== KONFIRMASI BANYAK ABSENSI DIMULAI ===', [
                'absen_ids' => $request->absen_ids,
                'status' => $request->status,
                'user_id' => Auth::id()
            ]);

            $validator = Validator::make($request->all(), [
                'absen_ids' => 'required|array|min:1',
                'absen_ids.*' => 'required|integer|exists:absensi_pending,id',
                'status' => 'required|in:disetujui,ditolak'
            ]);

            if ($validator->fails()) {
                Log::error('Validasi gagal', ['errors' => $validator->errors()->toArray()]);
                return response()->json([
                    'success' => false,
                    'message' => 'Validasi gagal: ' . implode(', ', $validator->errors()->all())
                ], 422);
            }

            $user = Auth::user();
            $absenIds = $request->absen_ids; // Sudah array dari validasi

            // Ambil data absensi pending berdasarkan role
            if ($user->role === 'iduka') {
                $absensiPendings = AbsensiPending::whereIn('id', $absenIds)
                    ->where('iduka_id', $user->iduka_id)
                    ->where('status_konfirmasi', 'pending')
                    ->get();
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data guru tidak ditemukan'
                    ], 404);
                }

                $absensiPendings = AbsensiPending::whereIn('id', $absenIds)
                    ->where('pembimbing_id', $guru->id)
                    ->where('status_konfirmasi', 'pending')
                    ->get();
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Data pembimbing untuk kaprog tidak ditemukan'
                    ], 404);
                }

                $absensiPendings = AbsensiPending::whereIn('id', $absenIds)
                    ->where('pembimbing_id', $guru->id)
                    ->where('status_konfirmasi', 'pending')
                    ->get();
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak diizinkan'
                ], 403);
            }

            if ($absensiPendings->isEmpty()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Tidak ada data absensi pending yang valid untuk dikonfirmasi'
                ], 404);
            }

            DB::beginTransaction();

            $berhasil = 0;
            $gagal = 0;

            // Kelompokkan absensi pending berdasarkan user_id dan tanggal
            $groupedPendings = $absensiPendings->groupBy(function ($item) {
                return $item->user_id . '_' . $item->tanggal;
            });

            foreach ($groupedPendings as $groupKey => $pendings) {
                try {
                    // Ambil user_id dan tanggal dari group key
                    list($userId, $tanggal) = explode('_', $groupKey);
                    $tanggal = Carbon::parse($tanggal)->toDateString();

                    // Cek apakah sudah ada absensi untuk user dan tanggal tersebut
                    $absensi = Absensi::where('user_id', $userId)
                        ->where('iduka_id', $pendings->first()->iduka_id)
                        ->whereDate('tanggal', $tanggal)
                        ->first();

                    if (!$absensi) {
                        // Buat record absensi baru
                        $absensi = new Absensi();
                        $absensi->user_id = $userId;
                        $absensi->iduka_id = $pendings->first()->iduka_id;
                        $absensi->tanggal = $tanggal;
                        $absensi->status = $pendings->first()->status ?? 'hadir';
                    }

                    // Proses setiap pending dalam grup
                    foreach ($pendings as $pending) {
                        if ($request->status === 'disetujui') {
                            // Update data sesuai jenis absensi
                            if ($pending->jenis === 'masuk') {
                                $absensi->jam_masuk = $pending->jam;
                                $absensi->latitude_masuk = $pending->latitude;
                                $absensi->longitude_masuk = $pending->longitude;
                            } elseif ($pending->jenis === 'pulang') {
                                $absensi->jam_pulang = $pending->jam;
                                $absensi->latitude_pulang = $pending->latitude;
                                $absensi->longitude_pulang = $pending->longitude;
                            }
                        }

                        // Update status dan hapus pending
                        $pending->status_konfirmasi = $request->status;
                        $pending->save();
                        $pending->delete();
                    }

                    // Simpan absensi
                    $absensi->save();

                    $berhasil += count($pendings);

                } catch (\Exception $e) {
                    Log::error('Error processing group of absensi', [
                        'group_key' => $groupKey,
                        'error' => $e->getMessage()
                    ]);
                    $gagal += count($pendings);
                }
            }

            DB::commit();

            $message = "Berhasil memproses {$berhasil} absensi";
            if ($gagal > 0) {
                $message .= ", gagal {$gagal} absensi";
            }

            // Set session flash data
            session()->flash('success', $message);

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            return response()->json([
                'success' => true,
                'message' => $message,
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in konfirmasiBanyakAbsen: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan sistem: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Menolak absensi pending
     */
    public function tolakAbsensi(Request $request, $id)
    {
        DB::beginTransaction();
        try {
            Log::info('=== TOLAK ABSENSI DIMULAI ===', [
                'pending_id' => $id,
                'user_id' => Auth::id(),
                'alasan' => $request->catatan
            ]);

            $absensiPending = AbsensiPending::find($id);

            if (!$absensiPending) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan'
                ], 404);
            }

            $user = Auth::user();

            // Validasi authorization
            if ($user->role === 'iduka') {
                if ($absensiPending->iduka_id != $user->iduka_id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || $absensiPending->pembimbing_id != $guru->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Unauthorized access'
                    ], 403);
                }
            } elseif ($user->role === 'kaprog') {
                // PERBAIKAN: Kaprog hanya bisa menolak absensi untuk siswa yang dibimbingnya
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || $absensiPending->pembimbing_id != $guru->id) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda bukan pembimbing siswa ini'
                    ], 403);
                }
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Role tidak diizinkan untuk menolak absensi'
                ], 403);
            }

            // Simpan ke absensi dengan status ditolak
            $absensi = Absensi::where('user_id', $absensiPending->user_id)
                ->where('iduka_id', $absensiPending->iduka_id)
                ->whereDate('tanggal', $absensiPending->tanggal)
                ->first();

            if (!$absensi) {
                $absensi = new Absensi();
                $absensi->user_id = $absensiPending->user_id;
                $absensi->iduka_id = $absensiPending->iduka_id;
                $absensi->tanggal = $absensiPending->tanggal;
                $absensi->status = 'ditolak';
                $absensi->alasan_penolakan = $request->catatan;
            } else {
                $absensi->status = 'ditolak';
                $absensi->alasan_penolakan = $request->catatan;
            }

            // Update data sesuai jenis absensi
            if ($absensiPending->jenis === 'masuk') {
                $absensi->jam_masuk = $absensiPending->jam;
                $absensi->latitude_masuk = $absensiPending->latitude;
                $absensi->longitude_masuk = $absensiPending->longitude;
            } elseif ($absensiPending->jenis === 'pulang') {
                $absensi->jam_pulang = $absensiPending->jam;
                $absensi->latitude_pulang = $absensiPending->latitude;
                $absensi->longitude_pulang = $absensiPending->longitude;
            }

            $absensi->save();

            // Hapus data pending
            $absensiPending->delete();

            DB::commit();

            Log::info('Absensi berhasil ditolak', [
                'pending_id' => $id,
                'absensi_id' => $absensi->id
            ]);

            // Set session flash data
            session()->flash('success', 'Absensi berhasil ditolak' . ($request->catatan ? ' dengan alasan: ' . $request->catatan : ''));

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil ditolak' . ($request->catatan ? ' dengan alasan: ' . $request->catatan : ''),
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in tolakAbsensi: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Hitung statistik kehadiran
     */
    private function hitungStatistik($user)
    {
        $bulanIni = Carbon::now()->startOfMonth();
        $bulanSekarang = Carbon::now()->endOfMonth();

        $query = User::where('role', 'siswa');

        if ($user->role === 'iduka') {
            $query->where('iduka_id', $user->iduka_id);
        } elseif ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('pembimbing_id', $guru->id);
            }
        } elseif ($user->role === 'kaprog') {
            // PERBAIKAN: Untuk kaprog, hitung statistik hanya untuk siswa yang dibimbingnya
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $query->where('pembimbing_id', $guru->id);
            }
        }

        $totalSiswa = $query->count();

        $absensiQuery = Absensi::whereBetween('tanggal', [$bulanIni, $bulanSekarang]);

        if ($user->role === 'iduka') {
            $absensiQuery->where('iduka_id', $user->iduka_id);
        } elseif ($user->role === 'guru') {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $absensiQuery->whereHas('user', function ($q) use ($guru) {
                    $q->where('pembimbing_id', $guru->id);
                });
            }
        } elseif ($user->role === 'kaprog') {
            // PERBAIKAN: Untuk kaprog, hitung statistik hanya untuk siswa yang dibimbingnya
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                $absensiQuery->whereHas('user', function ($q) use ($guru) {
                    $q->where('pembimbing_id', $guru->id);
                });
            }
        }

        $absensiStats = $absensiQuery->selectRaw('
                COUNT(*) as total_absensi,
                SUM(CASE WHEN status = "tepat_waktu" THEN 1 ELSE 0 END) as tepat_waktu,
                SUM(CASE WHEN status = "terlambat" THEN 1 ELSE 0 END) as terlambat,
                SUM(CASE WHEN status = "izin" THEN 1 ELSE 0 END) as izin,
                SUM(CASE WHEN status = "sakit" THEN 1 ELSE 0 END) as sakit,
                SUM(CASE WHEN status = "alpha" THEN 1 ELSE 0 END) as alpha
            ')->first();

        // Hitung persentase kehadiran
        $totalAbsensi = $absensiStats->total_absensi ?? 0;
        $persentaseKehadiran = $totalSiswa > 0 ? round(($totalAbsensi / ($totalSiswa * Carbon::now()->day)) * 100, 2) : 0;

        return [
            'total_siswa' => $totalSiswa,
            'persentase_kehadiran' => $persentaseKehadiran,
            'tepat_waktu' => $absensiStats->tepat_waktu ?? 0,
            'terlambat' => $absensiStats->terlambat ?? 0,
            'izin' => $absensiStats->izin ?? 0,
            'sakit' => $absensiStats->sakit ?? 0,
            'alpha' => $absensiStats->alpha ?? 0,
        ];
    }

    /**
     * Get status label untuk display
     */
    private function getStatusLabel($status)
    {
        return match ($status) {
            'tepat_waktu' => 'Tepat Waktu',
            'terlambat' => 'Terlambat',
            'izin' => 'Izin',
            'sakit' => 'Sakit',
            'alpha' => 'Alpha',
            default => 'Tidak Diketahui'
        };
    }

    /**
     * Get status class untuk styling
     */
    private function getStatusClass($status)
    {
        return match ($status) {
            'tepat_waktu' => 'bg-success',
            'terlambat' => 'bg-warning',
            'izin' => 'bg-info',
            'sakit' => 'bg-secondary',
            'alpha' => 'bg-danger',
            default => 'bg-light'
        };
    }

    /**
     * Export laporan absensi
     */
    public function exportLaporan(Request $request)
    {
        // TODO: Implementasi export ke Excel/PDF
        return response()->json([
            'success' => true,
            'message' => 'Laporan berhasil digenerate',
            'download_url' => '#'
        ]);
    }

    /**
     * Method untuk cek dan debug absensi pending
     */
    public function debugAbsensiPending()
    {
        $user = Auth::user();

        $pending = AbsensiPending::where('iduka_id', $user->iduka_id)->get();
        $absensi = Absensi::where('iduka_id', $user->iduka_id)->get();

        return response()->json([
            'pending_count' => $pending->count(),
            'absensi_count' => $absensi->count(),
            'pending_data' => $pending->toArray(),
            'absensi_data' => $absensi->toArray()
        ]);
    }

    /**
     * Method untuk proses konfirmasi absensi yang diperbaiki
     */
    public function prosesKonfirmasiAbsen(Request $request, $id)
    {
        try {
            Log::info('=== PROSES KONFIRMASI ABSEN ===', [
                'pending_id' => $id,
                'user_id' => Auth::id()
            ]);

            $absensiPending = AbsensiPending::findOrFail($id);
            $user = Auth::user();

            // Validasi ownership
            if ($absensiPending->iduka_id !== $user->iduka_id) {
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            DB::beginTransaction();

            // Cek apakah sudah ada absensi untuk tanggal tersebut
            $absensi = Absensi::where('user_id', $absensiPending->user_id)
                ->where('iduka_id', $absensiPending->iduka_id)
                ->whereDate('tanggal', $absensiPending->tanggal)
                ->first();

            if (!$absensi) {
                // Buat record absensi baru
                $absensi = new Absensi();
                $absensi->user_id = $absensiPending->user_id;
                $absensi->iduka_id = $absensiPending->iduka_id;
                $absensi->tanggal = $absensiPending->tanggal;
                $absensi->status = $absensiPending->status;
            }

            // Update data sesuai jenis
            if ($absensiPending->jenis === 'masuk') {
                $absensi->jam_masuk = $absensiPending->jam;
                $absensi->latitude_masuk = $absensiPending->latitude;
                $absensi->longitude_masuk = $absensiPending->longitude;
            } elseif ($absensiPending->jenis === 'pulang') {
                $absensi->jam_pulang = $absensiPending->jam;
                $absensi->latitude_pulang = $absensiPending->latitude;
                $absensi->longitude_pulang = $absensiPending->longitude;
            }

            $absensi->save();

            // Hapus pending setelah berhasil
            $absensiPending->delete();

            DB::commit();

            // Set session flash data
            session()->flash('success', 'Absensi berhasil dikonfirmasi');

            Log::info('Redirect URL yang akan dikirim', [
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil dikonfirmasi',
                'redirect_url' => route('iduka.konfirmasi-absen')
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in prosesKonfirmasiAbsen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function kordinat(Request $request)
    {
        $request->validate([
            'iduka_id' => 'required|exists:idukas,id',
            'latitude' => 'required',
            'longitude' => 'required',
            'radius' => 'required',
            'jam_masuk' => 'nullable|date_format:H:i',
            'jam_pulang' => 'nullable|date_format:H:i',
        ]);

        $iduka = Iduka::find($request->iduka_id);
        $iduka->update([
            'latitude' => $request->latitude,
            'longitude' => $request->longitude,
            'radius' => $request->radius,
            'jam_masuk' => $request->jam_masuk,
            'jam_pulang' => $request->jam_pulang
        ]);

        return redirect()->back()->with('success', 'Koordinat berhasil diperbarui!');
    }

    public function getData($id)
    {
        $iduka = Iduka::find($id);

        if (!$iduka) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        return response()->json($iduka);
    }


    /**
     * Detail absen
     */
    public function detailAbsen($id)
    {
        try {
            $absensi = Absensi::with(['user', 'iduka'])->findOrFail($id);

            // Cek otorisasi
            $user = Auth::user();
            if ($user->role === 'iduka' && $absensi->iduka_id != $user->iduka_id) {
                return redirect()->back()->with('error', 'Unauthorized access');
            } elseif (in_array($user->role, ['guru', 'kaprog'])) {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || $absensi->user->pembimbing_id != $guru->id) {
                    return redirect()->back()->with('error', 'Anda bukan pembimbing siswa ini');
                }
            }

            return view('iduka.konfir_absen_siswa.detail_absen', compact('absensi'));
        } catch (\Exception $e) {
            Log::error('Error in detailAbsen: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Detail izin
     */
    public function detailIzin($id)
    {
        try {
            $izin = IzinPending::with(['user', 'iduka'])->findOrFail($id);

            // Cek otorisasi
            $user = Auth::user();
            if ($user->role === 'iduka' && $izin->iduka_id != $user->iduka_id) {
                return redirect()->back()->with('error', 'Unauthorized access');
            } elseif (in_array($user->role, ['guru', 'kaprog'])) {
                $guru = Guru::where('user_id', $user->id)->first();
                if (!$guru || $izin->user->pembimbing_id != $guru->id) {
                    return redirect()->back()->with('error', 'Anda bukan pembimbing siswa ini');
                }
            }

            return view('iduka.konfir_absen_siswa.detail_izin', compact('izin'));
        } catch (\Exception $e) {
            Log::error('Error in detailIzin: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Data tidak ditemukan');
        }
    }

    /**
     * Get absensi hari ini untuk API
     */
    public function getAbsensiHariIni(Request $request)
    {
        try {
            $user = Auth::user();
            $today = Carbon::today();

            $query = Absensi::with(['user', 'iduka'])
                ->whereDate('tanggal', $today);

            if ($user->role === 'iduka') {
                $query->where('iduka_id', $user->iduka_id);
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            }

            $absensi = $query->get();

            return response()->json([
                'success' => true,
                'data' => $absensi
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAbsensiHariIni: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get absensi pending untuk API
     */
    public function getAbsensiPending(Request $request)
    {
        try {
            $user = Auth::user();

            $query = AbsensiPending::with(['user', 'iduka'])
                ->where('status_konfirmasi', 'pending');

            if ($user->role === 'iduka') {
                $query->where('iduka_id', $user->iduka_id);
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->where('pembimbing_id', $guru->id);
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->where('pembimbing_id', $guru->id);
                }
            }

            $absensiPending = $query->latest()->get();

            return response()->json([
                'success' => true,
                'data' => $absensiPending
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getAbsensiPending: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get izin pending untuk API
     */
    public function getIzinPending(Request $request)
    {
        try {
            $user = Auth::user();

            $query = IzinPending::with(['user', 'iduka'])
                ->where('status_konfirmasi', 'pending');

            if ($user->role === 'iduka') {
                $query->where('iduka_id', $user->iduka_id);
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            }

            $izinPending = $query->latest()->get();

            return response()->json([
                'success' => true,
                'data' => $izinPending
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getIzinPending: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Filter riwayat absensi
     */
    public function filterRiwayat(Request $request)
    {
        try {
            $user = Auth::user();
            $tanggalDari = $request->tanggal_dari;
            $tanggalSampai = $request->tanggal_sampai;
            $filterSiswa = $request->filter_siswa_riwayat;

            $query = Absensi::with(['user', 'iduka']);

            if ($user->role === 'iduka') {
                $query->where('iduka_id', $user->iduka_id);
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            }

            if ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            }
            if ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            }
            if ($filterSiswa) {
                $query->where('user_id', $filterSiswa);
            }

            $riwayat = $query->latest()->get();

            return view('iduka.konfir_absen_siswa.riwayat_table', compact('riwayat'));
        } catch (\Exception $e) {
            Log::error('Error in filterRiwayat: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
    /**
     * Get riwayat absensi untuk AJAX
     */
    public function getRiwayatAbsen(Request $request)
    {
        try {
            $user = Auth::user();
            $tanggalDari = $request->tanggal_dari;
            $tanggalSampai = $request->tanggal_sampai;
            $filterSiswa = $request->siswa_id;

            $query = Absensi::with(['user', 'iduka']);

            if ($user->role === 'iduka') {
                $query->where('iduka_id', $user->iduka_id);
            } elseif ($user->role === 'guru') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            } elseif ($user->role === 'kaprog') {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $query->whereHas('user', function ($q) use ($guru) {
                        $q->where('pembimbing_id', $guru->id);
                    });
                }
            }

            if ($tanggalDari) {
                $query->whereDate('tanggal', '>=', $tanggalDari);
            }
            if ($tanggalSampai) {
                $query->whereDate('tanggal', '<=', $tanggalSampai);
            }
            if ($filterSiswa) {
                $query->where('user_id', $filterSiswa);
            }

            $riwayat = $query->latest()->get();

            // Format data untuk response
            $data = $riwayat->map(function ($item) {
                return [
                    'tanggal' => Carbon::parse($item->tanggal)->format('d-m-Y'),
                    'nama_siswa' => $item->user->name,
                    'email' => $item->user->email,
                    'jam_masuk' => $item->jam_masuk ? Carbon::parse($item->jam_masuk)->format('H:i') . ' WIB' : '-',
                    'jam_pulang' => $item->jam_pulang ? Carbon::parse($item->jam_pulang)->format('H:i') . ' WIB' : '-',
                    'status' => $item->status
                ];
            });

            return response()->json([
                'success' => true,
                'data' => $data
            ]);
        } catch (\Exception $e) {
            Log::error('Error in getRiwayatAbsen: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * Get holidays for an iduka (JSON)
     */
    public function getHolidays(Request $request, $idukaId)
    {
        try {
            $user = Auth::user();

            // only iduka users or privileged roles can view; but allow guru/kaprog to view as well
            if (!in_array($user->role, ['iduka', 'guru', 'kaprog'])) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $holidays = IdukaHoliday::where('iduka_id', $idukaId)->orderBy('date', 'desc')->get();

            return response()->json(['success' => true, 'data' => $holidays]);
        } catch (\Exception $e) {
            Log::error('Error in getHolidays: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }

    /**
     * Store a holiday for an iduka
     */
    public function saveHoliday(Request $request)
    {
        $request->validate([
            'iduka_id' => 'required|exists:idukas,id',
            'date' => 'required|date',
            'name' => 'nullable|string|max:255',
            'recurring' => 'sometimes|boolean'
        ]);

        $user = Auth::user();

        // Allow iduka, guru, kaprog to manage holidays.
        // - iduka: only for their own iduka_id
        // - guru/kaprog: only if they are pembimbing for students in that iduka
        $allowed = false;
        if ($user->role === 'iduka' && $user->iduka_id && $user->iduka_id == $request->iduka_id) {
            $allowed = true;
        } elseif (in_array($user->role, ['guru', 'kaprog'])) {
            $guru = Guru::where('user_id', $user->id)->first();
            if ($guru) {
                // check if guru mentors any student in that iduka
                $has = User::where('role', 'siswa')
                    ->where('iduka_id', $request->iduka_id)
                    ->where('pembimbing_id', $guru->id)
                    ->exists();
                if ($has) {
                    $allowed = true;
                }
            }
        }

        if (! $allowed) {
            return redirect()->back()->with('error', 'Anda tidak berwenang mengelola hari libur untuk IDUKA ini');
        }

        try {
            IdukaHoliday::create([
                'iduka_id' => $request->iduka_id,
                'date' => $request->date,
                'name' => $request->name,
                'recurring' => $request->has('recurring') ? (bool) $request->recurring : false
            ]);

            return redirect()->back()->with('success', 'Hari libur berhasil ditambahkan');
        } catch (\Exception $e) {
            Log::error('Error saving holiday: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Gagal menyimpan hari libur: ' . $e->getMessage());
        }
    }

    /**
     * Delete a holiday
     */
    public function deleteHoliday(Request $request, $id)
    {
        try {
            $user = Auth::user();
            $holiday = IdukaHoliday::find($id);
            if (!$holiday) {
                return response()->json(['success' => false, 'message' => 'Data tidak ditemukan'], 404);
            }

            // authorize: allow iduka owner or guru/kaprog who mentors students in that iduka
            $allowed = false;
            if ($user->role === 'iduka' && $user->iduka_id && $user->iduka_id == $holiday->iduka_id) {
                $allowed = true;
            } elseif (in_array($user->role, ['guru', 'kaprog'])) {
                $guru = Guru::where('user_id', $user->id)->first();
                if ($guru) {
                    $has = User::where('role', 'siswa')
                        ->where('iduka_id', $holiday->iduka_id)
                        ->where('pembimbing_id', $guru->id)
                        ->exists();
                    if ($has) $allowed = true;
                }
            }

            if (! $allowed) {
                return response()->json(['success' => false, 'message' => 'Unauthorized'], 403);
            }

            $holiday->delete();
            return response()->json(['success' => true, 'message' => 'Hari libur dihapus']);
        } catch (\Exception $e) {
            Log::error('Error deleting holiday: ' . $e->getMessage());
            return response()->json(['success' => false, 'message' => 'Terjadi kesalahan: ' . $e->getMessage()], 500);
        }
    }
}
