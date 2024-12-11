<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Atasan extends Model
{
    protected $table = 'atasans';

    protected $fillable = [
        'uuid',
        'nip',
        'name',
        'jabatan',
        'pangkat_id',
        'unit_kerja',
        'user_id',
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

    /**
     * Relasi dengan pangkat
     */
    public function pangkat()
    {
        return $this->belongsTo(Pangkat::class);
    }

    public function skpAtasan()
    {
        return $this->hasMany(SkpAtasan::class, 'user_id', 'user_id');
    }

    /**
     * Relasi ke user yang menjadi atasan
     */
    public function users()
    {
        return $this->hasMany(User::class, 'atasan_id');
    }

    public function user()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }
}