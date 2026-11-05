<?php

namespace App\Http\Controllers;

use App\Models\AbsensiGuru;
use App\Models\PengajuanPkl;
use App\Models\PengajuanUsulan;
use App\Models\User;
use App\Models\UsulanIduka;
use App\Models\PindahPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;

class HakAksesController extends Controller
{
    public function hubin()
    {
        // Data Usulan dari dua tabel
        $jumlahUsulan = PengajuanUsulan::whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count() + UsulanIduka::whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count();

        // Status Diterima
        $jumlahDiterima = PengajuanUsulan::where('status', 'diterima')->
            whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count() + UsulanIduka::where('status', 'diterima')->whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count();

        // Status Ditolak
        $jumlahDitolak = PengajuanUsulan::where('status', 'ditolak')->
            whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count() + UsulanIduka::where('status', 'ditolak')->whereHas('user', function ($query) {
            $query->where('status', 'aktif');
        })->count();



        return view('hubin.dashboard', compact(
            'jumlahUsulan',
            'jumlahDiterima',
            'jumlahDitolak'
        ));
    }

    function siswa()
    {
        $user = auth()->user();

        $usulanSiswa = UsulanIduka::where('user_id', $user->id)->get();
        $pengajuanSiswa = PengajuanPkl::where('siswa_id', $user->id)->with('iduka')->get();
        $usulanPkl = PengajuanUsulan::with(['iduka'])->where('user_id', $user->id)->get();

        $sudahDiterima = PengajuanPkl::where('siswa_id', $user->id)
            ->where('status', 'diterima')
            ->exists();

        // 🔍 Cek apakah sudah pernah mengajukan usulan atau pengajuan PKL dan statusnya proses/diterima
        $usulanAktif = UsulanIduka::where('user_id', $user->id)
            ->whereIn('status', ['proses', 'diterima'])
            ->exists();

        $pengajuanAktif = PengajuanUsulan::where('user_id', $user->id)
            ->whereIn('status', ['proses', 'diterima'])
            ->exists();

        $sudahAjukan = $usulanAktif || $pengajuanAktif;

        $user = Auth::user()->load('pembimbing');

        // ✅ Cek status spesifik: proses atau diterima
        $statusAjukan = UsulanIduka::where('user_id', $user->id)
            ->whereIn('status', ['proses', 'diterima'])
            ->value('status') ?? PengajuanUsulan::where('user_id', $user->id)
                ->whereIn('status', ['proses', 'diterima'])
                ->value('status');

        // 🔍 Tambahan: Ambil data riwayat pindah PKL
        // Di Controller function siswa()
$riwayatPindahPkl = PindahPkl::where('siswa_id', $user->id)
    ->with([
        'idukaLama:id,nama',
        'historyPkl' // Load relasi historyPkl
    ])
    ->orderBy('created_at', 'desc')
    ->get();

        // 🔍 Cek status pengajuan pindah PKL terbaru
        $statusPindahPkl = PindahPkl::where('siswa_id', $user->id)
            ->whereIn('status', ['menunggu','menunggu_surat','diterima_iduka','ditolak_iduka','siap_kirim'])
            ->latest()
            ->first();

        return view('siswa.dashboard', compact(
            'usulanSiswa',
            'pengajuanSiswa',
            'usulanPkl',
            'sudahDiterima',
            'sudahAjukan',
            'statusAjukan',
            'user',
            'riwayatPindahPkl',
            'statusPindahPkl'
        ));
    }

    function guru()
    {
        $user = auth()->user();

        // Cek status absensi hari ini
        $todayAbsensi = AbsensiGuru::where('user_id', $user->id)
            ->where('tanggal', Carbon::today()->toDateString())
            ->first();

        $sudahAbsen = $todayAbsensi ? true : false;
        $sudahPulang = $todayAbsensi && $todayAbsensi->jam_pulang ? true : false;
        $statusHariIni = $todayAbsensi ? $todayAbsensi->status : null;

        // Ambil riwayat absensi guru (7 hari terakhir)
        $riwayatAbsensi = AbsensiGuru::where('user_id', $user->id)
            ->orderBy('tanggal', 'desc')
            ->orderBy('created_at', 'desc')
            ->take(7)
            ->get();

        // Data lokasi sekolah
        $lokasiSekolah = [
            'latitude' => -7.161891,
            'longitude' => 108.328864,
            'radius' => 1000 // dalam meter
        ];

        return view('dashboard', compact(
            'sudahAbsen',
            'sudahPulang',
            'statusHariIni',
            'riwayatAbsensi',
            'lokasiSekolah',
            'todayAbsensi'
        ));
    }


    function iduka()
    {
        return view('iduka.dashboard');
    }
    function kaprog()
    {
        return view('kaprog.dashboard');
    }
    function persuratan()
    {
        return view('persuratan.dashboard');
    }


    function ppkl()
    {
        return view('dashboard');
    }
    function orangtua()
    {
        return view('orangtua.dashboard');
    }
    function psekolah()
    {
        return view('dashboard');
    }
    function kepsek()
    {
        return view('dashboard');
    }
    function pendamping()
    {
        return view('dashboard');
    }
    public function index()
    {
        return view('login');
    }
    public function login(Request $request)
    {
        $request->validate([
            'nip' => 'required',
            'password' => 'required',
        ], [
            'nip.required' => 'nip Wajib Diisi',
            'password.required' => 'Password Wajib Diisi',
        ]);

        $infoLogin = [
            'nip' => $request->nip,
            'password' => $request->password,
        ];

        if (Auth::attempt($infoLogin)) {
            if (Auth::user()->status === 'alumni') {
                Auth::logout();
                return redirect('/login')->withErrors('Akun ini sudah berstatus alumni dan tidak dapat login.')->withInput();
            }

            if (Auth::user()->role == 'hubin') {
                return redirect()->route('hubin.dashboard');
            } elseif (Auth::user()->role == 'siswa') {
                return redirect()->route('siswa.dashboard');
            } elseif (Auth::user()->role == 'kaprog') {
                return redirect()->route('kaprog.dashboard');
            } elseif (Auth::user()->role == 'guru') {
                return redirect()->route('guru.dashboard');
            } elseif (Auth::user()->role == 'ppkl') {
                return redirect()->route('ppkl.dashboard');
            } elseif (Auth::user()->role == 'psekolah') {
                return redirect()->route('psekolah.dashboard');
            } elseif (Auth::user()->role == 'iduka') {
                return redirect()->route('iduka.dashboard');
            } elseif (Auth::user()->role == 'orangtua') {
                return redirect()->route('orangtua.dashboard');
            } elseif (Auth::user()->role == 'persuratan') {
                return redirect()->route('persuratan.dashboard');
            } elseif (Auth::user()->role == 'kepsek') {
                return redirect()->route('kepsek.dashboard');
            } elseif (Auth::user()->role == 'pendamping') {
                return redirect()->route('pendamping.dashboard');
            }
        } else {
            return redirect('/login')->withErrors('Username dan Password yang dimasukkan tidak sesuai')->withInput();
        }
    }
    public function logout()
    {
        Auth::logout();
        return redirect('');
    }
}
