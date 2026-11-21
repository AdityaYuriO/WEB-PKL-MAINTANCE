<?php

namespace App\Http\Controllers;

use App\Models\Jurnal;
use App\Models\Iduka;
use App\Models\Guru;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class JournalApprovalController extends Controller
{
    /**
     * Helper method untuk mencari atau membuat data guru
     */
    private function findOrCreateGuru($user)
    {
        // Cari berdasarkan user_id (cara normal)
        $guru = Guru::where('user_id', $user->id)->first();

        if ($guru) {
            return $guru;
        }

        // Cari berdasarkan nama dan email
        $guru = Guru::where('nama', $user->name)
            ->where('email', $user->email)
            ->first();

        if ($guru) {
            // Update user_id jika ditemukan
            $guru->user_id = $user->id;
            $guru->save();
            Log::info("Updated guru user_id for guru: {$guru->nama}");
            return $guru;
        }

        // Cari berdasarkan email saja
        $guru = Guru::where('email', $user->email)->first();

        if ($guru) {
            // Update user_id dan nama jika ditemukan
            $guru->user_id = $user->id;
            $guru->nama = $user->name;
            $guru->save();
            Log::info("Updated guru user_id and nama for guru: {$guru->nama}");
            return $guru;
        }

        // Jika tidak ditemukan sama sekali, buat data guru baru
        $guru = new Guru();
        $guru->user_id = $user->id;
        $guru->nama = $user->name;
        $guru->email = $user->email;
        $guru->nip = 'AUTO-' . date('Ymd') . '-' . $user->id; // NIP otomatis
        $guru->save();

        Log::info("Created new guru for user: {$user->name}");
        return $guru;
    }

    /**
     * Tampilkan jurnal yang perlu disetujui (IDUKA atau Guru/Pembimbing/Kaprog)
     */
    public function index()
    {
        try {
            $user = auth()->user();
            Log::info('JournalApprovalController@index - User role: ' . ($user->role ?? 'guest') . ' id: ' . ($user->id ?? 'null'));

            if ($user->role === 'iduka') {
                // Ambil model Iduka
                $iduka = Iduka::where('user_id', $user->id)->first();
                if (!$iduka) {
                    Log::warning("IDUKA not found for user_id: {$user->id}");
                    return redirect()->back()->with('error', 'Data IDUKA tidak ditemukan.');
                }

                // Query menggunakan relasi langsung
                // Hanya tampilkan jurnal yang belum disetujui oleh IDUKA dan belum disetujui oleh Pembimbing
                $jurnals = Jurnal::with('user')
                    ->where('iduka_id', $iduka->id)
                    ->where('validasi_iduka', 'belum')
                    ->where('status', '!=', 'rejected')
                    ->where(function ($q) {
                        // Jika pembimbing sudah menyetujui, jurnal tidak perlu ditampilkan lagi
                        $q->where('validasi_pembimbing', '!=', 'sudah')
                            ->orWhereNull('validasi_pembimbing');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                Log::info('IDUKA journals count: ' . $jurnals->total());
                return view('iduka.konfir_jurnal.index', compact('jurnals'));
            }

            if ($user->role === 'guru' || $user->role === 'kaprog') {
                // Gunakan helper method untuk mencari/membuat data guru
                $pembimbing = $this->findOrCreateGuru($user);

                // Query menggunakan relasi langsung
                // Hanya tampilkan jurnal yang belum disetujui oleh Pembimbing/Kaprog dan belum disetujui oleh IDUKA
                $jurnals = Jurnal::with('user')
                    ->where('pembimbing_id', $pembimbing->id)
                    ->where('validasi_pembimbing', 'belum')
                    ->where('status', '!=', 'rejected')
                    ->where(function ($q) {
                        // Jika IDUKA sudah menyetujui, jurnal tidak perlu ditampilkan lagi
                        $q->where('validasi_iduka', '!=', 'sudah')
                            ->orWhereNull('validasi_iduka');
                    })
                    ->orderBy('created_at', 'desc')
                    ->paginate(10);

                Log::info(($user->role === 'guru' ? 'Guru' : 'Kaprog') . ' journals count: ' . $jurnals->total());

                // Tentukan view berdasarkan role
                $viewName = $user->role === 'guru' ? 'guru.konfir_jurnal.index' : 'kaprog.konfir_jurnal.index';
                return view($viewName, compact('jurnals'));
            }

            Log::warning('Unauthorized role: ' . $user->role);
            return redirect()->back()->with('error', 'Akses tidak diizinkan.');
        } catch (\Exception $e) {
            Log::error('Error in index: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function riwayat()
    {
        try {
            $user = auth()->user();

            if ($user->role === 'iduka') {
                $iduka = Iduka::where('user_id', $user->id)->first();
                if (!$iduka) {
                    return redirect()->back()->with('error', 'Data IDUKA tidak ditemukan.');
                }

                // PERUBAHAN: Tampilkan semua jurnal yang sudah disetujui atau ditolak
                $jurnals = Jurnal::with('user')
                    ->where('iduka_id', $iduka->id)
                    ->where(function ($q) {
                        $q->where('status', 'approved')
                            ->orWhere('status', 'rejected');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);

                return view('iduka.konfir_jurnal.riwayat', compact('jurnals'));
            }

            if ($user->role === 'guru' || $user->role === 'kaprog') {
                // Gunakan helper method untuk mencari/membuat data guru
                $pembimbing = $this->findOrCreateGuru($user);

                // PERUBAHAN: Tampilkan semua jurnal yang sudah disetujui atau ditolak
                $jurnals = Jurnal::with('user')
                    ->where('pembimbing_id', $pembimbing->id)
                    ->where(function ($q) {
                        $q->where('status', 'approved')
                            ->orWhere('status', 'rejected');
                    })
                    ->orderBy('updated_at', 'desc')
                    ->paginate(10);

                // Tentukan view berdasarkan role
                $viewName = $user->role === 'guru' ? 'guru.konfir_jurnal.riwayat' : 'kaprog.konfir_jurnal.riwayat';
                return view($viewName, compact('jurnals'));
            }

            return redirect()->back()->with('error', 'Akses tidak diizinkan.');
        } catch (\Exception $e) {
            Log::error('Error in riwayat: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    public function approve($id)
    {
        DB::beginTransaction();
        try {
            $journal = Jurnal::with('user')->findOrFail($id);
            $user = auth()->user();

            if ($user->role === 'iduka') {
                $iduka = Iduka::where('user_id', $user->id)->first();
                if (!$iduka) {
                    return redirect()->back()->with('error', 'Data IDUKA tidak ditemukan.');
                }

                if ($journal->iduka_id !== $iduka->id) {
                    Log::warning("Unauthorized approval attempt by IDUKA {$iduka->id} for journal {$id}");
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui jurnal ini.');
                }

                $journal->validasi_iduka = 'sudah';
                $journal->approved_iduka_at = now();

                // PERUBAHAN: Status langsung menjadi 'approved' jika salah satu menyetujui
                $journal->status = 'approved';

            } elseif ($user->role === 'guru' || $user->role === 'kaprog') {
                $pembimbing = $this->findOrCreateGuru($user);

                if ($journal->pembimbing_id !== $pembimbing->id) {
                    Log::warning("Unauthorized approval attempt by " . ($user->role === 'guru' ? 'Guru' : 'Kaprog') . " {$pembimbing->id} for journal {$id}");
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menyetujui jurnal ini.');
                }

                $journal->validasi_pembimbing = 'sudah';
                $journal->approved_pembimbing_at = now();

                // PERUBAHAN: Status langsung menjadi 'approved' jika salah satu menyetujui
                $journal->status = 'approved';
            } else {
                return redirect()->back()->with('error', 'Akses tidak diizinkan.');
            }

            $journal->save();
            DB::commit();

            return redirect()->route('approval.riwayat')->with('success', 'Jurnal berhasil disetujui.');
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in approve: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menyetujui: ' . $e->getMessage());
        }
    }

    /**
     * Reject jurnal
     */
    public function reject(Request $request, $id)
    {
        $request->validate([
            'rejected_reason' => 'required|string|max:500'
        ]);

        DB::beginTransaction();
        try {
            $journal = Jurnal::with('user')->findOrFail($id);
            $user = auth()->user();

            if ($user->role === 'iduka') {
                $iduka = Iduka::where('user_id', $user->id)->first();
                if (!$iduka) {
                    return redirect()->back()->with('error', 'Data IDUKA tidak ditemukan.');
                }

                if ($journal->iduka_id !== $iduka->id) {
                    Log::warning("Unauthorized rejection attempt by IDUKA {$iduka->id} for journal {$id}");
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak jurnal ini.');
                }

                $journal->update([
                    'validasi_iduka' => 'belum', // Tetap 'belum' bukan 'ditolak'
                    'status' => 'rejected',
                    'rejected_reason' => $request->rejected_reason,
                    'rejected_at' => now()
                ]);

            } elseif ($user->role === 'guru' || $user->role === 'kaprog') {
                $pembimbing = $this->findOrCreateGuru($user);

                if ($journal->pembimbing_id !== $pembimbing->id) {
                    Log::warning("Unauthorized rejection attempt by " . ($user->role === 'guru' ? 'Guru' : 'Kaprog') . " {$pembimbing->id} for journal {$id}");
                    return redirect()->back()->with('error', 'Anda tidak memiliki akses untuk menolak jurnal ini.');
                }

                $journal->update([
                    'validasi_pembimbing' => 'belum', // Tetap 'belum' bukan 'ditolak'
                    'status' => 'rejected',
                    'rejected_reason' => $request->rejected_reason,
                    'rejected_at' => now()
                ]);
            }

            DB::commit();
            Log::info("Journal {$id} rejected successfully by {$user->role}");
            return redirect()->back()->with('success', 'Jurnal berhasil ditolak.');

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('Error in reject: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat menolak: ' . $e->getMessage());
        }
    }

    /**
     * Detail jurnal untuk modal
     */
    public function showDetail($id)
    {
        try {
            Log::info('Accessing journal detail: ' . $id);

            // Pastikan nama variabel adalah $journal (bukan $jurnal)
            $journal = Jurnal::with(['user'])->findOrFail($id);
            $user = auth()->user();

            // Validasi akses
            if ($user->role === 'iduka') {
                $iduka = Iduka::where('user_id', $user->id)->first();

                if (!$iduka || $journal->iduka_id !== $iduka->id) {
                    Log::warning('Unauthorized IDUKA access attempt for journal ' . $id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk melihat jurnal ini'
                    ], 403);
                }
            } elseif ($user->role === 'guru' || $user->role === 'kaprog') {
                $pembimbing = $this->findOrCreateGuru($user);

                if (!$pembimbing || $journal->pembimbing_id !== $pembimbing->id) {
                    Log::warning('Unauthorized ' . ($user->role === 'guru' ? 'Guru' : 'Kaprog') . ' access attempt for journal ' . $id);
                    return response()->json([
                        'success' => false,
                        'message' => 'Anda tidak memiliki akses untuk melihat jurnal ini'
                    ], 403);
                }
            } else {
                Log::warning('Invalid role access attempt: ' . $user->role);
                return response()->json([
                    'success' => false,
                    'message' => 'Akses tidak diizinkan'
                ], 403);
            }

            Log::info('Rendering detail view for journal ' . $id);

            // Tentukan view berdasarkan role
            if ($user->role === 'guru') {
                $viewName = 'guru.konfir_jurnal.detail_jurnal';
            } elseif ($user->role === 'kaprog') {
                $viewName = 'kaprog.konfir_jurnal.detail_jurnal';
            } else {
                $viewName = 'iduka.konfir_jurnal.detail_jurnal';
            }

            // Pastikan view ada
            if (!view()->exists($viewName)) {
                Log::error('View not found: ' . $viewName);
                throw new \Exception('View tidak ditemukan: ' . $viewName);
            }

            // Debug: Tambahkan log untuk memastikan variabel terkirim
            Log::info('Sending journal to view: ', ['id' => $journal->id, 'tgl' => $journal->tgl]);

            // Render view dengan compact('journal')
            $html = view($viewName, compact('journal'))->render();

            return response()->json([
                'success' => true,
                'data' => $html
            ]);

        } catch (\Illuminate\Database\Eloquent\ModelNotFoundException $e) {
            Log::error('Journal not found: ' . $id);
            return response()->json([
                'success' => false,
                'message' => 'Jurnal tidak ditemukan'
            ], 404);
        } catch (\Exception $e) {
            Log::error('Error in showDetail: ' . $e->getMessage() . ' | File: ' . $e->getFile() . ' | Line: ' . $e->getLine());
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan saat memuat detail jurnal: ' . $e->getMessage()
            ], 500);
        }
    }
}
