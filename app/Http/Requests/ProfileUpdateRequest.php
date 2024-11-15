<?php

namespace App\Http\Requests;

use App\Models\User;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class ProfileUpdateRequest extends FormRequest
{
    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'email' => [
                'required',
                'string',
                'lowercase',
                'email',
                'max:255',
                Rule::unique(User::class)->ignore($this->user()->id),
            ],
            'nip' => ['nullable', 'string', 'max:18'],  // Assuming NIP is a string with a maximum length of 18
            'pangkat_id' => ['nullable', 'exists:pangkats,id'],  // Ensure pangkat ID exists in the pangkats table
            'atasan_id' => ['nullable', 'exists:atasans,id'],  // Ensure atasan ID exists in the atasans table
            'unit_kerja' => ['nullable', 'string', 'max:255'],
            'tmt_jabatan' => ['nullable', 'date'],  // Validate that it's a valid date
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],  // Validate image upload if exists
        ];
    }

    /**
     * Get custom attribute names.
     *
     * @return array
     */
    public function attributes()
    {
        return [
            'name' => 'Nama',
            'email' => 'Email',
            'nip' => 'NIP',
            'pangkat_id' => 'Pangkat_ID',
            'atasan_id' => 'Atasan_ID',
            'unit_kerja' => 'Unit Kerja',
            'tmt_jabatan' => 'TMT Jabatan',
            'image' => 'Gambar Profil',
        ];
    }
}