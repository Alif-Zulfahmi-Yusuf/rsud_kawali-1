<?php

namespace App\Http\Services;

use App\Models\RencanaHasilKinerjaPegawai;
use App\Models\Skp;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Exception;

class RencanaKerjaPegawaiService
{
    /**
     * Menyimpan data Rencana Hasil Kerja Pegawai.
     *
     * @param array $data
     * @return RencanaHasilKinerjaPegawai
     * @throws Exception
     */
    public function create(array $data)
    {
        try {
            // Ambil user yang sedang login
            $user = Auth::user();

            // Ambil SKP terkait dengan user
            $skp = Skp::where('user_id', $user->id)->first();

            // Pastikan SKP ditemukan
            if (!$skp) {
                throw new Exception("SKP tidak ditemukan.");
            }

            // Menyimpan data RencanaHasilKerjaPegawai
            $rencanaPegawai = RencanaHasilKinerjaPegawai::create([
                'rencana_atasan_id' => $data['rencana_atasan_id'], // Dari form input
                'rencana' => $data['rencana'], // Nama rencana dari form input
                'user_id' => $user->id,       // User yang sedang login
                'skp_id' => $skp->id,         // SKP yang terkait dengan user
            ]);

            return $rencanaPegawai;
        } catch (Exception $e) {
            // Log error jika terjadi kesalahan
            Log::error('Gagal menyimpan Rencana Hasil Kerja', [
                'error' => $e->getMessage(),
                'user_id' => Auth::id(),
                'data' => $data,
            ]);

            // Melemparkan error untuk ditangani oleh controller
            throw new Exception('Gagal menyimpan Rencana Hasil Kerja: ' . $e->getMessage());
        }
    }


    /**
     * Menghapus data berdasarkan UUID.
     *
     * @param string $uuid
     * @return bool
     * @throws Exception
     */
    public function delete($uuid)
    {
        try {
            $rencanaPegawai = RencanaHasilKinerjaPegawai::where('uuid', $uuid)->firstOrFail();

            return $rencanaPegawai->delete();
        } catch (Exception $e) {
            Log::error('Gagal menghapus Rencana Hasil Kerja Pegawai', [
                'error' => $e->getMessage(),
                'uuid' => $uuid,
            ]);

            throw new Exception('Gagal menghapus Rencana Hasil Kerja Pegawai: ' . $e->getMessage());
        }
    }

    /**
     * Mendapatkan detail data Rencana Hasil Kerja Pegawai.
     *
     * @param string $uuid
     * @return RencanaHasilKinerjaPegawai
     * @throws Exception
     */
    public function getDetail($uuid)
    {
        try {
            return RencanaHasilKinerjaPegawai::where('uuid', $uuid)->firstOrFail();
        } catch (Exception $e) {
            Log::error('Gagal mendapatkan detail Rencana Hasil Kerja Pegawai', [
                'error' => $e->getMessage(),
                'uuid' => $uuid,
            ]);

            throw new Exception('Gagal mendapatkan detail Rencana Hasil Kerja Pegawai: ' . $e->getMessage());
        }
    }
}