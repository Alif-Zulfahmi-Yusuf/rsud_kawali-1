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
        'skp_atasan_id',
        'rencana_pegawai_id',
        'kegiatan_harian_id',
        'bulan',
        'tanggal_capaian',
        'status',
        'kuantitas_output',
        'permasalahan',
        'kualitas',
        'realisasi',
        'jumlah_periode',
        'laporan'
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

    public function evaluasiPegawai()
    {
        return $this->hasMany(EvaluasiPegawai::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class);
    }

    public function rencanaPegawai()
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_pegawai_id');
    }

    public function kegiatanHarian()
    {
        return $this->belongsTo(KegiatanHarian::class, 'kegiatan_harian_id');
    }
    public function skpAtasan()
    {
        return $this->belongsTo(SkpAtasan::class, 'skp_atasan_id');
    }

    // cast
    protected $casts = [
        'tanggal_capaian' => 'date',
        'laporan' => 'array',
        'kuantitas_output' => 'array',
        'kualitas' => 'array',
        'realisasi' => 'array',
    ];
}