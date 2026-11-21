<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Fill;

class SiswaProgres implements FromCollection, WithHeadings, WithStyles, WithCustomStartCell, WithTitle
{
    protected $siswa;

    public function __construct($siswa)
    {
        $this->siswa = $siswa;
    }

    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        // Pisahkan data berdasarkan ada tidaknya iduka
        $siswaWithIduka = $this->siswa->filter(function($item) {
            return !empty($item->nama_iduka) && $item->nama_iduka !== null;
        });

        $siswaWithoutIduka = $this->siswa->filter(function($item) {
            return empty($item->nama_iduka) || $item->nama_iduka === null;
        });

        $result = collect();
        $counter = 1;

        // Pertama: Proses siswa yang SUDAH ADA IDUKA (dikelompokkan)
        if ($siswaWithIduka->isNotEmpty()) {
            $groupedSiswa = $siswaWithIduka->groupBy('nama_iduka')->sortKeys();

            foreach ($groupedSiswa as $namaIduka => $siswaGroup) {
                // Urutkan siswa dalam grup berdasarkan nama
                $sortedGroup = $siswaGroup->sortBy('nama_siswa');

                foreach ($sortedGroup as $item) {
                    $result->push([
                        'no' => $counter++,
                        'nama_siswa' => $item->nama_siswa,
                        'kelas' => $item->name_kelas,
                        'jurusan' => $item->jurusan,
                        'nama_iduka' => $item->nama_iduka,
                        'status_usulan' => $item->status_usulan ?? '-',
                        'status_surat_usulan' => $item->status_surat_usulan ?? '-',
                        'status_pengajuan' => $item->status_pengajuan ?? '-',
                    ]);
                }
            }
        }

        // Kedua: Proses siswa yang BELUM ADA IDUKA (di bawah)
        if ($siswaWithoutIduka->isNotEmpty()) {
            // Urutkan berdasarkan nama siswa
            $sortedWithoutIduka = $siswaWithoutIduka->sortBy('nama_siswa');

            foreach ($sortedWithoutIduka as $item) {
                $result->push([
                    'no' => $counter++,
                    'nama_siswa' => $item->nama_siswa,
                    'kelas' => $item->name_kelas,
                    'jurusan' => $item->jurusan,
                    'nama_iduka' => '-',
                    'status_usulan' => $item->status_usulan ?? '-',
                    'status_surat_usulan' => $item->status_surat_usulan ?? '-',
                    'status_pengajuan' => $item->status_pengajuan ?? '-',
                ]);
            }
        }

        return $result;
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        return [
            'no',
            'nama siswa',
            'kelas',
            'jurusan',
            'nama iduka',
            'status usulan',
            'status surat usulan',
            'status pengajuan'
        ];
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'A1';
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Data Siswa';
    }

    /**
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        $highestRow = $sheet->getHighestRow();
        $highestColumn = $sheet->getHighestColumn();

        // Set column widths
        $sheet->getColumnDimension('A')->setWidth(5);
        $sheet->getColumnDimension('B')->setWidth(20);
        $sheet->getColumnDimension('C')->setWidth(8);
        $sheet->getColumnDimension('D')->setWidth(15);
        $sheet->getColumnDimension('E')->setWidth(20);
        $sheet->getColumnDimension('F')->setWidth(15);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(18);

        // Header styling
        $sheet->getStyle('A1:' . $highestColumn . '1')->applyFromArray([
            'font' => [
                'bold' => true,
                'size' => 11,
                'color' => ['rgb' => '000000']
            ],
            'fill' => [
                'fillType' => Fill::FILL_SOLID,
                'startColor' => ['rgb' => 'E8E8E8']
            ],
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
                'vertical' => Alignment::VERTICAL_CENTER,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Data styling
        $sheet->getStyle('A2:' . $highestColumn . $highestRow)->applyFromArray([
            'font' => [
                'size' => 10
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' => Alignment::HORIZONTAL_LEFT,
            ],
            'borders' => [
                'allBorders' => [
                    'borderStyle' => Border::BORDER_THIN,
                    'color' => ['rgb' => '000000']
                ]
            ]
        ]);

        // Center align for no column
        $sheet->getStyle('A2:A' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        // Center align for kelas column
        $sheet->getStyle('C2:C' . $highestRow)->applyFromArray([
            'alignment' => [
                'horizontal' => Alignment::HORIZONTAL_CENTER,
            ]
        ]);

        return [];
    }
}
