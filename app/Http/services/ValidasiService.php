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
                    'skpAtasan' => function ($query) use ($user) {
                        // Pastikan hanya data yang terkait dengan atasan login
                        $query->where('user_id', $user->id)
                            ->with([
                                'rencanaHasilKinerja' => function ($queryHasil) use ($user) {
                                    $queryHasil->with([
                                        'rencanaPegawai' => function ($queryPegawai) use ($user) {
                                            // Hanya tampilkan data pegawai yang relevan
                                            $queryPegawai->with('indikatorKinerja');
                                        }
                                    ]);
                                }
                            ]);
                    },
                    'rencanaPegawai' => function ($query) use ($user) {
                        // Filter data rencana pegawai yang relevan
                        $query->with('indikatorKinerja');
                    }
                ])
                ->firstOrFail(); // Ambil data pertama atau gagal jika tidak ditemukan

            return $skpDetail;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail SKP untuk atasan', [
                'uuid' => $uuid,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Data SKP tidak ditemukan. Pastikan relasi dan data sudah sesuai.');
        }
    }
}