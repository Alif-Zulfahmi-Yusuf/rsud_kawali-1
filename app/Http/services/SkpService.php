<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\RencanaHasilKinerja;
use Illuminate\Support\Facades\Log;

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
            return Skp::where('uuid', $uuid)
                ->with([
                    'rencanaAtasan.rencanaPegawai.indikatorKinerja', // Mengambil rencana pegawai dan indikator kinerja
                ])
                ->firstOrFail();
        } catch (\Exception $e) {
            Log::error('Gagal mendapatkan detail SKP', ['uuid' => $uuid, 'error' => $e->getMessage()]);
            throw new \RuntimeException('Data SKP tidak ditemukan.');
        }
    }
}