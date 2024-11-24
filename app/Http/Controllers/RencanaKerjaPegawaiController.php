<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\RencanaKerjaPegawaiRequest;
use App\Http\Services\RencanaKerjaPegawaiService;

class RencanaKerjaPegawaiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $rencanaKerjaPegawaiService;

    public function __construct(RencanaKerjaPegawaiService $rencanaKerjaPegawaiService)
    {
        $this->rencanaKerjaPegawaiService = $rencanaKerjaPegawaiService;
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data dari form
        $validated = $request->validate([
            'rencana_atasan_id' => 'required|exists:rencana_hasil_kinerja,id', // Validasi rencana atasan
            'rencana' => 'required|string|max:255', // Validasi rencana hasil kerja
        ]);

        try {
            // Simpan data menggunakan service
            $this->rencanaKerjaPegawaiService->create($validated);

            // Redirect ke halaman index atau halaman sukses lainnya
            return back()->with('status', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            return back()->withErrors(['status' => $e->getMessage()]);
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}