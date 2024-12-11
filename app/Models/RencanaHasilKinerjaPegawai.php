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
        'rencana_atasan_id',
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

    public function rencanaAtasan()
    {
        return $this->belongsTo(RencanaHasilKinerja::class, 'rencana_atasan_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function skp()
    {
        return $this->belongsTo(Skp::class);
    }

    public function IndikatorKinerja()
    {
        return $this->hasMany(IndikatorKinerja::class, 'rencana_kerja_pegawai_id');
    }

    public function getIndikatorWithDetails()
    {
        return $this->indikatorKinerja()->with('user');
    }
}