<?php

namespace App\Http\Controllers;

use App\Models\Monitoring;
use App\Models\Iduka;
use App\Models\User;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;

class MonitoringController extends Controller
{
    public function index(Request $request)
    {
        $query = Monitoring::with(['iduka', 'guru']);

        if ($request->filled('nama_iduka')) {
            $search = $request->nama_iduka;
            $query->whereHas('iduka', function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%');
            });
        }

        $monitoring = $query->orderByDesc('id')->paginate(10);
        $totalMonitoring = Monitoring::count();
        $totalIduka = Iduka::count();
        $totalIdukaWithMonitoring = Monitoring::distinct('iduka_id')->count();
        $totalIdukaWithoutMonitoring = $totalIduka - $totalIdukaWithMonitoring;
        $totalPerkiraanSiswa = Monitoring::sum('perikiraan_siswa_diterima');
        $guruBelumMonitoringData = User::whereIn('role', ['guru', 'kaprog'])
            ->whereDoesntHave('monitoring')
            ->get();
        $guruBelumMonitoring = $guruBelumMonitoringData->count();

        return view('monitoring.index', compact(
            'monitoring',
            'totalMonitoring',
            'totalIduka',
            'totalIdukaWithMonitoring',
            'totalIdukaWithoutMonitoring',
            'totalPerkiraanSiswa',
            'guruBelumMonitoring',
            'guruBelumMonitoringData'
        ));
    }

    public function create()
    {
        $idukas = Iduka::orderBy('nama')->get();
        return view('monitoring.create', compact('idukas'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'iduka_id' => 'required|exists:idukas,id',
            'saran' => 'nullable|string',
            'perikiraan_siswa_diterima' => 'nullable|integer|min:0',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // cari guru yang sedang login (berdasarkan user_id)
        $guru = Guru::where('user_id', Auth::id())->first();

        // validasi jika bukan guru
        if (!$guru && Auth::user()->role === 'guru') {
            return redirect()->back()->with('error', 'Akun ini belum terdaftar sebagai guru.');
        }

        // upload foto
        $fotos = [];
        if ($request->hasFile('foto')) {
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'uploads/monitoring/' . $filename;
                    $file->move(public_path('uploads/monitoring'), $filename);
                    $fotos[] = $path;
                }
            }
        }

        Monitoring::create([
            'iduka_id' => $request->iduka_id,
            'saran' => $request->saran,
            'perikiraan_siswa_diterima' => $request->perikiraan_siswa_diterima,
            'foto' => $fotos ? json_encode($fotos) : null,
            'guru_id' => $guru ? $guru->id : null, // kalau bukan guru, biarkan NULL
        ]);

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $monitoring = Monitoring::findOrFail($id);

        $request->validate([
            'iduka_id' => 'required|exists:idukas,id',
            'saran' => 'nullable|string',
            'perikiraan_siswa_diterima' => 'nullable|integer|min:0',
            'foto.*' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        // upload foto baru jika ada
        if ($request->hasFile('foto')) {
            if ($monitoring->foto) {
                $oldFotos = json_decode($monitoring->foto, true);
                foreach ($oldFotos as $oldFoto) {
                    $oldPath = public_path($oldFoto);
                    if (file_exists($oldPath)) {
                        @unlink($oldPath);
                    }
                }
            }

            $fotos = [];
            foreach ($request->file('foto') as $file) {
                if ($file->isValid()) {
                    $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                    $path = 'uploads/monitoring/' . $filename;
                    $file->move(public_path('uploads/monitoring'), $filename);
                    $fotos[] = $path;
                }
            }

            $monitoring->foto = json_encode($fotos);
        }

        $monitoring->update([
            'iduka_id' => $request->iduka_id,
            'saran' => $request->saran,
            'perikiraan_siswa_diterima' => $request->perikiraan_siswa_diterima,
        ]);

        $monitoring->save();

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil diperbarui.');
    }

    public function show(Monitoring $monitoring)
    {
        $monitoring->load(['iduka', 'guru']);
        return view('monitoring.show', compact('monitoring'));
    }

    public function edit(Monitoring $monitoring)
    {
        $idukas = Iduka::orderBy('nama')->get();
        return view('monitoring.edit', compact('monitoring', 'idukas'));
    }

    public function destroy(Monitoring $monitoring)
    {
        $fotoPaths = json_decode($monitoring->foto ?? '[]', true);
        foreach ($fotoPaths as $path) {
            if (File::exists(public_path($path))) {
                File::delete(public_path($path));
            }
        }

        $monitoring->delete();

        return redirect()->route('monitoring.index')->with('success', 'Data monitoring berhasil dihapus.');
    }
}
