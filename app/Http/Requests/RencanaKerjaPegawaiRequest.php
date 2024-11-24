<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RencanaKerjaPegawaiRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize()
    {
        return true; // Pastikan autentikasi diatur sebelumnya
    }

    public function rules()
    {
        return [
            'rencana_atasan_id' => 'required|exists:rencana_hasil_kinerja,id', // Validasi rencana atasan
            'rencana' => 'required|string|max:255', // Validasi rencana hasil kerja
        ];
    }


    public function messages()
    {
        return [
            'rencana_atasan_id.required' => 'Rencana atasan wajib dipilih.',
            'rencana_atasan_id.exists' => 'Rencana atasan tidak valid.',
            'rencana.required' => 'Rencana hasil kerja wajib diisi.',
            'rencana.string' => 'Rencana hasil kerja harus berupa teks.',
            'rencana.max' => 'Rencana hasil kerja tidak boleh lebih dari 255 karakter.',
        ];
    }
}