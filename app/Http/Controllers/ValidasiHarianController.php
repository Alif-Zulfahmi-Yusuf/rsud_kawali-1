<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Models\KegiatanHarian;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class ValidasiHarianController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Logging awal untuk debug
        Log::info('Mengambil data Kegiatan Harian berdasarkan filter.', [
            'user_id' => Auth::id(),
            'bulan' => $request->bulan,
            'tahun' => $request->tahun,
        ]);

        // Aktifkan query log
        DB::enableQueryLog();

        // Filter bulan dan tahun
        $bulan = $request->bulan;
        $tahun = $request->tahun;

        // Ambil satu data per user berdasarkan filter
        $kegiatanHarianList = KegiatanHarian::with(['user', 'user.pangkat'])
            ->where('status', 'pending')
            ->where('is_draft', 1)
            ->whereHas('rencanaPegawai', function ($query) use ($bulan, $tahun) {
                $query->whereHas('rencanaAtasan', function ($queryAtasan) {
                    $queryAtasan->where('user_id', Auth::id());
                });

                // Filter berdasarkan bulan jika diberikan
                if ($bulan) {
                    $query->whereMonth('created_at', $bulan);
                }

                // Filter berdasarkan tahun jika diberikan
                if ($tahun) {
                    $query->whereYear('created_at', $tahun);
                }
            })
            ->get()
            ->unique('user_id'); // Ambil satu data per user

        // Logging query yang dieksekusi
        Log::info('Query SQL', DB::getQueryLog());
        DB::disableQueryLog();

        // Jika tidak ada data ditemukan, beri log tambahan
        if ($kegiatanHarianList->isEmpty()) {
            Log::warning('Tidak ada data Kegiatan Harian yang ditemukan.', [
                'user_id' => Auth::id(),
                'bulan' => $bulan,
                'tahun' => $tahun,
            ]);
        }

        // Return data ke view
        return view('backend.validasi_harian.index', compact('kegiatanHarianList'));
    }

    public function getByUser($userId)
    {
        try {
            Log::info('Memuat kegiatan harian untuk user_id: ' . $userId);

            if (!User::find($userId)) {
                Log::warning('User  dengan ID ' . $userId . ' tidak ditemukan.');
                return response()->json(['message' => 'User  tidak ditemukan.'], 404);
            }

            $kegiatanHarianList = KegiatanHarian::with(['rencanaPegawai'])
                ->where('user_id', $userId)
                ->where('status', 'pending')
                ->where('is_draft', 1)
                ->get()
                ->map(function ($item) {
                    // Pastikan $item->evidence hanya berisi nama file
                    $item->evidence = Storage::url($item->evidence); // Hanya gunakan nama file
                    return $item;
                });

            return response()->json($kegiatanHarianList, 200);
        } catch (\Exception $e) {
            Log::error('Error mengambil data kegiatan harian: ' . $e->getMessage(), [
                'user_id' => $userId,
            ]);

            return response()->json(['message' => 'Terjadi kesalahan saat mengambil data.'], 500);
        }
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $uuid)
    {
        try {
            // Validasi input
            $request->validate([
                'penilaian' => 'required|array', // Pastikan penilaian adalah array
            ]);

            // Temukan kegiatan harian berdasarkan UUID
            $kegiatanHarian = KegiatanHarian::where('uuid', $uuid)->firstOrFail();

            // Update kolom penilaian di tabel kegiatan_harians
            // Misalkan Anda ingin menyimpan penilaian sebagai array
            $penilaian = $request->penilaian[$uuid] ?? []; // Ambil penilaian untuk UUID ini
            $kegiatanHarian->penilaian = json_encode($penilaian); // Simpan sebagai JSON atau sesuai kebutuhan
            $kegiatanHarian->save(); // Simpan perubahan

            return redirect()->back()->with('success', 'Penilaian berhasil diperbarui.');
        } catch (\Exception $e) {
            Log::error('Error saat memperbarui penilaian: ' . $e->getMessage());
            return redirect()->back()->with('error', 'Terjadi kesalahan saat memperbarui penilaian.');
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