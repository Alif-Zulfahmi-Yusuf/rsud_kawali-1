<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

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

    // Relasi ke model User
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relasi ke model Atasan
    public function atasan()
    {
        return $this->belongsTo(Atasan::class);
    }
}