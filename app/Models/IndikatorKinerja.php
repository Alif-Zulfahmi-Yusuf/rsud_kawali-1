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
        'rencana_kerja_pegawai_id',
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

    public function rencanaPegawai(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_kerja_pegawai_id');
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}