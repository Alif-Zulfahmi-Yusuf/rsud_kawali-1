<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\EvaluasiPegawai;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;

class EvaluasiAtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        Log::info('Memulai proses index validasi harian.', [
            'user_id' => Auth::id(),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        DB::enableQueryLog();

        $bulan = $request->bulan;
        $tahun = $request->tahun;

        $EvaluasiAtasan = EvaluasiPegawai::with(['user', 'user.pangkat'])
            ->where('status', 'review')
            ->where('is_submit', 1)
            ->whereHas('rencanaPegawai', function ($query) use ($bulan, $tahun) {
                $query->whereHas('rencanaAtasan', function ($queryAtasan) {
                    $queryAtasan->where('user_id', Auth::id());
                });

                if ($bulan) {
                    $query->whereMonth('bulan', $bulan);
                }

                if ($tahun) {
                    $query->whereYear('bulan', $tahun);
                }
            })
            ->get()
            ->unique('user_id'); // Pastikan hanya satu data per user

        Log::info('Query SQL:', DB::getQueryLog());
        DB::disableQueryLog();

        if ($EvaluasiAtasan->isEmpty()) {
            Log::warning('Tidak ada data ditemukan untuk validasi harian.', [
                'user_id' => Auth::id(),
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }

        return view('backend.evaluasi-atasan.index', compact('EvaluasiAtasan'));
    }

    public function getByUser($userId)
    {
        try {
            $evaluasiAtasan = EvaluasiPegawai::where('user_id', $userId)
                ->where('status', 'review')
                ->where('is_submit', 1)
                ->get();

            if ($evaluasiAtasan->isEmpty()) {
                return redirect()->back()->with('warning', 'Tidak ada data ditemukan');
            }

            return response()->json($evaluasiAtasan);
        } catch (\Exception $e) {
            Log::error('Gagal mengambil data evaluasi kinerja untuk user_id ' . $userId, ['error' => $e->getMessage()]);
            return redirect()->back()->with('error', 'Terjadi kesalahan');
        }
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
        //

        return view('backend.evaluasi-atasan.edit', compact('uuid'));
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