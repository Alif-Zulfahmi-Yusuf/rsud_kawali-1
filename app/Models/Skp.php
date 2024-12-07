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
        'atasan_id',  // Menambahkan atasan_id pada $fillable
        'skp_atasan_id',
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
        // Sembunyikan kolom yang tidak diperlukan
        'user_id',
        'atasan_id',
        'skp_atasan_id',
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

    // SKP Atasan memiliki banyak SKP Pegawai
    public function skpAtasan()
    {
        return $this->belongsTo(SkpAtasan::class, 'skp_atasan_id', 'id');
    }

    public function rencanaHasilKinerja(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerja::class, 'skp_atasan_id');
    }

    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'skp_id');
    }

    public function rencanaPegawai(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerjaPegawai::class, 'skp_id');
    }

    public function scopeByYear(Builder $query, int $year): Builder
    {
        return $query->where('tahun', $year);
    }

    public function scopeByUser(Builder $query, int $userId): Builder
    {
        return $query->where('user_id', $userId);
    }

    // Menambahkan scope untuk status jika diperlukan
    public function scopeByStatus(Builder $query, string $status): Builder
    {
        return $query->where('status', $status);
    }
}
