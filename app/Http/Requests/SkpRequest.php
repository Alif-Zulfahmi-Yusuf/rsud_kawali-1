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
            'year' => ['required', 'integer', 'min:2019'], // Hapus 'max' untuk mengizinkan tahun di masa depan
            'module' => ['required', 'string'],
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