<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class SkpRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
        return auth()->check();
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|integer',
            'module' => 'required|string|in:kuantitatif,kualitatif',
        ];
    }

    public function messages(): array
    {
        return [
            'year.required' => 'Tahun SKP harus diisi.',
            'year.integer' => 'Tahun SKP harus berupa angka.',
            'module.required' => 'Module SKP harus dipilih.',
            'module.in' => 'Module SKP harus salah satu dari: kuantitatif, kualitatif.',
        ];
    }
}