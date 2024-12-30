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
        // Ambil evaluasi pegawai berdasarkan UUID
        $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->first();

        if (!$evaluasi) {
            throw new \Exception('Evaluasi tidak ditemukan.');
        }

        $bulan = \Carbon\Carbon::parse($evaluasi->bulan)->format('m'); // Ambil bulan (numerik)
        $tahun = \Carbon\Carbon::parse($evaluasi->bulan)->format('Y'); // Ambil tahun

        // Query data dengan LEFT JOIN tanpa menggunakan `kegiatan_harian_id`
        $data = DB::table('kegiatan_harians')
            ->leftJoin('skps', 'kegiatan_harians.rencana_pegawai_id', '=', 'skps.id')
            ->select(
                'kegiatan_harians.id as kegiatan_harian_id',
                'kegiatan_harians.uraian as uraian_kegiatan',
                'kegiatan_harians.tanggal',
                'skps.id as skp_id',
                'skps.module as nama_skp',
                'skps.tahun as tahun_skp'
            )
            ->whereMonth('kegiatan_harians.tanggal', '=', $bulan) // Filter bulan
            ->whereYear('kegiatan_harians.tanggal', '=', $tahun) // Filter tahun
            ->get();

        return $data;
    }



    public function delete($uuid)
    {
        $evaluasi = EvaluasiPegawai::where('uuid', $uuid)->firstOrFail();

        return $evaluasi->delete();
    }
}