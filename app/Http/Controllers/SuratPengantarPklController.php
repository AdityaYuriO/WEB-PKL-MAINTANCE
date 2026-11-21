<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Iduka;
use App\Models\User;
use PDF;
use ZipArchive;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\File;

class SuratPengantarPklController extends Controller
{
    /**
     * Menampilkan daftar IDUKA dengan pencarian dan pagination.
     */
    public function index(Request $request)
    {
        $query = Iduka::query();

        // Pencarian berdasarkan nama atau alamat
        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                    ->orWhere('alamat', 'like', "%{$search}%");
            });
        }


        $idukas = $query->paginate(10)->appends(['search' => $request->search]);


        return view('persuratan.surat_pengantar_pkl.index', compact('idukas'));
    }

    /**
     * Cetak surat pengantar untuk satu IDUKA.
     */
    public function cetak($id)
    {
        $iduka = Iduka::findOrFail($id);
        $siswa = User::with(['kelas.konke'])
            ->where('status', 'aktif')
            ->where('role', 'siswa')
            ->where('iduka_id', $iduka->id)
            ->get();

        // Validasi
        if ($siswa->isEmpty()) {
            return redirect()->back()->with('error', "IDUKA {$iduka->nama} tidak memiliki siswa.");
        }

        // Generate PDF
        $pdf = PDF::loadView('persuratan.surat_pengantar_pkl.surat', [
            'iduka' => $iduka,
            'siswa' => $siswa,
        ])->setPaper('a4', 'portrait');

        $namaFile = $this->createSafeFileName($iduka->nama);

        return $pdf->download($namaFile);
    }

    /**
     * Cetak semua surat pengantar dalam 1 file ZIP.
     */
    public function cetakSemua()
    {
        try {
            // Ambil semua IDUKA yang punya siswa
            $idukas = Iduka::whereHas('siswa', function ($query) {
                $query->where('role', 'siswa');
            })->pluck('id')->toArray();

            if (empty($idukas)) {
                return redirect()->back()
                    ->with('error', 'Tidak ada IDUKA yang memiliki siswa.');
            }

            // Atur batas resource
            ini_set('memory_limit', '2048M');
            set_time_limit(1200);

            // Ambil logo sekali saja
            $path = public_path('images/jawabarat.png');
            $logoBase64 = file_exists($path) ? base64_encode(file_get_contents($path)) : null;

            $tempPath = $this->createTempDirectory();
            $pdfFiles = [];

            // Proses satu per satu untuk menghemat memori
            foreach ($idukas as $idukaId) {
                // Reset memory sebelum setiap iterasi
                if (function_exists('gc_mem_caches')) {
                    gc_mem_caches();
                }
                gc_collect_cycles();

                try {
                    // Ambil data IDUKA
                    $iduka = Iduka::select('id', 'nama', 'alamat', 'nama_pimpinan')
                        ->find($idukaId);

                    if (!$iduka)
                        continue;

                    // Ambil siswa dengan query minimal
                    $siswa = User::select('id', 'name', 'nip', 'kelas_id', 'iduka_id')
                        ->with(['kelas:id,kelas,name_kelas,konke_id', 'kelas.konke:id,name_konke'])
                        ->where('role', 'siswa')
                        ->where('iduka_id', $idukaId)
                        ->get();

                    if ($siswa->isEmpty())
                        continue;

                    // Nonaktifkan output buffering
                    if (ob_get_level()) {
                        ob_end_clean();
                    }

                    // Generate PDF
                    $pdf = PDF::loadView('persuratan.surat_pengantar_pkl.surat_semua', [
                        'iduka' => $iduka,
                        'siswa' => $siswa,
                        'logoBase64' => $logoBase64,
                    ])->setPaper('a4', 'portrait');

                    $fileName = $this->createSafeFileName($iduka->nama);
                    $filePath = "{$tempPath}/{$fileName}";

                    // Simpan PDF
                    if ($pdf->save($filePath)) {
                        $pdfFiles[] = $filePath;
                    }

                    // Hapus variabel besar
                    unset($pdf, $iduka, $siswa);

                } catch (\Exception $e) {
                    \Log::error("Error untuk IDUKA ID {$idukaId}: " . $e->getMessage());
                    continue;
                }

                // Cek memory usage setiap iterasi
                $memoryUsage = memory_get_usage(true);
                if ($memoryUsage > 1024 * 1024 * 1024) { // Jika lebih dari 1GB
                    gc_collect_cycles();
                }
            }

            if (empty($pdfFiles)) {
                File::deleteDirectory($tempPath);
                return redirect()->back()
                    ->with('error', 'Tidak ada PDF yang berhasil dibuat.');
            }

            // Buat ZIP
            $zipFilePath = $this->createZipFile($pdfFiles);
            File::deleteDirectory($tempPath);

            return response()->download($zipFilePath, basename($zipFilePath))
                ->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            if (isset($tempPath) && File::exists($tempPath)) {
                File::deleteDirectory($tempPath);
            }

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }
    /**
     * Membuat nama file aman.
     */
    private function createSafeFileName($namaIduka, $extension = 'pdf')
    {
        $slug = Str::slug($namaIduka);
        return "Surat-Pengantar-{$slug}.{$extension}";
    }

    /**
     * Buat folder temporary penyimpanan PDF.
     */
    private function createTempDirectory()
    {
        $tempPath = storage_path('app/temp_surat_pkl_' . time());

        if (!File::exists($tempPath)) {
            File::makeDirectory($tempPath, 0755, true);
        }

        return $tempPath;
    }

    /**
     * Buat ZIP file dari kumpulan PDF.
     */
    private function createZipFile(array $pdfFiles)
    {
        $zipFileName = 'Semua-Surat-Pengantar-PKL-' . date('Y-m-d-His') . '.zip';
        $zipFilePath = storage_path("app/{$zipFileName}");

        $zip = new ZipArchive;

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE)) {
            foreach ($pdfFiles as $file) {
                $zip->addFile($file, basename($file));
            }
            $zip->close();
        }

        return $zipFilePath;
    }
}
