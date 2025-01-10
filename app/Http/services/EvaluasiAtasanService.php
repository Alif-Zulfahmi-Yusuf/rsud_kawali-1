<?php

namespace App\Http\Services;

use App\Models\Ekspetasi;
use App\Models\KegiatanHarian;
use App\Models\EvaluasiPegawai;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;
use App\Models\RencanaHasilKinerjaPegawai;

class EvaluasiAtasanService
{
    //

    public function getEvaluasiData(string $uuid, int $userId)
    {
        try {
            // Ambil evaluasi berdasarkan UUID dan user_id
            $evaluasi = EvaluasiPegawai::with(['rencanaPegawai', 'skp'])
                ->where('uuid', $uuid)
                ->where('user_id', $userId)
                ->firstOrFail();

            // Pastikan data kegiatan harian terkait ada
            $kegiatanHarian = KegiatanHarian::where('user_id', $userId)
                ->where('rencana_pegawai_id', $evaluasi->rencana_pegawai_id)
                ->first();

            if (!$kegiatanHarian) {
                throw new \Exception('Kegiatan harian tidak ditemukan.');
            }

            $bulan = \Carbon\Carbon::parse($kegiatanHarian->tanggal)->format('m');
            $tahun = \Carbon\Carbon::parse($kegiatanHarian->tanggal)->format('Y');

            // Data Realisasi Rencana Aksi
            $dataRencanaAksi = DB::table('rencana_hasil_kerja_pegawai')
                ->join('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->join('rencana_indikator_kinerja', 'rencana_hasil_kerja.id', '=', 'rencana_indikator_kinerja.rencana_atasan_id')
                ->leftJoin('evaluasi_pegawais', 'evaluasi_pegawais.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->leftJoin('kegiatan_harians', 'kegiatan_harians.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->leftJoin('realisasi_rencanas', 'realisasi_rencanas.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as rencana_pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as nama_rencana_pegawai',
                    'rencana_indikator_kinerja.indikator_kinerja as nama_indikator',
                    'rencana_indikator_kinerja.satuan',
                    'rencana_indikator_kinerja.target_minimum',
                    'rencana_indikator_kinerja.target_maksimum',
                    'evaluasi_pegawais.id as evaluasi_pegawai_id',
                    'kegiatan_harians.waktu_mulai',
                    'kegiatan_harians.waktu_selesai',
                    'realisasi_rencanas.file as file_realisasi'
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $userId)
                ->where('rencana_indikator_kinerja.user_id', $userId)
                ->where('rencana_indikator_kinerja.satuan', 'laporan') // Filter satuan "laporan"
                ->where('rencana_indikator_kinerja.target_minimum', 12) // Filter target_minimum "12"
                ->whereMonth('kegiatan_harians.tanggal', $bulan) // Filter bulan
                ->whereYear('kegiatan_harians.tanggal', $tahun) // Filter tahun
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('rencana_hasil_kerja_pegawai.user_id', $userId); // Filter berdasarkan pegawai
                })
                ->get()
                ->map(function ($item) {
                    // Hitung target bulanan (dibagi 12 bulan)
                    $item->target_bulanan = $item->target_minimum / 12;
                    return $item;
                });

            $groupedDataEvaluasi = DB::table('rencana_hasil_kerja_pegawai')
                ->leftJoin('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoin('rencana_indikator_kinerja', 'rencana_hasil_kerja.id', '=', 'rencana_indikator_kinerja.rencana_atasan_id')
                ->leftJoin('kegiatan_harians', 'rencana_hasil_kerja_pegawai.id', '=', 'kegiatan_harians.rencana_pegawai_id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as rencana_pegawai',
                    'rencana_hasil_kerja.rencana as rencana_pimpinan',
                    'rencana_indikator_kinerja.id as indikator_id',
                    'rencana_indikator_kinerja.indikator_kinerja as nama_indikator',
                    'rencana_indikator_kinerja.aspek as aspek_indikator',
                    'rencana_indikator_kinerja.satuan',
                    'rencana_indikator_kinerja.target_minimum',
                    'rencana_indikator_kinerja.target_maksimum'
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $userId)
                ->where('rencana_indikator_kinerja.user_id', $userId)
                ->whereMonth('kegiatan_harians.tanggal', $bulan)
                ->whereYear('kegiatan_harians.tanggal', $tahun)
                ->when($userId, function ($query) use ($userId) {
                    return $query->where('rencana_hasil_kerja_pegawai.user_id', $userId); // Filter berdasarkan pegawai
                })
                ->get()
                ->groupBy(['rencana_pimpinan', 'rencana_pegawai']);

            return compact('dataRencanaAksi', 'groupedDataEvaluasi');
        } catch (\Exception $e) {
            // Log error jika terjadi masalah
            Log::error('Gagal mendapatkan detail evaluasi', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);
            throw new \RuntimeException('Gagal mengambil data evaluasi. Pastikan data sudah benar.');
        }
    }
}