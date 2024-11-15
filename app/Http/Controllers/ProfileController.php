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
        $user = auth()->user();
        $pangkats = Pangkat::all(); // Pastikan model Pangkat ada dan terhubung dengan tabel pangkats
        $atasans = Atasan::all(); // Ambil semua data atasan

        return view('profile.edit', compact('user', 'pangkats', 'atasans'));
    }


    /**
     * Update the user's profile information.
     */


    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = $request->user();

        // Update profile image if provided
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            $imagePath = $image->store('profile_images', 'public');

            // Delete old image if exists
            if ($user->image) {
                Storage::disk('public')->delete($user->image);
            }

            // Set the new image path
            $user->image = $imagePath;
        }

        // Update the rest of the user's profile
        $user->fill([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'nip' => $request->input('nip'),
            'pangkat_id' => $request->input('pangkat_id'),
            'unit_kerja' => $request->input('unit_kerja'),
            'tmt_jabatan' => $request->input('tmt_jabatan'),
            'atasan_id' => $request->input('atasan_id'), // Update atasan_id
        ]);

        if ($user->isDirty('email')) {
            $user->email_verified_at = null; // Reset email verification when email is updated
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