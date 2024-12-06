<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SkpAtasan extends Model
{
    use HasFactory;

    protected $table = 'skp_atasan';

    protected $fillable = [
        'uuid',
        'user_id',
        'unit_kerja',
        'module',
        'tahun',
        'tanggal_skp',
        'tanggal_akhir',
    ];

    protected $casts = [
        'tanggal_skp' => 'datetime',
        'tanggal_akhir' => 'datetime',
    ];

    protected $hidden = [
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class, 'skp_id');
    }

    public function rencanaHasilKinerja(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerja::class, 'skp_atasan_id');
    }
    // Model Skp (untuk Pegawai)
    public function skpAtasan()
    {
        return $this->belongsTo(Skp::class, 'skp_atasan_id'); // Menghubungkan ke SKP Atasan
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