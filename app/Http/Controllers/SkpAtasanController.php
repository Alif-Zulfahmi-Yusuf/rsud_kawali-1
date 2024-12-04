<?php

namespace App\Http\Controllers;

use App\Models\SkpAtasan;
use Illuminate\Http\Request;
use App\Http\Requests\SkpRequest;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\SkpAtasanService;

class SkpAtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $skpAtasanService;

    public function __construct(SkpAtasanService $skpAtasanService)
    {
        $this->skpAtasanService = $skpAtasanService;
    }
    public function index()
    {
        // Ambil data SKP berdasarkan ID pengguna yang sedang login
        $skps = SkpAtasan::with(['user']) // Relasi dengan user dan atasan
            ->where('user_id', Auth::id()) // Filter berdasarkan pengguna yang login
            ->get();

        return view('backend.skp_atasan.index', compact('skps'));
    }
    /**
     * Show the form for creating a new resource.
     */

    /**
     * Store a newly created resource in storage.
     */
    public function store(SkpRequest $request)
    {
        try {
            $this->skpAtasanService->store($request->validated(), Auth::user());
            return back()->with('success', 'Data SKP berhasil disimpan.');
        } catch (\Exception $e) {
            Log::error('Gagal menyimpan data SKP', [
                'error' => $e->getMessage(),
            ]);
            return back()->with('error', 'Data SKP gagal disimpan. Silakan coba lagi.');
        }
    }

    /**
     * Display the specified resource.
     */


    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $uuid)
    {
        try {
            // Mendapatkan detail SKP menggunakan service
            $skpDetail = $this->skpAtasanService->getSkpDetail($uuid);

            // Menampilkan view edit dengan data SKP
            return view('backend.skp_atasan.edit', compact('skpDetail'));
        } catch (\RuntimeException $e) {
            // Tangani jika data tidak ditemukan
            abort(404, $e->getMessage());
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $uuid)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $uuid)
    {
        $result = $this->skpAtasanService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }

    public function getData($uuid)
    {
        // Ambil data utama dari RencanaHasilKinerja berdasarkan UUID
        $rencana = RencanaHasilKinerja::where('uuid', $uuid)
            ->with(['rencanaPegawai.indikatorKinerja'])
            ->first();

        if (!$rencana) {
            return response()->json(['error' => 'Data tidak ditemukan'], 404);
        }

        // Proses data menjadi format tabel
        $data = [];
        foreach ($rencana->rencanaPegawai as $pegawai) {
            foreach ($pegawai->indikatorKinerja as $indikator) {
                $data[] = [
                    'nama_pegawai' => $pegawai->user->name ?? 'N/A',
                    'rencana' => $pegawai->rencana,
                    'indikator' => $indikator->indikator_kinerja,
                    'target_min' => $indikator->target_minimum,
                    'target_max' => $indikator->target_maksimum,
                    'satuan' => $indikator->satuan,
                ];
            }
        }

        return response()->json(['data' => $data]);
    }
}