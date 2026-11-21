<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\File;
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
        $request->validate([
            'name'  => 'required|string|max:255',
            'nip' => 'required|unique:users,nip,' . Auth::id(),
            'profile_photo' => 'nullable|image|mimes:jpg,png,jpeg|max:2048',
        ]);

        $user = Auth::user();
        $user->name = $request->name;
        $user->nip = $request->nip;

        if ($request->hasFile('profile_photo')) {
            // Hapus foto lama jika ada
            if ($user->profile_photo && File::exists(public_path($user->profile_photo))) {
                File::delete(public_path($user->profile_photo));
            }

            // Buat folder profile_photos jika belum ada
            if (!File::exists(public_path('profile_photos'))) {
                File::makeDirectory(public_path('profile_photos'), 0755, true);
            }

            // Ambil file
            $file = $request->file('profile_photo');
            $extension = $file->getClientOriginalExtension();

            // Buat nama file: [nama_user]_[id]_[random].[extension]
            $cleanName = Str::slug($user->name, '_'); // Ubah spasi jadi underscore
            $randomString = Str::random(5); // String random 5 karakter
            $fileName = $cleanName . '_' . $user->id . '_' . $randomString . '.' . $extension;

            // Simpan foto baru
            $file->move(public_path('profile_photos'), $fileName);
            $user->profile_photo = 'profile_photos/' . $fileName;
        }

        $user->save();

        return back()->with('success', 'Profil berhasil diperbarui.');
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
