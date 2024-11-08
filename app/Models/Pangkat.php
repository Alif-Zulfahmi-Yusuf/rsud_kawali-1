<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pangkat extends Model
{
    protected $fillable = [
        'uuid',
        'name',
        'slug'
    ];
}
