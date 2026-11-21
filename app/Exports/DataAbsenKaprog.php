<?php

namespace App\Exports;

use App\Models\Absensi;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Illuminate\Support\Facades\Auth;

class DataAbsenKaprog implements FromCollection, WithHeadings
{
    protected $tanggal;
    protected $konkeId;

    // jadikan $konkeId optional
    public function __construct($tanggal, $konkeId = null)
    {
        $this->tanggal = $tanggal;
        // jika tidak diberikan, ambil dari user yang sedang login
        $this->konkeId = $konkeId ?? (Auth::check() ? Auth::user()->konke_id : null);
    }

    public function collection()
    {
        $konkeId = $this->konkeId;

        $absensi = Absensi::with('user.kelas.konke')
            ->whereDate('tanggal', $this->tanggal)
            // jika konkeId ada, filter, kalau tidak -> ambil semua
            ->when($konkeId, function ($q) use ($konkeId) {
                $q->whereHas('user.kelas', function ($qq) use ($konkeId) {
                    $qq->where('konke_id', $konkeId);
                });
            })
            ->get();

        return $absensi->map(function ($row) {
            $kelas = $row->user->kelas ?? null;
            $konke  = $kelas->konke ?? null;

            return [
                'Nama Siswa' => $row->user->name ?? '-',
                // gabungkan tingkat + singkatan/jurusan + nomor paralel
                'Kelas' => trim(
                    ($kelas->kelas ?? '') .
                    ' ' .
                    ($konke->singkatan ?? $konke->name_konke ?? '') .
                    ' ' .
                    ($kelas->name_kelas ?? '')
                ),
                'Status' => ucfirst($row->status ?? '-'),
                'Tanggal' => $row->tanggal ? $row->tanggal->format('Y-m-d') : '-',
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'Kelas',
            'Status',
            'Tanggal',
        ];
    }
}
