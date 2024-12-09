<?php

namespace App\Http\Services;

use App\Models\Perilaku;
use Illuminate\Support\Facades\Log;


class PerilakuService
{

    public function store(array $data)
    {
        return Perilaku::create($data);
    }

    public function update($data, $uuid)
    {
        // Log untuk memeriksa data yang diterima
        Log::info($data);

        // Pastikan uuid ada di dalam data
        if (!isset($data['uuid'])) {
            throw new \Exception('UUID tidak ditemukan dalam data.');
        }

        $perilaku = Perilaku::where('uuid', $data['uuid'])->firstOrFail();
        $perilaku->update([
            'category_perilaku_id' => $data['category_perilaku_id'],
            'name' => $data['name'],
        ]);

        return $perilaku;
    }




    public function selectFirstById($column, $value)
    {
        return Perilaku::where($column, $value)->firstOrFail();
    }

    public function delete($uuid)
    {
        $skp = Perilaku::where('uuid', $uuid)->firstOrFail();

        return $skp->delete();
    }
}