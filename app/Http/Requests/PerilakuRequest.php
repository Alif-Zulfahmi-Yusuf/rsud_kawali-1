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
}