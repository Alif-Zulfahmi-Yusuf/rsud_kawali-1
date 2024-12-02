<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkpPegawai extends Model
{
    protected $table = 'skp_pegawai';

    protected $fillable = [
        'user_id',
        'skp_id',
        'unit_kerja',
        'module',
        'status',
        'atasan_id',
        'tahun',
        'tanggal_skp',
        'tanggal_akhir',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->uuid)) {
                $model->uuid = (string) Str::uuid();
            }
        });
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }

    public function rencanaHasilKinerja(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerja::class, 'skp_id');
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'skp_id');
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('tahun', $year);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }
}