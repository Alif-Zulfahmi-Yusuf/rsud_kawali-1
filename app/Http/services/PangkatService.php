<?php

namespace App\Http\Services;

use App\Models\Pangkat;
use Illuminate\Support\Str;

class PangkatService
{
    //

    public function create($data)
    {

        // Membuat slug berdasarkan nama
        $data['slug'] = Str::slug($data['name']);

        return Pangkat::create($data);
    }
}