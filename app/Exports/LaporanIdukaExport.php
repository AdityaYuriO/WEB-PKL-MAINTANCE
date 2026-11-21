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

class LaporanIdukaExport implements FromArray, ShouldAutoSize, WithStyles, WithTitle, WithColumnWidths
{
    protected $iduka;

    public function __construct(Iduka $iduka)
    {
        $this->iduka = $iduka;
    }

    public function array(): array
    {
        $data = [];

        // Header utama
        $data[] = ['LAPORAN DATA SISWA PKL - ' . strtoupper($this->iduka->nama)];
        $data[] = ['Tanggal Export: ' . date('d/m/Y H:i:s')];

        // Header tabel
        $data[] = ['No', 'Nama Siswa', 'Kelas', 'Nama IDUKA', 'Kota/Kab'];

        $no = 1;
        if ($this->iduka->siswa->count() > 0) {
            // Urutkan siswa berdasarkan nama
            $siswaList = $this->iduka->siswa->sortBy('name');

            foreach ($siswaList as $siswa) {
                $data[] = [
                    $no++,
                    $siswa->name,
                    $siswa->kelas->name_kelas ?? '-',
                    strtoupper($this->iduka->nama),
                    $this->iduka->kota ?? '-'
                ];
            }
        } else {
            // Jika IDUKA tidak memiliki siswa
            $data[] = [
                $no,
                'Belum ada siswa terdaftar',
                '-',
                strtoupper($this->iduka->nama),
                $this->iduka->kota ?? '-'
            ];
        }

        // Informasi IDUKA
        $data[] = [];
        $data[] = ['INFORMASI IDUKA:', '', '', '', ''];
        $data[] = ['Nama IDUKA', strtoupper($this->iduka->nama), '', '', ''];
        $data[] = ['Kota/Kabupaten', $this->iduka->kota ?? '-', '', '', ''];
        $data[] = ['Alamat', $this->iduka->alamat ?? '-', '', '', ''];
        $data[] = ['Total Siswa PKL', $this->iduka->siswa->count() . ' orang', '', '', ''];

        return $data;
    }

    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $infoStartRow = $highestRow - 5;

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

            // Data rows (dari baris 4 sampai sebelum informasi)
            'A4:E' . ($infoStartRow - 2) => [
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
            'A4:A' . ($infoStartRow - 2) => [
                'alignment' => [
                    'horizontal' => Alignment::HORIZONTAL_CENTER,
                ],
            ],

            // Header informasi IDUKA
            $infoStartRow + 1 => [
                'font' => [
                    'bold' => true,
                    'size' => 12,
                    'color' => ['rgb' => 'FFFFFF']
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => '1976D2'] // Biru
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

            // Data informasi IDUKA
            'A' . ($infoStartRow + 2) . ':B' . $highestRow => [
                'font' => [
                    'bold' => true,
                ],
                'fill' => [
                    'fillType' => Fill::FILL_SOLID,
                    'color' => ['rgb' => 'E3F2FD'] // Biru muda
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
        return 'Laporan ' . $this->iduka->nama . ' - ' . date('d-m-Y');
    }
}
