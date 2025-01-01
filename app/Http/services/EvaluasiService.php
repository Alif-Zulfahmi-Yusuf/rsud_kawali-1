<?php

namespace App\Http\Services;

use App\Models\Skp;
use App\Models\Atasan;
use App\Models\SkpAtasan;
use App\Models\EvaluasiPegawai;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;

class EvaluasiService
{
    //

    public function getEvaluasiPegawai(string $uuid)
    {
        try {
            // Ambil evaluasi pegawai berdasarkan UUID
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->first();

            if (!$evaluasi) {
                throw new \Exception('Evaluasi tidak ditemukan.');
            }

            $bulan = \Carbon\Carbon::parse($evaluasi->bulan)->format('m'); // Ambil bulan (numerik)
            $tahun = \Carbon\Carbon::parse($evaluasi->bulan)->format('Y'); // Ambil tahun

            // Query data dengan LEFT JOIN dan filter berdasarkan bulan & tahun
            $data = DB::table('kegiatan_harians')
                ->leftJoin('evaluasi_pegawais', 'kegiatan_harians.id', '=', 'evaluasi_pegawais.kegiatan_harian_id')
                ->leftJoin('rencana_hasil_kerja_pegawai', 'evaluasi_pegawais.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->leftJoin('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoin('rencana_indikator_kinerja', 'rencana_hasil_kerja.id', '=', 'rencana_indikator_kinerja.rencana_atasan_id')
                ->select(
                    'kegiatan_harians.id as kegiatan_harian_id',
                    'kegiatan_harians.uraian as uraian_kegiatan',
                    'kegiatan_harians.tanggal as tanggal_kegiatan',
                    'kegiatan_harians.waktu_mulai',
                    'kegiatan_harians.waktu_selesai',
                    'evaluasi_pegawais.id as evaluasi_id',
                    'evaluasi_pegawais.rencana_pegawai_id',
                    'rencana_indikator_kinerja.id as indikator_id',
                    'rencana_indikator_kinerja.indikator_kinerja as nama_indikator',
                    'rencana_hasil_kerja_pegawai.rencana as nama_rencana_pegawai',
                    'rencana_hasil_kerja.rencana as nama_rencana_pimpinan'
                )
                ->whereMonth('kegiatan_harians.tanggal', '=', $bulan)
                ->whereYear('kegiatan_harians.tanggal', '=', $tahun)
                ->where('rencana_indikator_kinerja.satuan', '=', 'laporan')
                ->where('rencana_indikator_kinerja.tipe_target', '=', 'satu_nilai')
                ->where('rencana_indikator_kinerja.target_minimum', '>=', 12)
                ->get();

            return $data;
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail evaluasi', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Gagal mengambil data evaluasi. Pastikan data sudah benar.');
        }
    }



    public function delete($uuid)
    {
        $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();

        return $evaluasi->delete();
    }
}