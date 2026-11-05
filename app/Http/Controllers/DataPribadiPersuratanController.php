<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\DataPribadiPersuratan;

class DataPribadiPersuratanController extends Controller
{
    public function create()
    {
        $user = Auth::user();
        $dataPersuratan = DataPribadiPersuratan::where('user_id', $user->id)->first();
        
        // Jika tidak ada data, buat array kosong agar form tetap bisa menerima input
        if (!$dataPersuratan) {
            $dataPersuratan = new DataPribadiPersuratan();
        }
        
        return view('persuratan.data_pribadi.form', compact('user', 'dataPersuratan'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();
        $dataPersuratan = DataPribadiPersuratan::where('user_id', $user->id)->first();

        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|unique:data_pribadi_persuratans,nip,' . ($dataPersuratan->id ?? 'null') . ',id',
            'alamat' => 'required|string',
            'no_hp' => 'required|numeric|unique:data_pribadi_persuratans,no_hp,' . ($dataPersuratan->id ?? 'null') . ',id',
            'jk' => 'required|string',
            'agama' => 'required|string',
            'tempat_lahir' => 'required|string',
            'tanggal_lahir' => 'nullable|date',
            'email' => 'required|email|unique:data_pribadi_persuratans,email,' . ($dataPersuratan->id ?? 'null') . ',id',
        ]);

          // Jika agama adalah 'Lainnya', gunakan nilai dari input manual
    if ($request->agama === 'Lainnya' && $request->has('agama_lainnya')) {
        $validated['agama'] = $request->agama_lainnya;
    }
        // Data untuk tabel data_pribadi_persuratans
        $data = $request->except(['password']);

        if ($request->filled('password')) {
            $data['password'] = bcrypt($request->password);
        }

        $data['user_id'] = $user->id;

        if (!$dataPersuratan) {
            $dataPersuratan = DataPribadiPersuratan::create($data);
        } else {
            $dataPersuratan->update($data);
        }

        // Update data di tabel users
        $userData = [
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
        ];

        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->password);
        }

        $user->update($userData);

        return redirect()->back()
            ->with('success', 'Data pribadi berhasil disimpan.')
            ->withInput();
    }
}