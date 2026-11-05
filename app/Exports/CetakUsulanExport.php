<?php

namespace App\Exports;

use App\Models\CetakUsulan;
use Maatwebsite\Excel\Concerns\FromCollection;

class CetakUsulanExport implements FromCollection
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return CetakUsulan::with([
                'dataPribadi.kelas',
                'iduka',
                'user'
            ])
            ->where('status', 'sudah')
            ->whereHas('user', function ($query) {
                $query->where('status', 'aktif');
            })
            ->get()
            ->map(function ($item) {
                return [
                    'Nama Siswa'   => $item->dataPribadi->name ?? '-',
                    'Kelas'        => $item->dataPribadi->kelas->name_kelas ?? '-',
                    'Nama IDUKA'   => $item->iduka->nama ?? '-',
                    'Alamat IDUKA' => $item->iduka->alamat ?? '-',
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
