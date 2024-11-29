<?php

namespace App\Http\Controllers;

use App\Models\Atasan;
use App\Models\Pangkat;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit()
    {
        // Muat relasi atasan untuk pengguna yang sedang login
        $user = auth()->user()->load('atasan');

        // Ambil semua pangkat dan atasan untuk keperluan form
        $pangkats = Pangkat::all();
        $atasans = Atasan::all();

        return view('profile.edit', compact('user', 'pangkats', 'atasans'));
    }


    /**
     * Update the user's profile information.
     */


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Simpan data lainnya
        $user->fill($request->only([
            'name',
            'email',
            'nip',
            'pangkat_id',
            'unit_kerja',
            'tmt_jabatan',
            'atasan_id'
        ]));

        // Update gambar jika ada
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('profile_images', 'public');

            // Hapus gambar lama jika ada
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            $user->image = $imagePath;
        }

        if ($user->isDirty('email')) {
            $user->email_verified_at = null; // Reset verifikasi jika email berubah
        }

        $user->save();

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }




    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}