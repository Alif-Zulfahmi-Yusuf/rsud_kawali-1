<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class IndikatorKinerja extends Model
{
    use HasFactory;

    protected $table = 'rencana_indikator_kinerja';

    protected $fillable = [
        'uuid',
        'rencana_hasil_kerja_id',
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

        // Generate UUID on create
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke RencanaHasilKinerjaPegawai
    public function rencanaPegawai(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_pegawai_id');
    }
}