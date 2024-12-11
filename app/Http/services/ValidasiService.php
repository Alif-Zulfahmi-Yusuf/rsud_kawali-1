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
            $user = Auth::user(); // Mendapatkan user yang sedang login

            // Mendapatkan detail SKP beserta relasi yang terkait
            $skpDetail = Skp::where('uuid', $uuid)
                ->where('atasan_id', $user->atasan_id) // Filter berdasarkan atasan_id
                ->with([

                    'skpAtasan.rencanaHasilKinerja.rencanaPegawai.indikatorKinerja',
                    'skpAtasan.rencanaHasilKinerja',
                    'rencanaPegawai'
                ])
                ->firstOrFail(); // Ambil data pertama atau gagal jika tidak ditemukan

            // Jika diperlukan, Anda dapat menambahkan logika lain untuk memanipulasi data indikator atau relasi lainnya

            return $skpDetail;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail SKP', [
                'uuid' => $uuid,
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Data SKP tidak ditemukan. Pastikan relasi dan data sudah sesuai.');
        }
    }
}