<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\RealisasiRencana;

class RealisasiController extends Controller
{

    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,jpg,png|max:2048',
        ]);

        $filePath = $request->file('file')->store('realisasi_files', 'public');

        RealisasiRencana::create([
            'evaluasi_pegawai_id' => $request->evaluasi_pegawai_id,
            'rencana_pegawai_id' => $request->rencana_pegawai_id,
            'file' => $filePath,
        ]);

        return response()->json([
            'message' => 'File berhasil diupload!',
        ]);
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
