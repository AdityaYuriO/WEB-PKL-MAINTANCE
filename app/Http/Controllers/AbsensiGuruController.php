<?php
// app/Http/Controllers/AbsensiGuruController.php
namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Carbon\Carbon;

class AbsensiGuruController extends Controller
{
    public function store(Request $request)
    {
        try {
            // Validasi input
            $validated = $request->validate([
                'status' => 'required|in:hadir,sakit,izin,alpha',
                'tanggal' => 'required|date',
                'latitude' => 'nullable|numeric',
                'longitude' => 'nullable|numeric',
                'jarak' => 'nullable|numeric',
                'jam_masuk' => 'nullable|date_format:H:i:s',
                'jam_pulang' => 'nullable|date_format:H:i:s',
                'keterangan' => 'nullable|string',
                'alasan' => 'nullable|string',
                'bukti_file' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048'
            ]);

            $userId = Auth::id();

            // Validasi: cek apakah sudah absen hari ini
            $existingAbsensi = AbsensiGuru::where('user_id', $userId)
                ->where('tanggal', $request->tanggal)
                ->first();

            if ($existingAbsensi) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda sudah melakukan absensi pada tanggal ini"
                ], 400);
            }

            // Handle upload file bukti
            $buktiFilePath = null;
            if ($request->hasFile('bukti_file')) {
                $buktiFilePath = $request->file('bukti_file')->store('bukti_absensi', 'public');
            }

            // Persiapan data
            $dataAbsensi = [
                'user_id' => $userId,
                'status' => $request->status,
                'tanggal' => $request->tanggal,
                'keterangan' => $request->keterangan,
                'alasan' => $request->alasan,
                'bukti_file' => $buktiFilePath
            ];

            // Jika status hadir, wajib ada koordinat dan jam
            if ($request->status === 'hadir') {
                if (!$request->latitude || !$request->longitude) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Koordinat lokasi wajib diisi untuk status hadir'
                    ], 400);
                }

                $dataAbsensi['latitude'] = $request->latitude;
                $dataAbsensi['longitude'] = $request->longitude;
                $dataAbsensi['jarak'] = $request->jarak;
                $dataAbsensi['jam_masuk'] = $request->jam_masuk ?? Carbon::now()->format('H:i:s');
                $dataAbsensi['jam_pulang'] = $request->jam_pulang;
            }

            // Simpan data absensi
            $absensi = AbsensiGuru::create($dataAbsensi);

            return response()->json([
                'success' => true,
                'message' => 'Absensi berhasil disimpan',
                'data' => $absensi
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            return response()->json([
                'success' => false,
                'message' => 'Validasi gagal',
                'errors' => $e->errors()
            ], 422);
        } catch (\Exception $e) {
            Log::error('Error saving absensi: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat menyimpan data: ' . $e->getMessage()
            ], 500);
        }
    }

    // Method untuk update jam pulang
    public function updateJamPulang(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'jam_pulang' => 'required|date_format:H:i:s'
            ]);

            // Cari absensi berdasarkan ID dan user yang sedang login
            $absensi = AbsensiGuru::where('id', $id)
                ->where('user_id', Auth::id())
                ->where('status', 'hadir')
                ->where('tanggal', Carbon::today()->toDateString()) // Pastikan hanya hari ini
                ->first();

            if (!$absensi) {
                return response()->json([
                    'success' => false,
                    'message' => 'Data absensi tidak ditemukan atau Anda tidak memiliki akses'
                ], 404);
            }

            if ($absensi->jam_pulang) {
                return response()->json([
                    'success' => false,
                    'message' => 'Jam pulang sudah diisi sebelumnya'
                ], 400);
            }

            $absensi->update([
                'jam_pulang' => $request->jam_pulang
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Jam pulang berhasil disimpan',
                'data' => $absensi
            ]);

        } catch (\Exception $e) {
            Log::error('Error update jam pulang: ' . $e->getMessage());

            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }
}
