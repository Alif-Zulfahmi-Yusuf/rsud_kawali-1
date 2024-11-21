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

    protected $table = 'rencana_hasil_kinerja';

    protected $fillable = [
        'uuid',
        'user_id',
        'skp_id',
        'rencana'
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

    // Relasi ke SKP
    public function skp(): BelongsTo
    {
        return $this->belongsTo(Skp::class);
    }

    // Relasi ke RencanaHasilKinerjaPegawai
    public function rencanaPegawai(): HasMany
    {
        return $this->hasMany(RencanaHasilKinerjaPegawai::class, 'rencana_atasan_id');
    }
}