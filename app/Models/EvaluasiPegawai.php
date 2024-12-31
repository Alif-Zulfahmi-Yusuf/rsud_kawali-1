<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class EvaluasiPegawai extends Model
{
    //

    protected $table = 'evaluasi_pegawais';

    protected $fillable = [
        'uuid',
        'user_id',
        'skp_id',
        'rencana_pegawai_id',
        'kegiatan_harian_id',
        'bulan',
        'tanggal_capaian',
        'status',
    ];



    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class);
    }

    public function rencanaKerjaPegawai()
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_pegawai_id');
    }

    public function kegiatanHarian()
    {
        return $this->belongsTo(KegiatanHarian::class, 'kegiatan_harian_id');
    }
}