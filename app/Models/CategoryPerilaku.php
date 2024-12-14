<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class CategoryPerilaku extends Model
{
    //

    protected $table = 'category_perilakus';

    protected $fillable = [
        'uuid',
        'name',
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

    public function perilakus()
    {
        return $this->hasMany(Perilaku::class);
    }
}