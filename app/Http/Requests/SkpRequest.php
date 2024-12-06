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
        return true; // Mengizinkan semua pengguna untuk membuat request, sesuaikan dengan kebijakan akses Anda.
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'year' => 'required|integer|digits:4|gte:' . (date('Y') - 5) . '|lte:' . (date('Y') + 1), // Validasi tahun antara 5 tahun lalu dan tahun depan
            'module' => 'required|string|in:kuantitatif,kualitatif',
        ];
    }

    /**
     * Custom error messages for validation.
     *
     * @return array<string, string>
     */
    public function messages(): array
    {
        return [
            'year.required' => 'Tahun SKP harus diisi.',
            'year.integer' => 'Tahun SKP harus berupa angka.',
            'year.digits' => 'Tahun SKP harus terdiri dari 4 angka.',
            'year.gte' => 'Tahun SKP tidak boleh kurang dari 5 tahun yang lalu.',
            'year.lte' => 'Tahun SKP tidak boleh lebih dari tahun ini.',
            'module.required' => 'Module SKP harus dipilih.',
            'module.in' => 'Module SKP harus salah satu dari: kuantitatif, kualitatif.',
        ];
    }
}