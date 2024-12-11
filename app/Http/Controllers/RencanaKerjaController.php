<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use App\Http\Services\RencanaKerjaAtasanService;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class RencanaKerjaController extends Controller
{
    protected $rencanaKerjaatasanService;

    public function __construct(RencanaKerjaAtasanService $rencanaKerjaatasanService)
    {
        $this->rencanaKerjaatasanService = $rencanaKerjaatasanService;
    }
    /**
     * Display a listing of the resource.
     */
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data dari form
        $validated = $request->validate([
            'rencana_hasil_kerja' => 'required|string|max:255',
        ]);

        try {
            // Simpan data menggunakan service
            $this->rencanaKerjaatasanService->store($validated);

            // Redirect ke halaman index atau halaman sukses lainnya
            return back()->with('success', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            return back()->with('error', $e->getMessage()); // Hanya kirim string error
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {


        $data = $request->validate([
            'rencana' => 'required|string|max:255',
        ]);

        try {

            $this->rencanaKerjaatasanService->update($uuid, $data);

            // Redirect ke halaman index atau halaman sukses lainnya
            return back()->with('success', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (ModelNotFoundException $e) {

            return back()->with('error', 'Rencana Hasil Kerja tidak ditemukan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            return back()->with('error', $e->getMessage()); // Hanya kirim string error
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->rencanaKerjaatasanService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}