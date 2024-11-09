<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'slug'
    ];

    protected static function boot()
    {
        parent::boot();

        // Generate UUID on create
        static::creating(function ($model) {
            $model->uuid = (string) Str::uuid();
        });

        // Generate slug from name if not provided
        static::creating(function ($model) {
            if (empty($model->slug)) {
                $model->slug = Str::slug($model->name);
            }
        });
    }

    public function getRouteKeyName()
    {
        return 'uuid';
    }
}