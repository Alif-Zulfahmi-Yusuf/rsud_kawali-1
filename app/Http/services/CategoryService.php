<?php

namespace App\Http\Services;


use Illuminate\Support\Str;
use App\Models\CategoryPerilaku;
use Illuminate\Support\Facades\Log;

class CategoryService
{


    public function create($data)
    {
        return CategoryPerilaku::create($data);
    }

    public function update($data, $uuid)
    {
        return CategoryPerilaku::where('uuid', $uuid)->update($data);
    }

    public function selectFirstById($column, $value)
    {
        // Mengambil data pangakt berdasarkan kolom yang ditentukan
        return CategoryPerilaku::where($column, $value)->firstOrFail();
    }

    public function delete(string $uuid)
    {
        $category = CategoryPerilaku::where('uuid', $uuid)->first();

        if (!$category) {
            // Log error untuk memudahkan debugging
            Log::error("Category not found with UUID: $uuid");
            return false;
        }

        return $category->delete();
    }
}