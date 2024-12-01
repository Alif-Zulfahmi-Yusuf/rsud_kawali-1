<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use App\Http\Services\IndikatorService;
use App\Http\Requests\IndikatorKinerjaRequest;

class IndikatorKinerjaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $indikatorService;

    // Konstruktor untuk inject service
    public function __construct(IndikatorService $indikatorService)
    {
        $this->indikatorService = $indikatorService;
    }

    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function store(Request $request)
    {
        try {
            // Validasi input dari form
            $validated = $request->validate([
                'rencana_kerja_pegawai_id' => 'nullable|exists:rencana_hasil_kerja_pegawai,id',
                'rencana_kerja_atasan_id' => 'nullable|exists:rencana_hasil_kerja,id', // Validasi rencana_kerja_atasan_id
                'aspek' => 'required|string|in:kualitas,kuantitas,waktu',
                'indikator_kinerja' => 'required|string|max:255',
                'tipe_target' => 'required|string|in:satu_nilai,range_nilai,kualitatif',
                'target_minimum' => 'required|numeric|min:0',
                'target_maksimum' => 'required|numeric|min:0',
                'satuan' => 'required|string|max:50',
                'report' => 'required|string|in:bulanan,triwulan,semesteran,tahunan',
            ]);

            // Pastikan key tetap ada meskipun nullable
            $validated['rencana_kerja_pegawai_id'] = $validated['rencana_kerja_pegawai_id'] ?? null;
            $validated['rencana_kerja_atasan_id'] = $validated['rencana_kerja_atasan_id'] ?? null;

            // Simpan data menggunakan service
            $this->indikatorService->create($validated);

            return back()->with('success', 'Indikator Kinerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Indikator Kinerja', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', $e->getMessage());
        }
    }




    /**
     * Store a newly created resource in storage.
     */

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