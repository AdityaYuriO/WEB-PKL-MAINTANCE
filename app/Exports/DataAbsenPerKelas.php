<?php

namespace App\Exports;

use App\Models\Absensi;
use App\Models\User;
use App\Models\IdukaHoliday;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\AfterSheet;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Carbon\CarbonPeriod;

class DataAbsenPerKelas implements FromCollection, WithHeadings, WithEvents, ShouldAutoSize
{
    protected $start;
    protected $end;
    protected $konkeId;
    protected $kelasId;
    protected $guruId;
    protected $konkeName;
    protected $kelasName;

    public function __construct($start = null, $end = null, $konkeId = null, $kelasId = null, $guruId = null, $konkeName = null, $kelasName = null)
    {
        $this->start = $start;
        $this->end = $end;
        $this->konkeId = $konkeId;
        $this->kelasId = $kelasId;
        $this->guruId = $guruId;
        $this->konkeName = $konkeName;
        $this->kelasName = $kelasName;
    }

    public function collection()
    {
        // gather students constrained by provided filters and role (guruId)
        $userQuery = User::where('role', 'siswa')
            ->when($this->konkeId, function ($q) {
                $q->where('konke_id', $this->konkeId);
            })
            ->when($this->kelasId, function ($q) {
                $q->where('kelas_id', $this->kelasId);
            })
            ->when($this->guruId, function ($q) {
                $q->where('pembimbing_id', $this->guruId);
            });

        $students = $userQuery->select(['id', 'name', 'nip', 'kelas_id', 'iduka_id'])->with('kelas:id,name_kelas')->get();

        $studentIds = $students->pluck('id')->all();

        if (empty($studentIds)) {
            return collect([]);
        }

        // === Build Absence Query ===
        $absenQuery = Absensi::query();
        if ($this->start && $this->end) {
            $absenQuery->whereBetween('tanggal', [$this->start, $this->end]);
        }
        $absenQuery->whereIn('user_id', $studentIds);

        // === Hitung Kehadiran (H, S, I) dari Database ===
        $counts = $absenQuery->select([
            'absensi.user_id',
            DB::raw("SUM(CASE WHEN (absensi.status IN ('tepat_waktu','terlambat','hadir') OR (absensi.jenis_dinas IS NOT NULL AND absensi.status_dinas='disetujui')) THEN 1 ELSE 0 END) as H"),
            DB::raw("SUM(CASE WHEN absensi.status='izin' AND absensi.jenis_izin LIKE '%sakit%' THEN 1 ELSE 0 END) as S"),
            DB::raw("SUM(CASE WHEN absensi.status='izin' AND absensi.jenis_izin NOT LIKE '%sakit%' THEN 1 ELSE 0 END) as I"),
        ])->groupBy('absensi.user_id')->get()->keyBy('user_id');

        // === Persiapkan Holiday Data per IDUKA ===
        $idukaIds = $students->pluck('iduka_id')->unique()->filter();
        $holidaysByIduka = collect();

        if ($idukaIds->isNotEmpty()) {
            $holidaysByIduka = IdukaHoliday::whereIn('iduka_id', $idukaIds)
                ->where(function($q) {
                    if ($this->start && $this->end) {
                        $q->whereBetween('date', [$this->start, $this->end])
                          ->orWhere('recurring', true);
                    }
                })
                ->get()
                ->groupBy('iduka_id');
        }

        // === Hitung Effective Days dan Alpha per Siswa ===
        $period = CarbonPeriod::create($this->start, $this->end);

        $rows = $students->map(function ($s) use ($counts, $holidaysByIduka, $period) {
            $c = $counts->get($s->id);
            $H = $c->H ?? 0;
            $S = $c->S ?? 0;
            $I = $c->I ?? 0;

            // Hitung Hari Efektif untuk IDUKA siswa ini
            $effectiveDays = 0;
            $idukaId = $s->iduka_id;
            $liburIdukaIni = $holidaysByIduka->get($idukaId, collect());

            foreach ($period as $date) {
                // Skip Minggu
                if ($date->isSunday()) continue;

                // Cek apakah tanggal ini libur di IDUKA ini?
                $isHoliday = false;
                $dateStr = $date->format('Y-m-d');
                $mdStr = $date->format('m-d');

                foreach ($liburIdukaIni as $libur) {
                    $liburDate = is_string($libur->date) ? $libur->date : $libur->date->format('Y-m-d');
                    $liburMd = (is_string($libur->date) ? Carbon::parse($libur->date) : $libur->date)->format('m-d');

                    if ($liburDate == $dateStr || ($libur->recurring && $liburMd == $mdStr)) {
                        $isHoliday = true;
                        break;
                    }
                }

                if (!$isHoliday) {
                    $effectiveDays++;
                }
            }

            // Hitung Alpha
            $A = max(0, $effectiveDays - ($H + $S + $I));

            // hitung persentase kehadiran dari hari efektif
            $percent = 0;
            if ($effectiveDays > 0) {
                $percent = round(($H / $effectiveDays) * 100, 2);
            }

            return [
                'Nama Siswa' => $s->name ?? '-',
                'NIS' => $s->nip ?? '-',
                'Kelas' => optional($s->kelas)->name_kelas ?? '-',
                'Hadir (H)' => $H,
                'Sakit (S)' => $S,
                'Izin (I)' => $I,
                'Alpha (A)' => $A,
                'Persentase Kehadiran (%)' => $percent,
            ];
        });

        return $rows;
    }

    public function headings(): array
    {
        return [
            'Nama Siswa',
            'NIS',
            'Kelas',
            'Hadir (H)',
            'Sakit (S)',
            'Izin (I)',
            'Alpha (A)',
            'Persentase Kehadiran (%)',
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $sheet = $event->sheet->getDelegate();

                // build title and subtitle
                $title = 'Rekapan Absensi';
                if ($this->konkeName) {
                    $title .= ' - ' . $this->konkeName;
                }
                if ($this->kelasName) {
                    $title .= ' / ' . $this->kelasName;
                }

                $period = '';
                if ($this->start && $this->end) {
                    $period = 'Periode: ' . $this->start . ' s.d ' . $this->end;
                } elseif ($this->start) {
                    $period = 'Tanggal: ' . $this->start;
                }

                // insert two rows at top so headings shift down
                $sheet->insertNewRowBefore(1, 2);

                // write title and period
                $sheet->setCellValue('A1', $title);
                $sheet->setCellValue('A2', $period);

                // merge across columns A..H (8 columns)
                $sheet->mergeCells('A1:H1');
                $sheet->mergeCells('A2:H2');

                // style
                $sheet->getStyle('A1')->getFont()->setBold(true)->setSize(14);
                $sheet->getStyle('A1')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $sheet->getStyle('A2')->getFont()->setBold(false)->setSize(11);
                $sheet->getStyle('A2')->getAlignment()->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
            }
        ];
    }
}
