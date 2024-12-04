<?php

namespace App\Http\Services;

use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpAtasanService
{
    public function store(array $data, $user): SkpAtasan
    {
        return SkpAtasan::create([
            'user_id' => $user->id,
            'unit_kerja' => $user->unit_kerja,
            'tahun' => $data['year'],
            'module' => $data['module'],
            'tanggal_skp' => now(),
            'tanggal_akhir' => now()->endOfYear(),
        ]);
    }

    public function selectFirstById($column, $value)
    {
        return SkpAtasan::where($column, $value)->firstOrFail();
    }

    public function delete($uuid)
    {
        $skp = SkpAtasan::where('uuid', $uuid)->firstOrFail();

        return $skp->delete();
    }

    // Tambahkan fungsi untuk mendapatkan data detail
    public function getSkpDetail(string $uuid)
    {
        try {
            $user = Auth::user(); // Mendapatkan user yang sedang login

            // Mengambil detail SKP beserta relasi yang diperlukan
            $skpDetail = SkpAtasan::where('uuid', $uuid)
                ->where('user_id', $user->id) // Filter berdasarkan user ID
                ->with([
                    'rencanaHasilKinerja', // Relasi rencana atasan

                ])
                ->firstOrFail(); // Jika data tidak ditemukan, akan memunculkan ModelNotFoundException
            return $skpDetail;
        } catch (\Exception $e) {
            // Logging error jika terjadi kesalahan
            Log::error('Gagal mendapatkan detail SKP', [
                'uuid' => $uuid,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            // Lemparkan exception agar dapat ditangani oleh controller
            throw new \RuntimeException('Data SKP tidak ditemukan. Pastikan relasi dan data sudah sesuai.');
        }
    }
}