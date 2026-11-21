<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SiswaExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return User::with(['kelas', 'konke'])
            ->where('role', 'siswa')
            ->get();
    }

    public function headings(): array
    {
        return [
            'No',
            'Nama',
            'NIS',
            'Kelas',
            'Konsentrasi Keahlian',
            'Email',
        ];
    }

    public function map($siswa): array
    {
        static $no = 0;
        $no++;

        return [
            $no,
            $siswa->name,
            $siswa->nip,
            optional($siswa->kelas)->kelas . ' ' . optional($siswa->kelas)->name_kelas,
            optional($siswa->konke)->name_konke,
            $siswa->email,
        ];
    }
}
