<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class SkpService
{
    public function store(array $data, $user): Skp
    {
        // Pastikan user memiliki atasan_id yang valid
        if (!$user->atasan_id) {
            throw new \Exception('Atasan belum ditentukan untuk pengguna ini.');
        }

        Log::info('Mencari SKP Atasan', [
            'atasan_id' => $user->atasan_id,
            'tahun' => $data['year']
        ]);

        // Ambil data atasan berdasarkan atasan_id yang ada di users
        $atasan = Atasan::where('user_id', $user->atasan_id)->first();

        // Jika tidak ada data atasan, maka tampilkan pesan error
        if (!$atasan) {
            Log::warning('Atasan tidak ditemukan', [
                'atasan_id' => $user->atasan_id
            ]);
            throw new \Exception('Atasan dengan ID ' . $user->atasan_id . ' tidak ditemukan.');
        }

        // Cari SKP Atasan berdasarkan ID atasan dan tahun
        $skpAtasan = SkpAtasan::where('user_id', $atasan->user_id)
            ->where('tahun', $data['year'])
            ->first();

        // Log ID SKP Atasan yang ditemukan
        Log::info('ID SKP Atasan', [
            'skp_atasan_id' => $skpAtasan ? $skpAtasan->id : null // Pastikan id-nya ada atau null
        ]);

        // Jika SKP Atasan tidak ditemukan, tampilkan pesan error
        if (!$skpAtasan) {
            Log::warning('SKP Atasan tidak ditemukan', [
                'atasan_id' => $atasan->user_id,
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

            // Mendapatkan detail SKP beserta relasi yang terkait
            $skpDetail = Skp::where('uuid', $uuid)
                ->where('user_id', $user->id) // Filter berdasarkan user yang sedang login
                ->with([
                    // Relasi Rencana Hasil Kerja
                    'rencanaHasilKinerja' => function ($query) {
                        $query->orderBy('created_at', 'desc'); // Urutkan berdasarkan waktu pembuatan
                    },
                    // Relasi Rencana Pegawai
                    'rencanaHasilKinerja.rencanaPegawai' => function ($query) use ($user) {
                        $query->where('user_id', $user->id) // Filter rencana pegawai berdasarkan user
                            ->orderBy('created_at', 'desc');
                    },
                    // Relasi Indikator Kinerja melalui Rencana Pegawai
                    'rencanaHasilKinerja.rencanaPegawai.indikatorKinerja' => function ($query) use ($user) {
                        $query->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc'); // Urutkan indikator berdasarkan waktu pembuatan
                    },
                    // Relasi SKP Atasan
                    'skpAtasan' => function ($query) use ($user) {
                        $query->where('user_id', $user->atasan_id) // Filter berdasarkan atasan
                            ->orderBy('created_at', 'desc');
                    },
                    // Rencana Hasil Kerja milik Atasan
                    'skpAtasan.rencanaHasilKinerja',
                    // Rencana Pegawai milik Atasan
                    'skpAtasan.rencanaHasilKinerja.rencanaPegawai',
                    // Indikator Kinerja melalui Rencana Pegawai Atasan
                    'skpAtasan.rencanaHasilKinerja.rencanaPegawai.indikatorKinerja',
                ])
                ->firstOrFail(); // Ambil data pertama atau gagal jika tidak ditemukan

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