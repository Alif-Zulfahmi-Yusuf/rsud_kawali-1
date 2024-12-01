<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class IndikatorRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return false;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        $routeUuid = $this->route('indikator-kinerja');

        return [
            'rencana' => 'required|unique:rencana_indikator_kinerja,rencana,' . $routeUuid . ',uuid| max:255',
            'user_id' => 'required',
            'aspek' => 'required',
            'indikator_kinerja' => 'required',
            'tipe_target' => 'required',
            'target_minimum' => 'required',
            'target_maksimum' => 'required',
            'satuan' => 'required',
            'report' => 'required',
        ];
    }
}