<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class PangkatRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeUuid = $this->route('pangkats')?->uuid;

        return [
            'name' => ['required', 'unique:pangkats,name,' . $routeUuid, 'max:255'],
        ];
    }

    protected function prepareForValidation()
    {
        // membuat slug berdasarkan nama
        $this->merge([
            'slug' => Str::slug($this->name),
        ]);
    }
}