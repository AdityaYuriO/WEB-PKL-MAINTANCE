<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Penilaian;
use Barryvdh\DomPDF\Facade\Pdf;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\SimpleType\Jc;
use PhpOffice\PhpWord\SimpleType\VerticalJc;

class SertifikatController extends Controller
{
    private function getData($id)
    {
        $user      = User::findOrFail($id);
        $penilaian = Penilaian::where('users_id', $user->id)->get();

        $nilaiGuru  = $penilaian->where('jenis_penilaian', 'guru_pembimbing')->avg('nilai') ?? 0;
        $nilaiIduka = $penilaian->where('jenis_penilaian', 'instruktur_iduka')->avg('nilai') ?? 0;
        $nilaiAkhir = ($nilaiGuru + $nilaiIduka) / 2;

        $predikat = match(true) {
            $nilaiAkhir >= 86 => 'Sangat Baik',
            $nilaiAkhir >= 71 => 'Baik',
            $nilaiAkhir >= 56 => 'Cukup',
            $nilaiAkhir > 0   => 'Kurang',
            default           => 'Sangat Baik', // Default untuk siswa yang belum ada nilainya
        };

        // Extract kota from alamat if possible, or fallback to 'Kawali'
        $alamat = $user->iduka->alamat ?? '';
        $kotaIduka = 'Kawali'; 
        
        if (preg_match('/(?:Kab\.|Kabupaten|Kota)\s*([^,]+)/i', $alamat, $matches)) {
            $kotaIduka = trim($matches[1]);
        } elseif (stripos($alamat, 'Ciamis') !== false) {
            $kotaIduka = 'Ciamis';
        } elseif (stripos($alamat, 'Bandung') !== false) {
            $kotaIduka = 'Bandung';
        }

        $tglLahir = $user->dataPribadi->tgl_lahir ?? null;
        if ($tglLahir) {
            $tglLahir = \Carbon\Carbon::parse($tglLahir)->translatedFormat('d F Y');
        } else {
            $tglLahir = '-';
        }

        return [
            'user'            => $user,
            'nama'            => $user->name,
            'nis'             => $user->nip ?? ($user->dataPribadi->nip ?? '-'),
            'tempat_lahir'    => $user->dataPribadi->tempat_lhr ?? '-',
            'tgl_lahir'       => $tglLahir,
            'tahun_ajaran'    => $user->tahun_ajaran ?? '-',
            'asal_sekolah'    => 'SMKN 1 Kawali',
            'konsentrasi'     => $user->konke->name_konke ?? 'Konsentrasi Belum Diatur',
            'lama'            => '4 bulan',
            'tanggal_mulai'   => '13 Oktober 2025',
            'tanggal_selesai' => '14 Februari 2026',
            'predikat'        => $predikat,
            'kepala_sekolah'  => 'DEDE FAJRIADI, S.Pd., M.Pd',
            'nama_iduka'      => $user->iduka->nama ?? 'NAMA IDUKA',
            'foto_iduka'      => $user->iduka->foto ?? null,
            'alamat_iduka'    => $user->iduka->alamat ?? 'ALAMAT IDUKA',
            'kota_iduka'      => $kotaIduka,
            'nama_pimpinan'   => $user->iduka->nama_pimpinan ?? 'Nama Pimpinan',
        ];
    }

    public function cetakPdf($id)
    {
        $data = $this->getData($id);
        $user = $data['user'];

        $pdf      = Pdf::loadView('penilaian.sertifikat.index_pdf', $data)->setPaper('a4', 'landscape');
        $filename = 'Sertifikat-PKL-' . str_replace(' ', '-', strtolower($user->name)) . '.pdf';

        return $pdf->download($filename);
    }

    public function cetakWord($id)
    {
        $data = $this->getData($id);
        $user = $data['user'];

        $phpWord = new PhpWord();
        
        // Settings
        $sectionStyle = [
            'orientation' => 'landscape',
            'marginTop'    => 600,
            'marginBottom' => 600,
            'marginLeft'   => 600,
            'marginRight'  => 600,
        ];
        $section = $phpWord->addSection($sectionStyle);

        // Background Image (Border)
        $bgPath = public_path('images/templat-sertifikat.png');
        if (file_exists($bgPath)) {
            $section->addImage($bgPath, [
                'width'            => 800,
                'height'           => 560,
                'wrappingStyle'    => 'behind',
                'positioning'      => 'absolute',
                'posHorizontal'    => 'center',
                'posVertical'      => 'center',
                'posHorizontalRel' => 'page',
                'posVerticalRel'   => 'page',
            ]);
        }

        // Kop Table
        $table = $section->addTable(['width' => 100 * 50, 'unit' => 'pct', 'alignment' => Jc::CENTER]);
        $table->addRow();
        
        // Logo Cell
        $logoCell = $table->addCell(1500);
        if (!empty($data['foto_iduka'])) {
            $logoPath = storage_path('app/public/' . $data['foto_iduka']);
            if (file_exists($logoPath)) {
                $logoCell->addImage($logoPath, ['width' => 60, 'height' => 60, 'alignment' => Jc::LEFT]);
            }
        }

        // Text Cell
        $textCell = $table->addCell(8500);
        $textCell->addText($data['nama_iduka'], ['bold' => true, 'size' => 20, 'color' => '000000'], ['alignment' => Jc::CENTER]);
        $textCell->addText($data['alamat_iduka'], ['size' => 10, 'color' => '333333'], ['alignment' => Jc::CENTER]);
        
        // Line below Kop
        $section->addTextBreak(1);
        $section->addLine(['weight' => 2, 'width' => 700, 'height' => 0, 'color' => '000000', 'flip' => true]);

        // Title
        $section->addTextBreak(1);
        $section->addText('SERTIFIKAT', ['bold' => true, 'size' => 28, 'color' => '0f766e', 'letterSpacing' => 40], ['alignment' => Jc::CENTER]);
        $section->addText('Diberikan kepada:', ['italic' => true, 'size' => 12], ['alignment' => Jc::CENTER]);

        // Biodata
        $section->addTextBreak(1);
        $bioTable = $section->addTable(['alignment' => Jc::CENTER]);
        
        $bioData = [
            ['Nama Siswa', ': ', $data['nama']],
            ['Nomor Induk Siswa', ': ', $data['nis']],
            ['Tempat, Tanggal Lahir', ': ', $data['tempat_lahir'] . ', ' . $data['tgl_lahir']],
            ['Asal Sekolah', ': ', $data['asal_sekolah']],
            ['Tahun Pelajaran', ': ', $data['tahun_ajaran']],
        ];

        foreach ($bioData as $row) {
            $bioTable->addRow();
            $bioTable->addCell(3000)->addText($row[0], ['bold' => true, 'size' => 13]);
            $bioTable->addCell(200)->addText($row[1], ['size' => 13]);
            $bioTable->addCell(5000)->addText($row[2], ['bold' => true, 'size' => 13]);
        }

        // Body Text
        $section->addTextBreak(1);
        $textRun = $section->addTextRun(['alignment' => Jc::BOTH, 'lineHeight' => 1.5]);
        $textRun->addText('Telah melaksanakan Praktik Kerja Lapangan (PKL) untuk Konsentrasi Keahlian ');
        $textRun->addText($data['konsentrasi'], ['bold' => true]);
        $textRun->addText(' selama ' . $data['lama'] . ' dari tanggal ');
        $textRun->addText($data['tanggal_mulai'], ['bold' => true]);
        $textRun->addText(' sampai dengan ');
        $textRun->addText($data['tanggal_selesai'], ['bold' => true]);
        $textRun->addText(' dengan nilai yang tercantum di Rapor dengan Predikat : ');
        $textRun->addText($data['predikat'], ['bold' => true, 'color' => '0f766e', 'size' => 14]);

        // Footer Section (Foto & TTD)
        $section->addTextBreak(2);
        $footerTable = $section->addTable(['width' => 100 * 50, 'unit' => 'pct']);
        $footerTable->addRow(2000);
        
        // Foto Placeholder
        $fotoCell = $footerTable->addCell(5000, ['borderSize' => 6, 'borderColor' => '333333', 'borderStyle' => 'dashed', 'valign' => VerticalJc::CENTER]);
        $fotoCell->addText('Foto 3x4', ['size' => 9, 'color' => '666666'], ['alignment' => Jc::CENTER]);

        // Signature
        $sigCell = $footerTable->addCell(5000);
        $sigCell->addText($data['kota_iduka'] . ', 14 Februari 2026', ['size' => 12], ['alignment' => Jc::CENTER]);
        $sigCell->addText('Pimpinan IDUKA,', ['bold' => true, 'size' => 12], ['alignment' => Jc::CENTER]);
        $sigCell->addTextBreak(3);
        $sigCell->addText($data['nama_pimpinan'], ['bold' => true, 'size' => 12, 'underline' => 'single'], ['alignment' => Jc::CENTER]);

        $filename = 'Sertifikat-PKL-' . str_replace(' ', '-', strtolower($user->name)) . '.docx';
        $objWriter = IOFactory::createWriter($phpWord, 'Word2007');
        
        header('Content-Type: application/vnd.openxmlformats-officedocument.wordprocessingml.document');
        header('Content-Disposition: attachment;filename="' . $filename . '"');
        header('Cache-Control: max-age=0');
        
        $objWriter->save('php://output');
        exit;
    }

    public function previewEditable($id)
    {
        $data = $this->getData($id);
        return view('penilaian.sertifikat.preview_editable', $data);
    }

    public function cetakCustom(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        // Data diambil dari input request (hasil edit admin)
        $data = [
            'nama'            => $request->input('nama'),
            'nis'             => $request->input('nis'),
            'tempat_lahir'    => $request->input('tempat_lahir'),
            'tgl_lahir'       => $request->input('tgl_lahir'),
            'tahun_ajaran'    => $request->input('tahun_ajaran'),
            'asal_sekolah'    => $request->input('asal_sekolah'),
            
            // Gunakan teks penuh hasil edit jika ada
            'full_body'       => $request->input('full_body'),
            'full_date'       => $request->input('full_date'),
            'signature_role'  => $request->input('signature_role'),
            'logo_width'      => $request->input('logo_width', 85),
            'logo_top'        => $request->input('logo_top', 0),
            'logo_left'       => $request->input('logo_left', 0),
            'top_margin'      => $request->input('top_margin', 50),
            
            'nama_iduka'      => $request->input('nama_iduka'),
            'foto_iduka'      => $user->iduka->foto ?? null,
            'alamat_iduka'    => $request->input('alamat_iduka'),
            'nama_pimpinan'   => $request->input('nama_pimpinan'),
        ];

        $pdf      = Pdf::loadView('penilaian.sertifikat.index_pdf', $data)->setPaper('a4', 'landscape');
        $filename = 'Sertifikat-PKL-Custom-' . str_replace(' ', '-', strtolower($user->name)) . '.pdf';

        return $pdf->download($filename);
    }
}