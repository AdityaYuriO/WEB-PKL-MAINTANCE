<?php

namespace App\Exports;

use App\Models\Konke;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class JurusanKehadiranExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        $jurusanData = DB::table('konkes')
            ->leftJoin('users', 'users.konke_id', '=', 'konkes.id')
            ->leftJoin('absensi', function ($join) {
                $join->on('absensi.user_id', '=', 'users.id')
                    ->whereDate('absensi.tanggal', Carbon::today());
            })
            ->select(
                'konkes.name_konke as jurusan',
                DB::raw('COUNT(DISTINCT users.id) as total_siswa'),
                DB::raw('COUNT(absensi.id) as total_hadir_today')
            )
            ->where('users.role', 'siswa')
            ->groupBy('konkes.id', 'konkes.name_konke')
            ->get();

        return $jurusanData->map(function ($item) {
            $total_siswa = (int) $item->total_siswa;
            $total_hadir = (int) $item->total_hadir_today;
            $persentase = $total_siswa > 0 ? round(($total_hadir / $total_siswa) * 100) : 0;

            return [
                'Jurusan'     => $item->jurusan,
                'Total Siswa' => $total_siswa,
                'Persentase'  => $persentase . '%',
            ];
        });
    }

    public function headings(): array
    {
        return ['Jurusan', 'Total Siswa', 'Persentase Kehadiran'];
    }
}
