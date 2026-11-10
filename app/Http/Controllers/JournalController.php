<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class JournalController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Get all journals for both active and history
        $jurnalsQuery = Jurnal::where(function ($query) use ($user) {
            $query->where('user_id', $user->id)
                ->orWhere('nis', $user->nip);
        })
            ->with([
                'user.idukaDiterima',
                'user.pembimbing'
            ])
            ->orderBy('tgl', 'desc')
            ->orderBy('jam_mulai', 'desc');

        // Get all journals
        $jurnals = $jurnalsQuery->get();

        // Split berdasarkan status
        $activeJurnals = $jurnals->filter(function ($jurnal) {
            return $jurnal->status === 'pending' || $jurnal->status === 'rejected';
        });

        $historyJurnals = $jurnals->filter(function ($jurnal) {
            return $jurnal->status === 'approved';
        });

        return view('siswa.jurnal.index', compact('activeJurnals', 'historyJurnals'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tgl' => 'required|date|before_or_equal:today',
            'uraian' => 'required|string|max:1000',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_pengetahuan_baru' => 'nullable',
            'is_dalam_mapel' => 'nullable',
        ]);

        $user = Auth::user();

        $data = [
            'tgl' => $validated['tgl'],
            'uraian' => $validated['uraian'],
            'jam_mulai' => $validated['jam_mulai'],
            'jam_selesai' => $validated['jam_selesai'],
            'user_id' => $user->id,
            'nis' => $user->nip,
            'status' => 'pending',
            'validasi_iduka' => 'belum',
            'validasi_pembimbing' => 'belum',
            'iduka_id' => $user->iduka_id,
            'pembimbing_id' => $user->pembimbing_id,
            'is_pengetahuan_baru' => $request->has('is_pengetahuan_baru') ? 1 : 0,
            'is_dalam_mapel' => $request->has('is_dalam_mapel') ? 1 : 0,
        ];

        if ($request->hasFile('foto')) {
            $data['foto'] = $this->storeJournalPhoto($request->file('foto'));
        }

        Jurnal::create($data);

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil ditambahkan dan menunggu persetujuan.');
    }

    public function update(Request $request, $id)
    {
        try {
            $jurnal = Jurnal::findOrFail($id);
            $user = Auth::user();

            if ($jurnal->user_id !== $user->id && $jurnal->nis !== $user->nip) {
                abort(403, 'Unauthorized access');
            }

            $validator = Validator::make($request->all(), [
                'tgl' => 'required|date|before_or_equal:today',
                'uraian' => 'required|string|max:1000',
                'jam_mulai' => 'required|date_format:H:i',
                'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
                'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ]);

            if ($validator->fails()) {
                // Jika request AJAX, kembalikan error JSON
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json([
                        'success' => false,
                        'message' => 'Validasi gagal.',
                        'errors' => $validator->errors()
                    ], 422);
                }
                // Jika bukan, kembalikan dengan error seperti biasa
                return redirect()->back()->withErrors($validator)->withInput();
            }

            $data = [
                'tgl' => $request->tgl,
                'uraian' => $request->uraian,
                'jam_mulai' => $request->jam_mulai,
                'jam_selesai' => $request->jam_selesai,
                'is_pengetahuan_baru' => $request->has('is_pengetahuan_baru') ? 1 : 0,
                'is_dalam_mapel' => $request->has('is_dalam_mapel') ? 1 : 0,
            ];

            if ($request->hasFile('foto')) {
                if ($jurnal->foto && file_exists(public_path($jurnal->foto))) {
                    unlink(public_path($jurnal->foto));
                }

                $data['foto'] = $this->storeJournalPhoto($request->file('foto'));
            }

            $jurnal->update($data);

            // PERBAIKAN: Cek apakah request dari AJAX
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => 'Jurnal berhasil diperbarui.'
                ]);
            }

            return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error updating journal: ' . $e->getMessage());
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat mengupdate jurnal.'
                ], 500);
            }

            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengupdate jurnal.');
        }
    }

    public function show($id)
    {
        try {
            Log::info('Loading journal detail for ID: ' . $id);

            $jurnal = Jurnal::with(['user', 'user.idukaDiterima', 'user.pembimbing'])
                ->findOrFail($id);

            if ($jurnal->user_id !== auth()->id()) {
                Log::warning('Unauthorized journal access attempt: ' . $id);
                return response()->json([
                    'success' => false,
                    'message' => 'Unauthorized access'
                ], 403);
            }

            $html = view('siswa.jurnal.view', compact('jurnal'))->render();

            return response()->json([
                'success' => true,
                'data' => $html
            ]);

        } catch (\Exception $e) {
            Log::error('Error in show journal: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat detail jurnal: ' . $e->getMessage()
            ], 500);
        }
    }

    public function edit($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $user = Auth::user();

        if ($jurnal->user_id !== $user->id && $jurnal->nis !== $user->nip) {
            if (request()->ajax() || request()->wantsJson()) {
                return response()->json(['error' => 'Unauthorized access'], 403);
            }
            abort(403, 'Unauthorized access');
        }

        if (request()->ajax() || request()->wantsJson()) {
            try {
                // Render the complete edit form
                $html = view('siswa.jurnal.partials.edit', compact('jurnal'))->render();
                return response($html, 200);
            } catch (\Exception $e) {
                Log::error('Error rendering edit form: ' . $e->getMessage());

                // Return a complete form even if there's an error
                $html = '
                <div class="row g-3">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                            <i class="bi bi-calendar3 me-1"></i>Tanggal
                        </label>
                        <input type="date" class="form-control form-control-lg" name="tgl" value="' . $jurnal->tgl . '" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                            <i class="bi bi-image me-1"></i>Foto Kegiatan
                        </label>
                        <input type="file" class="form-control form-control-lg" name="foto" accept="image/*">';

                if ($jurnal->foto) {
                    $html .= '
                    <div class="mt-2 p-2 bg-light rounded">
                        <small class="text-muted d-flex align-items-center">
                            <i class="bi bi-image-fill text-primary me-2"></i>
                            <span>Foto saat ini: </span>
                            <a href="' . asset($jurnal->foto) . '" target="_blank" class="text-primary ms-1 text-decoration-none fw-semibold">
                                <i class="bi bi-eye me-1"></i>Lihat foto
                            </a>
                        </small>
                    </div>';
                } else {
                    $html .= '
                    <small class="text-muted d-block mt-1">
                        <i class="bi bi-info-circle me-1"></i>Belum ada foto. Upload foto baru jika diperlukan.
                    </small>';
                }

                $html .= '
                        <small class="text-muted d-block mt-1">Format: JPG, PNG, GIF (Max: 2MB)</small>
                    </div>
                </div>

                <div class="row g-3 mt-1">
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                            <i class="bi bi-clock me-1"></i>Jam Mulai
                        </label>
                        <input type="time" class="form-control form-control-lg" name="jam_mulai" value="' . $jurnal->jam_mulai . '" required>
                    </div>
                    <div class="col-md-6">
                        <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                            <i class="bi bi-clock-history me-1"></i>Jam Selesai
                        </label>
                        <input type="time" class="form-control form-control-lg" name="jam_selesai" value="' . $jurnal->jam_selesai . '" required>
                    </div>
                </div>

                <div class="mt-3">
                    <label class="form-label fw-semibold text-uppercase small text-muted mb-2">
                        <i class="bi bi-file-text me-1"></i>Uraian Kegiatan
                    </label>
                    <textarea class="form-control form-control-lg" name="uraian" rows="5"
                        placeholder="Tuliskan uraian kegiatan yang dilakukan..." required>' . $jurnal->uraian . '</textarea>
                    <small class="text-muted">
                        <i class="bi bi-info-circle me-1"></i>Maksimal 1000 karakter
                    </small>
                </div>

                <div class="mt-4 p-3 bg-light rounded">
                    <div class="form-check mb-3">
                        <input type="checkbox" class="form-check-input" name="is_pengetahuan_baru"
                            id="edit_is_pengetahuan_baru" value="1" ' . ($jurnal->is_pengetahuan_baru ? 'checked' : '') . '>
                        <label class="form-check-label fw-semibold" for="edit_is_pengetahuan_baru">
                            <i class="bi bi-lightbulb-fill text-warning me-1"></i>
                            Termasuk pengetahuan baru
                        </label>
                    </div>

                    <div class="form-check">
                        <input type="checkbox" class="form-check-input" name="is_dalam_mapel"
                            id="edit_is_dalam_mapel" value="1" ' . ($jurnal->is_dalam_mapel ? 'checked' : '') . '>
                        <label class="form-check-label fw-semibold" for="edit_is_dalam_mapel">
                            <i class="bi bi-book-fill text-primary me-1"></i>
                            Kegiatan ada dalam mapel sekolah
                        </label>
                    </div>
                </div>';

                if ($jurnal->status === 'rejected') {
                    $html .= '
                <div class="alert alert-danger mt-4 border-0 shadow-sm">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill fs-3 me-3"></i>
                        <div>
                            <strong class="d-block mb-1">Jurnal Ditolak</strong>
                            <small>Silakan perbaiki jurnal Anda dan kirim ulang untuk validasi.</small>
                        </div>
                    </div>
                </div>';
                }

                return response($html, 200);
            }
        }

        return view('siswa.jurnal.edit', compact('jurnal'));
    }

    public function destroy($id)
    {
        $jurnal = Jurnal::findOrFail($id);
        $user = Auth::user();

        if ($jurnal->user_id !== $user->id && $jurnal->nis !== $user->nip) {
            abort(403, 'Unauthorized access');
        }

        if ($jurnal->foto && file_exists(public_path($jurnal->foto))) {
            unlink(public_path($jurnal->foto));
        }

        $jurnal->delete();

        return redirect()->route('jurnal.index')->with('success', 'Jurnal berhasil dihapus.');
    }

    private function storeJournalPhoto(UploadedFile $file): string
    {
        $uploadPath = public_path('uploads/jurnals');
        if (!is_dir($uploadPath) && !mkdir($uploadPath, 0777, true) && !is_dir($uploadPath)) {
            throw new \RuntimeException('Folder penyimpanan foto jurnal tidak dapat dibuat.');
        }

        $image = @imagecreatefromstring(file_get_contents($file->getRealPath()));
        if ($image === false) {
            $fileName = $file->hashName();
            $file->move($uploadPath, $fileName);
        } else {
            $fileName = pathinfo($file->hashName(), PATHINFO_FILENAME) . '.jpg';
            $saved = imagejpeg($image, $uploadPath . DIRECTORY_SEPARATOR . $fileName, 30);
            imagedestroy($image);

            if (!$saved) {
                throw new \RuntimeException('Foto jurnal gagal disimpan.');
            }
        }

        return 'uploads/jurnals/' . $fileName;
    }

    public function siswaBelumIsi()
    {
        $user = Auth::user();

        // Hanya bisa diakses oleh hubin, kepsek, kaprog
        if (!in_array($user->role, ['hubin', 'kepsek', 'kaprog'])) {
            abort(403, 'Unauthorized access');
        }

        // Ambil tanggal hari ini
        $today = Carbon::today()->format('Y-m-d');

        // Ambil ID siswa yang sudah mengisi jurnal hari ini
        $siswaSudahIsi = Jurnal::whereDate('tgl', $today)
            ->pluck('user_id')
            ->toArray();

        // Ambil semua siswa dengan pagination
        $siswaQuery = User::where('role', 'siswa')
        ->where('status', 'aktif')
            ->whereNotIn('id', $siswaSudahIsi) // Filter langsung di query
            ->with(['iduka', 'kelas']); // Eager loading kelas

        // PERBAIKAN: Hapus filter jurusan untuk kaprog sementara
        // Hubin, Kepsek, dan Kaprog bisa melihat semua siswa

        // Gunakan pagination biasa untuk semua role
        $siswaBelumIsi = $siswaQuery->paginate(10);

        return view('jurnal.siswa-belumisi', [
            'siswaBelumIsi' => $siswaBelumIsi,
            'today' => $today
        ]);
    }

    public function kirimPengingat(Request $request)
    {
        $user = Auth::user();

        // Hanya bisa diakses oleh hubin, kepsek, kaprog
        if (!in_array($user->role, ['hubin', 'kepsek', 'kaprog'])) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized access'
            ], 403);
        }

        $validated = $request->validate([
            'siswa_id' => 'nullable|exists:users,id',
            'message' => 'required|string'
        ]);

        $siswaId = $request->siswa_id;
        $message = $request->message;

        // Ambil siswa yang akan dikirimi pengingat
        $siswaQuery = User::where('role', 'siswa');

        if ($siswaId) {
            // Jika hanya satu siswa
            $siswaQuery->where('id', $siswaId);
        } else {
            // Jika semua siswa yang belum mengisi jurnal hari ini
            $today = Carbon::today()->format('Y-m-d');
            $siswaSudahIsi = Jurnal::whereDate('tgl', $today)
                ->pluck('user_id')
                ->toArray();

            $siswaQuery->whereNotIn('id', $siswaSudahIsi);

            // Filter berdasarkan role
            if ($user->role === 'kaprog') {
                $siswaQuery->whereHas('kelas', function ($query) use ($user) {
                    $query->where('jurusan_id', $user->jurusan_id);
                });
            }
        }

        $siswaList = $siswaQuery->get();

        $count = 0;
        $errors = [];

        foreach ($siswaList as $siswa) {
            $personalMessage = str_replace('{nama_siswa}', $siswa->name, $message);

            // Kirim via Email
            if ($siswa->email) {
                try {
                    // Implementasi kirim email
                    // Mail::to($siswa->email)->send(new JournalReminder($personalMessage));

                    // Simulasi pengiriman email
                    Log::info("Mengirim email ke {$siswa->email}: {$personalMessage}");
                    $count++;
                } catch (\Exception $e) {
                    $errors[] = "Gagal kirim email ke {$siswa->name}: {$e->getMessage()}";
                }
            } else {
                $errors[] = "Siswa {$siswa->name} tidak memiliki alamat email";
            }
        }

        $responseMessage = "Pengingat berhasil dikirim ke {$count} siswa melalui email.";

        if (count($errors) > 0) {
            $responseMessage .= " Beberapa error terjadi: " . implode("; ", $errors);
        }

        return response()->json([
            'success' => true,
            'message' => $responseMessage
        ]);
    }

}
