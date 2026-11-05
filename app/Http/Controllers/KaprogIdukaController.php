<?php

namespace App\Http\Controllers;

use App\Models\Iduka;
use App\Models\Pembimbing;
use App\Models\User;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class KaprogIdukaController extends Controller
{
    public function index()
    {

        $iduka = Iduka::orderBy('rekomendasi', 'desc')  // Urutkan berdasarkan rekomendasi (1 di atas)
        ->orderBy('created_at', 'desc') // Jika ada yang sama, urutkan berdasarkan tanggal dibuat
        ->paginate(10);
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
        $iduka = Iduka::findOrFail($id);
    
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
        ]);
    
        DB::beginTransaction();
    
        try {
            // Update data iduka
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
                'kerjasama_lainnya' => $request->kerjasama === 'Lainnya' ? $request->kerjasama_lainnya : null
            ]);
    
            // Update user terkait
            $user = User::where('iduka_id', $iduka->id)->first();
            if ($user) {
                $userData = [
                    'name' => $request->nama,
                    'nip' => $request->email,
                ];
    
                if ($request->filled('password')) {
                    $userData['password'] = Hash::make($request->password);
                }
    
                $user->update($userData);
            }
    
            DB::commit();
    
            return redirect()->back()->with('success', 'Data IDUKA berhasil diperbarui.');
    
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'Gagal memperbarui data: ' . $e->getMessage())
                ->withInput();
        }
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
        $pembimbing = Pembimbing::where('user_id', $iduka->user_id)->first(); // Ambil data pembimbing

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
            'no_hp_pimpinan' =>  'required|numeric',
        ]);

        DB::transaction(function () use ($request, $iduka, $pembimbing) {
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


            ]);

            // Update atau buat data Pembimbing
            if ($pembimbing) {
                $pembimbing->update([
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'no_hp' => $request->no_hp_pembimbing,
                    'password' => $request->password,
                ]);
            } else {
                Pembimbing::create([
                    'user_id' => $iduka->user_id,
                    'name' => $request->nama_pembimbing,
                    'nip' => $request->nip_pembimbing,
                    'no_hp' => $request->no_hp_pembimbing,
                    'password' => $request->password,
                ]);
            }
        });

        return redirect()->route('iduka.data-pribadi')->with('success', 'Data berhasil diperbarui.');
    }






    public function show($id)
    {
        $iduka = Iduka::where('id', $id)->first();

        return view('iduka.dataiduka.detailDataIduka', compact('iduka'));
    }


    public function storeInstitusi(Request $request, $id)
    {
        // Validasi hanya untuk kolom6, kolom7, dan kolom8
        $validatedData = $request->validate([
            'kolom6' => 'required|string|in:Ya,Tidak', // Hanya menerima nilai "Ya" atau "Tidak"
            'kolom7' => 'required|string|in:Ya,Tidak', // Hanya menerima nilai "Ya" atau "Tidak"
            'kolom8' => 'required|string|in:Ya,Tidak', // Hanya menerima nilai "Ya" atau "Tidak"
        ]);

        // Menyimpan data ke tabel idukas
        DB::table('idukas')
            ->where('id', $id) // Menambahkan kondisi where untuk update berdasarkan ID
            ->update([
                'kolom6' => $validatedData['kolom6'],
                'kolom7' => $validatedData['kolom7'],
                'kolom8' => $validatedData['kolom8'],
                'updated_at' => now(),
            ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan!');
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
        ]);

        $iduka = Iduka::findOrFail($id);

        DB::transaction(function () use ($request, $iduka) {
            // Update data di tabel idukas
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
            ]);

            // Update data di tabel users (jika ada relasi user)
            if ($iduka->user) {
                $iduka->user->update([
                    'name' => $request->nama, // Update nama di tabel users
                    'nip' => $request->nip_pimpinan, // Update nip di tabel users
                ]);
            }

            // Update data pembimbing (jika ada relasi pembimbing)
            $pembimbing = Pembimbing::where('user_id', $iduka->user_id)->first();
            if ($pembimbing) {
                $pembimbing->update([
                    'name' => $request->name, // Update nama pembimbing
                    'nip' => $request->nip, // Update nip pembimbing
                ]);
            }
        });

        return redirect()->back()->with('success', 'Data institusi berhasil diperbarui!');
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
            'kolom6' => 'nullable|string|max:255', // Kolom baru
            'kolom7' => 'nullable|string|max:255', // Kolom baru
            'kolom8' => 'nullable|string|max:255', // Kolom baru
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048', // Kolom foto
            'tanggal_awal' => 'nullable|date', // Kolom tanggal_awal
            'tanggal_akhir' => 'nullable|date', // Kolom tanggal_akhir
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
                'kolom6' => $request->kolom6,
                'kolom7' => $request->kolom7,
                'kolom8' => $request->kolom8,
                'foto' => $request->foto ? $request->foto->store('public/foto_iduka') : null, // Simpan foto jika ada
                'tanggal_awal' => $request->tanggal_awal,
                'tanggal_akhir' => $request->tanggal_akhir,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // Update kolom iduka_id di tabel users
            $user->update(['iduka_id' => $iduka->id]);
        });

        return redirect()->back()->with('success', 'Data Iduka berhasil ditambahkan dan User berhasil dibuat.');
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
            'no_hp_pimpinan' =>  'required|numeric',
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

        return redirect(url()->previous())->with('success', 'Data IDUKA berhasil diperbarui!');
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


        return redirect()->route('hubin.iduka.index')->with('success', 'Data Iduka berhasil dihapus.');
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
}
