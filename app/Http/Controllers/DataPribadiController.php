<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Kelas;
use App\Models\Konke;
use App\Models\tahunAjaran;
use App\Models\DataPribadi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class DataPribadiController extends Controller
{
    public function create()
    {
        $dataPribadi = Auth::user()->dataPribadi ?? new DataPribadi();
        $siswa = User::with('siswa', 'kelas', 'konke')->where('id', Auth::id())->first();
        $konke = Konke::all();
        $tahunAjaran = tahunAjaran::all();
        $kelas = Kelas::all();

        return view('siswa.data_pribadi.form', compact('dataPribadi', 'siswa', 'kelas', 'konke', 'tahunAjaran'));
    }

public function store(Request $request)
    {
        $dataPribadi = Auth::user()->dataPribadi;

        // Validasi
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:data_pribadis,nip,' . ($dataPribadi->id ?? 'null') . ',id',
            'kelas_id' => 'nullable|exists:kelas,id',
            'konke_id' => 'nullable|exists:konkes,id',
            'alamat_siswa' => 'required|string',
            'no_hp' => 'required|numeric|unique:data_pribadis,no_hp,' . ($dataPribadi->id ?? 'null') . ',id',
            'jk' => 'required|string',
            'agama' => 'required|string',
            'tempat_lhr' => 'required|string',
            'tgl_lahir' => 'nullable|date',
            'tahun_ajaran_id' => 'required|exists:tahun_ajaran,id',
            'email' => 'required|email|unique:data_pribadis,email,' . ($dataPribadi->id ?? 'null') . ',id',

            'name_ayh' => 'required|string|max:255',
            'nik_ayh' => 'required|unique:data_pribadis,nik_ayh,' . ($dataPribadi->id ?? 'null') . ',id',
            'tempat_lhr_ayh' => 'required|string',
            'tgl_lahir_ayh' => 'nullable|date',
            'pekerjaan_ayh' => 'nullable|string',

            'name_ibu' => 'required|string|max:255',
            'nik_ibu' => 'required|unique:data_pribadis,nik_ibu,' . ($dataPribadi->id ?? 'null') . ',id',
            'tempat_lhr_ibu' => 'required|string',
            'tgl_lahir_ibu' => 'nullable|date',
            'pekerjaan_ibu' => 'nullable|string',

            'ttd_ortu_option' => 'nullable|string|max:255', // ayah / ibu / manual
            'ttd_ortu_manual_hubungan' => 'nullable|string|max:255', // contoh: Paman
            'ttd_ortu_manual_nama' => 'nullable|string|max:255', // contoh: Ujang

            'email_ortu' => 'nullable|email|unique:data_pribadis,email_ortu,' . ($dataPribadi->id ?? 'null') . ',id',
            'no_tlp' => 'required|string|max:15|unique:data_pribadis,no_tlp,' . ($dataPribadi->id ?? 'null') . ',id',
        ]);

        // Jika agama 'Lainnya', pakai input manual
        if ($request->agama === 'Lainnya' && $request->filled('agama_lainnya')) {
            $validated['agama'] = $request->agama_lainnya;
        }

        // Update user siswa (Auth user)
        $user = Auth::user();
        $user->update([
            'name' => $validated['name'],
            'nip' => $validated['nip'],
            'email' => $validated['email'],
            'kelas_id' => $validated['kelas_id'],
            'konke_id' => $validated['konke_id'],
        ]);

        if ($request->ttd_ortu_option === 'manual') {
            $validated['ttd_ortu'] = $request->ttd_ortu_manual_hubungan;
            $validated['ttd_ortu_nama'] = $request->ttd_ortu_manual_nama;
        } else {
            $validated['ttd_ortu'] = $request->ttd_ortu_option;
            $validated['ttd_ortu_nama'] = null;
        }


        if (!$dataPribadi) {
            $validated['user_id'] = Auth::id();
            DataPribadi::create($validated);
        } else {
            $dataPribadi->update($validated);
        }


        if ($request->filled('nik_ayh')) {
            $userOrtu = User::where('nip', $dataPribadi->nik_ayh ?? $request->nik_ayh)->first();

            if ($userOrtu) {
                $userOrtu->update([
                    'name' => $request->name_ayh,
                    'nip' => $request->nik_ayh,
                    'email' => $request->email_ortu,
                ]);
            } else {
                User::create([
                    'name' => $request->name_ayh,
                    'nip' => $request->nik_ayh,
                    'email' => $request->email_ortu,
                    'password' => Hash::make($request->nik_ayh),
                    'role' => 'orangtua',
                ]);
            }
        }

        return redirect()->route('siswa.data_pribadi.create')
            ->with('success', 'Data pribadi berhasil disimpan.');
    }

   }
