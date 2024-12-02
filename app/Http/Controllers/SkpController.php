<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use Illuminate\Http\Request;
use App\Http\Requests\SkpRequest;
use App\Http\Services\SkpService;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $skpService;

    public function __construct(SkpService $skpService)
    {
        $this->skpService = $skpService;
    }
    public function index()
    {
        // Ambil data SKP berdasarkan ID pengguna yang sedang login
        $skps = Skp::with(['user.atasan']) // Relasi dengan user dan atasan
            ->where('user_id', Auth::id()) // Filter berdasarkan pengguna yang login
            ->get();

        return view('backend.skp.index', compact('skps'));
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
    public function store(SkpRequest $request)
    {
        try {
            $this->skpService->store($request->validated(), Auth::user());
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
    public function show(string $uuid)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid)
    {
        try {
            // Mendapatkan detail SKP menggunakan service
            $skpDetail = $this->skpService->getSkpDetail($uuid);

            // Menampilkan view edit dengan data SKP
            return view('backend.skp.edit', compact('skpDetail'));
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
        $result = $this->skpService->delete($uuid);

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