<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RencanaHasilKinerja extends Model
{
    use HasFactory;

    protected $table = 'rencana_hasil_kerja';

    protected $fillable = [
        'uuid',
        'user_id',
        'skp_atasan_id',
        'rencana',
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

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function skp(): BelongsTo
    {
        return $this->belongsTo(SkpAtasan::class, 'skp_atasan_id');
    }

    public function rencanaHasilKinerja()
    {
        return $this->hasMany(RencanaHasilKinerja::class, 'rencana_atasan_id');
    }


    public function rencanaPegawai(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerjaPegawai::class, 'rencana_atasan_id', 'id');
    }


    public function indikatorKinerja(): HasMany
    {
        return $this->hasMany(IndikatorKinerja::class, 'rencana_kerja_atasan_id', 'id');
    }
}