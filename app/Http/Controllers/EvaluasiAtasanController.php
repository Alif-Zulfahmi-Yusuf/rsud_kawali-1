<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Ekspetasi;
use Illuminate\Http\Request;
use App\Models\EvaluasiPegawai;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\EvaluasiAtasanService;

class EvaluasiAtasanController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $evaluasiService;

    public function __construct(EvaluasiAtasanService $evaluasiService)
    {

        $this->evaluasiService = $evaluasiService;
    }

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
            });

        if (!$bulan && !$tahun) {
            $EvaluasiAtasan = $EvaluasiAtasan->orWhereDoesntHave('rencanaPegawai');
        }

        $EvaluasiAtasan = $EvaluasiAtasan->get()->unique('user_id');

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
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, EvaluasiAtasanService $evaluasiAtasanService)
    {
        try {
            // Ambil data evaluasi (Realisasi Rencana Aksi dan Evaluasi Kinerja Tahunan)
            $evaluasiData = $evaluasiAtasanService->getEvaluasiData($uuid);
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();
            $dataRencanaAksi = $evaluasiData['dataRencanaAksi'];
            $groupedDataEvaluasi = $evaluasiData['groupedDataEvaluasi'];

            // Mendapatkan kategori perilaku beserta perilaku yang terkait
            $categories = CategoryPerilaku::with('perilakus')
                ->whereHas('perilakus') // Hanya kategori dengan perilaku terkait
                ->get();

            // Mendapatkan ekspektasi berdasarkan SKP yang terhubung
            $skpId = optional($dataRencanaAksi->first())->skp_id ?? null;
            $ekspektasis = $skpId
                ? Ekspetasi::where('skp_id', $skpId)->get()
                : collect(); // Return collection kosong jika skp_id tidak ditemukan

            // Kirim data ke view
            return view('backend.evaluasi-atasan.edit', compact(
                'evaluasi',
                'dataRencanaAksi',
                'groupedDataEvaluasi',
                'categories',
                'ekspektasis'
            ));
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
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