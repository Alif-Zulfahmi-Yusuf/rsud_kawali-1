<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Ekspetasi;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
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
            ->whereIn('status', ['review', 'revisi'])
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
        try {
            // Validasi request
            $request->validate([
                'tanggal_terbit' => 'nullable|date',
                'kuantitas_output' => 'array',
                'laporan' => 'array',
                'kualitas' => 'array',
                'nilai' => 'array',
                'status' => 'required|in:review,selesai,revisi,nonaktif', // Pastikan nilai sesuai ENUM
                'umpan_balik' => 'array',
                'umpan_balik_berkelanjutan' => 'array',
                'realisasi' => 'array',
                'jumlah_periode' => 'nullable|integer',
                'rating' => 'nullable|string',
                'permasalahan' => 'nullable|string',
            ]);

            $evaluasi = EvaluasiPegawai::where('uuid', $id)->firstOrFail();

            // Ambil data bulan dan tahun
            $bulan = \Carbon\Carbon::parse($evaluasi->bulan)->format('m');
            $tahun = \Carbon\Carbon::parse($evaluasi->bulan)->format('Y');
            $kegiatan = KegiatanHarian::where('user_id', $evaluasi->user_id)
                ->whereMonth('tanggal', $bulan) // Filter bulan
                ->whereYear('tanggal', $tahun) // Filter tahun
                ->get();

            $kuantitas = [];
            $laporan = [];
            $kualitas = [];
            $nilai = [];
            $umpanBalik = [];
            $umpanBalikBerkelanjutan = [];
            $realisasi = [];

            // Loop untuk mengisi data kegiatan
            foreach ($kegiatan as $item) {
                $kuantitas[] = $request->kuantitas_output[$item->rencana_pegawai_id] ?? null;
                $laporan[] = $request->laporan[$item->rencana_pegawai_id] ?? null;
                $kualitas[] = $request->kualitas[$item->rencana_pegawai_id] ?? null;
            }

            // Mengisi data umpan balik secara terpisah
            foreach ($request->umpan_balik as $index => $value) {
                $umpanBalik[] = $value ?? null;
            }

            // Mengisi data realisasi dan umpan balik secara terpisah
            foreach ($request->realisasi as $index => $value) {
                $realisasi[] = $value ?? null;
            }


            foreach ($request->umpan_balik_berkelanjutan as $index => $value) {
                $umpanBalikBerkelanjutan[] = $value ?? null;
            }

            foreach ($request->nilai as $index => $value) {
                $nilai[] = $value ?? null;
            }

            // Update data evaluasi
            $evaluasi->update([
                'tanggal_terbit' => $request->tanggal_terbit,
                'kuantitas_output' => $kuantitas,
                'permasalahan' => $request->permasalahan,
                'jumlah_periode' => $request->jumlah_periode,
                'rating' => $request->rating,
                'nilai' => $nilai,
                'laporan' => $laporan,
                'kualitas' => $kualitas,
                'status' => $request->status, // Update status dari request
                'umpan_balik' => $umpanBalik,
                'umpan_balik_berkelanjutan' => $umpanBalikBerkelanjutan,
                'realisasi' => $realisasi,
                'is_submit' => $request->action === 'submit' ? true : $evaluasi->is_submit, // Update is_submit jika tombol "Ajukan" ditekan
            ]);

            return back()->with(
                'success',
                'Evaluasi berhasil disimpan' . ($request->action === 'submit' ? ' dan di Review.' : '.')
            );
        } catch (\Exception $e) {
            Log::error('Gagal mengupdate data evaluasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat mengupdate data.');
        }
    }



    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}