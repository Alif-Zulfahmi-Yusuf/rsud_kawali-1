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
            'pangkat' => ['nullable', 'string', 'in:I/a-Juru Muda,I/b-Juru Muda tingkat I,I/c-Juru,I/d-juru tingkat I,II/a-pengatur muda,II/b-pengatur muda TK.I,II/c-pengatur,II/d-pengatur TK.I,III/a-penata muda,III/b-penata muda TK.I,III/c-penata,III/d-penata TK.I,IV/a-pembina,IV/b-Pembina TK.I,IV/c-pembina utama muda,IV/d-pembina utama madya,IV/e-pembina utama,V-V,VII-VII,IX-IX,X-X,VIII-VIII'], // Validate against predefined rank values
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
            'pangkat' => 'Pangkat',
            'unit_kerja' => 'Unit Kerja',
            'tmt_jabatan' => 'TMT Jabatan',
            'image' => 'Gambar Profil',
        ];
    }
}
