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

    public function update($data, $uuid)
    {
        $data['slug'] = Str::slug($data['name']);

        return Pangkat::where('uuid', $uuid)->update($data);
    }

    public function selectFirstById($column, $value)
    {
        // Mengambil data pangakt berdasarkan kolom yang ditentukan
        return Pangkat::where($column, $value)->firstOrFail();
    }
}
