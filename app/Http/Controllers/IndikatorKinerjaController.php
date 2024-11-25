<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
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
    public function store(IndikatorKinerjaRequest $request)
    {
        // Validasi sudah dilakukan di IndikatorKinerjaRequest

        try {
            // Menyimpan data menggunakan service
            $indikator = $this->indikatorService->create($request->validated());

            // Redirect atau kembali dengan pesan sukses
            return back()->with('success', 'Rencana Hasil Kerja berhasil disimpan.');
        } catch (\Exception $e) {
            // Tangani error jika terjadi kesalahan
            return back()->with('error', $e->getMessage()); // Hanya kirim string error
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