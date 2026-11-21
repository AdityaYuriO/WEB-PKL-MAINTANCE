<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AbsensiPending;
use App\Models\IzinPending;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class GuruAbsensiController extends Controller
{
    public function index()
    {
        $guru = Auth::user();
        $siswaIds = User::where('pembimbing_id', $guru->id)
                      ->where('role', 'siswa')
                      ->pluck('id');

        $absensiPending = AbsensiPending::whereIn('user_id', $siswaIds)
            ->where('status_konfirmasi', 'disetujui')
            ->where('status_konfirmasi_guru', 'pending')
            ->with('user', 'iduka')
            ->orderBy('created_at', 'desc')
            ->get();

        $izinPending = IzinPending::whereIn('user_id', $siswaIds)
            ->where('status_konfirmasi', 'disetujui')
            ->where('status_konfirmasi_guru', 'pending')
            ->with('user', 'iduka')
            ->orderBy('created_at', 'desc')
            ->get();

        return view('guru.absensi.index', compact('absensiPending', 'izinPending'));
    }

    public function approveAbsensi($id)
    {
        $absensi = AbsensiPending::findOrFail($id);
        if ($absensi->user->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }
        $absensi->update([
            'status_konfirmasi_guru' => 'disetujui',
            'waktu_konfirmasi_guru' => Carbon::now(),
        ]);
        if ($absensi->status_konfirmasi === 'disetujui' && $absensi->status_konfirmasi_guru === 'disetujui') {
            $this->pindahkanKeAbsensi($absensi);
            $absensi->delete();
        }
        return redirect()->back()->with('success', 'Absensi berhasil disetujui.');
    }

    public function rejectAbsensi(Request $request, $id)
    {
        $request->validate([
            'catatan_guru' => 'required|string|min:10|max:500',
        ]);
        $absensi = AbsensiPending::findOrFail($id);
        if ($absensi->user->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }
        $absensi->update([
            'status_konfirmasi_guru' => 'ditolak',
            'catatan_guru' => $request->catatan_guru,
            'waktu_konfirmasi_guru' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Absensi berhasil ditolak.');
    }

    public function approveIzin($id)
    {
        $izin = IzinPending::findOrFail($id);
        if ($izin->user->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }
        $izin->update([
            'status_konfirmasi_guru' => 'disetujui',
            'waktu_konfirmasi_guru' => Carbon::now(),
        ]);
        if ($izin->status_konfirmasi === 'disetujui' && $izin->status_konfirmasi_guru === 'disetujui') {
            $this->pindahkanKeAbsensiIzin($izin);
            $izin->delete();
        }
        return redirect()->back()->with('success', 'Izin berhasil disetujui.');
    }

    public function rejectIzin(Request $request, $id)
    {
        $request->validate([
            'catatan_guru' => 'required|string|min:10|max:500',
        ]);
        $izin = IzinPending::findOrFail($id);
        if ($izin->user->pembimbing_id !== Auth::id()) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses.');
        }
        $izin->update([
            'status_konfirmasi_guru' => 'ditolak',
            'catatan_guru' => $request->catatan_guru,
            'waktu_konfirmasi_guru' => Carbon::now(),
        ]);
        return redirect()->back()->with('success', 'Izin berhasil ditolak.');
    }

    private function pindahkanKeAbsensi($absensiPending)
    {
        \App\Models\Absensi::create([
            'user_id' => $absensiPending->user_id,
            'iduka_id' => $absensiPending->iduka_id,
            'tanggal' => $absensiPending->tanggal,
            'jam_masuk' => $absensiPending->jenis === 'masuk' ? $absensiPending->jam : null,
            'jam_pulang' => $absensiPending->jenis === 'pulang' ? $absensiPending->jam : null,
            'latitude' => $absensiPending->latitude,
            'longitude' => $absensiPending->longitude,
            'status' => $absensiPending->status,
            'keterangan' => $absensiPending->keterangan,
        ]);
    }

    private function pindahkanKeAbsensiIzin($izinPending)
    {
        \App\Models\Absensi::create([
            'user_id' => $izinPending->user_id,
            'iduka_id' => $izinPending->iduka_id,
            'tanggal' => $izinPending->tanggal,
            'status' => 'izin',
            'jenis_izin' => $izinPending->jenis_izin,
            'keterangan_izin' => $izinPending->keterangan,
        ]);
    }
}