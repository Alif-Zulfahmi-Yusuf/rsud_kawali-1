<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CategoryPerilaku extends Model
{
    //

    protected $table = 'category_perilakus';

    protected $fillable = [
        'name',
    ];

    public function perilakus()
    {
        return $this->hasMany(Perilaku::class);
    }
}
