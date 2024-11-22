<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class RencanaHasilKinerjaPegawai extends Model
{
    use HasFactory;

    protected $table = 'rencana_hasil_kerja_pegawai';

    protected $fillable = [
        'uuid',
        'rencana_atasan_id', // Pastikan kolom ini sesuai dengan yang ada di database
        'user_id',
        'skp_id',
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

    public function rencanaAtasan()
    {
        return $this->belongsTo(RencanaHasilKinerja::class, 'rencana_atasan_id');
    }

    // Relasi ke IndikatorKinerja
    public function indikatorKinerja()
    {
        return $this->hasMany(IndikatorKinerja::class);
    }


    // Fungsi tambahan untuk data indikator
    public function getIndikatorWithDetails()
    {
        return $this->indikatorKinerja()->with('user');
    }
}