<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Konke;
use App\Imports\SiswaImport;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        // Default hanya tampilkan siswa aktif. Tambah ?status=alumni di URL untuk lihat data alumni.
        $status = $request->get('status', 'aktif');

        $siswa = User::where('role', 'siswa')
                ->where('status', $status)
                ->with(['kelas', 'konke'])
                ->orderBy('created_at', 'desc') // Urutkan berdasarkan created_at descending
                ->paginate(10);
        $konke = Konke::all();
        $kelas = Kelas::all();

        return view('siswa.datasiswa.datasiswa', compact('siswa', 'konke', 'kelas', 'status'));
    }

    // public function showSiswa($id) {
    //     $konke = Konke::all();
    //     $kelas = Kelas::all();
    //     $siswa = User::with('dataPribadi')->findOrFail($id);
    //     return view('siswa.datasiswa.showSiswa', compact('siswa', 'konke', 'kelas'));
    // }

    public function show($id)
    {
        $konke = Konke::all();
        $kelas = Kelas::all();
        $siswa = User::with('dataPribadi')->findOrFail($id);
        $dataPribadi = User::with('dataPribadi')->where('id', Auth::id())->first();
        return view('siswa.datasiswa.detailSiswa', compact('siswa', 'dataPribadi', 'konke', 'kelas'));
    }



    public function create()
    {
        return view('siswa.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:users,nip',
            'kelas_id' =>  'required|exists:kelas,id',
            'konke_id' => 'required|exists:konkes,id',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|min:6'
        ]);

        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'kelas_id' => $request->kelas_id,
            'konke_id' => $request->konke_id,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => 'siswa'
        ]);
        return redirect(url()->previous())->with('success', 'Siswa berhasil ditambahkan.');
    }



    public function update(Request $request, $id)
    {


        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => "required|unique:users,nip,$id",
           'kelas_id' =>  'required|exists:kelas,id',
            'konke_id' => 'required|exists:konkes,id',
            'email' => "required|email|unique:users,email,$id",
            'password' => 'nullable|min:6',

        ]);

        $siswa = User::findOrFail($id);

        $siswa->update([
            'name' => $request->name,
            'nip' => $request->nip,
            'kelas_id' => $request->kelas_id,
            'konke_id' => $request->konke_id,
            'email' => $request->email,
            'password' => $request->password ? Hash::make($request->password) : $siswa->password,
        ]);

        return redirect(url()->previous())->with('success', 'Data siswa berhasil diperbarui.');

    }


    public function destroy($id)
    {
        $siswa = User::findOrFail($id);
        $siswa->delete();

        return redirect(url()->previous())->with('success', 'Siswa berhasil dihapus.');
    }

    public function importExcel(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx,xls'
        ]);

        Excel::import(new SiswaImport, $request->file('file'));

        return back()->with('success', 'Data siswa berhasil diimport!');
    }

    public function dataPribadi()
    {
        $siswa = User::where('role', 'siswa')
            ->with(['dataPribadi', 'kelas', 'konke'])
            ->get();

        return view('admin.datasiswa.detailSiswa', compact('siswa', 'kelas', 'konke'));
    }


    public function downloadTemplate(): BinaryFileResponse
{
    $templatePath = public_path('templates/template_siswa.xlsx');

    // Jika file belum ada, buat secara dinamis
    if (!file_exists($templatePath)) {
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();


        // Contoh data (baris 2)
        $sheet->setCellValue('A2', 'John Doe');
        $sheet->setCellValue('B2', '123456');
        $sheet->setCellValue('C2', 'X IPA 1');
        $sheet->setCellValue('D2', 'A');
        $sheet->setCellValue('E2', 'john@example.com');
        $sheet->setCellValue('F2', 'password123');

        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        $writer->save($templatePath);
    }

    return response()->download($templatePath, 'template_import_siswa.xlsx');
}

public function updateSiswa(Request $request, $id)
{
    // Temukan siswa
    $siswa = User::findOrFail($id);

    // Update berdasarkan tipe data yang dikirim
    if ($request->has('name')) { // Jika ada field data pribadi
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|max:255',
            'kelas_id' => 'required|exists:kelas,id',
            'konke_id' => 'required|exists:konkes,id',
            'alamat' => 'required|string|max:255',
            'no_hp' => 'required|string|max:20',
            'jk' => 'required|string|max:10',
            'agama' => 'required|string|max:50',
            'tempat_lhr' => 'required|string|max:255',
            'tgl_lahir' => 'required|date',
            'email' => 'required|string|email|max:255',
        ]);

        // Handle agama "Lainnya"
        $agama = $request->agama === 'Lainnya' ? $request->agama_lainnya : $request->agama;

        // Update data siswa utama
        $siswaData = [
            'name' => $request->name,
            'nip' => $request->nip,
            'kelas_id' => $request->kelas_id,
            'konke_id' => $request->konke_id,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $siswaData['password'] = Hash::make($request->password);
        }

        $siswa->update($siswaData);

        // Update data pribadi
        $dataPribadi = [
            'alamat_siswa' => $request->alamat,
            'no_hp' => $request->no_hp,
            'jk' => $request->jk,
            'agama' => $agama,
            'tempat_lhr' => $request->tempat_lhr,
            'tgl_lahir' => $request->tgl_lahir,
        ];

        if ($siswa->dataPribadi) {
            $siswa->dataPribadi->update($dataPribadi);
        } else {
            $siswa->dataPribadi()->create($dataPribadi);
        }

        return redirect()->back()->with('success', 'Data pribadi siswa berhasil diperbarui');
    }
    else { // Jika data orang tua
        $request->validate([
            'name_ayh' => 'required|string|max:255',
            'nik_ayh' => 'required|string|max:20',
            'tempat_lhr_ayh' => 'required|string|max:255',
            'tgl_lahir_ayh' => 'required|date',
            'pekerjaan_ayh' => 'required|string|max:255',
            'name_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|string|max:20',
            'tempat_lhr_ibu' => 'required|string|max:255',
            'tgl_lahir_ibu' => 'required|date',
            'pekerjaan_ibu' => 'required|string|max:255',
            'email_ortu' => 'required|string|email|max:255',
            'no_tlp' => 'required|string|max:20',
        ]);

        $dataOrangTua = [
            'name_ayh' => $request->name_ayh,
            'nik_ayh' => $request->nik_ayh,
            'tempat_lhr_ayh' => $request->tempat_lhr_ayh,
            'tgl_lahir_ayh' => $request->tgl_lahir_ayh,
            'pekerjaan_ayh' => $request->pekerjaan_ayh,
            'name_ibu' => $request->name_ibu,
            'nik_ibu' => $request->nik_ibu,
            'tempat_lhr_ibu' => $request->tempat_lhr_ibu,
            'tgl_lahir_ibu' => $request->tgl_lahir_ibu,
            'pekerjaan_ibu' => $request->pekerjaan_ibu,
            'email_ortu' => $request->email_ortu,
            'no_tlp' => $request->no_tlp,
        ];

        if ($siswa->dataPribadi) {
            $siswa->dataPribadi->update($dataOrangTua);
        } else {
            $siswa->dataPribadi()->create($dataOrangTua);
        }

        return redirect()->back()->with('success', 'Data orang tua siswa berhasil diperbarui');
    }
}
}
