<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Absensi;
use App\Models\AbsensiPending;
use App\Models\DinasPending;
use App\Models\IzinPending;
use App\Models\Iduka;
use App\Models\User;
use App\Models\Guru;
use App\Models\IdukaHoliday;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class AbsensiController extends Controller
{
    /**
     * Returns true when today's date is within the configured PKL period.
     */
    private function isPklActive()
    {
        $today = Carbon::today();
        $start = Carbon::parse(config('pkl.start'));
        $end   = Carbon::parse(config('pkl.end'));

        return $today->between($start, $end);
    }

    /**
     * Throw exception if PKL period has ended; used to guard write actions.
     */
    private function ensurePklActive()
    {
        if (!$this->isPklActive()) {
            throw new \Exception('Periode PKL telah selesai. Absensi tidak dapat dilakukan lagi.');
        }
    }

    public function index()
    {
        $user = Auth::user();
        $pkEnded = !$this->isPklActive();
        $absensiHariIni = null;
        $riwayatAbsensi = collect();
        $canPulang = false;
        $isHoliday = false;
        $holidayLabel = '';

        // Get today's attendance if exists
        if ($user->idukaDiterima) {
            // Cek status hari libur
            try {
                if (Carbon::today()->isSunday()) {
                    $isHoliday = true;
                    $holidayLabel = 'Hari Minggu (Libur Umum)';
                } else {
                    $hols = IdukaHoliday::getHolidaysOn($user->idukaDiterima->id, Carbon::today()->toDateString());
                    if ($hols && $hols->isNotEmpty()) {
                        $isHoliday = true;
                        $names = $hols->pluck('name')->filter()->values();
                        $holidayLabel = $names->count() ? $names->implode(', ') : 'Hari libur';
                    }
                }

                Log::info('Status hari libur di method index', [
                    'user_id' => $user->id,
                    'is_holiday' => $isHoliday,
                    'holiday_label' => $holidayLabel,
                    'date' => Carbon::today()->toDateString()
                ]);
            } catch (\Exception $e) {
                Log::error('Gagal memeriksa hari libur di method index: ' . $e->getMessage());
            }

            // Get today's attendance if exists
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            // Get recent attendance history (last 7 days)
            $riwayatAbsensi = Absensi::where('user_id', $user->id)
                ->whereBetween('tanggal', [
                    Carbon::now()->subDays(7),
                    Carbon::today()
                ])
                ->orderBy('tanggal', 'desc')
                ->limit(10)
                ->get();

            // Determine if student can check out
            if ($absensiHariIni) {
                if ($absensiHariIni->status_dinas === 'disetujui') {
                    // Jika sedang dinas luar, bisa pulang langsung tanpa harus masuk
                    $canPulang = !$absensiHariIni->jam_pulang;
                } else {
                    // Normal case: harus sudah masuk dan belum pulang
                    $canPulang = $absensiHariIni->jam_masuk && !$absensiHariIni->jam_pulang && $absensiHariIni->status !== 'izin';
                }
            }
        }

        // Get pending status
        $absensiPending = AbsensiPending::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->get();

        $izinPending = IzinPending::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        $dinasPending = DinasPending::where('user_id', $user->id)
            ->whereDate('tanggal', Carbon::today())
            ->first();

        // Status untuk menentukan apakah tombol harus disabled
        $hasPendingIzin = $izinPending ? true : false;
        $hasPendingDinas = $dinasPending ? true : false;
        $hasApprovedIzin = $absensiHariIni && $absensiHariIni->status === 'izin';
        $hasApprovedDinas = $absensiHariIni && $absensiHariIni->status_dinas === 'disetujui';
        $hasPendingMasuk = $absensiPending->where('jenis', 'masuk')->count() > 0;
        $hasPendingPulang = $absensiPending->where('jenis', 'pulang')->count() > 0;

        return view('siswa.absensi.index', compact(
            'absensiHariIni',
            'riwayatAbsensi',
            'absensiPending',
            'izinPending',
            'dinasPending',
            'canPulang',
            'hasPendingIzin',
            'hasPendingDinas',
            'hasApprovedIzin',
            'hasApprovedDinas',
            'hasPendingMasuk',
            'hasPendingPulang',
            'isHoliday',
            'holidayLabel',
            'pkEnded'
        ));
    }

    public function getRiwayatData(Request $request)
    {
        try {
            $user = Auth::user();

            // Query dasar
            $query = Absensi::where('user_id', $user->id);

            // Urutkan dari yang terbaru
            $query->orderBy('tanggal', 'desc');

            // Pagination
            $perPage = $request->per_page ?? 10;
            $page = $request->page ?? 1;

            $data = $query->paginate($perPage, ['*'], 'page', $page);

            return response()->json($data);
        } catch (\Exception $e) {
            Log::error('Error in getRiwayatData: ' . $e->getMessage());
            return response()->json([
                'error' => 'Terjadi kesalahan saat mengambil data riwayat absensi'
            ], 500);
        }
    }

    public function detailRiwayat($id)
    {
        $user = Auth::user();

        // Cari absensi berdasarkan ID dan pastikan milik user yang login
        $absensi = Absensi::where('id', $id)
            ->where('user_id', $user->id)
            ->firstOrFail();

        return response()->json($absensi);
    }

    public function masuk(Request $request)
    {
        try {
            $this->ensurePklActive();
            DB::beginTransaction();

            $user = Auth::user();
            $today = Carbon::today();
            $now = Carbon::now();

            // Logging untuk debugging
            Log::info('Absensi masuk dipanggil', [
                'user_id' => $user->id,
                'date' => $today->toDateString(),
                'is_sunday' => $today->isSunday(),
                'iduka_id' => $user->idukaDiterima->id
            ]);

            // Validasi apakah user sudah terdaftar di IDUKA
            if (!$user->idukaDiterima) {
                return redirect()->back()->with('error', 'Anda belum terdaftar di IDUKA. Silakan hubungi administrator.');
            }

            // CEK HARI LIBUR - Harus di paling awal
            try {
                // Cek hari Minggu terlebih dahulu
                if ($today->isSunday()) {
                    Log::warning('Mencoba absen pada hari Minggu', [
                        'user_id' => $user->id,
                        'iduka_id' => $user->idukaDiterima->id,
                        'date' => $today->toDateString()
                    ]);
                    return redirect()->back()->with('error', 'Absensi ditutup: Hari Minggu (Libur Umum). Silakan hubungi IDUKA jika ada keperluan mendesak.');
                }

                // Cek hari libur khusus dari database
                $hols = IdukaHoliday::getHolidaysOn($user->idukaDiterima->id, $today->toDateString());
                if ($hols && $hols->isNotEmpty()) {
                    $names = $hols->pluck('name')->filter()->values();
                    $label = $names->count() ? $names->implode(', ') : 'Hari libur';
                    Log::warning('Mencoba absen pada hari libur', [
                        'user_id' => $user->id,
                        'iduka_id' => $user->idukaDiterima->id,
                        'date' => $today->toDateString(),
                        'holidays' => $names->toArray()
                    ]);
                    return redirect()->back()->with('error', "Absensi ditutup: {$label}. Silakan hubungi IDUKA jika ada keperluan mendesak.");
                }
            } catch (\Exception $e) {
                Log::error('Gagal memeriksa hari libur: ' . $e->getMessage());
            }

            // VALIDASI: Pastikan user memiliki pembimbing_id yang valid
            if (!$user->pembimbing_id) {
                return redirect()->back()->with('error', 'Anda belum memiliki pembimbing. Silakan hubungi administrator.');
            }

            // VALIDASI: Pastikan pembimbing_id ada di tabel gurus
            $pembimbingExists = \App\Models\Guru::where('id', $user->pembimbing_id)->exists();
            if (!$pembimbingExists) {
                Log::error('Pembimbing tidak ditemukan di tabel gurus', [
                    'user_id' => $user->id,
                    'pembimbing_id' => $user->pembimbing_id
                ]);
                return redirect()->back()->with('error', 'Data pembimbing tidak valid. Silakan hubungi administrator.');
            }

            // Cek apakah sudah ada absensi masuk hari ini
            $existingAbsensi = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->first();

            if ($existingAbsensi) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi masuk hari ini.');
            }

            // Cek apakah sudah ada pending absensi masuk hari ini
            $existingPending = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', $today)
                ->where('jenis', 'masuk')
                ->first();

            if ($existingPending) {
                return redirect()->back()->with('info', 'Anda sudah memiliki absensi masuk yang menunggu konfirmasi.');
            }

            // Validasi koordinat jika dikirim
            if ($request->has('latitude') && $request->has('longitude')) {
                $request->validate([
                    'latitude' => 'required|numeric|between:-90,90',
                    'longitude' => 'required|numeric|between:-180,180',
                    'lokasi_id' => 'required|string',
                    'accuracy' => 'required|numeric|min:0'
                ]);
            }

            // Tentukan lokasi yang digunakan (pusat atau cabang)
            $lokasi = $this->getSelectedLocation($request->lokasi_id, $user);

            // Validasi lokasi user
            if ($request->has('latitude') && $request->has('longitude')) {
                $validation = $this->validateLocation(
                    $request->latitude,
                    $request->longitude,
                    $lokasi->latitude,
                    $lokasi->longitude,
                    $lokasi->radius,
                    $request->accuracy
                );

                if (!$validation['isWithinRadius']) {
                    $lokasiName = $request->lokasi_id === 'pusat' ? 'Pusat' : $lokasi->nama;
                    return redirect()->back()->with(
                        'error',
                        "Anda berada di luar radius yang diizinkan untuk absensi di {$lokasiName}. Jarak Anda: " . round($validation['distance']) . " meter, Radius maksimal: " . $lokasi->radius . " meter, Akurasi GPS: ±" . round($request->accuracy) . "m"
                    );
                }
            }

            // Tentukan status berdasarkan waktu
            $status = $this->getStatusAbsensi($now, $lokasi->jam_masuk);

            // Ambil data guru yang menjadi pembimbing siswa
            $guru = Guru::find($user->pembimbing_id);
            if (!$guru) {
                return redirect()->back()->with('error', 'Data pembimbing tidak ditemukan. Silakan hubungi administrator.');
            }

            // Create pending absensi dengan SEMUA field yang diperlukan
            $pendingData = [
                'user_id' => $user->id,
                'iduka_id' => $user->idukaDiterima->id,
                'pembimbing_id' => $guru->id,
                'lokasi_iduka_id' => $lokasi->id,
                'tanggal' => $today->format('Y-m-d'),
                'jenis' => 'masuk',
                'jam' => $now->format('Y-m-d H:i:s'),
                'status' => $status,
                'status_konfirmasi' => 'pending',
                'validasi_iduka' => 'pending',
                'validasi_pembimbing' => 'pending',
                'approved_iduka_at' => null,
                'approved_pembimbing_at' => null,
            ];

            // Tambahkan field opsional jika ada
            if ($request->filled('latitude')) {
                $pendingData['latitude'] = $request->latitude;
            }

            if ($request->filled('longitude')) {
                $pendingData['longitude'] = $request->longitude;
            }

            if ($request->filled('keterangan')) {
                $pendingData['keterangan'] = $request->keterangan;
            }

            AbsensiPending::create($pendingData);

            DB::commit();

            Log::info('Absensi masuk pending berhasil dibuat', [
                'user_id' => $user->id,
                'pembimbing_id' => $guru->id,
                'pembimbing_name' => $guru->nama ?? 'N/A',
                'lokasi_id' => $lokasi->id,
                'lokasi_nama' => $lokasi->nama,
                'jam' => $now->format('Y-m-d H:i:s'),
                'status' => $status
            ]);

            return redirect()->back()->with('success', 'Absen masuk berhasil, menunggu konfirmasi IDUKA dan Pembimbing.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('Error in absen masuk: ' . $e->getMessage(), [
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat absen masuk: ' . $e->getMessage());
        }
    }

    public function pulang(Request $request)
    {
        $this->ensurePklActive();
        Log::info('=== ABSEN PULANG DIPANGGIL ===', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        // Cari record absensi hari ini terlebih dahulu untuk menentukan validasi
        $absensiHariIni = Absensi::where('user_id', Auth::id())
            ->whereDate('tanggal', Carbon::today())
            ->first();

        // Jika sedang dinas luar, validasi berbeda
        if ($absensiHariIni && $absensiHariIni->status_dinas === 'disetujui') {
            $request->validate([
                'lokasi_id' => 'required|string',
            ]);
        } else {
            $request->validate([
                'latitude' => 'required|numeric|between:-90,90',
                'longitude' => 'required|numeric|between:-180,180',
                'lokasi_id' => 'required|string',
                'accuracy' => 'required|numeric|min:0'
            ]);
        }

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar di IDUKA manapun.');
            }

            // Logging untuk debugging
            Log::info('Absensi pulang dipanggil', [
                'user_id' => $user->id,
                'date' => Carbon::today()->toDateString(),
                'is_sunday' => Carbon::today()->isSunday(),
                'iduka_id' => $user->idukaDiterima->id
            ]);

            // Cek hari libur untuk IDUKA sebelum memproses absen pulang
            try {
                // Cek hari Minggu terlebih dahulu
                if (Carbon::today()->isSunday()) {
                    Log::warning('Mencoba absen pulang pada hari Minggu', [
                        'user_id' => $user->id,
                        'iduka_id' => $user->idukaDiterima->id,
                        'date' => Carbon::today()->toDateString()
                    ]);
                    return redirect()->back()->with('error', 'Absensi ditutup: Hari Minggu (Libur Umum). Silakan hubungi IDUKA jika ada keperluan mendesak.');
                }

                // Cek hari libur khusus dari database
                $holsPulang = IdukaHoliday::getHolidaysOn($user->idukaDiterima->id, Carbon::today()->toDateString());
                if ($holsPulang && $holsPulang->isNotEmpty()) {
                    $namesP = $holsPulang->pluck('name')->filter()->values();
                    $labelP = $namesP->count() ? $namesP->implode(', ') : 'Hari libur';
                    Log::warning('Mencoba absen pulang pada hari libur', [
                        'user_id' => $user->id,
                        'iduka_id' => $user->idukaDiterima->id,
                        'date' => Carbon::today()->toDateString(),
                        'holidays' => $namesP->toArray()
                    ]);
                    return redirect()->back()->with('error', "Absensi ditutup: {$labelP}. Silakan hubungi IDUKA jika ada keperluan mendesak.");
                }
            } catch (\Exception $e) {
                Log::error('Gagal memeriksa hari libur untuk pulang: ' . $e->getMessage());
            }

            // Jika tidak ada absensi hari ini, cari lagi
            if (!$absensiHariIni) {
                $absensiHariIni = Absensi::where('user_id', $user->id)
                    ->whereDate('tanggal', Carbon::today())
                    ->first();

                if (!$absensiHariIni) {
                    return redirect()->back()->with('error', 'Anda belum memiliki record absensi hari ini.');
                }
            }

            // Jika sedang izin, tidak bisa absen pulang
            if ($absensiHariIni->status === 'izin') {
                return redirect()->back()->with('error', 'Anda sedang izin hari ini. Tidak bisa melakukan absensi pulang.');
            }

            // Jika sudah absen pulang
            if ($absensiHariIni->jam_pulang) {
                return redirect()->back()->with('error', 'Anda sudah melakukan absensi pulang hari ini pada ' . $absensiHariIni->jam_pulang->format('H:i'));
            }

            // Logika utama untuk dinas luar
            if ($absensiHariIni->status_dinas === 'disetujui') {
                // Untuk dinas luar, validasi jam 12.00
                $jamPulangMinimalDinas = Carbon::today()->setHour(12)->setMinute(0);
                if (Carbon::now()->lt($jamPulangMinimalDinas)) {
                    return redirect()->back()->with(
                        'error',
                        'Absen pulang untuk dinas luar hanya dapat dilakukan setelah jam 12.00 siang.'
                    );
                }
                Log::info('Siswa sedang dinas luar, diizinkan absen pulang langsung');

                // Untuk dinas luar, kita tidak perlu validasi lokasi
                // Gunakan lokasi pusat sebagai default
                $lokasi = $user->idukaDiterima;
            } else {
                // Untuk kasus normal, harus sudah absen masuk
                if (!$absensiHariIni->jam_masuk) {
                    return redirect()->back()->with('error', 'Anda belum melakukan absensi masuk hari ini.');
                }

                // Tentukan lokasi yang digunakan (pusat atau cabang)
                try {
                    $lokasi = $this->getSelectedLocation($request->lokasi_id, $user);
                    Log::info('Lokasi yang dipilih', [
                        'lokasi_id' => $lokasi->id,
                        'lokasi_nama' => $lokasi->nama,
                        'is_pusat' => $lokasi->is_pusat
                    ]);
                } catch (\Exception $e) {
                    Log::error('Error saat memilih lokasi', [
                        'error' => $e->getMessage()
                    ]);
                    return redirect()->back()->with('error', $e->getMessage());
                }

                // Validasi jam pulang minimal jam pulang yang ditetapkan IDUKA
                $jamPulangMinimal = Carbon::today()->setHour(15); // default jam 15:00

                // Gunakan jam pulang dari lokasi yang dipilih
                if ($lokasi->jam_pulang) {
                    $jamPulangMinimal = Carbon::createFromTimeString($lokasi->jam_pulang);
                }

                if (Carbon::now()->lt($jamPulangMinimal)) {
                    return redirect()->back()->with(
                        'error',
                        'Absen pulang hanya dapat dilakukan setelah jam ' . $jamPulangMinimal->format('H:i') . '.'
                    );
                }

                // Validasi lokasi user (hanya untuk non-dinas)
                $validation = $this->validateLocation(
                    $request->latitude,
                    $request->longitude,
                    $lokasi->latitude,
                    $lokasi->longitude,
                    $lokasi->radius,
                    $request->accuracy
                );

                if (!$validation['isWithinRadius']) {
                    $lokasiName = $request->lokasi_id === 'pusat' ? 'Pusat' : $lokasi->nama;
                    return redirect()->back()->with(
                        'error',
                        "Anda berada di luar radius yang diizinkan untuk absensi di {$lokasiName}. Jarak Anda: " . round($validation['distance']) . " meter, Radius maksimal: " . $lokasi->radius . " meter, Akurasi GPS: ±" . round($request->accuracy) . "m"
                    );
                }
            }

            // Simpan absensi pulang
            try {
                $updateData = [
                    'jam_pulang' => Carbon::now(),
                    'lokasi_iduka_id' => $lokasi->id,
                ];

                // Hanya tambahkan koordinat jika bukan dinas luar
                if ($absensiHariIni->status_dinas !== 'disetujui') {
                    $updateData['latitude_pulang'] = $request->latitude;
                    $updateData['longitude_pulang'] = $request->longitude;
                }

                $absensiHariIni->update($updateData);

                Log::info('Absensi pulang berhasil disimpan', [
                    'user_id' => $user->id,
                    'lokasi_id' => $lokasi->id,
                    'lokasi_nama' => $lokasi->nama,
                    'jam_pulang' => Carbon::now()->format('Y-m-d H:i:s'),
                    'is_dinas' => $absensiHariIni->status_dinas === 'disetujui'
                ]);
            } catch (\Exception $e) {
                Log::error('Error saat menyimpan absensi pulang', [
                    'error' => $e->getMessage(),
                    'lokasi_id' => $lokasi->id,
                    'absensi_id' => $absensiHariIni->id
                ]);

                // Cek apakah error karena foreign key
                if (strpos($e->getMessage(), 'foreign key constraint') !== false) {
                    return redirect()->back()->with('error', 'Lokasi absensi tidak valid. Silakan pilih lokasi yang tersedia.');
                }

                throw $e; // Re-throw exception untuk ditangkap di outer catch
            }

            DB::commit();

            return redirect()->back()->with('success', 'Absensi pulang berhasil disimpan.');
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ERROR saat menyimpan absensi pulang', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyimpan absensi: ' . $e->getMessage());
        }
    }

    public function izin(Request $request)
    {
        $this->ensurePklActive();
        Log::info('=== AJUKAN IZIN DIPANGGIL ===', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'jenis_izin' => 'required|string|in:sakit,keperluan_keluarga,keperluan_sekolah,lainnya',
            'keterangan' => 'required|string|min:10|max:500',
        ], [
            'jenis_izin.required' => 'Jenis izin harus dipilih.',
            'jenis_izin.in' => 'Jenis izin tidak valid.',
            'keterangan.required' => 'Keterangan izin harus diisi.',
            'keterangan.min' => 'Keterangan izin minimal 10 karakter.',
            'keterangan.max' => 'Keterangan izin maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar di IDUKA manapun. Silakan hubungi administrator.');
            }

            // PERBAIKAN: Ambil data guru yang menjadi pembimbing siswa
            $guru = Guru::find($user->pembimbing_id);
            if (!$guru) {
                return redirect()->back()->with('error', 'Data pembimbing tidak ditemukan. Silakan hubungi administrator.');
            }

            $iduka = $user->idukaDiterima;

            // Check if already has attendance record today
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($absensiHariIni) {
                if ($absensiHariIni->status === 'izin') {
                    return redirect()->back()->with('error', 'Anda sudah mengajukan izin hari ini.');
                } else {
                    return redirect()->back()->with('error', 'Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan izin.');
                }
            }

            // Check if there's pending izin
            $izinPendingHariIni = IzinPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($izinPendingHariIni) {
                return redirect()->back()->with('info', 'Anda sudah mengajukan izin. Menunggu konfirmasi IDUKA.');
            }

            // Check if there's pending attendance
            $absensiPendingHariIni = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($absensiPendingHariIni) {
                return redirect()->back()->with('error', 'Anda memiliki absensi yang menunggu konfirmasi. Tidak bisa mengajukan izin.');
            }

            // PERBAIKAN: Tambahkan pembimbing_id ke data izin pending
            IzinPending::create([
                'user_id' => $user->id,
                'iduka_id' => $iduka->id,
                'pembimbing_id' => $guru->id, // PERBAIKAN: Gunakan ID guru dari tabel gurus
                'tanggal' => Carbon::today(),
                'jenis_izin' => $request->jenis_izin,
                'keterangan' => $request->keterangan,
                'status_konfirmasi' => 'pending'
            ]);

            DB::commit();

            $jenisIzinText = [
                'sakit' => 'Sakit',
                'keperluan_keluarga' => 'Keperluan Keluarga',
                'keperluan_sekolah' => 'Keperluan Sekolah',
                'lainnya' => 'Lainnya'
            ];

            return redirect()->back()->with(
                'success',
                'Izin berhasil diajukan untuk hari ini. Menunggu konfirmasi IDUKA. Jenis: ' . $jenisIzinText[$request->jenis_izin] .
                '. Alasan: ' . $request->keterangan
            );
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ERROR saat mengajukan izin', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan izin: ' . $e->getMessage());
        }
    }

    // Tambahkan method ini di AbsensiController
    public function dinasLuar(Request $request)
    {
        $this->ensurePklActive();
        Log::info('=== AJUKAN DINAS LUAR DIPANGGIL ===', [
            'user_id' => Auth::id(),
            'request_data' => $request->all()
        ]);

        $request->validate([
            'jenis_dinas' => 'required|string|in:perusahaan,sekolah,instansi_pemerintah,lainnya',
            'keterangan' => 'required|string|min:10|max:500',
        ], [
            'jenis_dinas.required' => 'Jenis dinas harus dipilih.',
            'jenis_dinas.in' => 'Jenis dinas tidak valid.',
            'keterangan.required' => 'Keterangan dinas harus diisi.',
            'keterangan.min' => 'Keterangan dinas minimal 10 karakter.',
            'keterangan.max' => 'Keterangan dinas maksimal 500 karakter.',
        ]);

        try {
            DB::beginTransaction();

            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return redirect()->back()->with('error', 'Anda tidak terdaftar di IDUKA manapun. Silakan hubungi administrator.');
            }

            // PERBAIKAN: Ambil data guru yang menjadi pembimbing siswa
            $guru = Guru::find($user->pembimbing_id);
            if (!$guru) {
                return redirect()->back()->with('error', 'Data pembimbing tidak ditemukan. Silakan hubungi administrator.');
            }

            $iduka = $user->idukaDiterima;

            // Check if already has attendance record today
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($absensiHariIni) {
                if ($absensiHariIni->status === 'dinas') {
                    return redirect()->back()->with('error', 'Anda sudah mengajukan dinas luar hari ini.');
                } else {
                    return redirect()->back()->with('error', 'Anda sudah memiliki record absensi hari ini. Tidak bisa mengajukan dinas luar.');
                }
            }

            // Check if there's pending dinas
            $dinasPendingHariIni = DinasPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($dinasPendingHariIni) {
                return redirect()->back()->with('info', 'Anda sudah mengajukan dinas luar. Menunggu konfirmasi IDUKA.');
            }

            // Check if there's pending attendance
            $absensiPendingHariIni = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            if ($absensiPendingHariIni) {
                return redirect()->back()->with('error', 'Anda memiliki absensi yang menunggu konfirmasi. Tidak bisa mengajukan dinas luar.');
            }

            // PERBAIKAN: Tambahkan pembimbing_id ke data dinas pending
            DinasPending::create([
                'user_id' => $user->id,
                'iduka_id' => $iduka->id,
                'pembimbing_id' => $guru->id, // PERBAIKAN: Gunakan ID guru dari tabel gurus
                'tanggal' => Carbon::today(),
                'jenis_dinas' => $request->jenis_dinas,
                'keterangan' => $request->keterangan,
                'status_konfirmasi' => 'pending'
            ]);

            DB::commit();

            $jenisDinasText = [
                'perusahaan' => 'Perusahaan',
                'sekolah' => 'Sekolah',
                'instansi_pemerintah' => 'Instansi Pemerintah',
                'lainnya' => 'Lainnya'
            ];

            return redirect()->back()->with(
                'success',
                'Dinas luar berhasil diajukan untuk hari ini. Menunggu konfirmasi IDUKA. Jenis: ' . $jenisDinasText[$request->jenis_dinas] .
                '. Alasan: ' . $request->keterangan
            );
        } catch (\Exception $e) {
            DB::rollback();
            Log::error('ERROR saat mengajukan dinas luar', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return redirect()->back()->with('error', 'Terjadi kesalahan saat mengajukan dinas luar: ' . $e->getMessage());
        }
    }

    /**
     * Validate if user location is within IDUKA radius
     */
    private function validateLocation($latUser, $longUser, $latTarget, $longTarget, $radius, $accuracy = 0)
    {
        // Ensure all inputs are float
        $latUser = floatval($latUser);
        $longUser = floatval($longUser);
        $latTarget = floatval($latTarget);
        $longTarget = floatval($longTarget);
        $radius = floatval($radius);
        $accuracy = floatval($accuracy);

        Log::info('Validasi lokasi', [
            'user_lat' => $latUser,
            'user_lng' => $longUser,
            'target_lat' => $latTarget,
            'target_lng' => $longTarget,
            'radius' => $radius,
            'accuracy' => $accuracy
        ]);

        // Check if target coordinates are valid
        if ($latTarget == 0 && $longTarget == 0) {
            Log::warning('Koordinat target tidak valid');
            return [
                'distance' => 0,
                'isWithinRadius' => false,
                'error' => 'Koordinat target tidak valid'
            ];
        }

        // Calculate distance using Haversine formula
        $earthRadius = 6371000; // Earth radius in meters

        $latFrom = deg2rad($latUser);
        $lonFrom = deg2rad($longUser);
        $latTo = deg2rad($latTarget);
        $lonTo = deg2rad($longTarget);

        $latDelta = $latTo - $latFrom;
        $lonDelta = $lonTo - $lonFrom;

        $a = pow(sin($latDelta / 2), 2) +
            cos($latFrom) * cos($latTo) * pow(sin($lonDelta / 2), 2);

        $c = 2 * asin(sqrt($a));

        $distance = $earthRadius * $c;

        // Gunakan akurasi GPS sebagai toleransi
        $effectiveRadius = $radius + $accuracy;
        $isWithinRadius = $distance <= $effectiveRadius;

        Log::info('Hasil kalkulasi jarak', [
            'distance' => $distance,
            'effective_radius' => $effectiveRadius,
            'is_within_radius' => $isWithinRadius
        ]);

        return [
            'distance' => $distance,
            'effectiveRadius' => $effectiveRadius,
            'isWithinRadius' => $isWithinRadius,
        ];
    }

    /**
     * Get attendance status for API/AJAX requests
     */
    public function getStatus()
    {
        try {
            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return response()->json([
                    'success' => false,
                    'message' => 'Anda tidak terdaftar di IDUKA manapun'
                ]);
            }

            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            // Check pending status
            $absensiPending = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->get();

            $izinPending = IzinPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            return response()->json([
                'success' => true,
                'data' => [
                    'sudah_masuk' => $absensiHariIni && $absensiHariIni->jam_masuk ? true : false,
                    'sudah_pulang' => $absensiHariIni && $absensiHariIni->jam_pulang ? true : false,
                    'sedang_izin' => $absensiHariIni && $absensiHariIni->status === 'izin' ? true : false,
                    'jam_masuk' => $absensiHariIni ? $absensiHariIni->jam_masuk?->format('H:i') : null,
                    'jam_pulang' => $absensiHariIni ? $absensiHariIni->jam_pulang?->format('H:i') : null,
                    'status' => $absensiHariIni ? $absensiHariIni->status : null,
                    'jenis_izin' => $absensiHariIni && $absensiHariIni->status === 'izin' ? $absensiHariIni->jenis_izin : null,
                    'keterangan_izin' => $absensiHariIni && $absensiHariIni->status === 'izin' ? $absensiHariIni->keterangan_izin : null,
                    'pending_masuk' => $absensiPending->where('jenis', 'masuk')->count() > 0,
                    'pending_pulang' => $absensiPending->where('jenis', 'pulang')->count() > 0,
                    'pending_izin' => $izinPending ? true : false,
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ], 500);
        }
    }

    public function cekStatusIzin()
    {
        try {
            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return response()->json([
                    'has_attendance' => false,
                    'has_approved_izin' => false,
                    'has_pending_izin' => false,
                    'has_pending_masuk' => false,
                    'has_pending_pulang' => false,
                    'error' => 'Anda tidak terdaftar di IDUKA manapun'
                ]);
            }

            // Check if already has attendance record today
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            $hasAttendance = (bool) $absensiHariIni;
            $hasApprovedIzin = $absensiHariIni && $absensiHariIni->status === 'izin';

            // Check if there's pending izin
            $hasPendingIzin = IzinPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->exists();

            // Check if there's pending attendance
            $absensiPending = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->get();

            $hasPendingMasuk = $absensiPending->where('jenis', 'masuk')->count() > 0;
            $hasPendingPulang = $absensiPending->where('jenis', 'pulang')->count() > 0;

            return response()->json([
                'has_attendance' => $hasAttendance,
                'has_approved_izin' => $hasApprovedIzin,
                'has_pending_izin' => $hasPendingIzin,
                'has_pending_masuk' => $hasPendingMasuk,
                'has_pending_pulang' => $hasPendingPulang
            ]);
        } catch (\Exception $e) {
            Log::error('Error in cekStatusIzin: ' . $e->getMessage());
            return response()->json([
                'has_attendance' => false,
                'has_approved_izin' => false,
                'has_pending_izin' => false,
                'has_pending_masuk' => false,
                'has_pending_pulang' => false,
                'error' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    public function cekStatusDinas()
    {
        try {
            $user = Auth::user();

            if (!$user->idukaDiterima) {
                return response()->json([
                    'has_attendance' => false,
                    'has_approved_dinas' => false,
                    'has_pending_dinas' => false,
                    'has_pending_masuk' => false,
                    'has_pending_pulang' => false,
                    'error' => 'Anda tidak terdaftar di IDUKA manapun'
                ]);
            }

            // Check if already has attendance record today
            $absensiHariIni = Absensi::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->first();

            $hasAttendance = (bool) $absensiHariIni;
            $hasApprovedDinas = $absensiHariIni && $absensiHariIni->status === 'dinas';

            // Check if there's pending dinas
            $hasPendingDinas = DinasPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->exists();

            // Check if there's pending attendance
            $absensiPending = AbsensiPending::where('user_id', $user->id)
                ->whereDate('tanggal', Carbon::today())
                ->get();

            $hasPendingMasuk = $absensiPending->where('jenis', 'masuk')->count() > 0;
            $hasPendingPulang = $absensiPending->where('jenis', 'pulang')->count() > 0;

            return response()->json([
                'has_attendance' => $hasAttendance,
                'has_approved_dinas' => $hasApprovedDinas,
                'has_pending_dinas' => $hasPendingDinas,
                'has_pending_masuk' => $hasPendingMasuk,
                'has_pending_pulang' => $hasPendingPulang
            ]);
        } catch (\Exception $e) {
            Log::error('Error in cekStatusDinas: ' . $e->getMessage());
            return response()->json([
                'has_attendance' => false,
                'has_approved_dinas' => false,
                'has_pending_dinas' => false,
                'has_pending_masuk' => false,
                'has_pending_pulang' => false,
                'error' => 'Terjadi kesalahan sistem'
            ], 500);
        }
    }

    /**
     * Get selected location object based on lokasi_id parameter
     */
    private function getSelectedLocation($lokasiId, $user)
    {
        // Jika lokasi_id adalah 'pusat', cari lokasi pusat
        if ($lokasiId === 'pusat') {
            $lokasi = Iduka::where('id', $user->idukaDiterima->id)
                ->where('is_pusat', 1)
                ->first();

            if (!$lokasi) {
                throw new \Exception('Lokasi pusat tidak ditemukan untuk IDUKA Anda.');
            }

            return $lokasi;
        }

        // Jika lokasi_id adalah ID cabang, ekstrak ID numerik
        if (strpos($lokasiId, 'cabang_') === 0) {
            $cabangId = substr($lokasiId, 7); // Menghapus 'cabang_' prefix

            $lokasi = Iduka::find($cabangId);

            if (!$lokasi) {
                throw new \Exception("Lokasi cabang dengan ID {$cabangId} tidak ditemukan.");
            }

            // Pastikan cabang ini terkait dengan IDUKA user
            if ($lokasi->id_pusat && $lokasi->id_pusat != $user->idukaDiterima->id) {
                throw new \Exception('Lokasi cabang ini tidak terkait dengan IDUKA Anda.');
            }

            return $lokasi;
        }

        // Jika format tidak dikenali
        throw new \Exception("Format lokasi tidak valid: {$lokasiId}");
    }

    /**
     * Determine attendance status based on current time and IDUKA settings
     * PERBAIKAN: Menggunakan toleransi 15 menit untuk keterlambatan
     */
    private function getStatusAbsensi($waktu, $jamMasuk = null)
    {
        // Default jam masuk
        $jamMasukDefault = Carbon::createFromTime(8, 0, 0); // 08:00

        // Gunakan jam masuk dari parameter jika ada
        if ($jamMasuk) {
            $jamMasuk = Carbon::createFromTimeString($jamMasuk);
        } else {
            $jamMasuk = $jamMasukDefault;
        }

        // Hitung batas toleransi 15 menit setelah jam masuk
        $batasLambat = $jamMasuk->copy()->addMinutes(0.1);

        // Logika status
        if ($waktu->lte($jamMasuk)) {
            return 'tepat_waktu'; // Sebelum atau tepat pada jam masuk
        } elseif ($waktu->lte($batasLambat)) {
            return 'tepat_waktu'; // Terlambat tapi masih dalam toleransi 15 menit -> dianggap tepat waktu
        } else {
            return 'terlambat'; // Terlambat lebih dari 15 menit
        }
    }
}
