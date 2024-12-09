<?php

namespace App\Models;


use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Perilaku extends Model
{
    //

    protected $table = 'perilakus';

    protected $fillable = [
        'uuid',
        'category_perilaku_id',
        'name',
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

    public function categoryPerilaku()
    {
        return $this->belongsTo(CategoryPerilaku::class);
    }
}