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

    public function update(array $data, string $uuid)
    {
        // Temukan perilaku berdasarkan UUID
        $perilaku = Perilaku::where('uuid', $uuid)->firstOrFail();

        // Lakukan pembaruan data
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