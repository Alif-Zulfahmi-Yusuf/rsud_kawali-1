<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\SettingsRequest;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Redirect;

class SettingController extends Controller
{
    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        // Ambil data setting pertama
        $settings = Setting::first();

        return view('backend.settings.edit', compact('settings'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SettingsRequest $request): RedirectResponse
    {
        try {
            $setting = Setting::firstOrFail(); // Ambil data pertama, atau lempar error jika tidak ada

            // Menangani upload gambar
            if ($request->hasFile('image')) {
                $image = $request->file('image');
                $imagePath = $image->store('images', 'public');

                // Hapus gambar lama jika ada
                if ($setting->image && Storage::disk('public')->exists($setting->image)) {
                    Storage::disk('public')->delete($setting->image);
                }

                $setting->image = $imagePath; // Perbarui path gambar baru
            }

            // Perbarui data lainnya
            $setting->fill($request->only([
                'name',
                'address',
                'description',
            ]));

            $setting->save(); // Simpan perubahan

            return Redirect::route('settings.edit')->with('success', 'Setting updated successfully.');
        } catch (\Exception $e) {
            Log::error('Failed to update setting: ', [
                'error' => $e->getMessage(),
            ]);

            return Redirect::route('settings.edit')->with('error', 'Failed to update setting. Please try again.');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}