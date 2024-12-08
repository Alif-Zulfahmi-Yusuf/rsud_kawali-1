<?php

namespace App\Http\Services;

use App\Models\Perilaku;


class PerilakuService
{

    public function store(array $data)
    {
        return Perilaku::create($data);
    }

    public function update($data)
    {
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