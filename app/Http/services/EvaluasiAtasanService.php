<?php

namespace App\Http\Services;

use App\Models\Ekspetasi;
use App\Models\EvaluasiPegawai;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Models\RencanaHasilKinerjaPegawai;

class EvaluasiAtasanService
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

            // Ambil data terkait berdasarkan evaluasi pegawai
            $rencanaPegawai = RencanaHasilKinerjaPegawai::with('rencanaAtasan')
                ->find($evaluasi->rencana_pegawai_id);

            if (!$rencanaPegawai) {
                throw new \Exception('Rencana pegawai tidak ditemukan.');
            }

            $skpDetail = $rencanaPegawai->rencanaAtasan;

            // Ambil data kategori perilaku dengan perilaku terkait
            $categories = CategoryPerilaku::with('perilakus')
                ->whereHas('perilakus')
                ->get();

            // Ambil data ekspektasi berdasarkan ID SKP
            $ekspektasis = Ekspetasi::where('skp_id', $skpDetail->id)->get();

            // Data Realisasi Rencana Aksi
            $dataRencanaAksi = DB::table('rencana_hasil_kerja_pegawai')
                ->join('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->join('rencana_indikator_kinerja', 'rencana_hasil_kerja.id', '=', 'rencana_indikator_kinerja.rencana_atasan_id')
                ->leftJoin('evaluasi_pegawais', 'evaluasi_pegawais.rencana_pegawai_id', '=', 'rencana_hasil_kerja_pegawai.id')
                ->select(
                    'rencana_hasil_kerja_pegawai.id as rencana_pegawai_id',
                    'rencana_hasil_kerja_pegawai.rencana as nama_rencana_pegawai',
                    'rencana_indikator_kinerja.indikator_kinerja as nama_indikator',
                    'rencana_indikator_kinerja.satuan',
                    'rencana_indikator_kinerja.target_minimum',
                    'rencana_indikator_kinerja.target_maksimum',
                    'evaluasi_pegawais.realisasi as realisasi'
                )
                ->where('rencana_hasil_kerja_pegawai.id', $evaluasi->rencana_pegawai_id)
                ->get();

            // Grouped Data Evaluasi
            $groupedDataEvaluasi = DB::table('rencana_hasil_kerja_pegawai')
                ->leftJoin('rencana_hasil_kerja', 'rencana_hasil_kerja_pegawai.rencana_atasan_id', '=', 'rencana_hasil_kerja.id')
                ->leftJoin('rencana_indikator_kinerja', 'rencana_hasil_kerja.id', '=', 'rencana_indikator_kinerja.rencana_atasan_id')
                ->select(
                    'rencana_hasil_kerja.rencana as rencana_pimpinan',
                    'rencana_hasil_kerja_pegawai.rencana as rencana_pegawai',
                    'rencana_indikator_kinerja.indikator_kinerja as nama_indikator',
                    'rencana_indikator_kinerja.satuan'
                )
                ->where('rencana_hasil_kerja_pegawai.id', $evaluasi->rencana_pegawai_id)
                ->get()
                ->groupBy(['rencana_pimpinan', 'rencana_pegawai']);

            return compact('categories', 'ekspektasis', 'skpDetail', 'evaluasi', 'dataRencanaAksi', 'groupedDataEvaluasi');
        } catch (\Exception $e) {
            // Log error untuk debugging
            Log::error('Gagal mendapatkan detail evaluasi', [
                'uuid' => $uuid,
                'error' => $e->getMessage(),
            ]);

            // Lempar ulang exception dengan pesan yang relevan
            throw new \RuntimeException('Gagal mengambil data evaluasi. Pastikan data sudah benar.');
        }
    }
}
