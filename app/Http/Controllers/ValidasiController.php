<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\ValidasiService;

class ValidasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    private $validasiService;

    public function __construct(ValidasiService $validasiService)
    {
        $this->validasiService = $validasiService;
    }

    public function index()
    {
        // Ambil data SKP yang statusnya 'pending'
        $skps = Skp::with(['user', 'skpAtasan'])
            ->where('status', 'pending')
            ->whereHas('skpAtasan', function ($query) {
                $query->where('user_id', Auth::id());
            })
            ->get();

        return view('backend.validasi.index', compact('skps'));
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
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        try {
            // Mendapatkan detail SKP menggunakan service
            $skpDetail = $this->validasiService->getSkpDetail($uuid);

            // Menampilkan view edit dengan data SKP dan kategori perilaku
            return view('backend.validasi.edit', compact('skpDetail'));
        } catch (\RuntimeException $e) {
            // Log error jika data tidak ditemukan
            Log::error('Gagal menampilkan data SKP untuk edit', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            // Tangani jika data tidak ditemukan
            return redirect()->back()->with('error', $e->getMessage());
        }
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