<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RealisasiRencana extends Model
{
    //
    protected $table = 'realisasi_rencanas';

    protected $fillable = [
        'evaluasi_pegawai_id',
        'rencana_pegawai_id',
        'file',
    ];

    public function evaluasiPegawai()
    {
        return $this->belongsTo(EvaluasiPegawai::class);
    }

    public function rencanaPegawai()
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class);
    }
}