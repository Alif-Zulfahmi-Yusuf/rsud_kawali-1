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

            // Filter kegiatan harian berdasarkan user_id, bulan, dan tahun
            $filteredKegiatanHarian = DB::table('kegiatan_harians')
                ->select(
                    'id',
                    'rencana_pegawai_id',
                    'tanggal',
                    'uraian',
                    'waktu_mulai',
                    'waktu_selesai',
                    'jenis_kegiatan',
                    'penilaian',
                    'output',
                    'jumlah'
                )
                ->where('user_id', $userId) // Filter berdasarkan user_id pegawai yang sedang login
                ->whereMonth('tanggal', $bulan)   // Filter berdasarkan bulan
                ->whereYear('tanggal', $tahun)    // Filter berdasarkan tahun
                ->get();

            if ($filteredKegiatanHarian->isEmpty()) {
                throw new \Exception('Tidak ada kegiatan harian yang ditemukan pada bulan dan tahun ini.');
            }


            // Subquery untuk indikator dengan filter pembagian target_minimum
            $indikatorSubquery = DB::table('rencana_indikator_kinerja')
                ->selectRaw('
                    rencana_atasan_id, 
                    GROUP_CONCAT(indikator_kinerja SEPARATOR ", ") as indikator_kinerja, 
                    satuan,target_maksimum,aspek, 
                    target_minimum,
                    CASE 
                        WHEN target_minimum = 12 AND satuan = "laporan" THEN 1 
                        WHEN target_minimum = 4 AND satuan = "laporan" THEN CEIL(MONTH(CURRENT_DATE) / 3) 
                    ELSE 0 
                    END as bulan_muncul
                ')
                ->where('user_id', $userId)
                ->groupBy('rencana_atasan_id', 'satuan', 'target_minimum', 'target_maksimum', 'aspek');


            // Query utama untuk data rencana aksi dengan filter
            $dataRencanaAksi = DB::table('rencana_hasil_kerja_pegawai')
                ->join('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoinSub($indikatorSubquery, 'indikator', 'rencana_hasil_kerja.id', '=', 'indikator.rencana_atasan_id')
                ->leftJoin('evaluasi_pegawais', 'evaluasi_pegawais.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->leftJoin('realisasi_rencanas', 'realisasi_rencanas.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->leftJoin('kegiatan_harians', 'kegiatan_harians.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as rencana_pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as nama_rencana_pegawai',
                    'rencana_hasil_kerja.rencana as nama_rencana_pimpinan',
                    'indikator.indikator_kinerja as nama_indikator',
                    'indikator.satuan',
                    'indikator.target_minimum',
                    'indikator.bulan_muncul',
                    'evaluasi_pegawais.id as evaluasi_pegawai_id',
                    'realisasi_rencanas.id as realisasi_rencana_id',
                    'realisasi_rencanas.file as file_realisasi',
                    'kegiatan_harians.waktu_mulai',
                    'kegiatan_harians.waktu_selesai'
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $userId)
                ->where(function ($query) use ($bulan) {
                    $query->whereRaw('indikator.target_minimum = 12 AND indikator.satuan = "laporan" AND MONTH(CURRENT_DATE) = ?', [$bulan])
                        ->orWhereRaw('indikator.target_minimum = 4 AND indikator.satuan = "laporan" AND CEIL(? / 3) = indikator.bulan_muncul', [$bulan]);
                })
                ->whereYear('kegiatan_harians.tanggal', $tahun)
                ->get();

            $groupedDataEvaluasi = DB::table('rencana_hasil_kerja_pegawai')
                ->leftJoin('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoinSub($indikatorSubquery, 'indikator', 'rencana_hasil_kerja.id', '=', 'indikator.rencana_atasan_id')
                ->leftJoin('kegiatan_harians', 'rencana_hasil_kerja_pegawai.id', '=', 'kegiatan_harians.rencana_pegawai_id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as rencana_pegawai',
                    'rencana_hasil_kerja.rencana as rencana_pimpinan',
                    'indikator.indikator_kinerja as nama_indikator',
                    'indikator.satuan',
                    'indikator.aspek',
                    'indikator.target_minimum',
                    'indikator.target_maksimum',
                    'kegiatan_harians.waktu_mulai',
                    'kegiatan_harians.waktu_selesai'
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $userId)
                ->whereMonth('kegiatan_harians.tanggal', $bulan)
                ->whereYear('kegiatan_harians.tanggal', $tahun)
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