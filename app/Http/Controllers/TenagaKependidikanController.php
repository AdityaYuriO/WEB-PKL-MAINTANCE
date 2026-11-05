<?php

namespace App\Http\Controllers;

use App\Models\Kependik;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class TenagaKependidikanController extends Controller
{
    public function tenagaKependidikan()
    {
        $kependik = Kependik::query()->paginate(10); // Gunakan query builder agar tetap LengthAwarePaginator
        return view('tk.dataTk', compact('kependik'));
    }

    public function create()
    {
        return view('kependik.create');
    }
    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:kependik,nik',
           'nip_nuptk' => 'nullable|string|max:30|unique:kependik,nip_nuptk',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:100|unique:kependik,email|unique:users,email',
            'no_hp' => 'required|string|max:15',
            'password' => 'required|string|min:6',
        ]);
    
        // Hash password sebelum disimpan
        $hashedPassword = Hash::make($request->password);
    
        // Simpan ke tabel users terlebih dahulu
        $user = User::create([
            'name' => $request->nama,
            'nip' => $request->nik,
            'email' => $request->email,
            'password' => $hashedPassword,
            'role' => 'persuratan',
        ]);
    
        // Simpan ke tabel kependik dengan user_id dari $user
        Kependik::create([
            'nama' => $request->nama,
            'nik' => $request->nik,
            'nip_nuptk' => $request->nip_nuptk,
            'tempat_lahir' => $request->tempat_lahir,
            'tanggal_lahir' => $request->tanggal_lahir,
            'jenis_kelamin' => $request->jenis_kelamin,
            'alamat' => $request->alamat,
            'email' => $request->email,
            'no_hp' => $request->no_hp,
            'password' => $hashedPassword,
            'user_id' => $user->id, // Sekarang variabel $user sudah ada
        ]);
    
        return redirect()->back()->with('success', 'Data Guru Tenaga Pendidik berhasil ditambahkan dan User berhasil dibuat.');
    }
    

    public function edit($id)
    {
        $kependik = Kependik::findOrFail($id);
        return view('tk.editTk', compact('kependik'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'nik' => 'required|string|max:20|unique:kependik,nik,' . $id,
            'nip_nuptk' => 'nullable|string|max:30',
            'tempat_lahir' => 'required|string|max:50',
            'tanggal_lahir' => 'required|date',
            'jenis_kelamin' => 'required|in:Laki-laki,Perempuan',
            'alamat' => 'required|string',
            'email' => 'required|string|email|max:100|unique:kependik,email,' . $id . '|unique:users,email',
            'no_hp' => 'required|string|max:15',
            'password' => 'nullable|string|min:6',
        ]);
    
        try {
            DB::beginTransaction();
    
            // Ambil data kependik berdasarkan ID
            $kependik = Kependik::findOrFail($id);
    
            // Update data di tabel kependik
            $kependik->update([
                'nama' => $request->nama,
                'nik' => $request->nik,
                'nip_nuptk' => $request->nip_nuptk,
                'tempat_lahir' => $request->tempat_lahir,
                'tanggal_lahir' => $request->tanggal_lahir,
                'jenis_kelamin' => $request->jenis_kelamin,
                'alamat' => $request->alamat,
                'email' => $request->email,
                'no_hp' => $request->no_hp,
            ]);
    
            // Cari User berdasarkan user_id
            if ($kependik->user_id) {
                $user = User::find($kependik->user_id);
                if ($user) {
                    $user->update([
                        'name' => $request->nama,
                        'nip' => $request->nik,
                        'email' => $request->email,
                        'password' => $request->password ? Hash::make($request->password) : $user->password,
                    ]);
                }
            }
    
            DB::commit();
            return redirect()->route('kependik.index')->with('success', 'Data berhasil diperbarui!');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function destroy($id)
{
    try {
        DB::beginTransaction();

        // Cari data kependik berdasarkan ID
        $kependik = Kependik::findOrFail($id);

        // Cari user terkait berdasarkan email atau NIK
        $user = User::where('email', $kependik->email)->orWhere('nip', $kependik->nik)->first();

        // Hapus user jika ditemukan
        if ($user) {
            $user->delete();
        }

        // Hapus data kependik
        $kependik->delete();

        DB::commit();
        return redirect()->back()->with('success', 'Data berhasil dihapus!');
    } catch (\Exception $e) {
        DB::rollBack();
        return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
    }
}

    
}
