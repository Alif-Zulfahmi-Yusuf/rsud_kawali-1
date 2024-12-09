<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class PerilakuRequest extends FormRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'category_perilaku_id' => 'required|exists:category_perilakus,id',
            'name' => 'required|string|max:255',
        ];
    }

    public function messages()
    {
        return [
            'category_perilaku_id.required' => 'Category harus dipilih.',
            'category_perilaku_id.exists' => 'Category yang dipilih tidak valid.',
            'name.required' => 'Nama perilaku wajib diisi.',
            'name.max' => 'Nama perilaku maksimal 255 karakter.',
        ];
    }
}