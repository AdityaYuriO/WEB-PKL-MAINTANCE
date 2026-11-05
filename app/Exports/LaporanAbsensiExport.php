<?php

namespace App\Exports;

use App\Models\Absensi;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class LaporanAbsensiExport implements FromCollection, WithHeadings, WithMapping
{
    protected $request;

    public function __construct(array $request)
    {
        $this->request = $request;
    }

    public function collection(): Collection
    {
        $query = Absensi::with(['siswa', 'jurusan', 'iduka']);

        if (!empty($this->request['start_date'])) {
            $query->whereDate('tanggal', '>=', $this->request['start_date']);
        }

        if (!empty($this->request['end_date'])) {
            $query->whereDate('tanggal', '<=', $this->request['end_date']);
        }

        if (!empty($this->request['jurusan'])) {
            $query->where('jurusan_id', $this->request['jurusan']);
        }

        if (!empty($this->request['iduka'])) {
            $query->where('iduka_id', $this->request['iduka']);
        }

        if (!empty($this->request['status']) && $this->request['status'] != 'all') {
            $query->where('status', $this->request['status']);
        }

        return $query->get()->map(function ($item) {
            return [
                'tanggal'     => $item->tanggal,
                'nama_siswa'  => $item->siswa->name ?? '-',
                'email'       => $item->siswa->email ?? '-',
                'jurusan'     => $item->jurusan->name_konke ?? '-',
                'iduka'       => $item->iduka->nama ?? '-',
                'status'      => $item->status,
                'jam'         => $item->jam,
                'keterangan'  => $item->keterangan ?? '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Tanggal',
            'Nama Siswa',
            'Email',
            'Jurusan',
            'IDUKA',
            'Status',
            'Jam',
            'Keterangan'
        ];
    }

    public function map($row): array
    {
        return [
            $row['tanggal'],
            $row['nama_siswa'],
            $row['email'],
            $row['jurusan'],
            $row['iduka'],
            $row['status'],
            $row['jam'],
            $row['keterangan']
        ];
    }
}
