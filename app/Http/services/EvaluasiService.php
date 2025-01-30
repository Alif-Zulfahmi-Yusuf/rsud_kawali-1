<?php

namespace App\Http\Services;

use Illuminate\Support\Carbon;
use App\Models\EvaluasiPegawai;
use App\Models\RealisasiRencana;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Auth;


class EvaluasiService
{
    //

    public function getEvaluasiData(string $uuid)
    {
        try {
            // Ambil evaluasi pegawai berdasarkan UUID
            $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->first();

            if (!$evaluasi) {
                throw new \Exception('Evaluasi tidak ditemukan.');
            }

            // Ambil user ID pegawai yang sedang login
            $currentUserId = auth()->id(); // Menggunakan Auth Laravel untuk mendapatkan user ID

            // Pastikan evaluasi milik pegawai yang sedang login
            if ($evaluasi->user_id != $currentUserId) {
                throw new \Exception('Anda tidak berhak mengakses evaluasi ini.');
            }

            // Ambil bulan dan tahun dari tabel kegiatan_harians
            $kegiatanHarian = DB::table('kegiatan_harians')
                ->where('rencana_pegawai_id', $evaluasi->rencana_pegawai_id)
                ->first();

            if (!$kegiatanHarian) {
                throw new \Exception('Tidak ada data kegiatan harian yang terkait.');
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
                ->where('user_id', $currentUserId) // Filter berdasarkan user_id pegawai yang sedang login
                ->whereMonth('tanggal', $bulan)   // Filter berdasarkan bulan
                ->whereYear('tanggal', $tahun)    // Filter berdasarkan tahun
                ->get();

            // Hitung total waktu (dalam jam)
            $totalWaktu = 0;

            foreach ($filteredKegiatanHarian as $item) {
                if (isset($item->waktu_mulai, $item->waktu_selesai)) {
                    $waktuMulai = Carbon::parse($item->waktu_mulai);
                    $waktuSelesai = Carbon::parse($item->waktu_selesai);
                    $totalWaktu += $waktuMulai->diffInHours($waktuSelesai);
                }
            }

            if ($filteredKegiatanHarian->isEmpty()) {
                throw new \Exception('Tidak ada kegiatan harian yang ditemukan pada bulan dan tahun ini.');
            }

            $indikatorSubquery = DB::table('rencana_indikator_kinerja')
                ->selectRaw('
        rencana_atasan_id, 
        GROUP_CONCAT(DISTINCT indikator_kinerja SEPARATOR ", ") as indikator_kinerja, 
        satuan, target_maksimum, aspek, 
        target_minimum,
        CASE 
            WHEN target_minimum = 12 THEN 1  -- Muncul di setiap bulan
            WHEN target_minimum IN (1, 2, 3, 4, 6) THEN 
                CASE 
                    WHEN MOD(? - 1, CEIL(12 / target_minimum)) = 0 THEN 1
                    ELSE 0
                END
            ELSE 0 
        END as bulan_muncul
    ', [$bulan])
                ->where('user_id', $currentUserId)
                ->where('satuan', 'laporan')
                ->whereBetween('target_minimum', [1, 12])
                ->groupBy('rencana_atasan_id', 'satuan', 'target_minimum', 'target_maksimum', 'aspek');


            // Mapping nilai kualitas
            $mappingKualitas = [
                'sangat_kurang' => 1,
                'kurang' => 2,
                'butuh_perbaikan' => 3,
                'baik' => 4,
                'sangat_baik' => 5,
            ];

            // Subquery untuk total waktu berdasarkan rencana pegawai dalam bulan tertentu
            $totalWaktuSubquery = DB::table('kegiatan_harians')
                ->selectRaw('
                    rencana_pegawai_id,
                        SUM(TIMESTAMPDIFF(MINUTE, waktu_mulai, waktu_selesai)) as total_waktu
                ')
                ->where('user_id', $currentUserId)
                ->whereMonth('tanggal', $bulan)
                ->whereYear('tanggal', $tahun)
                ->groupBy('rencana_pegawai_id');

            // Subquery untuk menghitung rata-rata kualitas
            $rataRataKualitasSubquery = DB::table('evaluasi_pegawais')
                ->selectRaw('
                            rencana_pegawai_id,
                            ROUND(AVG(CASE
                                WHEN JSON_CONTAINS(kualitas, \'"sangat_kurang"\') THEN 1
                                WHEN JSON_CONTAINS(kualitas, \'"kurang"\') THEN 2
                                WHEN JSON_CONTAINS(kualitas, \'"butuh_perbaikan"\') THEN 3
                                WHEN JSON_CONTAINS(kualitas, \'"baik"\') THEN 4
                                WHEN JSON_CONTAINS(kualitas, \'"sangat_baik"\') THEN 5
                            ELSE NULL END
                            )) as rata_rata_kualitas
                        ')
                ->groupBy('rencana_pegawai_id');
            $tahunNew = Carbon::parse($evaluasi->bulan)->year;
            $bulanNew = Carbon::parse($evaluasi->bulan)->month;
            // Ambil data rencana aksi
            $dataRencanaAksi = DB::table('rencana_hasil_kerja_pegawai')
                ->join('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoinSub($indikatorSubquery, 'indikator', 'rencana_hasil_kerja.id', '=', 'indikator.rencana_atasan_id')
                ->leftJoinSub($totalWaktuSubquery, 'total_waktu', 'rencana_hasil_kerja_pegawai.id', '=', 'total_waktu.rencana_pegawai_id')
                ->leftJoinSub($rataRataKualitasSubquery, 'rata_rata_kualitas', 'rencana_hasil_kerja_pegawai.id', '=', 'rata_rata_kualitas.rencana_pegawai_id')
                ->leftJoin('evaluasi_pegawais', 'evaluasi_pegawais.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->select(
                    'rencana_hasil_kerja_pegawai.user_id as user_id',
                    'rencana_hasil_kerja_pegawai.id as rencana_pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as nama_rencana_pegawai',
                    'rencana_hasil_kerja.rencana as nama_rencana_pimpinan',
                    'indikator.indikator_kinerja as nama_indikator',
                    'indikator.satuan',
                    'indikator.target_minimum',
                    'indikator.bulan_muncul',
                    'evaluasi_pegawais.id as evaluasi_pegawai_id',
                    'total_waktu.total_waktu as waktu_total', // Total waktu yang dihitung
                    'rata_rata_kualitas.rata_rata_kualitas as rata_rata_kualitas' // Rata-rata kualitas
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $currentUserId)
                ->get()
                ->map(function ($item) use ($mappingKualitas, $evaluasi) {
                    // Konversi rata-rata kualitas ke teks
                    $item->rata_rata_kualitas_text = array_search(round($item->rata_rata_kualitas), $mappingKualitas) ?? 'Tidak Ada';

                    // Jika bukan bulan kemunculan, set semua nilai yang terkait ke 0
                    if ($item->bulan_muncul != 1) {
                        $item->waktu_total = 0;
                        $item->rata_rata_kualitas = 0;
                        $item->rata_rata_kualitas_text = 'Tidak Ada';
                    }
                    if ($item->evaluasi_pegawai_id == null) {
                        $item->evaluasi_pegawai_id = $evaluasi->id;
                    }

                    return $item;
                })
                ->where('evaluasi_pegawai_id', $evaluasi->id)
                ->unique('rencana_pegawai_id')
                ->values();

            $totalWaktuKeseluruhan = $dataRencanaAksi->sum('waktu_total');
            $totalWaktuKeseluruhanMenit = $totalWaktuKeseluruhan * 60;

            // Konversi total waktu ke jam dan menit
            $totalWaktuKeseluruhanJam = floor($totalWaktuKeseluruhanMenit / 60);
            $totalWaktuKeseluruhanSisaMenit = $totalWaktuKeseluruhanMenit % 60;

            $totalWaktuKeseluruhanJam = $totalWaktuKeseluruhanJam . ' Jam ' . $totalWaktuKeseluruhanSisaMenit . ' Menit';


            $dataRencanaAksi = $dataRencanaAksi->map(function ($item) use ($evaluasi) {
                $fileRealisasi = RealisasiRencana::where('rencana_pegawai_id', $item->rencana_pegawai_id)
                    ->where('evaluasi_pegawai_id', $evaluasi->id)
                    ->first();

                Log::info('Item:', ['rencana_pegawai_id' => $item->rencana_pegawai_id, 'evaluasi_pegawai_id' => $item->evaluasi_pegawai_id, 'file_realisasi' => $fileRealisasi]);

                // Perbaiki bulan_muncul agar tetap angka, bukan tanggal
                if ($item->bulan_muncul != 0) {
                    $item->bulan_muncul = (int) $item->bulan_muncul;
                } else {
                    $item->bulan_muncul = 0;
                }

                $item->file_realisasi = $fileRealisasi ? $fileRealisasi->file : null;

                return $item;
            });


            // Query utama untuk groupedDataEvaluasi dengan indikator unik
            $groupedDataEvaluasi = DB::table('rencana_hasil_kerja_pegawai')
                ->leftJoin('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoinSub(
                    DB::table('rencana_indikator_kinerja') // Subquery untuk indikator
                        ->selectRaw('
                            rencana_atasan_id, 
                            GROUP_CONCAT(DISTINCT indikator_kinerja SEPARATOR ", ") as indikator_kinerja,
                            satuan, aspek, target_minimum, target_maksimum
                        ')
                        ->where('user_id', $currentUserId)
                        ->groupBy('rencana_atasan_id', 'satuan', 'aspek', 'target_minimum', 'target_maksimum'),
                    'indikator',
                    'rencana_hasil_kerja.id',
                    '=',
                    'indikator.rencana_atasan_id'
                )
                ->leftJoin('kegiatan_harians', 'rencana_hasil_kerja_pegawai.id', '=', 'kegiatan_harians.rencana_pegawai_id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as rencana_pegawai',
                    'rencana_hasil_kerja.rencana as rencana_pimpinan',
                    DB::raw('GROUP_CONCAT(DISTINCT indikator.indikator_kinerja SEPARATOR ", ") as nama_indikator'), // Indikator unik
                    'indikator.satuan',
                    'indikator.aspek',
                    'indikator.target_minimum',
                    'indikator.target_maksimum',
                    DB::raw('MIN(kegiatan_harians.waktu_mulai) as waktu_mulai'), // Waktu mulai terendah
                    DB::raw('MAX(kegiatan_harians.waktu_selesai) as waktu_selesai') // Waktu selesai tertinggi
                )
                ->where('rencana_hasil_kerja_pegawai.user_id', $currentUserId)
                ->whereMonth('kegiatan_harians.tanggal', $bulan)
                ->whereYear('kegiatan_harians.tanggal', $tahun)
                ->groupBy(
                    'pegawai_id',
                    'rencana_pegawai',
                    'rencana_pimpinan',
                    'indikator.satuan',
                    'indikator.aspek',
                    'indikator.target_minimum',
                    'indikator.target_maksimum'
                )
                ->get()
                ->groupBy(['rencana_pimpinan', 'rencana_pegawai']);

            return compact('dataRencanaAksi', 'groupedDataEvaluasi', 'filteredKegiatanHarian', 'totalWaktuKeseluruhan', 'totalWaktuKeseluruhanJam', 'totalWaktuKeseluruhanSisaMenit');
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