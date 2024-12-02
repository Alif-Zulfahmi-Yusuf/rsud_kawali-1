<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpService
{
    public function store(array $data, $user): Skp
    {
        return Skp::create([
            'user_id' => $user->id,
            'atasan_id' => $user->atasan_id,
            'unit_kerja' => $user->unit_kerja,
            'tahun' => $data['year'],
            'module' => $data['module'],
            'tanggal_skp' => now(),
            'tanggal_akhir' => now()->endOfYear(),
            'status' => 'pending',
        ]);
    }

    public function selectFirstById($column, $value)
    {
        return Skp::where($column, $value)->firstOrFail();
    }

    public function delete($uuid)
    {
        $skp = Skp::where('uuid', $uuid)->firstOrFail();

        return $skp->delete();
    }

    // Tambahkan fungsi untuk mendapatkan data detail
    public function getSkpDetail(string $uuid)
    {
        try {
            $user = Auth::user(); // Mendapatkan user yang sedang login

            // Mengambil detail SKP beserta relasi yang diperlukan
            $skpDetail = Skp::where('uuid', $uuid)
                ->where('user_id', $user->id) // Filter berdasarkan user ID
                ->with([
                    'rencanaHasilKinerja', // Relasi rencana atasan
                    'rencanaHasilKinerja.indikatorKinerja', // Relasi indikator kinerja
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