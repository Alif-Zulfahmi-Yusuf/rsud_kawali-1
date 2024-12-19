<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class ValidasiService
{

    //

    public function selectFirstById($column, $value)
    {
        return Skp::where($column, $value)->firstOrFail();
    }

    public function getSkpDetail(string $uuid)
    {
        try {
            $user = Auth::user(); // Mendapatkan user yang sedang login (atasan)

            // Mendapatkan detail SKP beserta relasi yang terkait dengan filter berdasarkan atasan
            $skpDetail = Skp::where('uuid', $uuid)
                ->with([
                    // Relasi rencana atasan
                    'skpAtasan' => function ($query) use ($user) {
                        $query->where('user_id', $user->id) // Filter berdasarkan user atasan
                            ->with([
                                'rencanaHasilKinerja' => function ($queryHasil) {
                                    $queryHasil->with([
                                        'rencanaPegawai' => function ($queryPegawai) {
                                            $queryPegawai->with('indikatorKinerja');
                                        },
                                    ]);
                                },
                            ]);
                    },
                    // Relasi rencana pegawai
                    'rencanaPegawai' => function ($query) {
                        $query->with('indikatorKinerja'); // Pastikan indikator kinerja ikut di-load
                    },
                ])
                ->firstOrFail(); // Ambil data pertama atau gagal jika tidak ditemukan

            return $skpDetail;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail SKP untuk atasan', [
                'uuid' => $uuid,
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Data SKP tidak ditemukan. Pastikan relasi dan data sudah sesuai.');
        }
    }
}