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
        // Cari data Atasan berdasarkan UUID
        $atasan = Atasan::where('uuid', $uuid)->firstOrFail();

        // Pastikan hanya data yang diizinkan yang di-update
        $atasan->name = $data['name'];
        $atasan->nip = $data['nip'];
        $atasan->pangkat_id = $data['pangkat_id'];
        $atasan->jabatan = $data['jabatan'];
        $atasan->unit_kerja = $data['unit_kerja'];

        // Cek jika ada user_id di data
        if (isset($data['user_id'])) {
            $atasan->user_id = $data['user_id'];
        }

        // Simpan perubahan
        $atasan->save();

        return $atasan;
    }

    public function selectFirstById($column, $value)
    {
        // Cari data berdasarkan kolom tertentu
        return Atasan::where($column, $value)->firstOrFail();
    }


    public function delete($uuid)
    {
        $atasan = Atasan::where('uuid', $uuid)->firstOrFail();

        return $atasan->delete();
    }
}