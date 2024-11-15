<?php

namespace App\Http\Services;

use App\Models\Atasan;
use App\Models\Pangkat;
use Illuminate\Support\Str;

class AtasanService
{

    public function create($data)
    {
        return Atasan::create($data);
    }

    public function update($data, $uuid)
    {
        // Find the Atasan record by UUID and update its data
        $atasan = Atasan::where('uuid', $uuid)->firstOrFail();

        return $atasan->update($data);
    }

    public function selectFirstById($column, $value)
    {
        return Atasan::where($column, $value)->firstOrFail();
    }


    public function delete($uuid)
    {
        $atasan = Atasan::where('uuid', $uuid)->firstOrFail();

        return $atasan->delete();
    }
}