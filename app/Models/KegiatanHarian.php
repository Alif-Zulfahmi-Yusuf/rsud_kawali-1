<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class KegiatanHarian extends Model
{
    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'kegiatan_harians';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'uuid',
        'user_id',
        'rencana_pegawai_id',
        'atasan_id',
        'skp_id',
        'skp_atasan_id',
        'uraian',
        'jenis_kegiatan',
        'output',
        'jumlah',
        'biaya',
        'evidence',
        'is_draft',
        'status',
        'tanggal',
        'waktu_mulai',
        'waktu_selesai'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'is_draft' => 'boolean',
        'tanggal' => 'date',
        'waktu_mulai' => 'datetime:H:i',
        'waktu_selesai' => 'datetime:H:i',
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
     * Get the user associated with the kegiatan harian.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the rencana pegawai associated with the kegiatan harian.
     */
    public function rencanaPegawai(): BelongsTo
    {
        return $this->belongsTo(RencanaHasilKinerjaPegawai::class, 'rencana_pegawai_id');
    }

    /**
     * Get the SKP associated with the kegiatan harian.
     */
    public function skp(): BelongsTo
    {
        return $this->belongsTo(Skp::class);
    }

    public function atasan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'atasan_id');
    }

    public function skpAtasan(): BelongsTo
    {
        return $this->belongsTo(Skp::class, 'skp_atasan_id');
    }
}