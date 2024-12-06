<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\RencanaHasilKinerja;
use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpService
{
    public function store(array $data, $user): Skp
    {
        // Pastikan user memiliki atasan_id
        if (!$user->atasan_id) {
            throw new \Exception('Atasan belum ditentukan untuk pengguna ini.');
        }

        Log::info('Mencari SKP Atasan', [
            'atasan_id' => $user->atasan_id,
            'tahun' => $data['year']
        ]);

        // Ambil SKP Atasan berdasarkan atasan_id dan tahun
        $skpAtasan = SkpAtasan::where('user_id', $user->atasan_id)
            ->where('tahun', $data['year'])
            ->first();

        if (!$skpAtasan) {
            Log::warning('SKP Atasan tidak ditemukan', [
                'atasan_id' => $user->atasan_id,
                'year' => $data['year']
            ]);
            throw new \Exception('SKP atasan untuk tahun ' . $data['year'] . ' tidak ditemukan.');
        }

        // Buat SKP Pegawai dengan menghubungkan ke SKP Atasan
        return Skp::create([
            'user_id' => $user->id,
            'atasan_id' => $user->atasan_id,
            'unit_kerja' => $user->unit_kerja,
            'skp_atasan_id' => $skpAtasan->id, // Menghubungkan SKP Pegawai dengan SKP Atasan
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

            // Mengambil detail SKP beserta relasi-relasinya
            $skpDetail = Skp::where('uuid', $uuid)
                ->where('user_id', $user->id) // Filter berdasarkan user ID
                ->with([
                    'rencanaHasilKinerja.rencanaPegawai', // Menambahkan relasi rencanaPegawai
                    'rencanaHasilKinerja.rencanaPegawai.rencanaAtasan', // Relasi rencana atasan
                    'rencanaHasilKinerja.rencanaPegawai.rencanaAtasan.indikatorKinerja', // Relasi indikator kinerja
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