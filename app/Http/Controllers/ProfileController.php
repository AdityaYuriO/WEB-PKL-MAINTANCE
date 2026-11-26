<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ProfileController extends Controller
{
    public function edit()
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    public function update(Request $request)
    {
        $isPhotoOnly = $request->boolean('photo_only')
            || ($request->expectsJson() && !$request->has('name') && !$request->has('nip'));

        $rules = [
            'profile_photo' => ($isPhotoOnly ? 'required' : 'nullable') . '|image|mimes:jpg,png,jpeg|max:2048',
        ];
        if (!$isPhotoOnly) {
            $rules['name'] = 'required|string|max:255';
            $rules['nip']  = 'required|unique:users,nip,' . Auth::id();
        }

        $request->validate($rules, [
            'profile_photo.required' => 'Silakan pilih foto profil terlebih dahulu.',
            'profile_photo.image'    => 'File yang dipilih harus berupa gambar.',
            'profile_photo.mimes'    => 'Format foto harus JPG, JPEG, atau PNG.',
            'profile_photo.max'      => 'Ukuran foto profil maksimal 2 MB.',
        ]);

        try {
            $user = Auth::user();
            if (!$isPhotoOnly) {
                $user->name = $request->name;
                $user->nip = $request->nip;
            }

            $oldPhoto = null;
            if ($request->hasFile('profile_photo')) {
                $oldPhoto = $user->profile_photo;

                // Buat folder profile_photos jika belum ada
                if (!File::exists(public_path('profile_photos'))) {
                    File::makeDirectory(public_path('profile_photos'), 0755, true);
                }

                // Ambil file
                $file = $request->file('profile_photo');
                $extension = strtolower($file->getClientOriginalExtension());

                // Buat nama file: [nama_user]_[id]_[random].[extension]
                $cleanName = Str::slug($user->name, '_') ?: 'user'; // Ubah spasi jadi underscore
                $randomString = Str::random(5); // String random 5 karakter
                $fileName = $cleanName . '_' . $user->id . '_' . $randomString . '.' . $extension;

                // Simpan foto baru
                $file->move(public_path('profile_photos'), $fileName);
                $user->profile_photo = 'profile_photos/' . $fileName;
            }

            $user->save();

            if ($oldPhoto && $oldPhoto !== $user->profile_photo && File::exists(public_path($oldPhoto))) {
                File::delete(public_path($oldPhoto));
            }

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success'           => true,
                    'message'           => 'Foto profil berhasil diperbarui.',
                    'profile_photo_url' => $user->profile_photo ? asset($user->profile_photo) : asset('images/default.jpg'),
                ]);
            }

            return back()->with('success', 'Profil berhasil diperbarui.');
        } catch (\Throwable $e) {
            Log::error('Gagal memperbarui foto profil', [
                'user_id' => Auth::id(),
                'error'   => $e->getMessage(),
            ]);

            if ($request->expectsJson() || $request->ajax()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Terjadi kesalahan saat menyimpan foto profil. Silakan coba lagi.',
                ], 500);
            }

            return back()->with('error', 'Terjadi kesalahan saat memperbarui profil.');
        }
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => 'required',
            'new_password' => ['required', 'confirmed', Password::defaults()],
        ]);

        $user = Auth::user();

        if (!Hash::check($request->current_password, $user->password)) {
            return back()->withErrors(['current_password' => 'Password lama salah.']);
        }

        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('success', 'Password berhasil diubah.');
    }
}
