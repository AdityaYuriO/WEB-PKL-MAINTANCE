<?php

namespace App\Exports;

use App\Models\CetakUsulan;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class CetakUsulanExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return CetakUsulan::query()
            ->select('cetak_usulans.*')
            ->join('data_pribadis', 'cetak_usulans.siswa_id', '=', 'data_pribadis.user_id')
            ->leftJoin('kelas', 'data_pribadis.kelas_id', '=', 'kelas.id')
            ->join('users', 'cetak_usulans.siswa_id', '=', 'users.id')
            ->where('cetak_usulans.status', 'sudah')
            ->where('users.status', 'aktif')
            ->orderBy('kelas.name_kelas', 'asc')
            ->orderBy('data_pribadis.name', 'asc')
            ->with(['dataPribadi.kelas', 'iduka', 'user'])
            ->get()
            ->map(function ($item) {
                return [
                    'Nama Siswa'   => optional($item->dataPribadi)->name ?? '-',
                    'Kelas'        => optional(optional($item->dataPribadi)->kelas)->name_kelas ?? '-',
                    'Nama IDUKA'   => optional($item->iduka)->nama ?? '-',
                    'Alamat IDUKA' => optional($item->iduka)->alamat ?? '-',
                    'Status'       => $item->status,
                    'Dikirim'      => $item->dikirim ?? '-',
                ];
            });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Nama IDUKA',
            'Alamat IDUKA',
            'Status',
            'Dikirim',
        ];
    }
}