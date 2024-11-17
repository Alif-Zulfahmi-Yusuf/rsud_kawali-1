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
            'nip' => ['nullable', 'string', 'max:18'],  // Validasi untuk NIP
            'pangkat_id' => ['nullable', 'exists:pangkats,id'],  // Pastikan pangkat_id ada di tabel pangkats
            'atasan_id' => ['nullable', 'exists:atasans,id'],  // Pastikan atasan_id ada di tabel atasans
            'unit_kerja' => ['nullable', 'string', 'max:255'],  // Validasi unit kerja
            'tmt_jabatan' => ['nullable', 'date'],  // Pastikan TMT Jabatan adalah tanggal valid
            'image' => ['nullable', 'image', 'mimes:jpeg,png,jpg,gif,svg', 'max:2048'],  // Validasi untuk gambar profil
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
            'pangkat_id' => 'Pangkat',
            'atasan_id' => 'Atasan',
            'unit_kerja' => 'Unit Kerja',
            'tmt_jabatan' => 'TMT Jabatan',
            'image' => 'Gambar Profil',
        ];
    }
}