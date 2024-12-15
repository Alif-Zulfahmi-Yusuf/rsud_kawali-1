<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class HarianPegawaiRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true; // Atur otorisasi
    }

    public function rules(): array
    {
        return [
            'tanggal' => 'required|date',
            'jenis_kegiatan' => 'required|string',
            'uraian' => 'required|string',
            'rencana_pegawai_id' => 'required|exists:rencana_hasil_kerja_pegawai,id',
            'output' => 'required|string',
            'jumlah' => 'required|numeric',
            'waktu_mulai' => 'required|date_format:H:i',
            'waktu_selesai' => 'required|date_format:H:i|after:waktu_mulai',
            'biaya' => 'nullable|numeric',
            'evidence' => 'nullable|file|mimes:pdf,jpg,jpeg,png,doc,docx,xls,xlsx',
        ];
    }
}
