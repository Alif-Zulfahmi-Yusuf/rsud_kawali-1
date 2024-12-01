<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Skp extends Model
{
    use HasFactory;

    protected $table = 'skps';

    protected $fillable = [
        'uuid',
        'user_id',
        'atasan_id',
        'tahun',
        'module',
        'unit_kerja',
        'tanggal_skp',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_skp' => 'datetime',
        'tanggal_akhir' => 'datetime',
    ];

    protected $hidden = [
        'atasan_id',
        'user_id',
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

    public function getRouteKeyName()
    {
        return 'uuid';
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function rencanaHasilKinerja(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerja::class, 'skp_id');
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'skp_id');
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