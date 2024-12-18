<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ValidasiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Log awal untuk debug
        Log::info('Mengambil data Kegiatan Harian dari table kegiatan_harian untuk validasi.', [
            'user_id' => Auth::id(),
            'table' => 'kegiatan_harian',
        ]);

        // Ambil data Kegiatan Harian dengan filter status 'pending', is_draft = 1,
        // dan relasi ke skpAtasan yang user_id-nya sama dengan user yang login
        $kegiatanHarian = KegiatanHarian::with(['user', 'skpAtasan'])
            ->where('status', 'pending') // Filter untuk status pending
            ->where('is_draft', 1)       // Hanya yang berupa draft
            ->whereHas('skpAtasan', function ($query) {
                $query->where('user_id', Auth::id()); // Hanya SKP Atasan milik user login
            })
            ->get();

        // Log setelah data diambil untuk memastikan jumlah data
        Log::info('Data Kegiatan Harian berhasil diambil.', [
            'jumlah_data' => $kegiatanHarian->count(),
            'table' => 'kegiatan_harian',
            'user_id' => Auth::id(),
        ]);

        // Return ke view validasi harian
        return view('backend.validasi_harian.index', compact('kegiatanHarian'));
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