<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Iduka;
use App\Models\Pembimbing;
use App\Models\CetakUsulan;
use Illuminate\Http\Request;
use App\Models\PembimbingIduka;
use App\Models\PengajuanPkl;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Models\PengajuanUsulan;
use App\Models\DataPribadi;
use Illuminate\Support\Facades\Log;

class IdukaController extends Controller
{
    public function index(Request $request)
    {
        $filter = $request->get('filter');
        $query = Iduka::query();

        // Pencarian umum
        if ($request->has('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', "%$search%")
                    ->orWhere('alamat', 'like', "%$search%");
            });
        }

        // Filter
        if ($filter === 'rekomendasi') {
            $query->where('rekomendasi', true)
                ->where('hidden', false); // Hindari data yang disembunyikan
        } elseif ($filter === 'ajuan') {
            $query->where('rekomendasi', false)
                ->where('hidden', false);
        } elseif ($filter === 'hidden') {
            $query->where('hidden', true);
        }

        // Pagination tetap muncul
        $iduka = $query->paginate(10)->appends($request->all());

        return view('iduka.dataiduka.dataiduka', compact('iduka'));
    }


    public function dataPribadiIduka()
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil data Iduka dan Pembimbing terkait
        $iduka = Iduka::where('user_id', $user->id)->first();
        $pembimbing = Pembimbing::where('user_id', $user->id)->first();

        return view('iduka.data_pribadi_iduka.dataPribadiIduka', compact('iduka', 'pembimbing'));
    }
    public function editiduka($id)
    {
        // Ambil data IDUKA berdasarkan ID
        $iduka = Iduka::findOrFail($id);

        return view('iduka.dataiduka.editiduka', compact('iduka'));
    }
    public function updateiduka(Request $request, $id)
    {
        $iduka = Iduka::with('user')->findOrFail($id);

$validated = $request->validate([
    'nama' => 'required',
    'nama_pimpinan' => 'required',
    'nip_pimpinan' => 'required',
    'no_hp_pimpinan' => 'required',
    'jabatan' => 'required',
    'alamat' => 'required',
    'kode_pos' => 'required',
    'telepon' => 'required',
    'email' => 'required|email',
    'bidang_industri' => 'required',
    'kerjasama' => 'required',
    'kuota_pkl' => 'required|integer',
    'password' => 'nullable|min:6'
]);

DB::transaction(function () use ($request, $iduka) {

    // update iduka
    $iduka->update([
        'nama' => $request->nama,
        'nama_pimpinan' => $request->nama_pimpinan,
        'nip_pimpinan' => $request->nip_pimpinan,
        'no_hp_pimpinan' => $request->no_hp_pimpinan,
        'jabatan' => $request->jabatan,
        'alamat' => $request->alamat,
        'kode_pos' => $request->kode_pos,
        'telepon' => $request->telepon,
        'email' => $request->email,
        'bidang_industri' => $request->bidang_industri,
        'kerjasama' => $request->kerjasama,
        'kuota_pkl' => $request->kuota_pkl,
        'rekomendasi' => $request->has('rekomendasi') ? 1 : 0,
        'kerjasama_lainnya' => $request->kerjasama === 'Lainnya'
            ? $request->kerjasama_lainnya
            : null
    ]);

    // update user (AMAN NULL)
    if ($iduka->user) {
        $iduka->user->update([
            'name' => $request->nama,
            'email' => $request->email,
            'password' => $request->filled('password')
                ? Hash::make($request->password)
                : $iduka->user->password,
        ]);
    }

});
return redirect()->back()->with('success', 'Data IDUKA berhasil diperbarui.');
    }

    public function updateTanggal(Request $request, $id)
    {
        $request->validate([
            'tanggal_awal' => 'required|date',
            'tanggal_akhir' => 'required|date|after_or_equal:tanggal_awal',
        ]);

        $iduka = Iduka::findOrFail($id);
        $iduka->tanggal_awal = $request->tanggal_awal;
        $iduka->tanggal_akhir = $request->tanggal_akhir;
        $iduka->save();

        return redirect()->back()->with('success', 'Tanggal batas usulan berhasil diperbarui.');
    }
    public function edit()
    {
        $user = auth()->user();
        $iduka = Iduka::where('user_id', $user->id)->first();
        $pembimbing = Pembimbing::where('user_id', $user->id)->first();

        return view('iduka.data_pribadi_iduka.editPribadiIduka', compact('iduka', 'pembimbing'));
    }

    public function updatePribadi(Request $request, $id)
    {
        $iduka = Iduka::findOrFail($id);
        $pembimbing = Pembimbing::where('user_id', $iduka->user_id)->first();
        $user = User::where('iduka_id', $iduka->id)->first();

        $validationRules = [
            'nama' => 'required|string|max:255',
            'nama_pimpinan' => 'required|string|max:255',
            'nip_pimpinan' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kode_pos' => 'required|numeric',
            'telepon' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'bidang_industri' => 'required|string',
            'kerjasama' => 'required|string',
            'kerjasama_lainnya' => 'nullable|required_if:kerjasama,Lainnya|string|max:255',
            'kuota_pkl' => 'required|numeric',
            'no_hp_pimpinan' => 'required|numeric',
            'nama_pembimbing' => 'required|string',
            'nip_pembimbing' => 'required|string',
            'no_hp_pembimbing' => 'required|numeric',
            'mulai_kerjasama' => 'nullable|date',
            'akhir_kerjasama' => 'nullable|date|after_or_equal:mulai_kerjasama',

        ];

        $request->validate($validationRules);

        DB::transaction(function () use ($request, $iduka, $pembimbing, $user) {
            // Update Iduka
            $iduka->update([
                'nama' => $request->nama,
                'nama_pimpinan' => $request->nama_pimpinan,
                'nip_pimpinan' => $request->nip_pimpinan,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'kode_pos' => $request->kode_pos,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'bidang_industri' => $request->bidang_industri,
                'kerjasama' => $request->kerjasama,
                'kerjasama_lainnya' => $request->kerjasama_lainnya,
                'kuota_pkl' => $request->kuota_pkl,
                'no_hp_pimpinan' => $request->no_hp_pimpinan,
                'mulai_kerjasama' => $request->mulai_kerjasama,
                'akhir_kerjasama' => $request->akhir_kerjasama,
            ]);

            // Update Pembimbing
            if ($pembimbing) {
                /* $pembimbing->update([
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'no_hp' => $request->no_hp_pembimbing,
                    'password' => Hash::make($request->password),
                ]); */

                $updatePembimbing = [
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'no_hp' => $request->no_hp_pembimbing,
                ];

                if ($request->filled('password')) {
                    $updatePembimbing['password'] = Hash::make($request->password);
                }

                $pembimbing->update($updatePembimbing);

                // Update User
                if($user)
                    {
                    $user->update([
                    'name' => $request->nama,
                    'nip' => $request->email,
                    'password' => $request->filled('password') ? Hash::make($request->password) : $user->password,
                ]);}

            } else {
                /* User::create([
                    'iduka_id' => $iduka->id,
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'password' => Hash::make($request->password),
                    'role' => 'ppkl',
                ]); */

                $newUser = User::create([
                    'iduka_id' => $iduka->id,
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'password' => $request->filled('password')
                        ? Hash::make($request->password)
                        : null,
                    'role' => 'ppkl',
                ]);

                Pembimbing::create([
                    'user_id' => $newUser->id,
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'no_hp' => $request->no_hp_pembimbing,
                    'password' => Hash::make($request->password),
                ]);
            }
        });


        return redirect()->route('iduka.pribadi')->with('success', 'Data berhasil diperbarui.');
    }

    public function show($id)
    {
        $iduka = Iduka::where('id', $id)->first();

        return view('iduka.dataiduka.detailDataIduka', compact('iduka'));
    }

    public function updateInstitusi(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_pimpinan' => 'required|string|max:255',
            'nip_pimpinan' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'telepon' => 'required|numeric',
            'bidang_industri' => 'required|string',
            'no_hp_pimpinan' => 'required|numeric',
            'kolom6' => 'required|string|in:Ya,Tidak',
            'kolom7' => 'required|string|in:Ya,Tidak',
            'kolom8' => 'required|string|in:Ya,Tidak',
            'foto' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $iduka = Iduka::findOrFail($id);

        // Simpan foto baru jika ada, dan hapus yang lama
        $path = $iduka->foto; // Default: tetap pakai foto lama
        if ($request->hasFile('foto')) {
            if ($iduka->foto) {
                Storage::disk('public')->delete($iduka->foto);
            }
            $path = $request->file('foto')->store('iduka_fotos', 'public');
        }

        DB::transaction(function () use ($request, $iduka, $path) {
            // Update tabel idukas
            $iduka->update([
                'nama' => $request->nama,
                'nama_pimpinan' => $request->nama_pimpinan,
                'nip_pimpinan' => $request->nip_pimpinan,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'telepon' => $request->telepon,
                'bidang_industri' => $request->bidang_industri,
                'no_hp_pimpinan' => $request->no_hp_pimpinan,

                'kolom6' => $request->kolom6,
                'kolom7' => $request->kolom7,
                'kolom8' => $request->kolom8,
                'foto' => $path,
            ]);

            // Update nama user jika ada
            if ($iduka->user) {
                $iduka->user->update([
                    'name' => $request->nama,
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data institusi berhasil diperbarui!');
    }

    /**
     * Compatibility wrapper: some routes expect storeInstitusi
     */
    public function storeInstitusi(Request $request, $id)
    {
        // Delegate to updateInstitusi which already validates and updates
        return $this->updateInstitusi($request, $id);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_pimpinan' => 'required|string|max:255',
            'nip_pimpinan' => 'required|string|max:255',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kode_pos' => 'required|string|max:10',
            'telepon' => 'required|string|max:20',
            'email' => 'required|email|unique:idukas,email|unique:users,nip',
            'password' => 'required|string|min:6',
            'bidang_industri' => 'required|string',
            'kerjasama' => 'required|string',
            'kerjasama_lainnya' => 'nullable|required_if:kerjasama,Lainnya|string|max:255',
            'kuota_pkl' => 'required|integer|min:1',
            'rekomendasi' => 'nullable|boolean',
            'no_hp_pimpinan' => 'required|string|max:15',
        ]);

        DB::transaction(function () use ($request) {
            // Simpan ke tabel users terlebih dahulu
            $user = User::create([
                'name' => $request->nama,
                'nip' => $request->email,
                'password' => Hash::make($request->password),
                'role' => 'iduka',
            ]);

            // Simpan ke tabel idukas
            $iduka = Iduka::create([
                'user_id' => $user->id,
                'nama' => $request->nama,
                'nama_pimpinan' => $request->nama_pimpinan,
                'nip_pimpinan' => $request->nip_pimpinan,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'kode_pos' => $request->kode_pos,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'bidang_industri' => $request->bidang_industri,
                'kerjasama' => $request->kerjasama === 'Lainnya' ? $request->kerjasama_lainnya : $request->kerjasama,
                'kerjasama_lainnya' => $request->kerjasama_lainnya,
                'kuota_pkl' => $request->kuota_pkl,
                'rekomendasi' => $request->rekomendasi ?? 0,
                'no_hp_pimpinan' => $request->no_hp_pimpinan,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update kolom iduka_id di tabel users
            $user->update(['iduka_id' => $iduka->id]);
        });

        return redirect()->back()->with('success', 'Data Institusi berhasil ditambahkan dan User berhasil dibuat.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'nama_pimpinan' => 'required|string|max:255',
            'nip_pimpinan' => 'required|string|max:50',
            'jabatan' => 'required|string|max:255',
            'alamat' => 'required|string',
            'kode_pos' => 'required|numeric',
            'telepon' => 'required|numeric',
            'email' => 'required|email',
            'password' => 'nullable|string|min:6',
            'bidang_industri' => 'required|string',
            'kerjasama' => 'required|string',
            'kerjasama_lainnya' => 'nullable|required_if:kerjasama,Lainnya|string|max:255',
            'kuota_pkl' => 'required|numeric',
            'no_hp_pimpinan' => 'required|numeric',
        ]);

        $iduka = Iduka::findOrFail($id);
        $kerjasama = $request->kerjasama === 'Lainnya' ? $request->kerjasama_lainnya : $request->kerjasama;
        $rekomendasi = $request->has('rekomendasi') ? 1 : 0;

        DB::transaction(function () use ($request, $iduka) {
            if ($iduka->user) {
                $iduka->user->update([
                    'name' => $request->nama,
                    'nip' => $request->email,
                    'password' => $request->password ? Hash::make($request->password) : $iduka->user->password,
                    'role' => 'iduka',
                    'iduka_id' => $iduka->id, // Update iduka_id di tabel users
                ]);
            }

            $iduka->update([
                'nama' => $request->nama,
                'nama_pimpinan' => $request->nama_pimpinan,
                'nip_pimpinan' => $request->nip_pimpinan,
                'jabatan' => $request->jabatan,
                'alamat' => $request->alamat,
                'kode_pos' => $request->kode_pos,
                'telepon' => $request->telepon,
                'email' => $request->email,
                'password' => $request->password ? Hash::make($request->password) : $iduka->password,
                'bidang_industri' => $request->bidang_industri,
                'kerjasama' => $request->kerjasama,
                'kuota_pkl' => $request->kuota_pkl,
                'rekomendasi' => $request->rekomendasi ?? 0,
                'no_hp_pimpinan' => $request->no_hp_pimpinan,
                'kolom6' => $request->kolom6,
                'kolom7' => $request->kolom7,
                'kolom8' => $request->kolom8,

            ]);
        });

        return redirect(url()->previous())->with('success', 'Data Institusi berhasil diperbarui!');
    }



    public function dataInstitusi()
    {

        $user = auth()->user();

        // Ambil data Iduka dan Pembimbing terkait
        $iduka = Iduka::where('user_id', $user->id)->first();
        $pembimbing = Pembimbing::where('user_id', $user->id)->first();
        return view('iduka.data_pribadi_iduka.dataInstitusi', compact('iduka', 'pembimbing'));
    }


    public function destroy($id)
    {
        $iduka = Iduka::findOrFail($id);

        DB::transaction(function () use ($iduka) {
            if ($iduka->user) {
                $iduka->user->delete(); // Hapus user yang terkait langsung
            }
            $iduka->delete();
        });


        return redirect()->route('data.iduka')->with('success', 'Data Institusi berhasil dihapus.');
    }

    public function downloadPDF($id)
    {
        // Ambil user yang sedang login
        $user = auth()->user();

        // Ambil data Iduka dan Pembimbing terkait
        $iduka = Iduka::where('user_id', $user->id)->first();
        $pembimbing = Pembimbing::where('user_id', $user->id)->first();


        $pdf = Pdf::loadView('iduka.pdf_template', compact('iduka', 'pembimbing'))
            ->setPaper('A4', 'portrait'); // Ukuran kertas A4 vertikal

        return $pdf->download('laporan_pridasi_iduka.pdf' . $user->id . '.pdf');
    }

    public function TpIduka()
    {
        return view('iduka.tp.tp');
    }

    public function downloadAtpIduka($id)
    {
        $iduka = Iduka::with(['atps.konke', 'atps.cp', 'atps.atp'])
            ->findOrFail($id);

        return Pdf::loadView('persuratan.suratPengajuan.kaprogatp', compact('iduka'))
            ->download('Data_ATP_' . $iduka->nama . '.pdf');
    }

    public function siswaDiterima()
    {
        $user = auth()->user();

        $pengajuanDiterima = PengajuanPkl::with('siswa')
            ->where('iduka_id', $user->iduka->id) // relasi user ke iduka
            ->where('status', 'diterima')
            ->latest()
            ->get();

        // Mengelompokkan pengajuan berdasarkan tahun
        $pengajuanByYear = $pengajuanDiterima->groupBy(function ($item) {
            return $item->created_at->format('Y'); // Mengambil tahun dari created_at
        });

        return view('iduka.siswa_diterima', compact('pengajuanDiterima', 'pengajuanByYear'));
    }

    /**
     * Store pengajuan PKL dari siswa
     */
    public function storeAjukanPkl(Request $request, $iduka_id)
    {
        \Log::info('StoreAjukanPkl called', [
            'user_id' => Auth::id(),
            'iduka_id' => $iduka_id,
            'request_data' => $request->all()
        ]);

        try {
            $user = Auth::user();
            $dataPribadi = DataPribadi::where('user_id', $user->id)->first();

            \Log::info('User and DataPribadi', [
                'user' => $user,
                'dataPribadi' => $dataPribadi
            ]);

            if (!$dataPribadi) {
                \Log::warning('Data pribadi tidak ditemukan', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'Data pribadi tidak ditemukan.');
            }

            // Cek apakah siswa sudah punya pengajuan PKL yang aktif
            $cekPengajuanAktif = PengajuanUsulan::where('user_id', $user->id)
                ->whereIn('status', ['proses', 'diterima'])
                ->exists();

            if ($cekPengajuanAktif) {
                \Log::warning('Pengajuan aktif sudah ada', ['user_id' => $user->id]);
                return redirect()->back()->with('error', 'Kamu sudah memiliki pengajuan PKL yang sedang diproses atau sudah diterima.');
            }

            // Cek apakah sudah mengajukan ke IDUKA ini
            $cekUsulan = PengajuanUsulan::where('user_id', $user->id)
                ->where('iduka_id', $iduka_id)
                ->first();

            if ($cekUsulan) {
                \Log::warning('Sudah mengajukan ke IDUKA ini', [
                    'user_id' => $user->id,
                    'iduka_id' => $iduka_id
                ]);
                return redirect()->back()->with('error', 'Kamu sudah mengajukan PKL ke IDUKA ini.');
            }

            \Log::info('Creating PengajuanUsulan', [
                'user_id' => $user->id,
                'konke_id' => $dataPribadi->konke_id,
                'iduka_id' => $iduka_id
            ]);

            // Simpan data
            $pengajuan = PengajuanUsulan::create([
                'user_id' => $user->id,
                'konke_id' => $dataPribadi->konke_id,
                'iduka_id' => $iduka_id,
                'status' => 'proses',
            ]);

            \Log::info('PengajuanUsulan created successfully', ['pengajuan_id' => $pengajuan->id]);

            return redirect()->route('siswa.dashboard')->with('success', 'Usulan PKL berhasil diajukan!');

        } catch (\Exception $e) {
            \Log::error('Error in storeAjukanPkl', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);

            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

}
