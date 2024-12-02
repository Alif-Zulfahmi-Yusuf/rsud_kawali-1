<?php

namespace App\Http\Services;

use Exception;
use App\Models\Skp;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\IndikatorKinerja; // Model yang digunakan untuk indikator kinerja

class IndikatorService
{
    /**
     * Menyimpan data Indikator Kinerja.
     *
     * @param array $data
     * @return IndikatorKinerja
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            $user = Auth::user();
            // Dapatkan `skp_id` secara otomatis untuk pengguna
            $skp = Skp::where('user_id', $user->id)->first();

            if (!$skp) {
                throw new Exception('Tidak ada SKP untuk pengguna ini.');
            }

            // Pastikan rencana_kerja_pegawai_id dan rencana_kerja_atasan_id tidak null jika memang harus ada
            $rencanaKerjaAtasanId = $data['rencana_kerja_atasan_id'] ?? null;

            // Simpan data indikator kinerja ke database
            $indikator = IndikatorKinerja::create([
                'rencana_kerja_atasan_id' => $rencanaKerjaAtasanId,   // Biarkan null jika tidak ada
                'user_id' => $user->id,
                'skp_id' => $skp->id, // Otomatis diisi berdasarkan SKP aktif
                'aspek' => $data['aspek'],
                'indikator_kinerja' => $data['indikator_kinerja'],
                'tipe_target' => $data['tipe_target'],
                'target_minimum' => $data['target_minimum'],
                'target_maksimum' => $data['target_maksimum'],
                'satuan' => $data['satuan'],
                'report' => $data['report'],
            ]);

            return $indikator;
        } catch (\Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Indikator Kinerja', [
                'error' => $e->getMessage(),
                'data' => $data,
            ]);

            // Melemparkan error untuk ditangani oleh controller
            throw new Exception('Gagal menyimpan Indikator Kinerja: ' . $e->getMessage());
        }
    }
}