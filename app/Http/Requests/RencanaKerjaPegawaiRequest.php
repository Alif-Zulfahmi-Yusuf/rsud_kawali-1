<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RencanaKerjaPegawaiRequest extends FormRequest
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
        $routeUuid = $this->route('rencana-kerja-pegawai');

        return [
            'rencana' => 'required|unique:rencana_hasil_kerja_pegawai,rencana,' . $routeUuid . ',uuid| max:255',
            'skp_id' => 'required',
            'user_id' => 'required',
            'rencana_atasan_id' => 'required',
        ];
    }
}