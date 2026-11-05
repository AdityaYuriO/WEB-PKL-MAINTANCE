<?php

namespace App\Http\Controllers;

use App\Exports\SiswaProgres;
use App\Models\PengajuanUsulan;
use App\Models\PengajuanPkl;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class ProgresSiswaController extends Controller
{
    public function index(Request $request)
    {
        // Subquery pengajuan_pkl terbaru
        $latestPkl = DB::table('pengajuan_pkl as p1')
            ->select('p1.*')
            ->join(
                DB::raw('(SELECT siswa_id, MAX(created_at) as latest
                          FROM pengajuan_pkl
                          GROUP BY siswa_id) as p2'),
                function ($join) {
                    $join->on('p1.siswa_id', '=', 'p2.siswa_id')
                        ->on('p1.created_at', '=', 'p2.latest');
                }
            );

        // Subquery pengajuan_usulans terbaru
        $latestUsulan = DB::table('pengajuan_usulans as pu1')
            ->select('pu1.*')
            ->join(
                DB::raw('(SELECT user_id, MAX(created_at) as latest
                          FROM pengajuan_usulans
                          GROUP BY user_id) as pu2'),
                function ($join) {
                    $join->on('pu1.user_id', '=', 'pu2.user_id')
                        ->on('pu1.created_at', '=', 'pu2.latest');
                }
            );

        $query = DB::table('users as u')
            ->select(
                'u.id',
                'u.name as nama_siswa',
                'k.name_kelas',
                'ko.name_konke as jurusan',
                'i.nama as nama_iduka',
                'pu.status as status_usulan',
                'pu.surat_izin as status_surat_usulan',
                'p.status as status_pengajuan'
            )
            ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
            ->join('konkes as ko', 'k.konke_id', '=', 'ko.id')
            ->leftJoinSub($latestPkl, 'p', function ($join) {
                $join->on('u.id', '=', 'p.siswa_id');
            })
            ->leftJoinSub($latestUsulan, 'pu', function ($join) {
                $join->on('u.id', '=', 'pu.user_id');
            })
            ->leftJoin('idukas as i', 'p.iduka_id', '=', 'i.id')
            ->where('u.role', 'siswa')
            ->orderByDesc('u.id');

        // 🔍 filter jika ada
        if ($request->filled('nama_iduka')) {
            $search = $request->nama_iduka;
            $query->where(function ($q) use ($search) {
                $q->where('i.nama', 'like', '%' . $search . '%')
                    ->orWhere('ko.name_konke', 'like', '%' . $search . '%')
                    ->orWhere('u.name', 'like', '%' . $search . '%');
            });
        }


        $siswa = $query->paginate(10);

        // Hitung data usulan
        $totalUsulan = PengajuanUsulan::whereHas(
            'user', function ($query) {
                $query->where('status' , 'aktif')
                ->where('role', 'siswa');
            })->count();

        $totalUsulanDiterima = PengajuanUsulan::where('status', 'diterima')->whereHas(
            'user', function ($query) {
                $query->where('status', 'aktif')
                ->where('role', 'siswa');
            })->count();

        $totalUsulanDitolak = PengajuanUsulan::where('status', 'ditolak')->whereHas(
            'user', function ($query) {
                $query->where('status', 'aktif')
                ->where('role', 'siswa');
            })->count();

        // Hitung data pengajuan PKL
        $totalPengajuan = PengajuanPkl::whereHas(
            'siswa' , function ($query) {
                $query->where('status', 'aktif')
                ->where('role', 'siswa');
            })->count();

        $totalPengajuanDiterima = PengajuanPkl::where('status', 'diterima')
                        ->whereHas('siswa', function ($query) {
                            $query->where('status', 'aktif')
                            ->where('role', 'siswa');
                        })->count();

        $totalPengajuanDitolak = PengajuanPkl::where('status', 'ditolak')
                        ->whereHas('siswa', function ($query) {
                            $query->where('status', 'aktif')
                            ->where('role', 'siswa');
                        })->count();

        // Hitung siswa yang statusnya masih PROSES
        $totalBelumPKL = PengajuanPkl::where('status', 'proses')
                        ->whereHas('siswa', function ($query) {
                            $query->where('status', 'aktif')
                            ->where('role', 'siswa');
                        })->count();



        return view('siswa_progres.index', compact(
            'siswa',
            'totalUsulan',
            'totalUsulanDiterima',
            'totalUsulanDitolak',
            'totalPengajuan',
            'totalPengajuanDiterima',
            'totalPengajuanDitolak',
            'totalBelumPKL' // ⬅️ jangan lupa dikirim ke view
        ));
    }


public function export()
    {
        // 1. Subquery untuk mengambil pengajuan pkl terbaru per siswa
        $latestPkl = DB::table('pengajuan_pkl as p1')
            ->select('p1.*')
            ->join(
                DB::raw('(SELECT siswa_id, MAX(created_at) as latest FROM pengajuan_pkl GROUP BY siswa_id) as p2'),
                function ($join) {
                    $join->on('p1.siswa_id', '=', 'p2.siswa_id')
                        ->on('p1.created_at', '=', 'p2.latest');
                }
            );

        // 2. Subquery untuk mengambil pengajuan usulan terbaru per user
        $latestUsulan = DB::table('pengajuan_usulans as pu1')
            ->select('pu1.*')
            ->join(
                DB::raw('(SELECT user_id, MAX(created_at) as latest FROM pengajuan_usulans GROUP BY user_id) as pu2'),
                function ($join) {
                    $join->on('pu1.user_id', '=', 'pu2.user_id')
                        ->on('pu1.created_at', '=', 'pu2.latest');
                }
            );

        // 3. Query utama siswa aktif dengan left join subquery (Aman dari Group By Error 1055)
        $siswa = DB::table('users as u')
            ->join('kelas as k', 'u.kelas_id', '=', 'k.id')
            ->join('konkes as ko', 'k.konke_id', '=', 'ko.id')
            ->leftJoinSub($latestPkl, 'p', function ($join) {
                $join->on('u.id', '=', 'p.siswa_id');
            })
            ->leftJoinSub($latestUsulan, 'pu', function ($join) {
                $join->on('u.id', '=', 'pu.user_id');
            })
            ->leftJoin('idukas as i', 'p.iduka_id', '=', 'i.id')
            ->where('u.role', 'siswa')
            ->where('u.status', 'aktif')
            ->select(
                'u.id',
                'u.name as nama_siswa',
                'k.name_kelas as kelas', // Menggunakan name_kelas yang benar
                'ko.name_konke as jurusan',
                'i.nama as nama_iduka',
                'p.status as status_pengajuan',
                'pu.status as status_usulan',
                'pu.surat_izin as status_surat_usulan'
            )
            ->orderByDesc('u.id')
            ->get();

        return Excel::download(new SiswaProgres($siswa), 'data_siswa_pkl.xlsx');
    }
}
