<?php

namespace App\Http\Controllers;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
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

            // Pastikan user memiliki atasan_id yang valid
            if (!$user->atasan_id) {
                throw new \Exception('User belum memilih atasan.');
            }

            // Ambil data atasan berdasarkan atasan_id di tabel users (mengacu ke id di tabel atasans)
            $atasan = Atasan::find($user->atasan_id);

            // Jika data atasan tidak ditemukan, tampilkan error
            if (!$atasan) {
                Log::warning('Atasan tidak ditemukan', [
                    'atasan_id' => $user->atasan_id
                ]);
                throw new \Exception('Atasan dengan ID ' . $user->atasan_id . ' tidak ditemukan.');
            }


            // Cari SKP Atasan berdasarkan ID atasan dan tahun
            $skpAtasan = SkpAtasan::where('user_id', $atasan->user_id)->first();

            // Log ID SKP Atasan yang ditemukan
            Log::info('ID SKP Atasan', [
                'skp_atasan_id' => $skpAtasan ? $skpAtasan->id : null // Pastikan id-nya ada atau null
            ]);

            // Jika SKP Atasan tidak ditemukan, tampilkan pesan error
            if (!$skpAtasan) {
                Log::warning('SKP Atasan tidak ditemukan', [
                    'atasan_id' => $atasan->user_id,
                ]);
                throw new \Exception('Atasan belum membuat Skp.');
            }

            // Ambil SKP terkait (jika ada logika spesifik untuk mencari SKP, tambahkan di sini)
            $skp = Skp::where('user_id', $user->id)
                ->where('is_active', 1)
                ->where('status', 'approve')
                ->latest()
                ->first();

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
                    'skp_atasan_id' => $skpAtasan->id,
                    'bulan' => $bulanDenganTanggal,
                ]);
            } else {

                $kegiatanHarian = KegiatanHarian::where('id', $evaluasi->kegiatan_harian_id)->first();

                $evaluasi->update([
                    'skp_id' => $skp->id,
                    'skp_atasan_id' => $skpAtasan->id,
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
     * Show the form for editing the specified resource.
     */
    public function edit($uuid, EvaluasiService $evaluasiService)
    {
        try {
            // Ambil data evaluasi (Realisasi Rencana Aksi dan Evaluasi Kinerja Tahunan)
            $evaluasiData = $evaluasiService->getEvaluasiData($uuid);

            // Kirim data ke view
            return view('backend.evaluasi-pegawai.edit', $evaluasiData);
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function uploadFile(Request $request)
    {
        $request->validate([
            'file' => 'required|file|mimes:pdf,doc,docx,xls,xlsx,png,jpg|max:2048',
            'id' => 'required|integer'
        ]);

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $file->storeAs('uploads', $fileName, 'public');

            // Simpan informasi file ke database jika diperlukan
            //File::create(['item_id' => $request->id, 'file_name' => $fileName]);

            return response()->json(['success' => true, 'file' => $fileName]);
        }

        return response()->json(['success' => false]);
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