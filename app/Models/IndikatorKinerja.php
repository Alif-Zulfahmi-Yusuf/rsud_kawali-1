<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class IndikatorKinerja extends Model
{
    use HasFactory;

    protected $table = 'rencana_indikator_kinerja';

    protected $fillable = [
        'uuid',
        'rencana_atasan_id',
        'rencana_kerja_pegawai_id',
        'skp_id',
        'user_id',
        'aspek',
        'indikator_kinerja',
        'tipe_target',
        'target_minimum',
        'target_maksimum',
        'satuan',
        'report',
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


    public function rencanaHasilKinerja(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerja::class, 'rencana_atasan_id');
    }

    public function rencanaPegawai(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_kerja_pegawai_id');
    }

    public function skp(): BelongsTo
    {
        return $this->belongsTo(Skp::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}