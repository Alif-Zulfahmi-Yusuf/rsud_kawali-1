<?php

namespace App\Http\Services;

use App\Models\IndikatorKinerja; // Model yang digunakan untuk indikator kinerja
use Illuminate\Support\Facades\Log;
use Exception;

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
            // Simpan data indikator kinerja ke database
            $indikator = IndikatorKinerja::create([
                'nama' => $data['nama'],
                'deskripsi' => $data['deskripsi'],
                'nilai_target' => $data['nilai_target'],
            ]);

            return $indikator;
        } catch (Exception $e) {
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