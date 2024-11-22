<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Services\RencanaKerjaAtasanService;

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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

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
            $rencanaHasilKerja = $this->rencanaKerjaatasanService->store($validated);

            // Redirect ke halaman index atau halaman sukses lainnya
            return back()->with('status', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            return back()->withErrors(['error' => $e->getMessage()]);
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