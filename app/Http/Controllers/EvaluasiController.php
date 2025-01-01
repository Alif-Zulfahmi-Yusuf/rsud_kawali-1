<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use App\Models\EvaluasiPegawai;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Http\Services\EvaluasiService;

class EvaluasiController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $evaluasiService;

    public function __construct(EvaluasiService $evaluasiService)
    {

        $this->evaluasiService = $evaluasiService;
    }

    public function index()
    {
        // Ambil user yang sedang login
        $user = Auth::user();

        // Ambil data evaluasi pegawai berdasarkan user ID
        $evaluasiPegawai = EvaluasiPegawai::where('user_id', $user->id)
            ->where('status', '!=', 'nonaktif')
            ->with(['skp']) // Pastikan relasi dengan tabel SKP tersedia di model EvaluasiPegawai
            ->get();

        // Kirim data ke view
        return view('backend.evaluasi-pegawai.index', compact('evaluasiPegawai'));
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validasi data input
        $request->validate([
            'bulan' => 'required|date_format:Y-m', // Validasi format bulan
        ]);

        // Tambahkan hari default agar format sesuai dengan tipe DATE di MySQL
        $bulanDenganTanggal = $request->bulan . '-01';
        try {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Ambil SKP terkait (jika ada logika spesifik untuk mencari SKP, tambahkan di sini)
            $skp = Skp::where('user_id', $user->id)->latest()->first();

            if (!$skp) {
                return back()->with('error', 'SKP tidak ditemukan untuk user ini.');
            }

            $bulan = date('Y-m', strtotime($bulanDenganTanggal));

            $evaluasi = EvaluasiPegawai::where('user_id', $user->id)
                ->where('bulan', 'LIKE', "$bulan%")
                ->first();

            if (!$evaluasi) {
                // Simpan data evaluasi
                EvaluasiPegawai::create([
                    'user_id' => $user->id,
                    'skp_id' => $skp->id,
                    'bulan' => $bulanDenganTanggal,
                ]);
            } else {
                $kegiatanHarian = KegiatanHarian::where('id', $evaluasi->kegiatan_harian_id)->first();

                $evaluasi->update([
                    'skp_id' => $skp->id,
                    'rencana_pegawai_id' => $kegiatanHarian->rencana_pegawai_id,
                    'status' => 'review',

                ]);
            }


            return back()->with('success', 'Bulan evaluasi berhasil ditambahkan.');
        } catch (\Exception $e) {
            // Tangani error dan log
            Log::error('Gagal menyimpan bulan evaluasi', ['error' => $e->getMessage()]);
            return back()->with('error', 'Terjadi kesalahan saat menyimpan data.');
        }
    }


    /**
     * Display the specified resource.
     */

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, EvaluasiService $evaluasiPegawaiService)
    {
        try {
            // Ambil data evaluasi pegawai
            $data = $evaluasiPegawaiService->getEvaluasiPegawai($uuid);

            // Kirim data ke view
            return view('backend.evaluasi-pegawai.edit', compact('data'));
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
    public function destroy(string $uuid)
    {
        $result = $this->evaluasiService->delete($uuid);

        if ($result) {
            return response()->json(['message' => 'Item successfully deleted.']);
        }

        return response()->json(['message' => 'Failed to delete the item.'], 500);
    }
}
