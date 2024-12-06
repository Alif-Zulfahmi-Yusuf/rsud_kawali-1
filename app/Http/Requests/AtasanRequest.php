<?php

namespace App\Http\Requests;

use Illuminate\Support\Str;
use Illuminate\Foundation\Http\FormRequest;

class AtasanRequest extends FormRequest
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
        $routeUuid = $this->route('atasan');

        return [
            'name' => 'required|unique:atasans,name,' . $routeUuid . ',uuid| max:255',
            'user_id' => 'required',
            'nip' => 'required | max:255',
            'jabatan' => 'required | max:255',
            'pangkat_id' => 'required',
            'unit_kerja' => 'required | max:255',
        ];
    }
}