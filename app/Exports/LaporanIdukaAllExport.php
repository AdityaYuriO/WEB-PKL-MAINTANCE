<?php

namespace App\Exports;

use App\Models\Iduka;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class LaporanIdukaAllExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle, WithColumnWidths
{
    private $headerRow = 1;
    private $tableHeaderRow = 3;

    public function array(): array
    {
        $data = [];

        // Header utama
        $data[] = ['LAPORAN DATA SISWA PKL - SEMUA IDUKA'];
        $data[] = ['Tanggal Export: ' . date('d/m/Y H:i:s')];

        // Header tabel
        $data[] = ['No', 'Nama Siswa', 'Kelas', 'Nama IDUKA', 'Kota/Kab'];

        $idukas = Iduka::with(['siswa' => function ($query) {
                $query->where('status', 'aktif'); // Hanya ambil siswa yang statusnya aktif
            }, 'siswa.kelas'])
            ->orderBy('nama')
            ->get();

        $no = 1;
        foreach ($idukas as $iduka) {
            if ($iduka->siswa->count() > 0) {
                // Urutkan siswa berdasarkan nama
                $siswaList = $iduka->siswa->sortBy('name');

                foreach ($siswaList as $siswa) {
                    $data[] = [
                        $no++,
                        $siswa->name,
                        $siswa->kelas->name_kelas ?? '-',
                        strtoupper($iduka->nama),
                        $iduka->alamat ?? '-'
                    ];
                }
            } else {
                // Jika IDUKA tidak memiliki siswa, tetap tampilkan IDUKA-nya
                $data[] = [
                    $no++,
                    'Belum ada siswa terdaftar',
                    '-',
                    strtoupper($iduka->nama),
                    $iduka->kota ?? '-'
                ];
            }
        }

        // Ringkasan di bawah
        $totalSiswaAll = $idukas->sum(function($iduka) {
            return $iduka->siswa->count();
        });
        $totalIduka = $idukas->count();
        $idukaWithSiswa = $idukas->filter(function($iduka) {
            return $iduka->siswa->count() > 0;
        })->count();

        $data[] = [];
        $data[] = ['RINGKASAN:', '', '', '', ''];
        $data[] = ['Total IDUKA', $totalIduka, '', '', ''];
        $data[] = ['IDUKA dengan Siswa', $idukaWithSiswa, '', '', ''];
        $data[] = ['IDUKA tanpa Siswa', $totalIduka - $idukaWithSiswa, '', '', ''];
        $data[] = ['Total Siswa PKL', $totalSiswaAll . ' orang', '', '', ''];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $summaryStartRow = $highestRow - 5;

        return [
            // Header utama
            1 => [
                'font' => [
                    'bold' => true,
                    'size' => 16,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '2E7D32']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Tanggal export
            2 => [
                'font' => [
                    'italic' => true,
                    'size' => 10
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Header tabel
            3 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '4CAF50']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Data rows (dari baris 4 sampai sebelum ringkasan)
            'A4:E' . ($summaryStartRow - 2) => [
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => 'CCCCCC'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                ],
            ],

            // Kolom No (center alignment)
            'A4:A' . ($summaryStartRow - 2) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Header ringkasan
            $summaryStartRow + 1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '1976D2']
                ],
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_LEFT,
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '000000'],
                    ],
                ],
            ],

            // Data ringkasan
            'A' . ($summaryStartRow + 2) . ':B' . $highestRow => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E3F2FD']
                ],
                'borders' => [
                    'allBorders' => [
                        'borderStyle' => Border::BORDER_THIN,
                        'color' => ['rgb' => '1976D2'],
                    ],
                ],
                'alignment' => [
                    'vertical' => Alignment::VERTICAL_CENTER,
                ],
            ],
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 5,   // No
            'B' => 35,  // Nama Siswa
            'C' => 15,  // Kelas
            'D' => 30,  // Nama IDUKA
            'E' => 40,  // Alamat
            'F' => 15,  // Telepon
            'G' => 25,  // Email
        ];
    }

    public function title(): string
    {
        return 'Laporan Siswa PKL - ' . date('d-m-Y');
    }
}
